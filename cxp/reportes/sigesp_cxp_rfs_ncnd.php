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
	ini_set('memory_limit','512M');
	ini_set('max_execution_time ','0');
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_nota)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 15/07/2007
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_cxp;
		
		$ls_descripcion="Generó el Formato de salida la la Nota ".$as_nota;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_p_ncnd.php",$ls_descripcion);
		return $lb_valido;
	}
	//--------------------------------------------------------------------------------------------------------------------------------


	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_numdc,$ad_fecdc,$as_tipo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   as_numsol // numero de la SEP
		//	    		   ad_fecsol // fecha de la SEP
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->saveState();
		$io_pdf->rectangle(150,710,400,40);
		$io_pdf->line(400,750,400,710);
		$io_pdf->line(400,730,550,730);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,710,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(15,              $as_titulo);
		$tm=210;
		$io_pdf->addText($tm,725,15,                  $as_titulo); // Agregar el título
		$io_pdf->addText(410,735,11,"No.:");      // Agregar texto
		$io_pdf->addText(450,735,11,$as_numdc); // Agregar Numero de la solicitud
		$io_pdf->addText(410,715,10,"Fecha:"); // Agregar texto
		$io_pdf->addText(450,715,10,$ad_fecdc); // Agregar la Fecha
		$io_pdf->addText(515,760,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------
		function uf_print_cabecera($as_nompro,$as_nitpro,$as_dirpro,$as_tipo,$as_estatusnota,$ls_tipoprov,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numana // Número de análisis
		//	    		   as_fecana // Fecha del Análisis
		//	    		   as_observacion // Observación
		//	    		   as_estatus // estatus
		//	    		   io_pdf // Instancia del objeto PDF
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Lucena Selena
		// Fecha Creación: 14/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_cabecera=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		//---------------------------------------------------------------------------------------------------
		
		if($ls_tipoprov=="P")
		{
		    $ls_tipo="Proveedor";
		}
		else
		{
		    $ls_tipo="Beneficiario";
		}
		
		$la_data=array(array('name'=>'<b>'.$ls_tipo.': </b>'.$as_nompro),
					   array('name'=>'<b>Nit: </b>  '.$as_nitpro.''),
   					   array('name'=>'<b>Dirección: </b>  '.$as_dirpro.''),
   					   array('name'=>'<b>Estatus: </b>  '.$as_estatusnota.''),
					   );				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla						 
						 'maxWidth'=>500); // Ancho Máximo de la tabla
						 
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 
        //---------------------------------------------------------------------------------------------------		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}
	//--------------------------------------------------------------------------------------------------------------------------------			
	
    //--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera_detalle($as_tipoencabenzadosol,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: función que imprime la cabecera de cada página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_data=array(array('name'=>''),
 		               array('name'=>''),
		               array('name'=>'          LE(S) ROGAMOS TOMAR NOTA DE LOS SIGUIENTES ABONOS HECHOS A SU APRECIABLE CUENTA'),
					   array('name'=>'')
					  );				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla						 
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------


	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_concepto_nc($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_concepto_nc
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				

				
		$la_columna=array('denominacion'=>'<b>Concepto</b>',
						  'monto'=>'<b>Valor</b>'
						  );
						  
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 10, // Tamaño de Letras
						 'titleFontSize' => 11,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho Máximo de la tabla
						 'xPos'=>305, // Orientación de la tabla
						 'cols'=>array('denominacion'=>array('justification'=>'center','width'=>400), // Justificación y ancho de la columna
						 			   'monto'=>array('justification'=>'right','width'=>100) // Justificación y ancho de la columna
									  )
						); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------
	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detallespg($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci?
		//	   			   io_pdf // Objeto PDF
		//    Description: funci? que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci?: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		$la_data1=array(array('name'=>''));				
		$la_columna1=array('name'=>'');		
		$la_config1=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla						 
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna1,'',$la_config1);	

        unset($la_data1);
        unset($la_columna1);
        unset($la_config1);

	    $la_datasercon= array(array('programatica'=>'<b>Estructura</b>','spg_cuenta'=>'<b>Cuenta</b>','denominacion'=>"<b>Denominacion</b>",'monto'=>"<b>Monto </b>"));
		$la_columna=array('programatica'=>'<b>Estructura</b>','spg_cuenta'=>'<b>Cuenta</b>',
  						  'denominacion'=>'<b>Denominacion</b>',
						  'monto'=>'<b>Monto</b>',
						  );
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9, // Tama? de Letras
						 'titleFontSize' => 10,  // Tama? de Letras de los t?ulos
						 'showLines'=>1, // Mostrar L?eas
						 'shaded'=>2, // Sombra entre l?eas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Sombra entre l?eas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M?imo de la tabla
						 'xOrientation'=>'center', // Orientaci? de la tabla
						 'cols'=>array('programatica'=>array('justification'=>'center','width'=>160),
						 			   'spg_cuenta'=>array('justification'=>'center','width'=>70),
									   'denominacion'=>array('justification'=>'left','width'=>170),
									   'monto'=>array('justification'=>'right','width'=>100)
									  )
						); // Justificaci? y ancho de la columna
		$io_pdf->ezTable($la_datasercon,$la_columna,'Cuentas Presupuestarias',$la_config);

		$la_columna=array('programatica'=>'<b>Estructura</b>',
						  'spg_cuenta'=>'<b>Cuenta </b>',
						  'denominacion'=>'<b>Denominacion</b>',
						  'monto'=>'<b>Monto </b>',
						  );
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama? de Letras
						 'titleFontSize' => 10,  // Tama? de Letras de los t?ulos
						 'showLines'=>1, // Mostrar L?eas
						 'shaded'=>0, // Sombra entre l?eas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M?imo de la tabla
						 'xOrientation'=>'center', // Orientaci? de la tabla
						 'cols'=>array('programatica'=>array('justification'=>'center','width'=>160),
						 			   'spg_cuenta'=>array('justification'=>'center','width'=>70),
									   'denominacion'=>array('justification'=>'left','width'=>170),
									   'monto'=>array('justification'=>'right','width'=>100)
									  )
						); // Justificaci? y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	
    //--------------------------------------------------------------------------------------------------------------------------------
    function uf_print_detalle_cuentas_scg($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci?
		//	   			   io_pdf // Objeto PDF
		//    Description: funci? que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci?: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				

		$la_data1=array(array('name'=>''));				
		$la_columna1=array('name'=>'');		
		$la_config1=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>500, // Ancho de la tabla						 
						 'maxWidth'=>500); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna1,'',$la_config1);	

        unset($la_data1);
        unset($la_columna1);
        unset($la_config1);
		
		$la_datasercon= array(array('cuenta'=>"<b>Cuenta Contable</b>",'denominacion'=>"<b>Denominacion</b>",'debe'=>"<b>Debe </b>",'haber'=>"<b>Haber </b>"));
		$la_columna=array('cuenta'=>'<b>Cuenta Presupuestaria</b>',
  						  'denominacion'=>'<b>Denominacion</b>',
						  'debe'=>'<b>Debe</b>',
						  'haber'=>'<b>Haber</b>'			
						  );
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama? de Letras
						 'titleFontSize' => 10,  // Tama? de Letras de los t?ulos
						 'showLines'=>1, // Mostrar L?eas
						 'shaded'=>2, // Sombra entre l?eas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Sombra entre l?eas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M?imo de la tabla
						 'xOrientation'=>'center', // Orientaci? de la tabla
						 'cols'=>array('spg_cuenta'=>array('justification'=>'left','width'=>120),
									   'denominacion'=>array('justification'=>'left','width'=>220),
									   'debe'=>array('justification'=>'right','width'=>80),   						 			  
									   'haber'=>array('justification'=>'right','width'=>80)
									  )
						); // Justificaci? y ancho de la columna
		$io_pdf->ezTable($la_datasercon,$la_columna,'Cuentas Contables',$la_config);


        $la_columna=array('cuenta'=>'<b>Cuenta</b>',
						  'denominacion'=>'<b>Denominacion</b>',
						  'debe'=>'<b>Debe</b>',
						  'haber'=>'<b>Haber</b>'	
						  );
						  
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama? de Letras
						 'titleFontSize' => 10,  // Tama? de Letras de los t?ulos
						 'showLines'=>1, // Mostrar L?eas
						 'shaded'=>0, // Sombra entre l?eas
						 'width'=>500, // Ancho de la tabla
						 'maxWidth'=>500, // Ancho M?imo de la tabla
						 'xOrientation'=>'center', // Orientaci? de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'left','width'=>120),
									   'denominacion'=>array('justification'=>'left','width'=>220),
									   'debe'=>array('justification'=>'right','width'=>80),   						 			  
									   'haber'=>array('justification'=>'right','width'=>80)
									  )
						); // Justificaci? y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pie_pagina($ldec_monto,$ls_monto,$ls_fecha,&$io_pdf)
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
		
		$la_data=array(array('1'=>' ','2'=>' ','monto'=>'','4'=>' '),array('1'=>' ','2'=>' ','monto'=>'','4'=>' '));
		$la_columna=array('1'=>' ','2'=>' ','monto'=>'','4'=>' ');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'fontSize' =>10, // Tamaño de Letras
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('1'=>array('justification'=>'center','width'=>190),'2'=>array('justification'=>'center','width'=>190),
						 'monto'=>array('justification'=>'center','width'=>150),'4'=>array('justification'=>'center','width'=>50))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		
		$la_data=array(array('space'=>'','data'=>' '),
					   array('space'=>'','data'=>$ls_monto));
		$la_columna=array('space'=>'','data'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar Líneas
						 'fontSize' =>10, // Tamaño de Letras
						 'shaded'=>0, // Sombra entre líneas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientación de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580,
						 'cols'=>array('space'=>array('justification'=>'center','width'=>60),'data'=>array('justification'=>'center','width'=>520))); // Ancho Máximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);			
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/class_folder/sigesp_include.php");
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	require_once("../../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($con);	
	
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report= new sigesp_cxp_class_report("../../");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();		
		
	//----------------------------------------------------  Inicializacion de variables  -----------------------------------------------
	$lb_valido=false;
	//----------------------------------------------------  Parámetros del encabezado    -----------------------------------------------
	$ls_titulo ="NOTA DE ";	
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_numnota=$_GET["numnota"];
	$ls_numord=$_GET["numord"];
	$ls_numrecdoc=$_GET["numrecdoc"];
	$ls_codtipdoc=$_GET["codtipdoc"];
	$ls_tipproben=$_GET["tipproben"];
	$ls_codproben=$_GET["codproben"];
	$ls_tiponota =$_GET["tiponota"];
	$ls_tiporeporte =$_GET["tiporeporte"];
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf("../../");
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	//--------------------------------------------------------------------------------------------------------------------------------
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
	if($ls_tipproben=='P')
	{
		$ls_aux=" AND cod_pro='".$ls_codproben."' ";
	}
	else
	{
		$ls_aux=" AND ced_bene='".$ls_codproben."' ";
	}
	

	$la_nota=$io_report->uf_select_notaformatosalida($ls_codemp,$ls_numnota,$ls_tiponota,$ls_numord,$ls_numrecdoc,$ls_codtipdoc,$ls_aux);
	
	if(count($la_nota)>0) 
	{
		error_reporting(E_ALL);
		set_time_limit(1800);
		$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(3.5,3,3,3); // Configuración de los margenes en centímetros		
		$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
	    $io_pdf->transaction('start'); // Iniciamos la transacción
		$li_numpag=$io_pdf->ezPageCount; // Número de página				 
				 
     		   			  				     		   
		$ls_tiponota   =$la_nota["codope"];					     		   
		$ls_descripcion=$la_nota["desope"];		
		$ls_fecha      =$la_nota["fecope"];
		$ls_fecha      =$io_funciones->uf_convertirfecmostrar($la_nota["fecope"]);						
	    $ls_estatus    =$la_nota["estnotadc"];

		 if($ls_estatus=="R")
		  {
			  $ls_estatusnota="Registrada";
		  }

		  if($ls_estatus=="C")
		  {
			  $ls_estatusnota="Contabilizada";
		  }

		  if($ls_estatus=="A")
		  {
			  $ls_estatusnota="Anulada";
		  }
		  if($ls_estatus=="E")
		  {
			  $ls_estatusnota="Emitida";
		  }		 

		  if($ls_estatus=="P")
		  {
			  $ls_estatusnota="Pagada";
		  }    

		  if($ls_tiponota=="NC")
		  {
			 $ls_tipo="CRÉDITO";   
		  }
		  if($ls_tiponota=="ND")
		  {
			$ls_tipo="DÉBITO";   
		  }
			 
		 $ldec_monto  =$la_nota["monto"];		
		 if($ls_tipproben=='P')
		 {
			$la_provben=$io_report->uf_select_rowdata($io_sql,"SELECT rifpro,nitpro,dirpro,nompro FROM rpc_proveedor WHERE codemp='".$ls_codemp."' AND cod_pro='".$ls_codproben."'");
			$ls_rif=$la_provben["rifpro"];
			$ls_nit=$la_provben["nitpro"];
			$ls_dir=$la_provben["dirpro"];
			$ls_nombre=$la_provben["nompro"];
		 }	
		 else
		 {
			$la_provben=$io_report->uf_select_rowdata($io_sql,"SELECT rifben,ced_bene,dirbene,nombene,apebene FROM rpc_beneficiario WHERE codemp='".$ls_codemp."' AND  ced_bene='".$ls_codproben."'");
			$ls_rif=$la_provben["rifben"];
			$ls_nit=$la_provben["ced_bene"];
			$ls_dir=$la_provben["dirbene"];
			$ls_nombre=$la_provben["nombene"]." ".$la_provben["apebene"];
		 }	
		 $ls_titulo =$ls_titulo.$ls_tipo;
		 
		 uf_print_encabezado_pagina($ls_titulo,$ls_numnota,$ls_fecha,$ls_tipo,$io_pdf); // Imprimimos el encabezado de la página		 		   			  						 
		 uf_print_cabecera($ls_nombre,$ls_nit,$ls_dir,$ls_tipo,$ls_estatusnota,$ls_tipproben,$io_pdf); // Imprimimos la cabecera del registro  			    
		 $ls_tipoencabenzadosol="";
		 uf_print_cabecera_detalle($ls_tipoencabenzadosol,$io_pdf);
		 $ldec_monto = number_format($ldec_monto,2,",",".");	
		 $la_data[1]= array('denominacion'=>$ls_descripcion,'monto'=>$ldec_monto);									
		 uf_print_concepto_nc($la_data,&$io_pdf);
		//**************************************************************************************************************************
		$lb_valido=$io_report->uf_select_dt_spg_nota($ls_numnota,$ls_numord,$ls_numrecdoc,$ls_codtipdoc,$ls_tipproben,$ls_codproben,&$rs_data);
		if($lb_valido)
		{
		   $b=0;	
		   while($row=$io_report->io_sql->fetch_row($rs_data))
		   {										  													  
				$b++;  
				$ls_cuentaspg = trim($row["spg_cuenta"]);
				$ls_codestpro = $row["codestpro"];
				$ls_modalidad = $_SESSION['la_empresa']['estmodest'];
				switch($ls_modalidad)
				{
					case "1": // Modalidad por Proyecto
						$ls_codestpro=substr($ls_codestpro,0,29);
						break;						
					case "2": // Modalidad por Programa
						$ls_codestpro1=substr(substr($ls_codestpro,0,20),-2);
						$ls_codestpro2=substr(substr($ls_codestpro,20,6),-2);
						$ls_codestpro3=substr(substr($ls_codestpro,26,3),-2);
						$ls_codestpro4=substr($ls_codestpro,29,2);
						$ls_codestpro5=substr($ls_codestpro,31,2);
						$ls_codestpro=$ls_codestpro1."-".$ls_codestpro2."-".$ls_codestpro3."-".$ls_codestpro4."-".$ls_codestpro5;
						break;
				}
				$ls_denominacionspg= $row["denominacion"];
				$ldec_montocuentaspg = $row["monto"];
				$ldec_montocuentaspg = number_format($ldec_montocuentaspg,2,",",".");	
				$la_data1[$b]= array('programatica'=>$ls_codestpro,'spg_cuenta'=>$ls_cuentaspg,
								    'denominacion'=>$ls_denominacionspg,'monto'=>$ldec_montocuentaspg);
			}								  
		   	uf_print_detallespg($la_data1,$io_pdf); 
	 	}
		$io_report->io_sql->free_result($rs_data);
		$lb_valido=$io_report->uf_select_dt_scg_nota($ls_numnota,$ls_numord,$ls_numrecdoc,$ls_codtipdoc,$ls_tipproben,$ls_codproben,&$rs_data);
		if($lb_valido)
		{
		   $a=0;	
		   while($row=$io_report->io_sql->fetch_row($rs_data))
		   {										  													  
				$a++;											 		     											  							  			 
				$ldec_montocuentascgdeb="";
				$ldec_montocuentascghab="";
				$ls_denominacionscg="";
				$ls_cuentascg      = trim($row["sc_cuenta"]);
				$ls_denominacionscg= $row["denominacion"];											  
				$ls_columna        = $row["debhab"];
				
				if($ls_columna=="D")
				{
					$ldec_montodeb= number_format($row["monto"],2,",",".");									
					$ldec_montohab="";
				}
				else
				{
					$ldec_montohab= number_format($row["monto"],2,",",".");					
					$ldec_montodeb = "";
				}
															  
				$la_data[$a]= array('cuenta'=>$ls_cuentascg,
				  				    'denominacion'=>$ls_denominacionscg,									                    																  
									'debe'=>$ldec_montodeb,'haber'=>$ldec_montohab);
		   }						   
		   uf_print_detalle_cuentas_scg($la_data,$io_pdf);								    						    
		}											
		//Asigno el monto a la clase numero-letras para la conversion.
		$ldec_monto=str_replace('.','',$ldec_monto);
		$ldec_monto=str_replace(',','.',$ldec_monto);
		$numalet->setNumero($ldec_monto);
		//Obtengo el texto del monto enviado.
		$ls_monto= $numalet->letra();
		uf_print_encabezado_pie_pagina(number_format($ldec_monto,2,",","."),$ls_monto,"Barquisimeto  ".date("d/m/Y"),$io_pdf); // Imprimimos el encabezado de la página
		//------------------------------------------------------------------
		$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
		$io_pdf->ezStream(); // Mostramos el reporte
		unset($io_pdf);
	}
	unset($io_report,$io_funciones);
?> 