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
		//	    Arguments: ldec_monto : Monto del cheque
		//	    		   ls_nomproben:  Nombre del proveedor o beneficiario
		//	    		   ls_monto : Monto en letras
		//	    		   ls_fecha : Fecha del cheque
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creación: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/logo.jpg',30,700,80); // Agregar Logo
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
	$ls_cuenta_desde=$_GET["cuentadesde"];
	$ls_cuenta_hasta=$_GET["cuentahasta"];
	$ls_aux="";
	if((!empty($ls_cuenta_desde))&&(!empty($ls_cuenta_hasta)))
	{
		$ls_aux=" AND sc_cuenta between '".$ls_cuenta_desde."' AND '".$ls_cuenta_hasta."'";
	}
	$ls_sql="SELECT distinct(sc_cuenta),denominacion FROM scg_cuentas WHERE codemp='".$ls_codemp."'".$ls_aux;

	$rs_data=$io_sql->select($ls_sql);	
	if($rs_data===false)
	{
		?>
		<script language=javascript>
		 alert("<?php print $io_funciones->uf_convertirmsg($io_sql->message);?>");
		 close();
		</script>
		<?php
		exit();
	}
	else
	{
		$ds_cta->data=$io_sql->obtener_datos($rs_data);
	}
	error_reporting(E_ALL);
	set_time_limit(1800);
	$li_totrow=$ds_cta->getRowCount("sc_cuenta");
	if($li_totrow<=0)
	{
		?>
		<script language=javascript>
		 alert('No hay datos a reportar');
		 close();
		</script>
		<?php
		exit();
	}
	$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(3.5,3.5,3.5,3.5); // Configuración de los margenes en centímetros
	uf_print_encabezado_pagina("<b>Listado de Cuentas Contables</b>","Desde ".$ls_cuenta_desde." hasta ".$ls_cuenta_hasta,$io_pdf); // Imprimimos el encabezado de la página
	$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
	
	for($li_i=1;$li_i<=$li_totrow;$li_i++)
	{
		$io_pdf->transaction('start'); // Iniciamos la transacción
		$thisPageNum=$io_pdf->ezPageCount;
		$li_totprenom=0;
		$ldec_mondeb=0;
		$ldec_monhab=0;
		$li_totant=0;
		//unset($la_data);
		$ls_cuenta=$ds_cta->getValue("sc_cuenta",$li_i);
		$ls_denominacion=$ds_cta->getValue("denominacion",$li_i);

		$la_data[$li_i]=array('cuenta'=>$ls_cuenta,'denominacion'=>$ls_denominacion);
		$la_columna=array('cuenta'=>'<b>Cuenta</b>','denominacion'=>'<b>Denominacion</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
 						 'shadeCol'=>array(0.95,0.95,0.95), // Color de la sombra
						 'shadeCol2'=>array(1.5,1.5,1.5), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550,
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>100),'denominacion'=>array('justification'=>'left','width'=>450))); // Ancho Máximo de la tabla
		//uf_print_autorizacion($io_pdf);			
	}

	uf_print_detalle($la_columna,$la_config,$la_data,$io_pdf); // Imprimimos el detalle 
	unset($la_data);
	//uf_print_totales(number_format($ldec_total_debe,2,",","."),number_format($ldec_total_haber,2,",","."),number_format($ldec_saldoactual,2,",","."),$io_pdf); // Imprimimos el detalle
	 
	/*if ($io_pdf->ezPageCount==$thisPageNum)
	{// Hacemos el commit de los registros que se desean imprimir*/
		$io_pdf->transaction('commit');
	/*}
	else
	{// Hacemos un rollback de los registros, agregamos una nueva página y volvemos a imprimir
		$io_pdf->transaction('rewind');
		$io_pdf->ezNewPage();
		uf_print_cabecera($ls_numdoc,$ls_codban,$ls_ctaban,$ls_chevau,$io_pdf); // Imprimimos la cabecera del registro
		uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
		uf_print_autorizacion($io_pdf);
	}*/
	$io_pdf->ezStopPageNumbers(1,1);
	$io_pdf->ezStream();
	unset($io_pdf);
	unset($class_report);
	unset($io_funciones);
?> 