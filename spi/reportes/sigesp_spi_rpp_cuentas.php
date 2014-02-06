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
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_periodo,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: ldec_monto : Monto del cheque
		//	    		   ls_nomproben:  Nombre del proveedor o beneficiario
		//	    		   ls_monto : Monto en letras
		//	    		   ls_fecha : Fecha del cheque
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 27/09/2006 
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,700,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,718,11,$as_periodo); // Agregar el título
		$io_pdf->addText(500,730,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');		
	}// end function uf_print_encabezadopagina

	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_columna,$la_config,$la_data,&$io_pdf)
	{
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yozelin Barragan
		// Fecha Creación: 27/09/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_nomina;

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	require_once("../../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($con);	
	$io_sql2=new class_sql($con);	
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../../shared/class_folder/class_datastore.php");
	$ds_prog=new class_datastore();	
	$ds_ctas=new class_datastore();	
	
    $io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(3.5,3.5,3.5,3.5); // Configuración de los margenes en centímetros
	uf_print_encabezado_pagina("Listado de Cuentas Presupuestarias "," ",$io_pdf); // Imprimimos el encabezado de la página
	$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página

		$io_pdf->transaction('start'); // Iniciamos la transacción
		$thisPageNum=$io_pdf->ezPageCount;
		$li_totprenom=0;
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$li_totant=0;
		unset($la_data);
		unset($la_data_ctas);
		$ls_sql="SELECT a.spg_cuenta as spg_cuenta,a.denominacion as denspg,a.sc_cuenta as sc_cuenta,a.status status ,
		                b.denominacion as denscg
				 FROM spg_cuentas a,scg_cuentas b 
				 WHERE a.codemp='".$ls_codemp."'  AND a.codemp=b.codemp AND a.sc_cuenta=b.sc_cuenta
				 AND CONCAT(a.codestpro1,a.codestpro2,a.codestpro3)='".$ls_codestpro1.$ls_codestpro2.$ls_codestpro3."' ".$ls_aux;
		$rs_data2=$io_sql2->select($ls_sql);
		if($rs_data2===false)
		{
			
		}
		else	
		{
			$ds_ctas->data=$io_sql2->obtener_datos($rs_data2);
		}
		$li_totspg=$ds_ctas->getRowCount("spg_cuenta");
		if($li_totspg>0)
		{
			uf_print_detalle($la_columna,$la_config,$la_data,$io_pdf); // Imprimimos el detalle 
		}
		for($li_a=1;$li_a<=$li_totspg;$li_a++)
		{
			$ls_cuenta      = $ds_ctas->getValue("spg_cuenta",$li_a);
			$ls_denominacion= $ds_ctas->getValue("denspg",$li_a);
			$ls_cuenta_scg  = $ds_ctas->getValue("sc_cuenta",$li_a);
			$ls_status      = $ds_ctas->getValue("status",$li_a);
			$ls_denscg      = $ds_ctas->getValue("denscg",$li_a);
			if($ls_status=='C')
			{
				$la_data_ctas[$li_a] = array('cuenta'=>'<b>'.$ls_cuenta.'</b>','denominacion'=>'<b>'.$ls_denominacion.'</b>','cuenta_scg'=>'<b>'.$ls_cuenta_scg.'</b>','denscg'=>'<b>'.$ls_denscg.'</b>');
			}
			else
			{
				$la_data_ctas[$li_a] = array('cuenta'=>$ls_cuenta,'denominacion'=>$ls_denominacion,'cuenta_scg'=>' ','denscg'=>' ');
			}
			$la_columna     = array('cuenta'=>'<b>Cuenta</b>   ','denominacion'=>"<b>Denominacion Cta. Presupuestaria</b>",'cuenta_scg'=>"<b>Cuenta Contable</b>",'denscg'=>'<b>Denominacion Cta.Contable</b>');
			$la_config      = array('showHeadings'=>1, // Mostrar encabezados
									'showLines'=>1, // Mostrar Líneas
									'shaded'=>0, // Sombra entre líneas
									'shadeCol'=>array(0.95,0.95,0.95), // Color de la sombra
									'shadeCol2'=>array(1.5,1.5,1.5), // Color de la sombra
									'xOrientation'=>'center', // Orientación de la tabla
									'width'=>550, // Ancho de la tabla
									'maxWidth'=>550,
									'cols'=>array('cuenta'=>array('justification'=>'center','width'=>80) ,'denominacion'=>array('justification'=>'left','width'=>190),
												  'cuenta_scg'=>array('justification'=>'center','width'=>90),'denominacion'=>array('justification'=>'left','width'=>190))); // Ancho Máximo de la tabla
		}
		if($li_totspg>0)
		{
			uf_print_detalle($la_columna,$la_config,$la_data_ctas,$io_pdf); // Imprimimos el detalle 
		}
		if ($io_pdf->ezPageCount==$thisPageNum)
		{// Hacemos el commit de los registros que se desean imprimir
			$io_pdf->transaction('commit');
		}
	$io_pdf->transaction('commit');
	$io_pdf->ezStopPageNumbers(1,1);
	$io_pdf->ezStream();
	unset($io_pdf);
	unset($class_report);
	unset($io_funciones);
?> 