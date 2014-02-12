<?PHP
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//    REPORTE: AR-C
	//  ORGANISMO: Ninguno en particular
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";		
		print "</script>";		
	}
	ini_set('memory_limit','512M');
	ini_set('max_execution_time ','0');

	//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
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
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_cxp->uf_load_seguridad_reporte("CXP","sigesp_cxp_r_arc.php",$ls_descripcion);
		return $lb_valido;
	}
	//-----------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 15/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $ls_tiporeporte;
		if($ls_tiporeporte==1)
		{
			$ls_sufijo="Bs.F.";
		}
		else
		{
			$ls_sufijo="Bs.";
		}
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],50,550,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(13,"<b>COMPROBANTE DE RETENCIONES VARIAS</b>");
		$tm=396-($li_tm/2);
		$io_pdf->addText(180,530,13,"<b>COMPROBANTE DE RETENCIONES VARIAS</b>"); // Agregar el título
		/*$li_tm=$io_pdf->getTextWidth(11,"<b>DEL IMPUESTO SOBRE LA RENTA ".$ls_sufijo."</b>");
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,540,11,"<b>DEL IMPUESTO SOBRE LA RENTA ".$ls_sufijo."</b>"); // Agregar el título
		$li_tm=$io_pdf->getTextWidth(11,"<b>(EXCEPTO SUELDOS, SALARIOS Y DEMÁS REMUNERACIONES SIMILARES A PERSONAS NATURALES RESIDENTES)</b>");
		$tm=396-($li_tm/2);
		$io_pdf->addText($tm,525,11,"<b>(EXCEPTO SUELDOS, SALARIOS Y DEMÁS REMUNERACIONES SIMILARES A PERSONAS NATURALES RESIDENTES)</b>"); // Agregar el título
		*/$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina1
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_tipproben,$as_nompro,$as_nacpro,$as_rifpro,$as_nitpro,$as_dirpro,$as_telpro,$as_nombene,
							   $as_apebene,$as_nacben,$as_cedbene,$as_numpasben,$as_dirbene,$as_telbene,&$io_cabecera,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera1
		//		   Access: private 
		//	    Arguments: as_tipproben //  Tipo si es proveedor ó beneficiario
		//	    		   as_nompro // Nombre del proveedor
		//	    		   as_nacpro // nacionalidad del proveedor
		//	    		   as_nitpro // Nit del proveedor
		//	    		   as_dirpro // Dirección del proveedor
		//	    		   as_telpro // Teléfono del proveedor
		//	    		   as_nombene // Nombre del beneficiario
		//	    		   as_apebene // Apellido del beneficiario
		//	    		   as_nacben // Nacionalidad del beneficiario
		//	    		   as_cedbene // Cédula del beneficiario
		//	    		   as_numpasben // Número de pasaporte
		//	    		   as_dirbene // dirección del beneficiario
		//	    		   as_telbene // Teléfono del beneficiario
		//	    		   io_cabecera // objeto cabecera
		//	    		   io_pdf // Objeto PDF
		//    Description: función que imprime la cabecera por proveedor ó beneficiario
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 15/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fecha;
		$ls_ciudad=$_SESSION["la_empresa"]["ciuemp"];
		$ls_dia=date("d");
		$li_mes=date("m");
		$ls_anio=date("Y");
		$ls_mes=$io_fecha->uf_load_nombre_mes($li_mes);
		$io_pdf->saveState();
        $io_pdf->setColor(0,0,0);
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->rectangle(27,280,719,238.5);
		$io_pdf->rectangle(50,30,250,60);
		$io_pdf->line(50,75,300,75);
		$io_pdf->addText(55,80,9,'AGENTE DE RETENCIÓN  (SELLO, FECHA Y FIRMA)'); // Agregar el título
		$io_pdf->addText(55,55,9,'Lic. Ailide García'); // Agregar el título
		$io_pdf->addText(55,35,8,$ls_ciudad.', '.$ls_dia.' de '.$ls_mes.' de '.$ls_anio); // Agregar el título
		//$io_pdf->addText(55,70,9,'FECHA:     /     /'); // Agregar el título
		//$io_pdf->addText(540,95,9,' PARA USO DE LA ADMINISTRACIÓN PÚBLICA'); // Agregar el título
		if($as_tipproben=="P")
		{
			$ls_nombre=str_pad($as_nompro,600," ");
			$ls_cedula="";
			$ls_pasaporte="";
			$ls_rif=$as_rifpro;
			$ls_nit=$as_nitpro;
			$ls_direccion=$as_dirpro;
			$ls_telefono=$as_telpro;
			$li_pos_tipper=475;
			$ls_nacionalidad=$as_nacpro;
		}
		else
		{
			$ls_nombre=str_pad($as_apebene.", ".$as_nombene,600," ");
			$ls_cedula=$as_cedbene;
			$ls_pasaporte=$as_numpasben;
			$ls_rif="";
			$ls_nit="";
			$ls_direccion=$as_dirbene;
			$ls_telefono=$as_telbene;
			$li_pos_tipper=492;
			$ls_nacionalidad=$as_nacben;
		}
		if($ls_nacionalidad=="V")
		{
			$li_pos_nac=457;
			$li_pos_con=455;
		}
		else
		{
			$li_pos_nac=437;
			$li_pos_con=520;
		}
		$io_pdf->addText(304,492,8,"X"); // Agente de Retención
		$io_pdf->addText(716,$li_pos_tipper,8,"X"); // Tipo de Persona
		$io_pdf->addText(440,$li_pos_nac,8,"X"); // Nacionalidad
		$io_pdf->addText(603,$li_pos_nac,8,"X"); // Residente
		$io_pdf->addText($li_pos_con,419,8,"X"); // Constituida
		$io_pdf->ezSetY(520);
		$la_data=array(array('tipo'=>'Marque el tipo de agente de retención', 
		                     'titnatural'=>'             Persona   Natural:', 
							 'desnatural'=>'              ____',
		                     'titjuridica'=>'             Persona  Jurídica:', 
							 'desjuridica'=>'             ____',
		                     'titpublica'=>'              Entidad    Pública:', 
							 'despublica'=>'              ____'));
		$la_columna=array('tipo'=>'','titnatural'=>'','desnatural'=>'','titjuridica'=>'','desjuridica'=>'','titpublica'=>'','despublica'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>295, // Ancho de la tabla
						 'maxWidth'=>295, // Ancho Máximo de la tabla
						 'xPos'=>180, // Orientación de la tabla
						 'cols'=>array('tipo'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'titnatural'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'desnatural'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'titjuridica'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'desjuridica'=>array('justification'=>'center','width'=>30), // Justificación y ancho de la columna
						 			   'titpublica'=>array('justification'=>'center','width'=>45), // Justificación y ancho de la columna
						 			   'despublica'=>array('justification'=>'left','width'=>30))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('nombre'=>'<b>AGENTE DE RETENCIÓN</b>'));
		$la_columna=array('nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>295, // Ancho de la tabla
						 'maxWidth'=>295, // Ancho Máximo de la tabla
						 'xPos'=>180, // Orientación de la tabla
						 'rowGap' =>4.7,
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>295))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSety(520);
		$la_data=array(array('nombre'=>'<b>APELLIDOS Y NOMBRES:</b> '.$ls_nombre));
		$la_columna=array('nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>350, // Ancho de la tabla
						 'maxWidth'=>350, // Ancho Máximo de la tabla
						 'xPos'=>502.5, // Orientación de la tabla
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>350))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSety(520);
		$la_data=array(array('nombre'=>'Tipo de Persona'),array('nombre'=>' Natural:_____'),array('nombre'=>'Jurídica:_____'));
		$la_columna=array('nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>73, // Ancho de la tabla
						 'maxWidth'=>73, // Ancho Máximo de la tabla
						 'xPos'=>714, // Orientación de la tabla
						 'rowGap' =>3.8,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>73))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$ls_nombre_emp=str_pad($_SESSION["la_empresa"]["nombre"],280," ");
		$la_data=array(array('nombre'=>'<b>NOMBRE DEL ORGANISMO</b> '.$ls_nombre_emp));
		$la_columna=array('nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>295, // Ancho de la tabla
						 'maxWidth'=>295, // Ancho Máximo de la tabla
						 'xPos'=>180, // Orientación de la tabla
						 'rowGap' =>5,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>295))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSety(469);
		$la_data=array(array('nacionalidad'=>'                                          Nacionalidad', 'descripcionnac'=>'V:___          E:___',
		               		 'residente'=>'                                          Residente en el Pais', 'descripcionres'=>' Si:___                                                                                                                             No:___' ));
		$la_columna=array('nacionalidad'=>'','descripcionnac'=>'','residente'=>'','descripcionres'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>423, // Ancho de la tabla
						 'maxWidth'=>423, // Ancho Máximo de la tabla
						 'xPos'=>539, // Orientación de la tabla
						 'rowGap' =>4.7,
						 'cols'=>array('nacionalidad'=>array('justification'=>'right','width'=>100),
						 			   'descripcionnac'=>array('justification'=>'left','width'=>40),
									   'residente'=>array('justification'=>'right','width'=>120),
									   'descripcionres'=>array('justification'=>'left','width'=>163))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$ls_rif_emp=$_SESSION["la_empresa"]["rifemp"];
		$ls_nit_emp=$_SESSION["la_empresa"]["nitemp"];
		$la_data=array(array('nombre'=>'<b>TIPO DE PERSONA JURÍDICA</b>     RIF: '.$ls_rif_emp.'      NIT: '.$ls_nit_emp ));
		$la_columna=array('nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>295, // Ancho de la tabla
						 'maxWidth'=>295, // Ancho Máximo de la tabla
						 'xPos'=>180, // Orientación de la tabla
						 'rowGap' =>5,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>295))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSety(431.8);
		$la_data=array(array('nombre'=>'   Constituida en el Pais              Si:___                 No:___'));
		$la_columna=array('nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>423, // Ancho de la tabla
						 'maxWidth'=>423, // Ancho Máximo de la tabla
						 'xPos'=>539, // Orientación de la tabla
						 'rowGap' =>5,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>423))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSetDy(-10);
		$ls_direccion_emp=$_SESSION["la_empresa"]["direccion"];
		$ls_telemp=$_SESSION["la_empresa"]["telemp"];
		$ld_fecha="31/12/".substr($_SESSION["la_empresa"]["periodo"],0,4);
		$la_data=array(array('nombre'=>'<b>DIRECCIÓN </b> '.str_pad($ls_direccion_emp,254," "), 'fecha'=>'Fecha de Cierre    '.$ld_fecha),
		               array('nombre'=>'<b>TELEFONO(S) </b> '.str_pad($ls_telemp,280," "), 'fecha'=>''));
		$la_columna=array('nombre'=>'','fecha'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>295, // Ancho de la tabla
						 'maxWidth'=>295, // Ancho Máximo de la tabla
						 'xPos'=>180, // Orientación de la tabla
						 'rowGap' =>4.7,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>215),
						               'fecha'=>array('justification'=>'center','width'=>80))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->ezSety(412.5);
		$la_data=array(array('cedula'=>'Cédula', 'pasaporte'=>'Número de Pasaporte','rif'=>'Nro R.I.F.', 'nit'=>'Nro N.I.T.' ),
		               array('cedula'=>$ls_cedula, 'pasaporte'=>$ls_pasaporte,'rif'=>$ls_rif, 'nit'=>$ls_nit));
		$la_columna=array('cedula'=>'','pasaporte'=>'','rif'=>'','nit'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>423, // Ancho de la tabla
						 'maxWidth'=>423, // Ancho Máximo de la tabla
						 'xPos'=>539, // Orientación de la tabla
						 'rowGap' =>3,
						 'cols'=>array('cedula'=>array('justification'=>'left','width'=>110),
						 			   'pasaporte'=>array('justification'=>'left','width'=>113),
									   'rif'=>array('justification'=>'left','width'=>100),
									   'nit'=>array('justification'=>'left','width'=>100))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$ld_fechadesde="01/01/".substr($_SESSION["la_empresa"]["periodo"],0,4);
		$ld_fechahasta="31/12/".substr($_SESSION["la_empresa"]["periodo"],0,4);
		$la_data=array(array('nombre'=>'PERÍODO AL QUE COMPRENDEN LOS PAGOS EFECTUADOS DURANTE '.$ld_fechadesde.' AL '.$ld_fechahasta));
		$la_columna=array('nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>423, // Ancho de la tabla
						 'maxWidth'=>423, // Ancho Máximo de la tabla
						 'xPos'=>539, // Orientación de la tabla
						 'rowGap' =>3,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>423))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('nombre'=>'<b>DIRECCIÓN</b> '.str_pad($ls_direccion,200," ")),
		               array('nombre'=>'<b>TELÉFONO</b> '.str_pad($ls_telefono,200," ")));
		$la_columna=array('nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>423, // Ancho de la tabla
						 'maxWidth'=>423, // Ancho Máximo de la tabla
						 'xPos'=>539, // Orientación de la tabla
						 'rowGap' =>2.5,
						 'cols'=>array('nombre'=>array('justification'=>'left','width'=>423))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('nombre'=>'<b>INFORMACIÓN DEL IMPUESTO RETENIDO Y ENTERADO</b> '));
		$la_columna=array('nombre'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>718, // Ancho de la tabla
						 'maxWidth'=>718, // Ancho Máximo de la tabla
						 'xPos'=>391.5, // Orientación de la tabla
						 'rowGap' =>2.5,
						 'cols'=>array('nombre'=>array('justification'=>'center','width'=>718))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$la_data=array(array('fecha'=>'             Fecha de Pago            Día    Mes   Año','total'=>'Monto Pagado',
		                     'retencion'=>'Base Imponible','porcentaje'=>'Tarifa % ',
							 'impuesto'=>'Impuesto Retenido','totalacumulado'=>'Base Imp. Acumulada',
							 'retencionacumulada'=>'Impuesto Acumulado','conceptoretencion'=>'Concepto Retencion','impuestoenterado'=>'   Impuesto Enterado    Fecha       Banco'));
		$la_columna=array('fecha'=>'','total'=>'','retencion'=>'','porcentaje'=>'','impuesto'=>'','totalacumulado'=>'','retencionacumulada'=>'','conceptoretencion'=>'','impuestoenterado'=>'' );
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>718, // Ancho de la tabla
						 'maxWidth'=>718, // Ancho Máximo de la tabla
						 'xPos'=>391.5, // Orientación de la tabla
						 'rowGap' =>2.5,
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>118),
						               'total'=>array('justification'=>'center','width'=>70),
									   'retencion'=>array('justification'=>'center','width'=>70),
									   'porcentaje'=>array('justification'=>'center','width'=>70),
									   'impuesto'=>array('justification'=>'center','width'=>70),
									   'totalacumulado'=>array('justification'=>'center','width'=>70),
									   'retencionacumulada'=>array('justification'=>'center','width'=>70),
									   'conceptoretencion'=>array('justification'=>'center','width'=>70),
									   'impuestoenterado'=>array('justification'=>'center','width'=>100),
									   )); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
		unset($la_data);
		unset($la_columna);
		unset($la_config);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_cabecera,'all');
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//	   			   io_pdf // Objeto PDF
		//    Description: función que imprime el detalle por deducción
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 15/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$la_columna=array('fecha'=>'','total'=>'','retencion'=>'','porcentaje'=>'','impuesto'=>'','totalacumulado'=>'','retencionacumulada'=>'','conceptoretencion'=>'','impuestoenterado'=>'' );
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>718, // Ancho de la tabla
						 'maxWidth'=>718, // Ancho Máximo de la tabla
						 'xPos'=>391.5, // Orientación de la tabla
						 'rowGap' =>2.5,
						 'cols'=>array('fecha'=>array('justification'=>'center','width'=>130),
						               'total'=>array('justification'=>'center','width'=>70),
									   'retencion'=>array('justification'=>'center','width'=>70),
									   'porcentaje'=>array('justification'=>'center','width'=>70),
									   'impuesto'=>array('justification'=>'center','width'=>70),
									   'totalacumulado'=>array('justification'=>'center','width'=>70),
									   'retencionacumulada'=>array('justification'=>'center','width'=>70),
									   'conceptoretencion'=>array('justification'=>'center','width'=>70),
									   'impuestoenterado'=>array('justification'=>'center','width'=>100),
									   )); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);	
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_total_pagado,$ai_total_retencion,$ai_total_impuesto,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera1
		//		   Access: private 
		//	    Arguments: ai_total_pagado // Total Pagado
		//	   			   ai_total_retencion // Total Retención
		//	   			   ai_total_impuesto // Total Impuesto
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: función que imprime el fin de la cabecera por proveedor
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 15/07/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*$io_pdf->ezSetDy(-5);
		$la_data=array(array('linea'=>str_pad('_',156,'_')));
		$la_columna=array('linea'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>718, // Ancho de la tabla
						 'maxWidth'=>718, // Ancho Máximo de la tabla
						 'xPos'=>391.5, // Orientación de la tabla
						 'rowGap' =>2.5,
						 'cols'=>array('linea'=>array('justification'=>'center','width'=>718))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		unset($la_data);
		unset($la_columna);
		unset($la_config);*/
		$la_data=array(array('total'=>'<b>Total</b>', 'pagado'=>$ai_total_pagado, 'retencion'=>$ai_total_retencion,
		                     'porcentaje'=>'','impuesto'=>$ai_total_impuesto,'totalacumulado'=>'','retencionacumulada'=>'','conceptoretencion'=>'','impuestoenterado'=>''));
		$la_columna=array('total'=>'','pagado'=>'','retencion'=>'','porcentaje'=>'','impuesto'=>'','totalacumulado'=>'','retencionacumulada'=>'','conceptoretencion'=>'','impuestoenterado'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tamaño de Letras
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>718, // Ancho de la tabla
						 'maxWidth'=>718, // Ancho Máximo de la tabla
						 'xPos'=>391.5, // Orientación de la tabla
						 'rowGap' =>2.5,
						 'cols'=>array('total'=>array('justification'=>'center','width'=>130),
						               'pagado'=>array('justification'=>'center','width'=>70),
									   'retencion'=>array('justification'=>'center','width'=>70),
									   'porcentaje'=>array('justification'=>'center','width'=>70),
									   'impuesto'=>array('justification'=>'center','width'=>70),
									   'totalacumulado'=>array('justification'=>'center','width'=>70),
									   'retencionacumulada'=>array('justification'=>'center','width'=>70),
									   'conceptoretencion'=>array('justification'=>'center','width'=>70),
									   'impuestoenterado'=>array('justification'=>'center','width'=>100),
									   )); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------
	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_cxp_class_report.php");
	$io_report=new sigesp_cxp_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();				
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha=new class_fecha();				
	require_once("../class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
	$ls_tipo=$io_fun_cxp->uf_obtenervalor_get("tipproben","");
	$ls_coddes=$io_fun_cxp->uf_obtenervalor_get("codprobendes","");
	$ls_codhas=$io_fun_cxp->uf_obtenervalor_get("codprobenhas","");
	$ls_tiporeporte=$io_fun_cxp->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_cxp_class_reportbsf.php");
		$io_report=new sigesp_cxp_class_reportbsf();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad("Reporte de ARC"); // Seguridad de Reporte
	if($lb_valido)
	{
		$lb_valido=$io_report->uf_arc_cabecera($ls_coddes,$ls_codhas,$ls_tipo); // Cargar el DS con los datos de la cabecera del reporte
	}
	if($lb_valido==false) // Existe algún error ó no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{
		error_reporting(E_ALL);
		$io_pdf=new Cezpdf('LETTER','landscape'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->ezSetCmMargins(11.7,4,1,2); // Configuración de los margenes en centímetros
		uf_print_encabezado_pagina($io_pdf); // Imprimimos el encabezado de la página
		$li_totrow=$io_report->DS->getRowCount("tipproben");
		for($li_i=1;(($li_i<=$li_totrow)&&($lb_valido));$li_i++)
		{
			$li_total_pagado=0;
			$li_total_retencion=0;
			$li_total_impuesto=0;
			$ls_tipproben=$io_report->DS->data["tipproben"][$li_i];
			$ls_nompro=$io_report->DS->data["nompro"][$li_i];
			$ls_nacpro=$io_report->DS->data["nacpro"][$li_i];
			$ls_rifpro=$io_report->DS->data["rifpro"][$li_i];
			$ls_nitpro=$io_report->DS->data["nitpro"][$li_i];
			$ls_dirpro=$io_report->DS->data["dirpro"][$li_i];
			$ls_telpro=$io_report->DS->data["telpro"][$li_i];
			$ls_nombene=$io_report->DS->data["nombene"][$li_i];
			$ls_apebene=$io_report->DS->data["apebene"][$li_i];
			$ls_nacben=$io_report->DS->data["nacben"][$li_i];
			$ls_cedbene=$io_report->DS->data["ced_bene"][$li_i];
			$ls_numpasben=$io_report->DS->data["numpasben"][$li_i];
			$ls_dirbene=$io_report->DS->data["dirbene"][$li_i];
			$ls_telbene=$io_report->DS->data["telbene"][$li_i];
			if($ls_tipproben=="P")
			{
				$ls_codigo=$io_report->DS->data["cod_pro"][$li_i];
			}
			else
			{
				$ls_codigo=$io_report->DS->data["ced_bene"][$li_i];
			}
			$io_cabecera=$io_pdf->openObject(); // Creamos el objeto cabecera
			uf_print_cabecera($ls_tipproben,$ls_nompro,$ls_nacpro,$ls_rifpro,$ls_nitpro,$ls_dirpro,$ls_telpro,$ls_nombene,
							  $ls_apebene,$ls_nacben,$ls_cedbene,$ls_numpasben,$ls_dirbene,$ls_telbene,&$io_cabecera,&$io_pdf); // Imprimimos la cabecera del registro
			$lb_valido=$io_report->uf_arc_detalle($ls_codigo,$ls_tipproben); // Obtenemos el detalle del reporte
			if($lb_valido)
			{
				$li_totrow_det=$io_report->ds_detalle->getRowCount("cod_pro");
				$li_totalacumulado=0;
				$li_retencionacumulada=0;
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++) 
				{
					$ls_fecha=substr($io_report->ds_detalle->data["fecemidoc"][$li_s],8,2);
					$ls_fecha=$ls_fecha."    ".substr($io_report->ds_detalle->data["fecemidoc"][$li_s],5,2);
					$ls_fecha=$ls_fecha."    ".substr($io_report->ds_detalle->data["fecemidoc"][$li_s],0,4);
					$li_montotdoc=number_format($io_report->ds_detalle->data["montotdoc"][$li_s],2,",",".");
					$li_monobjret=number_format($io_report->ds_detalle->data["monobjret"][$li_s],2,",",".");
					$li_porded=number_format($io_report->ds_detalle->data["porded"][$li_s],2,",",".");
					$li_monret=number_format($io_report->ds_detalle->data["monret"][$li_s],2,",",".");
					$li_total_pagado=$li_total_pagado+$io_report->ds_detalle->data["montotdoc"][$li_s];
					$li_total_retencion=$li_total_retencion+$io_report->ds_detalle->data["monobjret"][$li_s];
					$li_total_impuesto=$li_total_impuesto+$io_report->ds_detalle->data["monret"][$li_s];
					$li_totalacumulado=$li_totalacumulado+$io_report->ds_detalle->data["monobjret"][$li_s];
					$li_retencionacumulada=$li_retencionacumulada+$io_report->ds_detalle->data["monret"][$li_s];
					$li_total=number_format($li_totalacumulado,2,",",".");
					$li_retencion=number_format($li_retencionacumulada,2,",",".");
					$la_data[$li_s]=array('fecha'=>$ls_fecha,'total'=>$li_montotdoc,'retencion'=>$li_monobjret,
					                      'porcentaje'=>$li_porded,'impuesto'=>$li_monret,'totalacumulado'=>$li_total,
										  'retencionacumulada'=>$li_retencion,'conceptoretencion'=>'','impuestoenterado'=>'');
				}
				uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle 
				unset($la_data);
				$li_total_pagado=number_format($li_total_pagado,2,",",".");
				$li_total_retencion=number_format($li_total_retencion,2,",",".");
				$li_total_impuesto=number_format($li_total_impuesto,2,",",".");
				uf_print_pie_cabecera($li_total_pagado,$li_total_retencion,$li_total_impuesto,&$io_pdf);
				$io_pdf->stopObject($io_cabecera); // Detener el objeto cabecera
				if($li_i<$li_totrow)
				{
					$io_pdf->ezNewPage(); // Insertar una nueva página
				}
				$io_report->ds_detalle->reset_ds();
			}
		}
		$io_report->DS->reset_ds();
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
	unset($io_report);
	unset($io_funciones);
	unset($io_fun_nomina);
?> 