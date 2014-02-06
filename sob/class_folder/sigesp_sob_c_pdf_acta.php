<?Php
//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_encabezado_pagina($as_rutalogo,$as_titulo,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: as_rutalogo : Ruta del archivo .jpg que se desea publicar como logo
		//	    		   as_titulo:  Titulo del acta
		//	    		   io_pdf   : Instancia de objeto pdf
		//    Description: función que imprime los encabezados por página
		//	   Creado Por: Ing. Laura Cabré
		// Fecha Creación: 28/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(20,40,578,40);
		$io_pdf->addJpegFromFile($as_rutalogo,45,730,500,45); // Agregar Logo
		$li_tm=$io_pdf->getTextWidth(11,$as_titulo);
		$tm=306-($li_tm/2);
		$io_pdf->addText($tm,700,11,"<b>".$as_titulo."</b>"); // Agregar el título		
		$io_pdf->addText(500,720,8,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');		
	}// end function uf_print_encabezadopagina

//-------------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_obra,$as_ubicacion,$as_contratista,$as_codcon,&$io_pdf,$as_numcontraloria,$as_memorandumcontraloria,$as_fechacontraloria,$as_objetocontraloria)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: as_obra: descripcion de la obra.
		//				   as_ubicacion: ubicación de la obra
		//				   as_contratista: empresa ontratista 
		//				   as_codcon: codigo del contrato			  
		//	    		   io_pdf instancia obj PDF
		//    Description: funcion que imprime la cabecera principal del acta
		//	   Creado Por: Ing. Laura Cabre
		// Fecha Creación: 28/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->setLineStyle(2);
		$io_pdf->line(45,680,550,680);
		$io_pdf->line(45,530,550,530);
		$io_pdf->setLineStyle(1);
		$io_pdf->line(45,650,550,650);
		$io_pdf->line(350,620,350,590);
		$io_pdf->line(45,560,550,560);		
		$io_pdf->setLineStyle(2);
		$io_pdf->line(45,620,550,620);
		//$io_pdf->setLineStyle(2);
		$io_pdf->line(45,590,550,590);
		$io_pdf->addText(50,660,11,"<b>Obra: </b>".$as_obra);
		$io_pdf->addText(50,630,11,"<b>Ubicación:</b> ".$as_ubicacion);
		$io_pdf->addText(50,600,11,"<b>Contratista:</b> ".$as_contratista);
		$io_pdf->addText(360,600,11,"<b>Contrato Nº: </b> ".$as_codcon);
		$io_pdf->addText(50,580,9,"<b>Aprobación Contraloría Interna: </b> ");
		$io_pdf->addText(50,562,11,"<b>Nº: </b> ".$as_numcontraloria);
		$io_pdf->addText(200,562,11,"<b>Memorandum: </b> ".$as_memorandumcontraloria);
		$io_pdf->addText(400,562,11,"<b>Fecha: </b> ".$as_fechacontraloria);
		$io_pdf->addText(50,540,11,"<b>Objeto: </b> ".$as_objetocontraloria);			
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------
	
	require_once("../../shared/ezpdf/class.ezpdf.php");	
	$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(3.5,3.5,3.5,3.5); // Configuración de los margenes en centímetros
	uf_print_encabezado_pagina("cabecera_minfra.jpg","Acta de Inicio",$io_pdf); // Imprimimos el encabezado de la página
	$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el número de página
	uf_print_cabecera("Construccion de Casas","Calle Concordia","Construcciones C.A.","12569862585",&$io_pdf,"","","","") ;
	$io_pdf->transaction('commit');
	$io_pdf->ezStopPageNumbers(1,1);
	$io_pdf->ezStream();
	unset($io_pdf);	
	unset($io_funciones);
?>