<?PHP
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");//Fundacite
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();"	;	
		print "close();";
		print "</script>";		
	}
	//----------------Estas son las variables que controlan la posicion x e y de la tabla inferior (en mm)-------------------//
	$ls_x=0;// valores menores a cero para mover la tabla hacia la izquierda; valores mayores a cero para mover la tabla hacia la derecha.
	$ls_y=0;// valores menores a cero para subir la tabla; valores mayores a cero para bajar la tabla.
	
	//------------------------------------------------------------------------------------------------------------------------//	
	$x_pos=0;
	$y_pos=-1;
	$ls_directorio="cheque_configurable";
	$ls_archivo="cheque_configurable/medidas.txt";
	$li_medidas=16;
	//-------------------------------------------------------------------------------------------------
	function uf_inicializar_variables()
	{
		global $valores;
		global $ls_directorio;
		global $ls_archivo;	
		global $li_medidas;	
		if(!file_exists ($ls_directorio))
		{
			$lb_exito=mkdir($ls_directorio,0777);
			if(!$lb_exito)
			{
				print "<script>";
				print "alert('Error al crear directorio cheque_configurable');";
				print "close();";
				print "</script>";
			}
		}
		if((!file_exists ($ls_archivo)) || (filesize($ls_archivo)==0))
		{	
			if(file_exists ($ls_directorio))
			{
				$archivo = fopen($ls_archivo, "w");			
				$ls_contenido="138.00-6.00-32.00-24.00-32.00-28.00-32.00-34.00-32.00-43.00-77.00-43.00-137.00-65.00-131.00-70.00";
				fwrite($archivo,$ls_contenido);
				fclose($archivo);
			}
		}
			
		if((file_exists($ls_archivo)) && (filesize($ls_archivo)>0))
		{
			$archivo = fopen($ls_archivo, "r");
			$contenido = fread($archivo, filesize($ls_archivo));			
			fclose($archivo);			
			$valores = explode("-",$contenido);	
			if(count($valores)<>$li_medidas)
			{
				$archivo = fopen($ls_archivo, "w");
				fclose($archivo);			
				print "<script>";
				print "alert('Ocurrio un error, por favor cargue de nuevo el cheque (Las medidas seran inicializadas por fallo de lectura y escritura)');";
				print "close();";
				print "</script>";
			}
		}
		else
		{
			print "<script>";
			print "alert('Ocurrio un error, por favor cargue de nuevo el cheque (Las medidas seran inicializadas por fallo de lectura y escritura)');";
			print "close();";
			print "</script>";
		}
	}
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulo(&$io_pdf)
	{
		///////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_titulo
		//		   Access: private 
		//	    Arguments: io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime el titulo
		//	   Creado Por: Ing. Laura Cabre
		// Fecha Creación: 24/04/2006 
		////////////////////////////////////////////////////////////////////////////////////////
		global $valores;
		$io_pdf->add_texto($valores[2],$valores[1],9,"<b>DEPÓSITO/NOTA DE DÉBITO/CRÉDITO</b>");
	}
	//--------------------------------------------------------------------------------------------------------------------------------	
	
	function uf_print_encabezado_pagina($ldec_monto,$ls_nomproben,$ls_monto,$ls_fecha,&$io_pdf,$x)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: ldec_monto : Monto del cheque
		//	    		   ls_nomproben:  Nombre del proveedor o beneficiario
		//	    		   ls_monto : Monto en letras
		//	    		   ls_fecha : Fecha del cheque
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $valores;
		//Imprimo el monto
		$io_pdf->add_texto($valores[0],$valores[1],10,"<b>***".$ldec_monto."***</b>");
		//Beneficiario del Cheque
		$io_pdf->add_texto($valores[2],$valores[3],9,"<b>$ls_nomproben</b>");
		$ls_anio=substr($ls_fecha,-4);
		$ls_fecha_corta=substr($ls_fecha,0,(strlen($ls_fecha)-5));	
		//Fecha del Cheque
		$io_pdf->add_texto($valores[8],$valores[9],9,"<b>$ls_fecha_corta</b>");
		$io_pdf->add_texto($valores[10],$valores[11],9,"<b>$ls_anio</b>");	
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_solicitudes,$ls_conmov,$ls_nomemp,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: ls_numdoc : Numero de documento
		//	    		   ls_nomban : Nombre del banco
		//				   ls_cbtan  : Cuenta del banco
		//				   ls_chevau : Voucher del cheuqe
		//				   ls_nomproben: Nombre del proveedor o beneficiario
		//				   ls_solicitudes: Solicitudes canceladas con el cheque					  
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime los datos basicos del cheque
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 24/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_x;
		global $ls_y;	
		
		 $li_pos=145-$ls_y;
		 $io_pdf->convertir_valor_mm_px($li_pos);		
		 $io_pdf->ezSetY($li_pos);
		 $la_data=array();
		 $la_data[0]["1"]="<b>Ordenes de Pago: ".$ls_solicitudes."</b>";		
		 $la_anchos_col = array(110);
		 $la_justificaciones = array("left");
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2);
		$io_pdf->add_tabla(5+$ls_x,$la_data,$la_opciones);
		$la_data=array();
		$la_data[0]["1"]="<b>Banco: ".$ls_nomban."</b>";
		$la_data[0]["2"]="<b>Cheque No: ".$ls_numdoc."</b>";		
		$la_anchos_col = array(60,50);
		$la_justificaciones = array("left","left");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 10,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2);
		$io_pdf->add_tabla(5+$ls_x,$la_data,$la_opciones);
		$li_pos=145-$ls_y;
		$io_pdf->convertir_valor_mm_px($li_pos);		
		$io_pdf->ezSetY($li_pos);
		$la_data=array();
		$la_data[0]["1"]="<b>".strtoupper($ls_nomemp)."</b>";			
		$la_anchos_col = array(100);
		$la_justificaciones = array("left");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 8,
							   "lineas"=>0,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2);
		$io_pdf->add_tabla(120+$ls_x,$la_data,$la_opciones);
		$io_pdf->add_lineas(1);
		$la_data=array();
		$la_data[0]["1"]="<b>Concepto: ".strtoupper($ls_conmov)."</b>";			
		$la_anchos_col = array(169.9);
		$la_justificaciones = array("left");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2);
		$io_pdf->add_tabla(5+$ls_x,$la_data,$la_opciones);		
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_notas_depositos($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_solicitudes,$ls_conmov,$ls_nomemp,$ls_nomproben,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_notas_depositos
		//		   Access: private 
		//	    Arguments: ls_numdoc : Numero de documento
		//	    		   ls_nomban : Nombre del banco
		//				   ls_cbtan  : Cuenta del banco
		//				   ls_chevau : Voucher del cheuqe
		//				   ls_nomproben: Nombre del proveedor o beneficiario
		//				   ls_solicitudes: Solicitudes canceladas con el cheque					  		
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime los datos basicos del cheque, en caso de que el movimiento sea una Nota de Debito,
		//					de credito o un deposito
		//	   Creado Por: Ing. Laura Cabre
		// Fecha Creación: 29/01/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_x;
		global $ls_y;
		
		 $li_pos=185-$ls_y;
		 $io_pdf->convertir_valor_mm_px($li_pos);
		 $io_pdf->ezSetY($li_pos);
		 //  No de voucher
		 $la_data=array();
		 $la_data[0]["1"]="<b>Voucher Número: \n".$ls_chevau."</b>";		
		 $la_anchos_col = array(40);
		 $la_justificaciones = array("center");
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2);
		$io_pdf->add_tabla(5+$ls_x,$la_data,$la_opciones);				
		$io_pdf->ezSetY($li_pos);
		$la_data=array();
		$la_data[0]["1"]="<b>\n".date("d/m/Y")."</b>";		
		$la_anchos_col = array(30);
		$la_justificaciones = array("center");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2);
		$io_pdf->add_tabla(160+$ls_x,$la_data,$la_opciones);
		$li_xpos=5+$ls_x+50;
		$io_pdf->convertir_valor_mm_px($li_xpos);		
		
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],$li_xpos,$li_pos-20,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->ezSetDy(-5);
		$la_data=array();
		$la_data[0]["1"]="<b>Banco:</b>";
		$la_data[0]["2"]="<b>Nota de Débito/Crédito No.:</b>";
		$la_data[0]["3"]="<b>Cuenta No. ".$ls_ctaban."</b>";			
		$la_anchos_col = array(62,61,62);
		$la_justificaciones = array("left","left","left");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>0,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2,
							   "margen_vertical"=>2);
		$io_pdf->add_tabla(5+$ls_x,$la_data,$la_opciones);
		
		$la_data=array();
		$la_data[0]["1"]="<b>".$ls_nomban."</b>";
		$la_data[0]["2"]="<b>".$ls_numdoc."</b>";
		$la_data[0]["3"]="<b>".$ls_ctaban."</b>";	
		$la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>2,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2,
							   "margen_vertical"=>2);
		$io_pdf->add_tabla(5+$ls_x,$la_data,$la_opciones);

		$la_data=array();
		$la_data[0]["1"]="<b>Orden(es) de Pago(s): ".$ls_solicitudes."</b>";
		$la_data[1]["1"]="<b>Beneficiario: ".$ls_nomproben."</b>";
		$la_data[2]["1"]="<b>Concepto: ".$ls_conmov."</b>";			
		$la_anchos_col = array(185);
		$la_justificaciones = array("left");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>2,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2,
							   "margen_vertical"=>3);
		$io_pdf->add_tabla(5+$ls_x,$la_data,$la_opciones);	
		$li_y_actual=$io_pdf->y;
	}// end function uf_print_cabecera_nd_nc_dp
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf,$x_pos)
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
		global $ls_x;
		global $ls_y;	
		$la_dataaux=array();
		$li_totrow=count($la_data);
		for($li_i=0;$li_i<$li_totrow;$li_i++)
		{
			$la_dataaux[$li_i]["estpro"]=$la_data[$li_i+1]["estpro"];
			$la_dataaux[$li_i]["spg_cuenta"]=$la_data[$li_i+1]["spg_cuenta"];
			$la_dataaux[$li_i]["monto_spg"]=$la_data[$li_i+1]["monto_spg"];
			$la_dataaux[$li_i]["scg_cuenta"]=$la_data[$li_i+1]["scg_cuenta"];
			$la_dataaux[$li_i]["debe"]=$la_data[$li_i+1]["debe"];
			$la_dataaux[$li_i]["haber"]=$la_data[$li_i+1]["haber"];
			
		}
		$la_titulos[0]["1"]="Registro Presupuesto";
		$la_titulos[0]["2"]="Cuenta";
		$la_titulos[0]["3"]="Monto";
		$la_titulos[0]["4"]="Registro Contable";
		$la_titulos[0]["5"]="Debe";
		$la_titulos[0]["6"]="Haber";
		$la_anchos_col = array(50,20,25,25,25,25);
		$la_justificaciones = array("center","center","center","center","center","center");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1);
		$io_pdf->add_tabla(4.5+$ls_x,$la_titulos,$la_opciones);
		$la_justificaciones = array("center","center","right","center","right","right");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1);		
		$io_pdf->add_tabla(4.5+$ls_x,$la_dataaux,$la_opciones);					
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_autorizacion(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_autorizacion
		//		    Acess: private 
		//	    Arguments: io_pdf // Objeto PDF
		//    Description: función el final del voucher 
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pie=$io_pdf->openObject();
		global $io_fun_nomina;
		$la_data[0]=array('elaborado'=>'<b>Elaborado por:</b>','presupuesto'=>'<b>Contabilidad:</b>','administracion'=>'<b>Revisado por Administración:</b>','presidencia'=>'<b>Gerencia General:</b>');
		$la_data[1]=array('elaborado'=>'','presupuesto'=>'','administracion'=>'','presidencia'=>'');
		$la_data[2]=array('elaborado'=>'','presupuesto'=>'','administracion'=>'','presidencia'=>'');
		$la_data[3]=array('elaborado'=>'','presupuesto'=>'','administracion'=>'','presidencia'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
						 'cols'=>array('elaborado'=>array('justification'=>'left','width'=>145),
			 						   'presupuesto'=>array('justification'=>'left','width'=>145),
						 			   'administracion'=>array('justification'=>'left','width'=>145), // Justificación y ancho de la columna
						 			   'presidencia'=>array('justification'=>'left','width'=>145))); // Justificación y ancho de la columna
		$la_columnas=array('elaborado'=>'','presupuesto'=>'','administracion'=>'','presidencia'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		
		$la_data=array(array('title'=>'<b>Recibi Conforme</b>  '));
		$la_columna=array('title'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('title'=>array('justification'=>'center','width'=>580))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		$la_data=array(array('nombre'=>'<b>Nombre:</b>  ','cedula'=>'<b>Cedula de Identidad:</b>  ','fecha'=>'<b>Fecha:</b>  ','firma'=>'<b>Firma:</b>  '),
						array('nombre'=>'','cedula'=>'','fecha'=>'','firma'=>''));
		$la_columna=array('nombre'=>'','cedula'=>'','fecha'=>'','firma'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>180),'cedula'=>array('justification'=>'left','width'=>120),
						 'fecha'=>array('justification'=>'left','width'=>100),'firma'=>array('justification'=>'left','width'=>180))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$io_pdf->ezText('                     ',10);
		$io_pdf->closeObject();
		$io_pdf->addObject($io_pie,'all');
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------
	function uf_pint_autorizacion_notas_depositos(&$io_pdf)
	{
		////////////////////////////////////////////////////////////////
		//       Function: uf_pint_autorizacion_notas_depositos
		//		    Acess: private 
		//	    Arguments: io_pdf // Objeto PDF
		//    Description: función que imprime el pie del voucher 
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 30/01/2007 
		///////////////////////////////////////////////////////////
		global $ls_x;
		global $ls_y;	
		$li_pos=245;
		$io_pdf->convertir_valor_mm_px($li_pos);		
		$io_pdf->ezSetY(792-$li_pos);
		$la_titulos[0]["1"]="<b>PREPARADO:</b>\n\n\n";
		$la_titulos[0]["2"]="<b>REVISADO:</b>";
		$la_titulos[0]["3"]="<b>CONTABILIZADO:</b>";
		$la_anchos_col = array(61.666666666666666666,61.66666666666666666,61.666666666666666);
		$la_justificaciones = array("center","center","center");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 8,
							   "lineas"=>2,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2);
		$io_pdf->add_tabla(4.5+$ls_x,$la_titulos,$la_opciones);
	}
	//-----------------------------------------------------------------------------------------------------
	function uf_convertir($ls_numero)
	{
		$ls_numero=str_replace(".","",$ls_numero);
		$ls_numero=str_replace(",",".",$ls_numero);
		return $ls_numero;
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	uf_inicializar_variables();
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_sql.php");
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	$io_sql=new class_sql($con);	
	require_once("sigesp_scb_report.php");
	$class_report=new sigesp_scb_report($con);
	$io_funciones=new class_funciones();				
	$ds_voucher=new class_datastore();	
	$ds_dt_scg=new class_datastore();				
	$ds_dt_spg=new class_datastore();
	//Instancio a la clase de conversión de numeros a letras.
	include("../../shared/class_folder/cnumero_letra.php");
	$numalet= new cnumero_letra();
	//imprime numero con los cambios
	
	
		$ls_tipimp = "";
	if (array_key_exists("tipimp",$_GET))
	{
		$ls_tipimp = $_GET["tipimp"];
	}	

	if ($ls_tipimp=='lote')
	{
		$ls_codemp		=$_SESSION["la_empresa"]["codemp"];
		$ls_codban      = $_GET["codban"];
		$ls_ctaban      = $_GET["ctaban"];
		$ls_documentos  = $_GET["documentos"];
		$ls_fechas      = $_GET["fechas"];
		$ld_fecdes      = $_GET["fecdesde"];
		$ld_fechas      = $_GET["fechasta"];
		$ls_operaciones = $_GET["operaciones"];
			
		//Descompongo la cadena de documentos en un arreglo tomando como separación el ','
		$arr_documentos = split(",",$ls_documentos);
		$li_totdoc		= count($arr_documentos);
		//Descompongo la cadena de fechas en un arreglo tomando como separación el '-'
		$arr_fecmov = split("-",$ls_fechas);
		$li_totfec  = count($arr_fecmov);
	   //Descompongo la cadena de operaciones en un arreglo tomando como separación el '-'
		$arr_operaciones = split("-",$ls_operaciones);
		$li_totdoc	= count($arr_operaciones);		
		$class_report->uf_buscar_cheques_vouchers($arr_documentos,$arr_fecmov,$arr_operaciones,$ls_codban,$ls_ctaban);
		
		$li_total   = $class_report->ds_voucher1->getRowCount("numdoc");

		if ($li_total>0)
		{	
			error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new class_pdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra	
			$io_pdf->set_margenes(0,0,$x_pos,0);	
		
			for ($i=1;$i<=$li_total;$i++)
			{
				$ls_numdoc=$class_report->ds_voucher1->getValue("numdoc",$i);
				
				$ls_chevau=$class_report->ds_voucher1->getValue("chevau",$i);
				$ls_codope='CH';
				
				$data=$class_report->uf_cargar_chq_voucher($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
				$class_report->SQL->begin_transaction();
				$lb_valido=$class_report->uf_actualizar_status_impreso($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
				if(!$lb_valido)
				{
					print "Error al actualizar";
					$class_report->is_msg_error;	
					$class_report->SQL->rollback();
				}
				else
				{
					$class_report->SQL->commit();
				}
				$ds_voucher->data=$data;
				$li_totrow=$ds_voucher->getRowCount("numdoc");
				$io_pdf->transaction('start'); // Iniciamos la transacción
				$thisPageNum=$io_pdf->ezPageCount;
				for ($li_i=1;$li_i<=$li_totrow;$li_i++)
					{
					  unset($la_data);
					  $li_totprenom=0;
					  $ldec_mondeb=0;
					  $ldec_monhab=0;
					  $li_totant=0;
					  $ls_numdoc	  = $ds_voucher->data["numdoc"][$li_i];
					  $ls_codban	  = $ds_voucher->data["codban"][$li_i];
					  $ls_nomban	  = $class_report->uf_select_data($io_sql,"SELECT nomban FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
					  $ls_ctaban	  = $ds_voucher->data["ctaban"][$li_i];
					  $ls_chevau	  = $ds_voucher->data["chevau"][$li_i];
					  $ld_fecmov	  = $io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
					  $ls_nomproben	  = $ds_voucher->data["nomproben"][$li_i];
					  $ls_solicitudes = $class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
					  $ls_conmov	  = $ds_voucher->getValue("conmov",$li_i);
					  $ldec_monret	  = $ds_voucher->getValue("monret",$li_i);
					  $ldec_monto	  = $ds_voucher->getValue("monto",$li_i);
					  $ldec_total	  = $ldec_monto-$ldec_monret;
					  $ls_monto		  = $numalet->uf_convertir_letra($ldec_total,'','');
					  uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$_SESSION["la_empresa"]["ciuemp"].", ".$ld_fecmov,$io_pdf,$y_pos); // Imprimimos el encabezado de la página
					  if (($ls_codope == "NC") || ($ls_codope == "ND") || ($ls_codope == "DP"))
						 {
						   uf_print_titulo($io_pdf);
						   uf_print_cabecera_notas_depositos($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_solicitudes,$ls_conmov,$ls_nomemp,$ls_nomproben,$io_pdf); // Imprimimos la cabecera del registro
						 }
					  else	
						uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_solicitudes,$ls_conmov,$ls_nomemp,$io_pdf); // Imprimimos la cabecera del registro
					$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope); // Obtenemos el detalle del reporte
					$ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
					$la_campos=array("scg_cuenta");
					$la_monto=array("monto");
					$ds_dt_scg->group_by($la_campos,$la_monto,"scg_cuenta");
					$la_campos=array("spg_cuenta","estpro");
					$ds_dt_spg->group_by($la_campos,$la_monto,"spg_cuenta");
					$li_totrow_det=$ds_dt_scg->getRowCount("scg_cuenta");
					$li_totrow_spg=$ds_dt_spg->getRowCount("spg_cuenta");
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					// Hago un ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a pintarlos
					if($li_totrow_det>=$li_totrow_spg)
					{
						for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
						{
							$ls_scg_cuenta=$ds_dt_scg->data["scg_cuenta"][$li_s];
							$ls_debhab = $ds_dt_scg->data["debhab"][$li_s];
							$ldec_monto= $ds_dt_scg->data["monto"][$li_s];
							if($ls_debhab=='D')
							{
								$ldec_mondeb=number_format($ldec_monto,2,",",".");
								$ldec_monhab=" ";
							}
							else
							{
								$ldec_monhab=number_format($ldec_monto,2,",",".");
								$ldec_mondeb=" ";
							}
							if(array_key_exists("spg_cuenta",$ds_dt_spg->data))
							{
								if(array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
								{
									$ls_cuentaspg=$ds_dt_spg->getValue("spg_cuenta",$li_s);	
									$ls_estpro=$ds_dt_spg->getValue("estpro",$li_s);	  
									$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
								}
								else
								{
									$ls_cuentaspg=" ";	
									$ls_estpro=" ";	  
									$ldec_monto_spg=" ";
								}
							}
							else
							{
								$ls_cuentaspg=" ";	
								$ls_estpro=" ";	  
								$ldec_monto_spg=" ";
							}
							$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
						}
					}
					if($li_totrow_spg>$li_totrow_det)
					{
						for($li_s=1;$li_s<=$li_totrow_spg;$li_s++)
						{
							if(array_key_exists("scg_cuenta",$ds_dt_scg->data))
							{
								if(array_key_exists($li_s,$ds_dt_scg->data["scg_cuenta"]))
								{
									$ls_scg_cuenta=$ds_dt_scg->data["scg_cuenta"][$li_s];
									$ls_debhab = $ds_dt_scg->data["debhab"][$li_s];
									$ldec_monto= $ds_dt_scg->data["monto"][$li_s];
									if($ls_debhab=='D')
									{
										$ldec_mondeb=number_format($ldec_monto,2,",",".");
										$ldec_monhab=" ";
									}
									else
									{
										$ldec_monhab=number_format($ldec_monto,2,",",".");
										$ldec_mondeb=" ";
									}
								}
								else
								{
									$ls_scg_cuenta="";
									$ls_debhab = "";
									$ldec_monto= "";
									$ldec_mondeb="";
									$ldec_monhab="";					
								}
							}
							else
							{
								$ls_scg_cuenta="";
								$ls_debhab = "";
								$ldec_monto= "";
								$ldec_mondeb="";
								$ldec_monhab="";					
							}
							if(array_key_exists("spg_cuenta",$ds_dt_spg->data))
							{
								if(array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
								{
									$ls_cuentaspg=$ds_dt_spg->getValue("spg_cuenta",$li_s);	
									$ls_estpro=$ds_dt_spg->getValue("estpro",$li_s);	  
									$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
								}
								else
								{
									$ls_cuentaspg=" ";	
									$ls_estpro=" ";	  
									$ldec_monto_spg=" ";
								}
							}
							else
							{
								$ls_cuentaspg=" ";	
								$ls_estpro=" ";	  
								$ldec_monto_spg=" ";
							}
							$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
						}
					}
					////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					if (empty($la_data))
					   {
						 $ls_cuentaspg='';
						 $ls_estpro='';
						 $ldec_monto_spg='';
						 $ls_scg_cuenta='';
						 $ldec_mondeb='';
						 $ldec_monhab='';
						 $la_data[1]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
						 $la_data[2]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
						 $la_data[3]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
					   }
				 if (($ls_codope=="NC") || ($ls_codope=="ND") || ($ls_codope=="DP"))
					{			
					  uf_print_detalle($la_data,$io_pdf,$x_pos); // Imprimimos el detalle 	
					  uf_pint_autorizacion_notas_depositos(&$io_pdf);// Imprimimos el detalle 	
					}		
				 else
					uf_print_detalle($la_data,$io_pdf,$x_pos); // Imprimimos el detalle 	
				} // Fin del for 2
			
				if ($i<$li_total)
				{			
					$io_pdf->ezNewPage(); // Insertar una nueva página
					$io_pdf->set_margenes(0,0,$x_pos,0);	
				}
		
			}// Fin del for 1
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
			unset($io_pdf,$class_report,$io_funciones,$ds_dt_spg,$ds_dt_scg,$ds_voucher,$la_data);
		}//Fin del if ($li_total>0)
		else
		{
			 print("<script language=JavaScript>");
			 print(" alert('No hay nada que Reportar');"); 
			 print(" close();");
			 print("</script>");	  
		} 
		
	} // Fin de si es lote
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SI NO ES POR LOTE ENTONCES
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	else 
	{
	
		$ls_codemp=$_SESSION["la_empresa"]["codemp"];
		$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
		$ls_codban=$_GET["codban"];
		$ls_ctaban=$_GET["ctaban"];
		$ls_numdoc=$_GET["numdoc"];
		$ls_chevau=$_GET["chevau"];
		$ls_codope=$_GET["codope"];				
	
		$data=$class_report->uf_cargar_chq_voucher($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
		$class_report->SQL->begin_transaction();
		$lb_valido=$class_report->uf_actualizar_status_impreso($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
		if(!$lb_valido)
		{
			print "Error al actualizar";
			$class_report->is_msg_error;	
			$class_report->SQL->rollback();
		}
		else
		{
			$class_report->SQL->commit();
		}
		$ds_voucher->data=$data;
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new class_pdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra	
		$io_pdf->set_margenes(0,0,$x_pos,0);	
		$li_totrow=$ds_voucher->getRowCount("numdoc");
		$io_pdf->transaction('start'); // Iniciamos la transacción
		$thisPageNum=$io_pdf->ezPageCount;
		for ($li_i=1;$li_i<=$li_totrow;$li_i++)
			{
			  unset($la_data);
			  $li_totprenom=0;
			  $ldec_mondeb=0;
			  $ldec_monhab=0;
			  $li_totant=0;
			  $ls_numdoc	  = $ds_voucher->data["numdoc"][$li_i];
			  $ls_codban	  = $ds_voucher->data["codban"][$li_i];
			  $ls_nomban	  = $class_report->uf_select_data($io_sql,"SELECT nomban FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
			  $ls_ctaban	  = $ds_voucher->data["ctaban"][$li_i];
			  $ls_chevau	  = $ds_voucher->data["chevau"][$li_i];
			  $ld_fecmov	  = $io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
			  $ls_nomproben	  = $ds_voucher->data["nomproben"][$li_i];
			  $ls_solicitudes = $class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
			  $ls_conmov	  = $ds_voucher->getValue("conmov",$li_i);
			  $ldec_monret	  = $ds_voucher->getValue("monret",$li_i);
			  $ldec_monto	  = $ds_voucher->getValue("monto",$li_i);
			  $ldec_total	  = $ldec_monto-$ldec_monret;
			  $ls_monto		  = $numalet->uf_convertir_letra($ldec_total,'','');
			  uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$_SESSION["la_empresa"]["ciuemp"].", ".$ld_fecmov,$io_pdf,$y_pos); // Imprimimos el encabezado de la página
			  if (($ls_codope == "NC") || ($ls_codope == "ND") || ($ls_codope == "DP"))
				 {
				   uf_print_titulo($io_pdf);
				   uf_print_cabecera_notas_depositos($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_solicitudes,$ls_conmov,$ls_nomemp,$ls_nomproben,$io_pdf); // Imprimimos la cabecera del registro
				 }
			  else	
				uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_solicitudes,$ls_conmov,$ls_nomemp,$io_pdf); // Imprimimos la cabecera del registro
			$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope); // Obtenemos el detalle del reporte
			$ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
			$la_campos=array("scg_cuenta");
			$la_monto=array("monto");
			$ds_dt_scg->group_by($la_campos,$la_monto,"scg_cuenta");
			$la_campos=array("spg_cuenta","estpro");
			$ds_dt_spg->group_by($la_campos,$la_monto,"spg_cuenta");
			$li_totrow_det=$ds_dt_scg->getRowCount("scg_cuenta");
			$li_totrow_spg=$ds_dt_spg->getRowCount("spg_cuenta");
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// Hago un ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a pintarlos
			if($li_totrow_det>=$li_totrow_spg)
			{
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{
					$ls_scg_cuenta=$ds_dt_scg->data["scg_cuenta"][$li_s];
					$ls_debhab = $ds_dt_scg->data["debhab"][$li_s];
					$ldec_monto= $ds_dt_scg->data["monto"][$li_s];
					if($ls_debhab=='D')
					{
						$ldec_mondeb=number_format($ldec_monto,2,",",".");
						$ldec_monhab=" ";
					}
					else
					{
						$ldec_monhab=number_format($ldec_monto,2,",",".");
						$ldec_mondeb=" ";
					}
					if(array_key_exists("spg_cuenta",$ds_dt_spg->data))
					{
						if(array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
						{
							$ls_cuentaspg=$ds_dt_spg->getValue("spg_cuenta",$li_s);	
							$ls_estpro=$ds_dt_spg->getValue("estpro",$li_s);	  
							$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
						}
						else
						{
							$ls_cuentaspg=" ";	
							$ls_estpro=" ";	  
							$ldec_monto_spg=" ";
						}
					}
					else
					{
						$ls_cuentaspg=" ";	
						$ls_estpro=" ";	  
						$ldec_monto_spg=" ";
					}
					$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
				}
			}
			if($li_totrow_spg>$li_totrow_det)
			{
				for($li_s=1;$li_s<=$li_totrow_spg;$li_s++)
				{
					if(array_key_exists("scg_cuenta",$ds_dt_scg->data))
					{
						if(array_key_exists($li_s,$ds_dt_scg->data["scg_cuenta"]))
						{
							$ls_scg_cuenta=$ds_dt_scg->data["scg_cuenta"][$li_s];
							$ls_debhab = $ds_dt_scg->data["debhab"][$li_s];
							$ldec_monto= $ds_dt_scg->data["monto"][$li_s];
							if($ls_debhab=='D')
							{
								$ldec_mondeb=number_format($ldec_monto,2,",",".");
								$ldec_monhab=" ";
							}
							else
							{
								$ldec_monhab=number_format($ldec_monto,2,",",".");
								$ldec_mondeb=" ";
							}
						}
						else
						{
							$ls_scg_cuenta="";
							$ls_debhab = "";
							$ldec_monto= "";
							$ldec_mondeb="";
							$ldec_monhab="";					
						}
					}
					else
					{
						$ls_scg_cuenta="";
						$ls_debhab = "";
						$ldec_monto= "";
						$ldec_mondeb="";
						$ldec_monhab="";					
					}
					if(array_key_exists("spg_cuenta",$ds_dt_spg->data))
					{
						if(array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
						{
							$ls_cuentaspg=$ds_dt_spg->getValue("spg_cuenta",$li_s);	
							$ls_estpro=$ds_dt_spg->getValue("estpro",$li_s);	  
							$ldec_monto_spg=number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
						}
						else
						{
							$ls_cuentaspg=" ";	
							$ls_estpro=" ";	  
							$ldec_monto_spg=" ";
						}
					}
					else
					{
						$ls_cuentaspg=" ";	
						$ls_estpro=" ";	  
						$ldec_monto_spg=" ";
					}
					$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
				}
			}
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if (empty($la_data))
			   {
				 $ls_cuentaspg='';
				 $ls_estpro='';
				 $ldec_monto_spg='';
				 $ls_scg_cuenta='';
				 $ldec_mondeb='';
				 $ldec_monhab='';
				 $la_data[1]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
				 $la_data[2]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
				 $la_data[3]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
			   }
		 if (($ls_codope=="NC") || ($ls_codope=="ND") || ($ls_codope=="DP"))
			{			
			  uf_print_detalle($la_data,$io_pdf,$x_pos); // Imprimimos el detalle 	
			  uf_pint_autorizacion_notas_depositos(&$io_pdf);// Imprimimos el detalle 	
			}		
		 else
			uf_print_detalle($la_data,$io_pdf,$x_pos); // Imprimimos el detalle 	
		}
		$io_pdf->ezStopPageNumbers(1,1);
		$io_pdf->ezStream();
		unset($io_pdf);
		unset($class_report);
		unset($io_funciones);
	}
?> 