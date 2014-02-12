<?PHP
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
	//print "Paso no se porq1ue no imprime";
 	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_titulo,$as_org,$as_rifageret,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private 
		//	    Arguments: as_titulo // T�ulo del Reporte
		//	    		   ls_codigo // nmero de an�isis
		//	    		   as_fecana // fecha de an�isis
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci� que imprime los encabezados por p�ina
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creaci�: 14/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(50,40,960,40);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],47,539,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$io_pdf->addText(910,585,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->setStrokeColor(0,0,0);
        $io_pdf->Rectangle(150,540,800,40);	
		
		$io_pdf->addText(240,555,13,"<b>".$as_titulo."</b>"); // Agregar el t�ulo				
		
		$as_ley="Ley IVA - Art 11 Seran responsable del pago del impuesto en calidad de agentes de retencion, los";
		$as_ley2="compradores o adquirientes de determinados bienes muebles y los receptores de";
		$as_ley3="ciertos servicios, quienes la Administracion Tributaria designe como tal.";
		
		if($as_org=='ENMOHCA')
		{
		    $io_pdf->addText(70,500,10,"<b>".$as_ley."</b>"); // Agregar el t�ulo		
		    $io_pdf->addText(90,490,10,"<b>".$as_ley2."</b>"); // Agregar el t�ulo
		    $io_pdf->addText(100,480,10,"<b>".$as_ley3."</b>"); // Agregar el t�ulo
			
    		$io_pdf->addText(50,530,10,"<b> RIF.- ".$as_rifageret."</b>"); // Agregar el t�ulo
		    $ls_nomadm="COORDINADORA DE ADMINISTRACION";
	 	    $io_pdf->line(350,90,660,90);
			$io_pdf->addText(380,75,13,"<b>".$ls_nomadm."</b>"); // Agregar el t�ulo			
		}			
       
		if($as_org=='C.G.E.M.')
		{
		    $ls_nombre="CONTRALORIA DE MERIDA";
    		$io_pdf->addText(400,590,15,"<b>".$ls_nombre."</b>"); 
			$io_pdf->addText(60,108,10,"<b>              REVISADO POR                                               CONTABILIZADO POR                                                     AUTORIZADO                                                   POR RECIBIDO POR</b>"); 
			$io_pdf->line(50,105,950,105);
			$io_pdf->Rectangle(50,60,900,60);
			$io_pdf->line(230,60,230,120);	
			$io_pdf->line(480,60,480,120);	
			$io_pdf->line(710,60,710,120);	
			$io_pdf->addText(710,75,10,"<b> PROVEEDOR : ____________________________ </b>"); 
			
		}			
		
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}
	//--------------------------------------------------------------------------------------------------------------------------------	
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($ls_nomageret,$ls_rifageret,$ls_dirageret,$ls_perfis,
	                           $ls_sujret,$ls_nomsuj,$ls_rif,$ls_direc,$ls_codigo,
							   $as_fecana,$ai_estcom,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_numana // Nmero de an�isis
		//	    		   as_fecana // Fecha del An�isis
		//	    		   as_observacion // Observaci�
		//	    		   as_estatus // estatus
		//	    		   io_pdf // Instancia del objeto PDF
		//    Description: funci� que imprime la cabecera de cada p�ina
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creaci�: 14/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*$io_cabecera=$io_pdf->openObject();
		$io_pdf->saveState();*/
		$io_pdf->setStrokeColor(0,0,0);
		//---------------------------------------------------------------------------------------------------
		
		//print $ai_estcom."<br>";		
		if($ai_estcom==2)
		{
		    $io_pdf->Rectangle(45,480,180,30);		
			$io_pdf->addText(90,490,15,"<b> ANULADO </b>"); 
		}	
		
		$la_data=array(array('name'=>'<b>NRO COMPROBANTE </b>'),
					   array('name'=>$ls_codigo)   					   
					   );				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama� de Letras
						 'showLines'=>1, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>700, // Orientaci� de la tabla
						 'width'=>150, // Ancho de la tabla						 
						 'maxWidth'=>150,
						 'yPos'=>200 ); // Ancho M�imo de la tabla
						 
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 
        //---------------------------------------------------------------------------------------------------
		//---------------------------------------------------------------------------------------------------
		$io_pdf->Rectangle(845,483,100,28);	
		$io_pdf->addText(850,500,9,"<b>FECHA</b>"); // Agregar el t�ulo
		$io_pdf->addText(850,485,9,$as_fecana); // Agregar el t�ulo									 
        //---------------------------------------------------------------------------------------------------
		//---------------------------------------------------------------------------------------------------
		$io_pdf->Rectangle(845,439,100,28);	
		$io_pdf->addText(850,456,9,"<b>PERIODO FISCAL</b>"); // Agregar el t�ulo
		$io_pdf->addText(850,443,9,$ls_perfis); // Agregar el t�ulo									 
        //---------------------------------------------------------------------------------------------------
		//---------------------------------------------------------------------------------------------------
		$la_data=array(array('titulo'=>''));				
		$la_columna=array('titulo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama� de Letras
						 'showLines'=>0, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'xOrientation'=>'center', // Orientaci� de la tabla
						 'width'=>500, // Ancho de la tabla						 
						 'justification'=>'center', // Ancho de la tabla						 
						 'maxWidth'=>500,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>500))); // Ancho M�imo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);			
		//---------------------------------------------------------------------------------------------------
		//---------------------------------------------------------------------------------------------------
		$la_data=array(array('name'=>'<b>NOMBRE O RAZON SOCIAL DEL AGENTE DE RETENCION </b>'),
					   array('name'=>$ls_nomageret.'')   					   
					   );				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama� de Letras
						 'showLines'=>1, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>200, // Orientaci� de la tabla
						 'width'=>300, // Ancho de la tabla						 
						 'maxWidth'=>300,
						 'yPos'=>200 ); // Ancho M�imo de la tabla
						 
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);								 
        //---------------------------------------------------------------------------------------------------
		//---------------------------------------------------------------------------------------------------
		$io_pdf->Rectangle(400,439,370,30);	
		$io_pdf->addText(405,458,9,"<b>REGISTRO DE INFORMACION FISCAL DEL AGENTE DE RETENCION</b>"); // Agregar el t�ulo
		$io_pdf->addText(405,443,9,$ls_rifageret); // Agregar el t�ulo									 
        //---------------------------------------------------------------------------------------------------
		$la_data=array(array('titulo'=>''));				
		$la_columna=array('titulo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama� de Letras
						 'showLines'=>0, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'xOrientation'=>'center', // Orientaci� de la tabla
						 'width'=>500, // Ancho de la tabla						 
						 'justification'=>'center', // Ancho de la tabla						 
						 'maxWidth'=>500,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>500))); // Ancho M�imo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);	
		//---------------------------------------------------------------------------------------------------
		$la_data=array(array('name'=>'<b>DIRECCION FISCAL DEL AGENTE DE RETENCION</b>  '),
		               array('name'=>$ls_direc)
					   );				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama� de Letras
						 'showLines'=>1, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>413, // Orientaci� de la tabla
						 'width'=>725, // Ancho de la tabla						 
						 'maxWidth'=>725
						 ); // Ancho M�imo de la tabla
						 
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		//---------------------------------------------------------------------------------------------------
		$la_data=array(array('titulo'=>''));				
		$la_columna=array('titulo'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama� de Letras
						 'showLines'=>0, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'xOrientation'=>'center', // Orientaci� de la tabla
						 'width'=>500, // Ancho de la tabla						 
						 'justification'=>'center', // Ancho de la tabla						 
						 'maxWidth'=>500,
						 'cols'=>array('titulo'=>array('justification'=>'center','width'=>500))); // Ancho M�imo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);			
		//---------------------------------------------------------------------------------------------------
		$la_data=array(array('name'=>'<b>NOMBRE O RAZON SOCIAL DEL SUJETO RETENIDO</b>  '),
		               array('name'=>$ls_nomsuj.'')   					 
					   );				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama� de Letras
						 'showLines'=>1, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>200, // Orientaci� de la tabla
						 'width'=>300, // Ancho de la tabla						 
						 'maxWidth'=>300); // Ancho M�imo de la tabla
						 
        $io_pdf->ezTable($la_data,$la_columna,'',$la_config);		
		unset($la_data);
		unset($la_columna);
		unset($la_config);								
		//---------------------------------------------------------------------------------------------------
		$io_pdf->Rectangle(400,355,370,30);	
		$io_pdf->addText(405,375,9,"<b>REGISTRO DE INFORMACION FISCAL DEL SUJETO RETENIDO(R.I.F)</b>"); // Agregar el t�ulo
		$io_pdf->addText(405,360,9,$ls_rif); // Agregar el t�ulo									 
        //---------------------------------------------------------------------------------------------------											       		 		
		/*$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');*/
		
	}
	//--------------------------------------------------------------------------------------------------------------------------------			
	function uf_print_det_sum_ret_iva($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_codper // total de registros que va a tener el reporte
		//	    		   as_nomper // total de registros que va a tener el reporte
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: funci� que imprime la cabecera de cada p�ina
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creaci�: 21/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('name1'=>'','name2'=>'','name3'=>'','name4'=>'','name5'=>'');
		$la_config=array('showHeadings'=>0,     // Mostrar encabezados
						 'fontSize' => 6,       // Tama� de Letras
						 'titleFontSize' => 8, // Tama� de Letras de los t�ulos
						 'showLines'=>1,        // Mostrar L�eas
						 'shaded'=>2,           // Sombra entre l�eas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci� de la tabla
						 'width'=>990, // Ancho de la tabla
						 'maxWidth'=>990,
						 'colGap'=>0,
						 'cols'=>array('name1'=>array('justification'=>'center','width'=>15), // Justificaci� y ancho de la columna Nro de Operacion.
						 			   'name2'=>array('justification'=>'center','width'=>55), // Justificaci� y ancho de la columna RIF.
						 			   'name3'=>array('justification'=>'center','width'=>50), // Justificaci� y ancho de la columna Nmero de la Fcatura.
						 			   'name4'=>array('justification'=>'left','width'=>130), // Justificaci� y ancho de la columna Nombre o Raz� Social.
									   'name5'=>array('justification'=>'center','width'=>25), // Justificaci� y ancho de la columna Nro de Comprobante.
						 			  )
						); 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}
			
	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_det_ret_iva($la_data,$ldec_totconiva,$ldec_totsiniva,$ldec_totbasimp,
						          $ldec_totmonimp,$ldec_totivaret,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle_analisis
		//		   Access: private 
		//	    Arguments: la_data // arreglo de informaci�
		//	   			   io_pdf // Objeto PDF
		//    Description: funci� que imprime el detalle
		//	   Creado Por: Ing. Nelson Barraez
		// Fecha Creaci�: 14/06/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
		$la_data1=array(array('name'=>''));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 11,  // Tama� de Letras
						 'showLines'=>0,    // Mostrar L�eas
						 'shaded'=>0,       // Sombra entre l�eas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci� de la tabla
						 'width'=>900,      // Ancho de la tabla						 
						 'maxWidth'=>900);  // Ancho M�imo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
		//-----------------------------------------------------------------------------------------------------------------		
		if($_SESSION["la_empresa"]["titulo"]=='ENMOHCA')
		{
			$ls_titulo1="Compras y/o Servicios Internas o Importaciones";
		}
		else
		{
			$ls_titulo1="Compras Internas o Importaciones";
		}
		$la_data1=array(array('name'=>$ls_titulo1));				
		$la_columna=array('name'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>9,    // Tama� de Letras
						 'showLines'=>1,    // Mostrar L�eas
						 'shaded'=>0,       // Sombra entre l�eas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>785, 						 					
						 'width'=>185,      // Ancho de la tabla						 
						 'maxWidth'=>185,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>185))
						 );  // Ancho M�imo de la tabla
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
        //----------------------------------------------------------------------------------------------------------------						
		if($_SESSION["la_empresa"]["titulo"]=='ENMOHCA')
		{
			$ls_titulo1="Total Compras y/o Servicios Incluyendo el IVA";
			$ls_titulo2="Compras y/o Servicios sin Derecho a Credito fiscal";
		}
		else
		{
			$ls_titulo1="Total Compras Incluyendo el IVA";
			$ls_titulo2="Compras sin Derecho a Credito IVA";
		}
		
		$la_columna=array('numope'=>'<b>Oper Nro.</b>',
						  'fecfac'=>'<b>Fecha de la Factura</b>',
						  'numfac'=>'<b>Numero de Factura</b>',
  						  'numref'=>'<b>Num. Ctrol de Factura</b>',		
						  'numnotdeb'=>'<b>Numero Nota Debit.</b>',
						  'numnotcre'=>'<b>Numero Nota Crdt.</b>',				  
  						  'tiptrans'=>'<b>Tipo de Transacc.</b>',
						  'numfacafec'=>'<b>Numero de Factura  Afectada</b>',
						  'totalconiva'=>'<b>'.$ls_titulo1.'</b>',
						  'compsinderiva'=>'<b>'.$ls_titulo2.'</b>',
						  'baseimp'=>'<b>Base Imposible</b>',
						  'porimp'=>'<b>%     Alicuota</b>',
						  'totimp'=>'<b>Impuesto IVA</b>',
						  'ivaret'=>'<b>IVA Retenido</b>'
						  
						  );
						  		  						 
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama� de Letras
						 'titleFontSize' => 9,  // Tama� de Letras de los t�ulos
						 'showLines'=>1, // Mostrar L�eas
						 'shaded'=>0, // Sombra entre l�eas
						 'width'=>900, // Ancho de la tabla
						 'maxWidth'=>900, // Ancho M�imo de la tabla
						 'xPos'=>500, // Orientaci� de la tabla
						 'cols'=>array('numope'=>array('justification'=>'center','width'=>60), // Justificaci� y ancho de la columna
						 			   'fecfac'=>array('justification'=>'center','width'=>60), // Justificaci� y ancho de la columna
						 			   'numfac'=>array('justification'=>'center','width'=>80), // Justificaci� y ancho de la columna
									   'numref'=>array('justification'=>'center','width'=>80), // Justificaci� y ancho de la columna
									   'numnotdeb'=>array('justification'=>'center','width'=>50),
  						 			   'numnotcre'=>array('justification'=>'center','width'=>50),
   						 			   'tiptrans'=>array('justification'=>'center','width'=>50),		
									   'numfacafec'=>array('justification'=>'center','width'=>50),		   									   
   						 			   'totalconiva'=>array('justification'=>'center','width'=>90),
									   'compsinderiva'=>array('justification'=>'center','width'=>70),
						 			   'baseimp'=>array('justification'=>'center','width'=>70),
						 			   'porimp'=>array('justification'=>'center','width'=>45),
   						 			   'totimp'=>array('justification'=>'center','width'=>70),
  						 			   'ivaret'=>array('justification'=>'center','width'=>70)									   
									   )
						); 
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		unset($la_data1);		
		//-----------------------------------------------------------------------------------------------------------------												  
		$la_data1=array(array('name'=>'','name1'=>''.$ldec_totconiva,'name2'=>''.$ldec_totsiniva,
		                      'name3'=>''.$ldec_totbasimp,'name4'=>'','name5'=>''.$ldec_totmonimp,
							  'name6'=>''.$ldec_totivaret));						                      
		$la_columna=array('name'=>'','name1'=>'','name2'=>'','name3'=>'','name4'=>'','name5'=>'','name6'=>'');		
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' =>8,    // Tama� de Letras
						 'showLines'=>0,    // Mostrar L�eas
						 'shaded'=>0,       // Sombra entre l�eas
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xPos'=>482, 
						 'yPos'=>734,       // Orientaci� de la tabla						
						 'width'=>200,
						 'xOrientation'=>'right',      // Ancho de la tabla						 
						 'maxWidth'=>200,
						 'cols'=>array('name'=>array('justification'=>'center','width'=>50), // Justificaci� y ancho de la columna
						               'name1'=>array('justification'=>'center','width'=>90), // Justificaci� y ancho de la columna
						 			   'name2'=>array('justification'=>'center','width'=>70), // Justificaci� y ancho de la columna
						 			   'name3'=>array('justification'=>'center','width'=>70), // Justificaci� y ancho de la columna
									   'name4'=>array('justification'=>'center','width'=>45), // Justificaci� y ancho de la columna
									   'name5'=>array('justification'=>'center','width'=>70), // Justificaci� y ancho de la columna
   						 			   'name6'=>array('justification'=>'center','width'=>70)
									   )							 
						 );  
		$io_pdf->ezTable($la_data1,$la_columna,'',$la_config);	
		unset($la_data1);
		unset($la_columna);
		unset($la_config);
        //----------------------------------------------------------------------------------------------------------------			
	}
	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_sql.php");	
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("sigesp_scb_c_cmpret_op.php");	
	require_once("../../shared/class_folder/class_funciones.php");
	
	$in          =new sigesp_include();
	$con         =$in->uf_conectar();
	$io_sql      =new class_sql($con);	
	$io_report   =new sigesp_scb_c_cmpret_op($con);
	require_once("sigesp_scb_report.php");
	$class_report=new sigesp_scb_report($con);
	$io_funciones=new class_funciones();			
	//----------------------------------------------------  Inicializacion de variables  -----------------------------------------------
	$lb_valido   =false;
	$lb_validobie=false;
	$lb_validoser=false;
	//----------------------------------------------------  Paraetros del encabezado    -----------------------------------------------
	$ls_titulo="COMPROBANTE DE RETENCION DEL IMPUESTO AL VALOR AGREGADO";	
	//--------------------------------------------------  Paraetros para Filtar el Reporte  -----------------------------------------
	$ls_org   ="";
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_org   =$_SESSION["la_empresa"]["titulo"];
	$ls_dirorg=$_SESSION["la_empresa"]["direccion"];
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_numdoc=$_GET["numdoc"];
	$ls_codope=$_GET["codope"];	
	$ls_numdocres =$_GET["numdocres"];
	$ls_fecdocres =$_GET["fecdocres"];
	$ls_nrocontrol=$_GET["nrocontrol"];
	$ls_desope=$_GET["desope"];
	$ldec_monto=$_GET["monto"];
	$ls_tipodestino=$_GET["tipodestino"];
	$ls_codpro=$_GET["codpro"];
	$ls_cedben=$_GET["cedbene"];
	$ld_fecmov=$_GET["fecmov"];
	$ls_chevau="";
	
	
	$hidnumero=$ls_numdoc;
	$la_data_compret=array('numdocres'=>$ls_numdocres,'fecdocres'=>$ls_fecdocres,'nrocontrol'=>$ls_nrocontrol,'monto'=>$ldec_monto,'desope'=>$ls_desope,'nrocontrol'=>$ls_nrocontrol);
	$class_report->uf_generar_cmpret_ordendepagodirecta($ls_codemp,$ls_numdoc,$ls_codban,$ls_ctaban,$ls_tipodestino,$ls_codpro,$ls_cedben,$ld_fecmov,$la_data_compret);
	 $li_valini=0;
	 $li_valfin=15;								
	 $ls_const =15;
	 error_reporting(E_ALL);
	 set_time_limit(1800);	
	 $io_pdf = new class_pdf("LEGAL","landscape");				
	 $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); 
	 $io_pdf->ezSetCmMargins(3.5,3,3,3); 
	 $io_pdf->ezStartPageNumbers(950,50,10,'','',1);
				            
			$lb_valido=$io_report->uf_select_ret_iva_cab($ls_codemp,$ls_numdoc);				
			if($lb_valido==false)
			{
				print("<script language=JavaScript>");
				print(" alert('No hay nada que Reportar');"); 
				print("close();");
				print("</script>");
			}
			else 
			{
				
				$li_totrow=$io_report->ds_ret->getRowCount("numcom");
				
				for($i=1;$i<=$li_totrow;$i++)
				{
					$li_estcom = 1;
					$ls_numcon = $io_report->ds_ret->data["numcom"][$i];												
					$ls_codret = $io_report->ds_ret->data["codret"][$i];			   
					$ls_fecha  = $io_report->ds_ret->data["fecrep"][$i];		
					$ls_fecrep = substr($ls_fecha,8,2)."/".substr($ls_fecha,5,2)."/".substr($ls_fecha,0,4);							 
					$ls_perfis = $io_report->ds_ret->data["perfiscal"][$i];						
					$ls_sujret = $io_report->ds_ret->data["codsujret"][$i];			     
					$ls_nomsuj = $io_report->ds_ret->data["nomsujret"][$i];	
					$ls_rif    = $io_report->ds_ret->data["rif"][$i];	
					$ls_direc  = $io_report->ds_ret->data["dirsujret"][$i];		
					$li_estcom = $io_report->ds_ret->data["estcmpret"][$i];										
				}											
																	
				$ls_nomageret=$_SESSION["la_empresa"]["nombre"];
				$ls_rifageret=$_SESSION["la_empresa"]["rifemp"];
				$ls_dirageret=$_SESSION["la_empresa"]["direccion"];
									
				uf_print_encabezado_pagina($ls_titulo,$ls_org,$ls_rifageret,$io_pdf); 
													
				uf_print_cabecera($ls_nomageret,$ls_rifageret,$ls_rifageret,$ls_perfis,
								  $ls_sujret,$ls_nomsuj,$ls_rif,$ls_dirorg,$ls_numcon,$ls_fecrep,$li_estcom,$io_pdf);
											
				if($lb_valido)
				{				
					unset($la_data);
					$lb_validodt=$io_report->uf_select_ret_iva_det($ls_codemp,$ls_numdoc);						
					if($lb_validodt)
					{  
					   $ldec_totconiva = 0;
					   $ldec_totsiniva = 0;
					   $ldec_totbasimp = 0;
					   $ldec_totmonimp = 0;
					   $ldec_totivaret = 0;
					   
					   $li_totdet=$io_report->ds_det_ret->getRowCount("numfac");			   
					   
					   for ($w=1;$w<=$li_totdet;$w++)
					   {
							$ls_numope        = $io_report->ds_det_ret->data["numope"][$w];					
							$ls_numfac        = $io_report->ds_det_ret->data["numfac"][$w];	
							$ls_numref        = $io_report->ds_det_ret->data["numcon"][$w];	              
							$ld_fecfac        = $io_report->ds_det_ret->data["fecfac"][$w];	
							$ls_monfac        = $io_report->uf_select_monfac($ls_codemp,$ls_numfac); 
							$ld_fecfac        = substr($ld_fecfac,8,2)."/".substr($ld_fecfac,5,2)."/".substr($ld_fecfac,0,4);							
							$ldec_totalsiniva = $io_report->ds_det_ret->data["totcmp_sin_iva"][$w];	
							$ldec_totalconiva = $io_report->ds_det_ret->data["totcmp_con_iva"][$w];	
							$ldec_baseimp     = $io_report->ds_det_ret->data["basimp"][$w];	
							$ldec_porimp      = $io_report->ds_det_ret->data["porimp"][$w];	
							$ldec_totimp      = $io_report->ds_det_ret->data["totimp"][$w];	
							$ldec_ivaret      = $io_report->ds_det_ret->data["iva_ret"][$w];	
							$ls_numdoc        = $io_report->ds_det_ret->data["numdoc"][$w];	
							$ls_tiptrans      = $io_report->ds_det_ret->data["tiptrans"][$w];	
							$ls_numnotdeb     = $io_report->ds_det_ret->data["numnd"][$w];	
							$ls_numnotcre     = $io_report->ds_det_ret->data["numnc"][$w];									
							$ldec_monto       = $ldec_baseimp + $ldec_totimp;  
							$ldec_totdersiniva= abs($ldec_totalconiva - $ldec_monto);
							$ls_numfacafec    = "";
							
							//--------------------------------------------------------------
							$ldec_totconiva = $ldec_totconiva + $ldec_totalconiva;	
							$ldec_totsiniva = $ldec_totsiniva + $ldec_totdersiniva;
							$ldec_totbasimp = $ldec_totbasimp + $ldec_baseimp ;	
							$ldec_totmonimp = $ldec_totmonimp + $ldec_totimp;	
							$ldec_totivaret = $ldec_totivaret + $ldec_ivaret;								
							//--------------------------------------------------------------
							$ldec_totdersiniva= number_format($ldec_totdersiniva,2,",","."); 
							$ldec_totalsiniva = number_format($ldec_totalsiniva,2,",","."); 
							$ldec_totalconiva = number_format($ldec_totalconiva,2,",",".");			
							$ldec_baseimp     = number_format($ldec_baseimp,2,",",".");			
							$ldec_porimp      = number_format($ldec_porimp,2,",",".");			
							$ldec_totimp      = number_format($ldec_totimp,2,",",".");							
							$ldec_ivaret      = number_format($ldec_ivaret,2,",",".");		
													
							$la_data[$w]= array('numope'=>$ls_numope,'fecfac'=>$ld_fecfac,'numfac'=>$ls_numfac,
												'numref'=>$ls_numref,'numnotdeb'=>$ls_numnotdeb,
												'numnotcre'=>$ls_numnotcre,'tiptrans'=>$ls_tiptrans,'numfacafec'=>$ls_numfacafec,
												'totalconiva'=>$ldec_totalconiva, 'compsinderiva'=>$ldec_totdersiniva,
												'baseimp'=>$ldec_baseimp,'porimp'=>$ldec_porimp,'totimp'=>$ldec_totimp,
												'ivaret'=>$ldec_ivaret,'numdoc'=>$ls_numdoc,'totalsiniva'=>$ldec_totalsiniva
												);														
					  }																		 																						  
					  $ldec_totconiva= number_format($ldec_totconiva,2,",","."); 
					  $ldec_totsiniva= number_format($ldec_totsiniva,2,",","."); 
					  $ldec_totbasimp= number_format($ldec_totbasimp,2,",","."); 
					  $ldec_totmonimp= number_format($ldec_totmonimp,2,",","."); 
					  $ldec_totivaret= number_format($ldec_totivaret,2,",","."); 
					  //print_r($la_data);
					  uf_print_det_ret_iva($la_data,$ldec_totconiva,$ldec_totsiniva,$ldec_totbasimp,
										   $ldec_totmonimp,$ldec_totivaret,$io_pdf); 						 						  
					  unset($la_data);							 
					}
			   }										
		 }	

	$io_pdf->ezStream(); 	
	if($lb_valido)
	{			
				
	}
	else  
	{
		print("<script language=JavaScript>");
		print("alert('Ocurrio un error al generar el reporte. Intente de Nuevo');"); 
		print("close();");
		print("</script>");		
	}
	unset($io_pdf);			
	unset($io_report);
	unset($io_funciones);
?> 