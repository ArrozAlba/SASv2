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
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: 
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Victor Mendoza
		// Fecha Creación: 15/02/2007 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=330-($li_tm/2);
		$io_pdf->addText($tm,730,11,$as_titulo); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,$as_periodo);
		$tm=330-($li_tm/2);
		$io_pdf->addText($tm,718,11,$as_periodo); // Agregar el título
		$io_pdf->addText(500,730,10,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(500,720,10,date("h:i a")); // Agregar la hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');		
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	function uf_print_detalle($la_columna,$la_config,$la_data,&$io_pdf)
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
		global $io_fun_nomina;

		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_detalle(&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera_detalle
		//		    Acess: private
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing.Yozelin Barragán
		// Fecha Creación: 21/04/2006
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$io_pdf->ezSetY(663);
		$la_data=array(array('procede'=>'<b>Procede</b>','comprobante'=>'<b>Comprobante</b>','fecha'=>'<b>Fecha</b>',
		                     'totdebe'=>'<b>Total Debe</b>','tothaber'=>'<b>Total Haber</b>'));
		$la_columnas=array('procede'=>'','comprobante'=>'','fecha'=>'','totdebe'=>'','tothaber'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>290, // Orientación de la tabla
						 'cols'=>array('procede'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la 
						 			   'comprobante'=>array('justification'=>'center','width'=>110), // Justificación y ancho de la
						 			   'fecha'=>array('justification'=>'center','width'=>100), // Justificación y ancho de la 
						 			   'totdebe'=>array('justification'=>'center','width'=>120), // Justificación y ancho de la 
						 			   'tothaber'=>array('justification'=>'center','width'=>120))); // Justificación y ancho 
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
	}// end function uf_print_cabecera_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	require_once("../../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($con);	
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../../shared/class_folder/class_datastore.php");
	$ds_cta=new class_datastore();	
	
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];

/*	$ls_sql="SELECT codemp,procede, comprobante, fecha, SUM(CASE debhab WHEN 'D' THEN monto
								ELSE -1*monto END) AS total,
SUM(CASE debhab WHEN 'D' THEN coalesce(monto,0)
								ELSE 0 END) AS total_debe,
SUM(CASE debhab WHEN 'H' THEN coalesce(monto,0)
								ELSE 0  END) AS total_haber
  FROM scg_dt_cmp 
 WHERE codemp = '0001' 
GROUP BY codemp,procede,comprobante,fecha";
*/
	$ls_sql="select *
		from (
		select curdebe.procede, curdebe.comprobante, curdebe.fecha, curdebe.total_debe,curhaber.total_haber,(curdebe.total_debe-curhaber.total_haber) as diferencia
		from
				(select codemp,procede, comprobante, fecha, sum(monto) as total_debe
				from scg_dt_cmp
				where codemp='".$ls_codemp."' and debhab='D'
				group by codemp,procede, comprobante, fecha
				order by codemp,procede, comprobante, fecha) as curdebe left outer join
				(select codemp,procede, comprobante, fecha, sum(coalesce(monto,0)) as total_haber
				from scg_dt_cmp
				where codemp='".$ls_codemp."' and debhab='H'
				group by codemp,procede, comprobante, fecha
				order by codemp,procede, comprobante, fecha) as curhaber
		on
				(curdebe.codemp = curhaber.codemp and
				curdebe.procede    = curhaber.procede and
				curdebe.comprobante = curhaber.comprobante and
				curdebe.fecha       = curhaber.fecha  ) ) as curfinal
		where (diferencia is null or 
			diferencia <> 0) ";
	$rs_data=$io_sql->select($ls_sql);	
	if($rs_data===false)
	{
		?>
		<script language=javascript>
		 alert("<? print $io_funciones->uf_convertirmsg($io_sql->message);?>");
		 close();
		</script>
		<?
		exit();
	}
	else
	{
		$ds_cta->data=$io_sql->obtener_datos($rs_data);
	}
	error_reporting(E_ALL);
	set_time_limit(1800);
	$li_totrow=$ds_cta->getRowCount("procede");
	if($li_totrow<=0)
	{
		?>
		<script language=javascript>
		 alert('No hay datos a reportar');
		 close();
		</script>
		<?
		exit();
	}
	$io_pdf=new Cezpdf('LETTER','portrait'); 												// Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); 							// Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(3.5,3.5,3.5,3.5); 												// Configuración de los margenes en centímetros
	uf_print_encabezado_pagina("<b>Listado de Comprobantes Descuadrados</b>"," ",$io_pdf); 	// Imprimimos el encabezado de la página
	$io_pdf->ezStartPageNumbers(550,50,10,'','',1); 										// Insertar el número de página
	$li_j=0;
	for($li_i=1;$li_i<=$li_totrow;$li_i++)
	{
		$thisPageNum=$io_pdf->ezPageCount;
		$li_totprenom=0;
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$li_totant=0;
		
		$ls_procede 	  = $ds_cta->getValue("procede",$li_i);
		$ls_comprobante	  = $ds_cta->getValue("comprobante",$li_i);
		$ld_fecha		  = substr($ds_cta->getValue("fecha",$li_i),0,10);
		$ld_fecha=$io_funciones->uf_convertirfecmostrar($ld_fecha);
		$ldec_total_debe  = number_format($ds_cta->getValue("total_debe",$li_i),2,",",".");
		$ldec_total_haber = number_format($ds_cta->getValue("total_haber",$li_i),2,",",".");
		if(strval($ldec_total_debe)!=strval($ldec_total_haber))
		{
			$li_j++;
			$la_data[$li_j]=array('procede'=>$ls_procede,'comprobante'=>$ls_comprobante,'fecha'=>$ld_fecha,'totdebe'=>$ldec_total_debe,'tothaber'=>$ldec_total_haber);
		}
		
	}
		$la_columna=array('procede'=>'<b>Procede</b>','comprobante'=>'<b>Comprobante</b>','fecha'=>'<b>Fecha</b>','totdebe'=>'<b>Total Debe</b>','tothaber'=>'<b>Total Haber</b>');

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 7, // Tamaño de Letras
						 'titleFontSize' => 7,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'colGap'=>1, // separacion entre tablas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho Máximo de la tabla
						 'xPos'=>290, // Orientación de la tabla
						 'cols'=>array('procede'=>array('justification'=>'center','width'=>100),
						               'comprobante'=>array('justification'=>'left','width'=>110),
									   'fecha'=>array('justification'=>'center','width'=>100),
									   'totdebe'=>array('justification'=>'right','width'=>120),
									   'tothaber'=>array('justification'=>'right','width'=>120))); // Ancho Máximo de la tabla
	if($li_j<=0)
	{
		?>
		<script language=javascript>
		 alert('No hay datos a reportar');
		 close();
		</script>
		<?
		exit();
	}
	else
	{
		uf_print_cabecera_detalle($io_pdf);
		uf_print_detalle($la_columna,$la_config,$la_data,$io_pdf); // Imprimimos el detalle 
		unset($la_data);
		$io_pdf->ezStopPageNumbers(1,1);
		$io_pdf->ezStream();
	}
	unset($io_pdf);
	unset($class_report);
	unset($io_funciones);
?> 