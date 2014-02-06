<?php
    session_start();   
	ini_set('memory_limit','512M');
	ini_set('max_execution_time','0');

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/08/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_nomina->uf_load_seguridad_reporte("SNR","sigesp_snorh_r_listadopersonalgenerico.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_titulos($lo_titulo,&$lo_hoja)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_titulos
		//		   Access: private 
		//	    Arguments: lo_hoja // hoja en excel
		//    Description: función que los títulos del reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_total=$_SESSION["li_total"];
		for($li_i=0;($li_i<=$li_total);$li_i++)
		{
			$lo_hoja->set_column($li_i,$li_i,$_SESSION["la_titulos"][$li_i]["ancho"]);
		}
		for($li_i=0;($li_i<=$li_total);$li_i++)
		{
			$lo_hoja->write(3, $li_i, $_SESSION["la_titulos"][$li_i]["titulo"],$lo_titulo);
		}
	}// end function uf_print_titulos
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($lo_titulo,&$lo_hoja)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: lo_hoja // hoja en excel
		//    Description: función que los títulos del reporte
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_total=$_SESSION["li_total"];
		for($li_i=0;($li_i<=$li_total);$li_i++)
		{
			$lo_hoja->set_column($li_i,$li_i,$_SESSION["la_titulos"][$li_i]["ancho"]);
		}
		for($li_i=0;($li_i<=$li_total);$li_i++)
		{
			$lo_hoja->write(3, $li_i, $_SESSION["la_titulos"][$li_i]["titulo"],$lo_titulo);
		}
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_validar_campo($id,&$as_campo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_validar_campo
		//		   Access: private 
		//	    Arguments: id // Identificador del campo que se desea validar
		//				   as_campo // Contenido del campo que se desea validar
		//    Description: función que valida los campos 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 22/08/2007 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina, $io_funciones,$io_report;
		
		$ls_tipo=$_SESSION["la_titulos"][$id]["tipo"];
		switch($ls_tipo)
		{
			case "string":
				$as_campo=rtrim($as_campo);
				break;
			case "date":
				$as_campo=$io_funciones->uf_convertirfecmostrar($as_campo);
				break;		
			case "integer":
				$as_campo=number_format($as_campo,0,",",".");
				break;
			case "double":
				$as_campo=number_format($as_campo,2,",",".");
				break;
		}
		$ls_campo=$_SESSION["la_titulos"][$id]["campo"];
		switch($ls_campo)
		{
			case "sno_personal.nacper":
				switch($as_campo)
				{
					case "V":
						$as_campo="Venezolano";
						break;
					case "E":
						$as_campo="Estranjero";
						break;
				}
				break;
			case "sno_personal.sexper":
				switch($as_campo)
				{
					case "M":
						$as_campo="Masculino";
						break;
					case "F":
						$as_campo="Femenino";
						break;
				}
				break;
			case "sno_personal.edocivper":
				switch($as_campo)
				{
					case "S":
						$as_campo="Soltero";
						break;
					case "C":
						$as_campo="Casado";
						break;
					case "D":
						$as_campo="Divorciado";
						break;
					case "V":
						$as_campo="Viudo";
						break;
					case "K":
						$as_campo="Concubino";
						break;
				}
				break;
			case "sno_personal.nivacaper":
				switch($as_campo)
				{
					case "0":
						$as_campo="Ninguno";
						break;
					case "1":
						$as_campo="Primaria";
						break;
					case "2":
						$as_campo="Bachiller";
						break;
					case "3":
						$as_campo="Técnico Superior";
						break;
					case "4":
						$as_campo="Universitario";
						break;
					case "5":
						$as_campo="Maestria";
						break;
					case "6":
						$as_campo="PostGrado";
						break;
					case "7":
						$as_campo="Doctorado";
						break;
				}
				break;
			case "sno_personal.estper":
				switch($as_campo)
				{
					case "0":
						$as_campo="Pre-Ingreso";
						break;
					case "1":
						$as_campo="Activo";
						break;
					case "2":
						$as_campo="N/A";
						break;
					case "3":
						$as_campo="Egresado";
						break;
				}
				break;
			case "sno_personalnomina.staper":
				switch($as_campo)
				{
					case "0":
						$as_campo="N/A";
						break;
					case "1":
						$as_campo="Activo";
						break;
					case "2":
						$as_campo="Vacaciones";
						break;
					case "3":
						$as_campo="Egresado";
						break;
					case "4":
						$as_campo="Suspendido";
						break;
				}
				break;
			case "sno_personal.cauegrper":
				switch($as_campo)
				{
					case "N": // Ninguna
						$as_campo="Ninguna";
						break;
					case "D": // Despido
						$as_campo="Despido";
						break;
					case "R": // Renuncia
						$as_campo="Renuncia";
						break;
					case "J": // Jubilado
						$as_campo="Jubilado";
						break;
					case "P": // Pensionado
						$as_campo="Pensionado";
						break;
					case "T": // Traslado
						$as_campo="Traslado";
						break;
					case "F": // Fallecido
						$as_campo="Fallecido";
						break;
				}
				break;
				case "sno_personal.codorg":
					$as_campo=$io_report->uf_buscar_ubicacion_fisica($as_campo);
				break;
		}
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
	require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
	require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
	$lo_archivo = tempnam("/home/production/tmp", "listado_personal_generico.xls");
	$lo_libro = &new writeexcel_workbookbig($lo_archivo);
	$lo_hoja = &$lo_libro->addworksheet();
	
	//---------------------------------------------------------------------------------------------------------------------------
	// para crear la data necesaria del reporte
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_snorh_class_report.php");
	$io_report=new sigesp_snorh_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	//----------------------------------------------------  Parámetros del encabezado  -----------------------------------------------
	$ls_titulo=$_SESSION["ls_titulo"];
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	//---------------------------------------------------------------------------------------------------------------------------
	//Busqueda de la data 
	$lb_valido=uf_insert_seguridad("<b>Listado de Personal en Excel</b>"); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_listadogenerico(); // Obtenemos el detalle del reporte
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		//print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		$lo_encabezado= &$lo_libro->addformat();
		$lo_encabezado->set_bold();
		$lo_encabezado->set_font("Verdana");
		$lo_encabezado->set_align('center');
		$lo_encabezado->set_size('11');
		$lo_titulo= &$lo_libro->addformat();
		$lo_titulo->set_text_wrap();
		$lo_titulo->set_bold();
		$lo_titulo->set_font("Verdana");
		$lo_titulo->set_align('center');
		$lo_titulo->set_size('9');		
		$lo_datacenter= &$lo_libro->addformat();
		$lo_datacenter->set_font("Verdana");
		$lo_datacenter->set_align('center');
		$lo_datacenter->set_size('9');
		$lo_dataleft= &$lo_libro->addformat();
		$lo_dataleft->set_text_wrap();
		$lo_dataleft->set_font("Verdana");
		$lo_dataleft->set_align('left');
		$lo_dataleft->set_size('9');
		$lo_dataright= &$lo_libro->addformat(array(num_format => '#,##0.00'));
		$lo_dataright->set_font("Verdana");
		$lo_dataright->set_align('right');
		$lo_dataright->set_size('9');
		$lo_hoja->write(0,1,$ls_titulo,$lo_encabezado);
		uf_print_titulos($lo_titulo,&$lo_hoja);			
		$li_row=3;
		$li_totrow=$io_report->DS->getRowCount("codper");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$ls_campo0=$io_report->DS->data["campo0"][$li_i];
			if ($ls_campo0=='A')
				{
					$ls_campo0='AHORRO';
				}
				elseif ($ls_campo0=='C')
				{
					$ls_campo0='CORRIENTE';
				}
			$ls_campo1=$io_report->DS->data["campo1"][$li_i];
			if ($ls_campo1=='A')
				{
					$ls_campo1='AHORRO';
				}
				elseif ($ls_campo1=='C')
				{
					$ls_campo1='CORRIENTE';
				}
			$ls_campo2=$io_report->DS->data["campo2"][$li_i];
			if ($ls_campo2=='A')
				{
					$ls_campo2='AHORRO';
				}
				elseif ($ls_campo2=='C')
				{
					$ls_campo2='CORRIENTE';
				}
			$ls_campo3=$io_report->DS->data["campo3"][$li_i];
			if ($ls_campo3=='A')
				{
					$ls_campo3='AHORRO';
				}
				elseif ($ls_campo3=='C')
				{
					$ls_campo3='CORRIENTE';
				}
			$ls_campo4=$io_report->DS->data["campo4"][$li_i];
			if ($ls_campo4=='A')
				{
					$ls_campo4='AHORRO';
				}
				elseif ($ls_campo4=='C')
				{
					$ls_campo4='CORRIENTE';
				}
			$ls_campo5=$io_report->DS->data["campo5"][$li_i];
			if ($ls_campo5=='A')
				{
					$ls_campo5='AHORRO';
				}
				elseif ($ls_campo5=='C')
				{
					$ls_campo5='CORRIENTE';
				}
			$ls_campo6=$io_report->DS->data["campo6"][$li_i];
			if ($ls_campo6=='A')
				{
					$ls_campo6='AHORRO';
				}
				elseif ($ls_campo6=='C')
				{
					$ls_campo6='CORRIENTE';
				}
			$ls_campo7=$io_report->DS->data["campo7"][$li_i];
			if ($ls_campo7=='A')
				{
					$ls_campo7='AHORRO';
				}
				elseif ($ls_campo7=='C')
				{
					$ls_campo7='CORRIENTE';
				}
			$ls_campo8=$io_report->DS->data["campo8"][$li_i];
			if ($ls_campo8=='A')
				{
					$ls_campo8='AHORRO';
				}
				elseif ($ls_campo8=='C')
				{
					$ls_campo8='CORRIENTE';
				}
			$ls_campo9=$io_report->DS->data["campo9"][$li_i];
			if ($ls_campo9=='A')
				{
					$ls_campo9='AHORRO';
				}
				elseif ($ls_campo9=='C')
				{
					$ls_campo9='CORRIENTE';
				}
			$ls_campo10=$io_report->DS->data["campo10"][$li_i];
			if ($ls_campo10=='A')
				{
					$ls_campo10='AHORRO';
				}
				elseif ($ls_campo10=='C')
				{
					$ls_campo10='CORRIENTE';
				}
			$ls_campo11=$io_report->DS->data["campo11"][$li_i];
			if ($ls_campo11=='A')
				{
					$ls_campo11='AHORRO';
				}
				elseif ($ls_campo11=='C')
				{
					$ls_campo11='CORRIENTE';
				}
			$ls_campo12=$io_report->DS->data["campo12"][$li_i];
			if ($ls_campo12=='A')
				{
					$ls_campo12='AHORRO';
				}
				elseif ($ls_campo12=='C')
				{
					$ls_campo12='CORRIENTE';
				}
			$ls_campo13=$io_report->DS->data["campo13"][$li_i];
			if ($ls_campo13=='A')
				{
					$ls_campo13='AHORRO';
				}
				elseif ($ls_campo13=='C')
				{
					$ls_campo13='CORRIENTE';
				}
			$ls_campo14=$io_report->DS->data["campo14"][$li_i];
			if ($ls_campo14=='A')
				{
					$ls_campo14='AHORRO';
				}
				elseif ($ls_campo14=='C')
				{
					$ls_campo14='CORRIENTE';
				}
			$ls_campo15=$io_report->DS->data["campo15"][$li_i];
			if ($ls_campo15=='A')
				{
					$ls_campo15='AHORRO';
				}
				elseif ($ls_campo15=='C')
				{
					$ls_campo15='CORRIENTE';
				}
			$ls_campo16=$io_report->DS->data["campo16"][$li_i];
			if ($ls_campo16=='A')
				{
					$ls_campo16='AHORRO';
				}
				elseif ($ls_campo16=='C')
				{
					$ls_campo16='CORRIENTE';
				}
			$ls_campo17=$io_report->DS->data["campo17"][$li_i];
			if ($ls_campo17=='A')
				{
					$ls_campo17='AHORRO';
				}
				elseif ($ls_campo17=='C')
				{
					$ls_campo17='CORRIENTE';
				}
			$ls_campo18=$io_report->DS->data["campo18"][$li_i];
			if ($ls_campo18=='A')
				{
					$ls_campo18='AHORRO';
				}
				elseif ($ls_campo18=='C')
				{
					$ls_campo18='CORRIENTE';
				}
			$ls_campo19=$io_report->DS->data["campo19"][$li_i];
			if ($ls_campo19=='A')
				{
					$ls_campo19='AHORRO';
				}
				elseif ($ls_campo19=='C')
				{
					$ls_campo19='CORRIENTE';
				}
			$ls_campo20=$io_report->DS->data["campo20"][$li_i];
			if ($ls_campo20=='A')
				{
					$ls_campo20='AHORRO';
				}
				elseif ($ls_campo20=='C')
				{
					$ls_campo20='CORRIENTE';
				}
			$ls_campo21=$io_report->DS->data["campo21"][$li_i];
			if ($ls_campo21=='A')
				{
					$ls_campo21='AHORRO';
				}
				elseif ($ls_campo21=='C')
				{
					$ls_campo21='CORRIENTE';
				}
			$ls_campo22=$io_report->DS->data["campo22"][$li_i];
			if ($ls_campo22=='A')
				{
					$ls_campo22='AHORRO';
				}
				elseif ($ls_campo22=='C')
				{
					$ls_campo22='CORRIENTE';
				}
			$ls_campo23=$io_report->DS->data["campo23"][$li_i];
			if ($ls_campo23=='A')
				{
					$ls_campo23='AHORRO';
				}
				elseif ($ls_campo23=='C')
				{
					$ls_campo23='CORRIENTE';
				}
			$ls_campo24=$io_report->DS->data["campo24"][$li_i];
			if ($ls_campo24=='A')
				{
					$ls_campo24='AHORRO';
				}
				elseif ($ls_campo24=='C')
				{
					$ls_campo24='CORRIENTE';
				}
			$ls_campo25=$io_report->DS->data["campo25"][$li_i];
			if ($ls_campo25=='A')
				{
					$ls_campo25='AHORRO';
				}
				elseif ($ls_campo25=='C')
				{
					$ls_campo25='CORRIENTE';
				}
			$ls_campo26=$io_report->DS->data["campo26"][$li_i];
			if ($ls_campo26=='A')
				{
					$ls_campo26='AHORRO';
				}
				elseif ($ls_campo26=='C')
				{
					$ls_campo26='CORRIENTE';
				}
			$ls_campo27=$io_report->DS->data["campo27"][$li_i];
			if ($ls_campo27=='A')
				{
					$ls_campo27='AHORRO';
				}
				elseif ($ls_campo27=='C')
				{
					$ls_campo27='CORRIENTE';
				}
			$ls_campo28=$io_report->DS->data["campo28"][$li_i];
			if ($ls_campo28=='A')
				{
					$ls_campo28='AHORRO';
				}
				elseif ($ls_campo28=='C')
				{
					$ls_campo28='CORRIENTE';
				}
			$ls_campo29=$io_report->DS->data["campo29"][$li_i];
			if ($ls_campo29=='A')
				{
					$ls_campo29='AHORRO';
				}
				elseif ($ls_campo29=='C')
				{
					$ls_campo29='CORRIENTE';
				}
			$ls_campo30=$io_report->DS->data["campo30"][$li_i];
			if ($ls_campo30=='A')
				{
					$ls_campo30='AHORRO';
				}
				elseif ($ls_campo30=='C')
				{
					$ls_campo30='CORRIENTE';
				}
			$ls_campo31=$io_report->DS->data["campo31"][$li_i];
			if ($ls_campo31=='A')
				{
					$ls_campo31='AHORRO';
				}
				elseif ($ls_campo31=='C')
				{
					$ls_campo31='CORRIENTE';
				}
			$ls_campo32=$io_report->DS->data["campo32"][$li_i];
			if ($ls_campo32=='A')
				{
					$ls_campo32='AHORRO';
				}
				elseif ($ls_campo32=='C')
				{
					$ls_campo32='CORRIENTE';
				}
			$ls_campo33=$io_report->DS->data["campo33"][$li_i];
			if ($ls_campo33=='A')
				{
					$ls_campo33='AHORRO';
				}
				elseif ($ls_campo33=='C')
				{
					$ls_campo33='CORRIENTE';
				}
			$ls_campo34=$io_report->DS->data["campo34"][$li_i];
			if ($ls_campo34=='A')
				{
					$ls_campo34='AHORRO';
				}
				elseif ($ls_campo34=='C')
				{
					$ls_campo34='CORRIENTE';
				}
			$ls_campo35=$io_report->DS->data["campo35"][$li_i];
			if ($ls_campo35=='A')
				{
					$ls_campo35='AHORRO';
				}
				elseif ($ls_campo35=='C')
				{
					$ls_campo35='CORRIENTE';
				}
			$ls_campo36=$io_report->DS->data["campo36"][$li_i];
			if ($ls_campo36=='A')
				{
					$ls_campo36='AHORRO';
				}
				elseif ($ls_campo36=='C')
				{
					$ls_campo36='CORRIENTE';
				}
			$ls_campo37=$io_report->DS->data["campo37"][$li_i];
			if ($ls_campo37=='A')
				{
					$ls_campo37='AHORRO';
				}
				elseif ($ls_campo37=='C')
				{
					$ls_campo37='CORRIENTE';
				}
			$ls_campo38=$io_report->DS->data["campo38"][$li_i];
			if ($ls_campo38=='A')
				{
					$ls_campo38='AHORRO';
				}
				elseif ($ls_campo38=='C')
				{
					$ls_campo38='CORRIENTE';
				}
			uf_validar_campo(0,&$ls_campo0);
			uf_validar_campo(1,&$ls_campo1);
			uf_validar_campo(2,&$ls_campo2);
			uf_validar_campo(3,&$ls_campo3);
			uf_validar_campo(4,&$ls_campo4);
			uf_validar_campo(5,&$ls_campo5);
			uf_validar_campo(6,&$ls_campo6);
			uf_validar_campo(7,&$ls_campo7);
			uf_validar_campo(8,&$ls_campo8);
			uf_validar_campo(9,&$ls_campo9);
			uf_validar_campo(10,&$ls_campo10);
			uf_validar_campo(11,&$ls_campo11);
			uf_validar_campo(12,&$ls_campo12);
			uf_validar_campo(13,&$ls_campo13);
			uf_validar_campo(14,&$ls_campo14);
			uf_validar_campo(15,&$ls_campo15);
			uf_validar_campo(16,&$ls_campo16);
			uf_validar_campo(17,&$ls_campo17);
			uf_validar_campo(18,&$ls_campo18);
			uf_validar_campo(19,&$ls_campo19);
			uf_validar_campo(20,&$ls_campo20);
			uf_validar_campo(21,&$ls_campo21);
			uf_validar_campo(22,&$ls_campo22);
			uf_validar_campo(23,&$ls_campo23);
			uf_validar_campo(24,&$ls_campo24);
			uf_validar_campo(25,&$ls_campo25);
			uf_validar_campo(26,&$ls_campo26);
			uf_validar_campo(27,&$ls_campo27);
			uf_validar_campo(28,&$ls_campo28);
			uf_validar_campo(29,&$ls_campo29);
			uf_validar_campo(30,&$ls_campo30);
			uf_validar_campo(31,&$ls_campo31);
			uf_validar_campo(32,&$ls_campo32);
			uf_validar_campo(33,&$ls_campo33);
			uf_validar_campo(34,&$ls_campo34);
			uf_validar_campo(35,&$ls_campo35);
			uf_validar_campo(36,&$ls_campo36);
			uf_validar_campo(37,&$ls_campo37);
			uf_validar_campo(38,&$ls_campo38);

			$la_campos[0]=$ls_campo0;
			$la_campos[1]=$ls_campo1;
			$la_campos[2]=$ls_campo2;
			$la_campos[3]=$ls_campo3;
			$la_campos[4]=$ls_campo4;
			$la_campos[5]=$ls_campo5;
			$la_campos[6]=$ls_campo6;
			$la_campos[7]=$ls_campo7;
			$la_campos[8]=$ls_campo8;
			$la_campos[9]=$ls_campo9;
			$la_campos[10]=$ls_campo10;
			$la_campos[11]=$ls_campo11;
			$la_campos[12]=$ls_campo12;
			$la_campos[13]=$ls_campo13;
			$la_campos[14]=$ls_campo14;
			$la_campos[15]=$ls_campo15;
			$la_campos[16]=$ls_campo16;
			$la_campos[17]=$ls_campo17;
			$la_campos[18]=$ls_campo18;
			$la_campos[19]=$ls_campo19;
			$la_campos[20]=$ls_campo20;
			$la_campos[21]=$ls_campo21;
			$la_campos[22]=$ls_campo22;
			$la_campos[23]=$ls_campo23;
			$la_campos[24]=$ls_campo24;
			$la_campos[25]=$ls_campo25;
			$la_campos[26]=$ls_campo26;
			$la_campos[27]=$ls_campo27;
			$la_campos[28]=$ls_campo28;
			$la_campos[29]=$ls_campo29;
			$la_campos[30]=$ls_campo30;
			$la_campos[31]=$ls_campo31;
			$la_campos[32]=$ls_campo32;
			$la_campos[33]=$ls_campo33;
			$la_campos[34]=$ls_campo34;
			$la_campos[35]=$ls_campo35;
			$la_campos[36]=$ls_campo36;
			$la_campos[37]=$ls_campo37;
			$la_campos[38]=$ls_campo38;

			$li_row=$li_row+1;
			for($li_k=0;($li_k<=$_SESSION["li_total"]);$li_k++)
			{
				switch($_SESSION["la_titulos"][$li_k]["alineacion"])
				{
					case "center":
						$lo_hoja->write($li_row, $li_k, $la_campos[$li_k]." ", $lo_datacenter);
						break;
					case "left":
						$lo_hoja->write($li_row, $li_k, $la_campos[$li_k]." ", $lo_dataleft);
						break;
					case "right":
						$lo_hoja->write($li_row, $li_k, $la_campos[$li_k], $lo_dataright);
						break;
				}
			}
		}
		$io_report->DS->resetds("codper");
		$lo_libro->close();
		header("Content-Type: application/x-msexcel; name=\"listado_personal_generico.xls\"");
		header("Content-Disposition: inline; filename=\"listado_personal_generico.xls\"");
		$fh=fopen($lo_archivo, "rb");
		fpassthru($fh);
		unlink($lo_archivo);
		print("<script language=JavaScript>");
		//print(" close();");
		print("</script>");
		unset($io_pdf);
	}
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 