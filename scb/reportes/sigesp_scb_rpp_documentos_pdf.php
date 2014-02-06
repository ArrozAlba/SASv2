<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();";
		print "close();";
		print "</script>";		
	}
	ini_set('memory_limit','1024M');
	ini_set('max_execution_time ','0');
	//--------------------------------------------------------------------------------------------------------------------------------	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$ad_fecdesde,$ad_fechasta,$ls_nomban,$ls_tipcta,$ls_ctaban,$ls_tipolistado,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 06/02/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$li_alto=$_SESSION["ls_height"];
		$io_pdf->convertir_valor_px_mm($li_alto);
		$li_ancho=$_SESSION["ls_width"];
		$io_pdf->convertir_valor_px_mm($li_ancho);
		$li_altura_logo=20;
		$io_pdf->convertir_valor_mm_px($li_altura_logo);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],40,(792-$li_altura_logo),$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->add_texto(65,15,13,$as_titulo);
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		
		//Fecha y hora
		$io_pdf->add_texto(142,25,9,"<b>Pág. </b>");
		$io_pdf->add_texto(142,30,9,"<b>Fecha de Impresión: ".date("d/m/Y")."</b>");
		$io_pdf->add_texto(142,35,9,"<b>Hora de Impresión: ".date("G:i:s")."</b>");		
		$io_pdf->add_texto(10,25,9,"<b><i>".$_SESSION["la_empresa"]["nombre"]."</i></b>");
		//Banco
		$io_pdf->add_texto(10,30,10,"<b>BANCO:                    ".$ls_nomban."</b>");
		//Tipo de Cuenta
		$io_pdf->add_texto(10,35,10,"<b>TIPO DE CUENTA:  ".$ls_tipcta."</b>");
		//Cuenta
		$io_pdf->add_texto(10,40,10,"<b>CUENTA:                  ".$ls_ctaban."</b>");
		//Listado de 
		$io_pdf->add_texto(10,50,10,"<b>LISTADO DE:           ".$ls_tipolistado."</b>");		
		//Rango de Fechas
		if(($ad_fecdesde!="") && ($ad_fechasta!=""))
			$io_pdf->add_texto(10,60,9,"<b>PERIODO DESDE: ".$ad_fecdesde." HASTA ".$ad_fechasta."</b>");	
		elseif(($ad_fecdesde!="") && ($ad_fechasta==""))
			$io_pdf->add_texto(10,60,9,"<b>PERIODO DESDE: ".$ad_fecdesde." HASTA LA FECHA ACTUAL</b>");	
		elseif(($ad_fecdesde=="") && ($ad_fechasta!=""))
			$io_pdf->add_texto(10,60,9,"<b>PERIODO HASTA: ".$ad_fechasta."</b>");
		else
			$io_pdf->add_texto(10,60,9,"<b>TODAS LAS FECHAS</b>");		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_banco($ls_banco, $ls_cuenta, &$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_banco
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el banco y su respectiva cuenta
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 07/12/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		 $io_pdf->add_lineas(1);
		 $la_data[0]["1"]="<b>Banco:</b>";
		 $la_data[0]["2"]="<b>$ls_banco</b>";
		 $la_data[1]["1"]="<b>Cuenta:</b>";
		 $la_data[1]["2"]="<b>$ls_cuenta</b>";
		 $la_anchos_col = array(15,170);
		 $la_justificaciones = array("left","left");
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 10,
							   "lineas"=>0,
							   "color_fondo"=>array(242,242,242),
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2,
							   "margen_vertical"=>2);
		 $io_pdf->add_tabla(7,$la_data,$la_opciones);
		 $io_pdf->add_lineas(1);
		 
	}// end function uf_print_detalle	

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_dataimprimir,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: $la_data
		//	    		   io_pdf // 
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. laura Cabre
		// Fecha Creación: 06/02/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->set_margenes(70,40,3,3);

		$la_data[0]["1"]="<b>Nro</b>";
		$la_data[0]["2"]="<b>Documento</b>";
		$la_data[0]["3"]="<b>Fecha</b>";
		$la_data[0]["4"]="<b>Proveedor/Beneficiario</b>";
		$la_data[0]["5"]="<b>Concepto</b>";
		$la_data[0]["6"]="<b>Monto</b>";
		$la_data[0]["7"]="<b>Estatus</b>";
		$la_anchos_col = array(11,30,18,45,50,27,15);
		$la_justificaciones = array("center","center","center","center","center","center","center");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "color_fondo"=>array(219,219,219),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>2,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2,
							   "margen_vertical"=>3);
		$io_pdf->add_tabla(7,$la_data,$la_opciones);
		//Datos
		$li_totrow=count($la_dataimprimir);
		$la_dataaux=array();
		for($li_i=0;$li_i<$li_totrow;$li_i++)
		{
			$la_dataaux[$li_i]["numero"]=($li_i+1);
			$la_dataaux[$li_i]["documento"]=$la_dataimprimir[$li_i+1]["documento"];			
			$la_dataaux[$li_i]["fecha"]=$la_dataimprimir[$li_i+1]["fecha"];
			$la_dataaux[$li_i]["proveedor"]=$la_dataimprimir[$li_i+1]["proveedor"];		
			$la_dataaux[$li_i]["conmov"]=$la_dataimprimir[$li_i+1]["conmov"];	
			$la_dataaux[$li_i]["monto"]=$la_dataimprimir[$li_i+1]["monto"];									
			$la_dataaux[$li_i]["status"]=$la_dataimprimir[$li_i+1]["status"];						
		}		
		$la_justificaciones = array("center","center","center","left","left","right","center");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "color_fondo"=>array(255,255,255),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>2,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2,
							   "margen_vertical"=>2);	
		$io_pdf->add_tabla(7,$la_dataaux,$la_opciones);	
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($ad_debitos,$ad_creditos,$ad_total,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private 
		//	    Arguments: as_numdoc // Número del documento
		//	    		   as_conmov // concepto del documento
		//	    		   as_nomproben // nombre del proveedor beneficiario
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Laura Cabre
		// Fecha Creación: 05/02/2007
		/////////////////////////////////////////////////////////////////////////////////
		$li_pos=$io_pdf->y;
		$la_data=array();
		$la_data[0]["1"]="<b>Total Créditos</b>";
		$la_data[0]["2"]="<b>".$ad_creditos."</b>";
		$la_data[0]["3"]="";
		$la_data[1]["1"]="<b>Total Débitos</b>";
		$la_data[1]["2"]="<b>".$ad_debitos."</b>";
		$la_data[1]["3"]="";
		$la_data[2]["1"]="<b>Total Saldo</b>";
		$la_data[2]["2"]="<b>".$ad_total."</b>";
		$la_data[2]["3"]="";
		
		$la_anchos_col = array(154,30,12);
		$la_justificaciones = array("right","right","left");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "color_fondo"=>array(219,219,219),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2,
							   "margen_vertical"=>3);
		$io_pdf->add_tabla(7,$la_data,$la_opciones);
		//Linea de REVISADO POR
		$li_pos=$io_pdf->get_alto_usado();
		$li_pos+=90;
		
		//Para que la lnea la imprima negra y no gris
		$la_opciones = array("color_texto" => array(0,0,0),
							   "color_fondo"=>array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>0,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2,
							   "margen_vertical"=>3);
		$io_pdf->add_tabla(300,$la_data,$la_opciones);		
		$io_pdf->set_margenes(3,3,3,3);	
		$io_pdf->add_linea(130,$li_pos,180,$li_pos)	;
		$io_pdf->add_texto(143,$li_pos,9,"<b>REVISADO POR</b>");
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_total_anulados($ad_totanu,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_total_anulados
		//		   Access: private 
		//	    Arguments: as_numdoc // Número del documento
		//	    		   as_conmov // concepto del documento
		//	    		   as_nomproben // nombre del proveedor beneficiario
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Néstor Falcón.
		// Fecha Creación: 05/02/2007
		/////////////////////////////////////////////////////////////////////////////////

		$li_pos    = $io_pdf->y;
		$la_data   = array();
		$ad_totanu = number_format($ad_totanu,2,',','.');
		$la_data[0]["1"]="<b>Total Anulados</b>";
		$la_data[0]["2"]="<b>".$ad_totanu."</b>";
		$la_data[0]["3"]="";
		
		$la_anchos_col = array(154,30,12);
		$la_justificaciones = array("right","right","left");
		$la_opciones = array("color_texto" => array(0,0,0),
							   "color_fondo"=>array(219,219,219),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2,
							   "margen_vertical"=>3);
		$io_pdf->add_tabla(7,$la_data,$la_opciones);
		//Linea de REVISADO POR
		$li_pos=$io_pdf->get_alto_usado();
		$li_pos+=90;
		
		//Para que la lnea la imprima negra y no gris
		$la_opciones = array("color_texto" => array(0,0,0),
							   "color_fondo"=>array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 9,
							   "lineas"=>0,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>2,
							   "margen_vertical"=>3);
		$io_pdf->add_tabla(300,$la_data,$la_opciones);		
		$io_pdf->set_margenes(3,3,3,3);	
	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------
	
	function uf_print_pie(&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el pie del reporte
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 06/02/2007 
		////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->add_texto(10,250,8,"C: DOCUMENTOS CONTABILIZADOS");
		$io_pdf->add_texto(73,250,8,"N: DOCUMENTOS POR CONTABILIZAR");
		$io_pdf->add_texto(150,250,8,"A: DOCUMENTOS ANULADOS");
		$io_pdf->add_texto(10,255,8,"O: DOCUMENTOS ORIGINALES");
		$io_pdf->add_texto(73,255,8,"L: DOCUMENTOS SIN AFECTACION CONTABLE");
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}
	

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("sigesp_scb_class_report.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/sigesp_include.php");

	$sig_inc   = new sigesp_include();
	$con       = $sig_inc->uf_conectar();
	$io_report = new sigesp_scb_class_report($con);
	$in        = new sigesp_include();
	$con       = $in->uf_conectar();
	$io_sql    = new class_sql($con);	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp    	= $_SESSION["la_empresa"]["codemp"];
	$ld_fecdesde  	= $_GET["fecdes"];
	$ld_fechasta  	= $_GET["fechas"];
	$ls_codope    	= $_GET["codope"];
	$ls_codban    	= $_GET["codban"];
	$ls_nomban    	= $_GET["nomban"];
	$ls_ctaban    	= $_GET["ctaban"];
	$ls_codconcep   = $_GET["codconcep"];
	$ls_orden     	= $_GET["orden"];
	$ls_estatus     = $_GET["hidestmov"];
	$ls_tipbol      = 'Bs.';
	$ls_tiporeporte = 0;
	$ls_tiporeporte = $_GET["tiporeporte"];
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_scb_class_reportbsf.php");
		$io_report = new sigesp_scb_class_reportbsf($con);
		$ls_tipbol = 'Bs.F.';
	}
	$ls_titulo    		= "<b>Listado de Movimientos Bancarios $ls_tipbol</b>";
	$ldec_totaldebitos  = 0;
	$ldec_totalcreditos = 0;
	$ldec_saldo         = 0;
	$lb_valido          = true;
	$rs_data            = $io_report->uf_cargar_documentos($ls_codope,$ld_fecdesde,$ld_fechasta,$ls_codban,$ls_ctaban,$ls_codconcep,$ls_estatus,$ls_orden,&$lb_valido);
	if($lb_valido)
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new class_pdf('LETTER','portrait');
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->set_margenes(3,3,3,3);	 // Configuración de los margenes en centímetros
		$ls_nomtipcta = $io_report->uf_select_data($io_sql,"SELECT nomtipcta FROM scb_tipocuenta t, scb_ctabanco c WHERE c.codemp='".$ls_codemp."' AND c.codtipcta=t.codtipcta AND c.ctaban='".$ls_ctaban."'","nomtipcta");
		
		switch($ls_codope)
		{
			case "ND":
				$ls_tipolistado="NOTA DE DEBITO";
			break;
			case "NC":
				$ls_tipolistado="NOTA DE CREDITO";
			break;
			case "DP":
				$ls_tipolistado="DEPOSITO";
			break;
			case "CH":
				$ls_tipolistado="CHEQUE";
			break;
			case "RE":
				$ls_tipolistado="RETIRO";
			break;
			default:
				$ls_tipolistado="TODO TIPO DE DOCUMENTO";
			break;
		}
		uf_print_encabezado_pagina($ls_titulo,$ld_fecdesde,$ld_fechasta,$ls_nomban,$ls_nomtipcta,$ls_ctaban,$ls_tipolistado,$io_pdf); // Imprimimos el encabezado de la página
		uf_print_pie($io_pdf);
		$li_x=163;
		$li_y=31;
		$io_pdf->convertir_valor_mm_px($li_x);
		$io_pdf->convertir_valor_mm_px($li_y);
		$io_pdf->ezStartPageNumbers($li_x,792-$li_y,9,'','',1); // Insertar el número de página
        $i=0;
		$ld_totanu = 0;
		$ld_totdeb = 0;
		$ld_totcre = 0;
		while ($row=$io_sql->fetch_row($rs_data))
		      {
	            $i++;
				$io_pdf->transaction('start'); // Iniciamos la transacción
			    $li_numpag    = $io_pdf->ezPageCount; // Número de página			
			    $ls_numdoc    = $row["numdoc"];
			    $ldec_monto   = $row["monto"]; 
			    $ld_fecmov    = $row["fecmov"];
			    $ld_fecmov    = $io_report->fun->uf_convertirfecmostrar($ld_fecmov);
			    $ls_nombre    = $row["nomproben"];
			    $ls_codope    = $row["codope"]; 
			    $ls_conmov    = $row["conmov"];
			    $ls_estbpd    = $row["estbpd"];
				$ls_estmov    = $row["estmov"];
				$ls_numcarord = $row["numcarord"];
				if ($ls_estbpd=='T')
				   {
				     $ls_numdoc = $ls_numcarord;
				   } 
				if ($ls_estatus=='A')
				   {
				     $ld_totanu = ($ld_totanu+$ldec_monto);
				   }
				if (strlen($ls_conmov)>48)
				   {
					 $ls_conmov=substr($ls_conmov,0,46)."..";
				   }
				switch($ls_codope)
				{
					case "ND":
                      if ($ls_estmov=='A')
					     {
						   $ld_totdeb = ($ld_totdeb+$ldec_monto);
						 }
					  else
					     {
						   $ld_totcre = ($ld_totcre+$ldec_monto);						   
						 }
					break;
					case "NC":
                      if ($ls_estmov=='A')
					     {
						   $ld_totcre = ($ld_totcre+$ldec_monto);						   
						 }
					  else
					     {
						   $ld_totdeb = ($ld_totdeb+$ldec_monto);
						 }
					break;
					case "DP":
                      if ($ls_estmov=='A')
					     {
						   $ld_totcre = ($ld_totcre+$ldec_monto);						   
						 }
					  else
					     {
						   $ld_totdeb = ($ld_totdeb+$ldec_monto);
						 }
					break;
					case "CH":
                      if ($ls_estmov=='A')
					     {
						   $ld_totdeb = ($ld_totdeb+$ldec_monto);
						 }
					  else
					     {
						   $ld_totcre = ($ld_totcre+$ldec_monto);						   
						 }
					break;
					case "RE":
                      if ($ls_estmov=='A')
					     {
						   $ld_totdeb = ($ld_totdeb+$ldec_monto);
						 }
					  else
					     {
						   $ld_totcre = ($ld_totcre+$ldec_monto);						   
						 }
					break;
				}
				$ld_mon=number_format($ldec_monto,2,",",".");
				if(($ls_codban!=$row["codban"]) || (trim($ls_ctaban)!=trim($row["ctaban"])))
				{
					uf_print_detalle($la_data,&$io_pdf);
					$ls_codban = $row["codban"];
					$ls_cuenta = $row["ctaban"];
					$ls_nomban = $row["nomban"];
					$la_data   = array();				
				}
			$la_data[$i]=array('documento'=>$ls_numdoc,'proveedor'=>$ls_nombre,
							   'operacion'=>$ls_codope,'fecha'=>$ld_fecmov,'monto'=>$ld_mon,'status'=>$ls_estmov,'conmov'=>$ls_conmov);		
			
		}
		uf_print_detalle($la_data,&$io_pdf);		
		if ($ls_estatus=='A')
		   {
		     uf_print_total_anulados($ld_totanu,&$io_pdf);
		   }
		else
		   {
			 $ld_saldo  = $ld_totcre-$ld_totdeb;//Calculo del saldo total para todas las cuentas
			 $ld_totcre = number_format($ld_totcre,2,",",".");
			 $ld_totdeb = number_format($ld_totdeb,2,",",".");
			 $ld_saldo  = number_format($ld_saldo,2,",",".");
		     uf_print_totales($ld_totdeb,$ld_totcre,$ld_saldo,&$io_pdf);   
		   }
		if($lb_valido) // Si no ocurrio ningún error
		{
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		}
		else  // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
			print(" close();");
			print("</script>");		
		}
		unset($io_pdf);
	}
	else
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
?> 