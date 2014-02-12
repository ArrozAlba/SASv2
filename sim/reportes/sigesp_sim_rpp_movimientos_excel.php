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
	/*function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezadopagina
		//		   Access: private
		//	    Arguments: as_titulo // T�tulo del Reporte
		//	    		   as_desnom // Descripci�n de la n�mina
		//	    		   as_fecha // periodo de fecha
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creaci�n: 26/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->line(20,40,730,40);
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezadopagina
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_cabecera($as_nomemp,$as_codart,$as_denart,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private
		//	    Arguments: as_nomemp    // nombre de la empresa
		//	    		   as_codart    // codigo del articulo
		//	    		   as_denart    // denominacion del articulo
		//	    		   io_pdf       // total de registros que va a tener el reporte
		//    Description: funci�n que imprime la cabecera de cada p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 21/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//$as_nomfisalm=substr($as_nomfisalm,0,35);
		//$as_denpro=substr($as_denpro,0,25);
		$la_data=array(array('name'=>'<b>Empresa</b>  '.$as_nomemp.''),
					   array ('name'=>'<b>Art�culo</b>  '.$as_codart.' '.$as_denart));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tama�o de Letras
						 'lineCol'=>array(0.9,0.9,0.9), // Mostrar L�neas
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>2	, // Sombra entre l�neas
						 'shadeCol'=>array(0.9,0.9,0.9), // Color de la sombra
						 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>760, // Ancho de la tabla
						 'maxWidth'=>760); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_cabecera
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_detalle($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 21/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->ezSetDy(-5);
		$la_columna=array('fecha'=>'<b>Fecha</b>',
						  'operacion'=>'<b>Operaci�n</b>',
						  'documento'=>'<b>Documento</b>',
						  'almacen'=>'<b>Almac�n</b>',
						  'cantidad'=>'<b>Cantidad</b>',
						  'costo'=>'<b>Costo</b>',
						  'existe'=>'<b>Existencia</b>',
						  'total'=>'<b>Total Costo</b>');
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('fecha'=>array('justification'=>'left','width'=>80), // Justificaci�n y ancho de la columna
						 			   'operacion'=>array('justification'=>'left','width'=>100), // Justificaci�n y ancho de la columna
						 			   'documento'=>array('justification'=>'left','width'=>100), // Justificaci�n y ancho de la columna
						 			   'almacen'=>array('justification'=>'left','width'=>160), // Justificaci�n y ancho de la columna
						 			   'cantidad'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'costo'=>array('justification'=>'right','width'=>80),

									   'existe'=>array('justification'=>'right','width'=>80),
									   'total'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_detalle
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_totales($la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_totales
		//		   Access: private
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el detalle por personal
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 06/07/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$io_pdf->ezSetDy(-15);
		$la_columna = array('name1'=>'','name2'=>'');

		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'titleFontSize' => 11,  // Tama�o de Letras de los t�tulos
						 'showLines'=>1, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>670, // Ancho de la tabla
						 'maxWidth'=>670, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'cols'=>array('name1'=>array('justification'=>'left','width'=>160), // Justificaci�n y ancho de la columna
						 			   'name2'=>array('justification'=>'right','width'=>50))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);

	}// end function uf_print_totales
	//--------------------------------------------------------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------------------------------------------------------
	function uf_print_pie_cabecera($ai_totent,$ai_totsal,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_pie_cabecera
		//		   Access: private
		//	    Arguments: ai_totent // Total Entradas
		//	   			   ai_totsal // Total Salidas
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: funci�n que imprime el fin de la cabecera de cada p�gina
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 26/04/2006
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//print "Entradas".$ai_totent."Salidas".$ai_totsal."<br>";
		$la_data=array(array('name'=>'____________________________________________________________________________________________________________________'));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 10, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>660); // Ancho M�ximo de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('total'=>'<b>Totales:        Entradas  </b>'.$ai_totent.' '.'<b>Salidas  </b>'.$ai_totsal.''));
		$la_columna=array('total'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>660, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>500), // Justificaci�n y ancho de la columna
						 			   'entradas'=>array('justification'=>'right','width'=>100), // Justificaci�n y ancho de la columna
						 			   'salidas'=>array('justification'=>'right','width'=>100))); // Justificaci�n y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
		$la_data=array(array('name'=>''));
		$la_columna=array('name'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>660, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center'); // Orientaci�n de la tabla
		$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	}// end function uf_print_pie_cabecera*/
	//--------------------------------------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_init_niveles()
	{	///////////////////////////////////////////////////////////////////////////////////////////////////////
		//	   Function: uf_init_niveles
		//	     Access: public
		//	    Returns: vacio
		//	Description: Este m�todo realiza una consulta a los formatos de las cuentas
		//               para conocer los niveles de la escalera de las cuentas contables
		//////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_funciones,$ia_niveles_scg;

		$ls_formato=""; $li_posicion=0; $li_indice=0;
		$dat_emp=$_SESSION["la_empresa"];
		//contable
		$ls_formato = trim($dat_emp["formcont"])."-";
		$li_posicion = 1 ;
		$li_indice   = 1 ;
		$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		do
		{
			$ia_niveles_scg[$li_indice] = $li_posicion;
			$li_indice   = $li_indice+1;
			$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
		} while ($li_posicion>=0);
	}// end function uf_init_niveles
	//-----------------------------------------------------------------------------------------------------------------------------------




//---------------------------------------------------------------------------------------------------------------------------
	// para crear el libro excel
		require_once ("../../shared/writeexcel/class.writeexcel_workbookbig.inc.php");
		require_once ("../../shared/writeexcel/class.writeexcel_worksheet.inc.php");
		$lo_archivo = tempnam("/home/production/tmp", "movimientos.xls");
		$lo_libro = &new writeexcel_workbookbig($lo_archivo);
		$lo_hoja = &$lo_libro->addworksheet();
	//---------------------------------------------------------------------------------------------------------------------------
	//


	//-----------------------------------------------------  Instancia de las clases  ------------------------------------------------

	require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("sigesp_sim_class_report.php");
	$io_report=new sigesp_sim_class_report();
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("../class_funciones_inventario.php");
	$io_fun_inventario=new class_funciones_inventario();
	require_once("../../shared/class_folder/class_datastore.php");
	$ds_existencia=new class_datastore();
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_sql.php");
	$io_datastore= new class_datastore();
	$io_include=new sigesp_include();
	$io_connect=$io_include->uf_conectar();
	$io_msg=new class_mensajes();
	$io_sql=new class_sql($io_connect);


        $ia_niveles_scg[0]="";
		uf_init_niveles();
		$li_total=count($ia_niveles_scg)-1;

	//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------

	if(array_key_exists("orden",$_GET))
	{
		$li_orden=$_GET["orden"];
	}
	else
	{
		$li_orden="";
	}


			/*$ld_desde=$_GET["fecdesde"];
			$ld_hasta=$_GET["fechasta"];
			$ls_codart=$_GET["codart"];
			$ls_codalm=$_GET["codalm"];*/
	$ld_desde=$io_fun_inventario->uf_obtenervalor_get("fecdesde","");
	$ld_hasta=$io_fun_inventario->uf_obtenervalor_get("fechasta","");
	$ls_tienda_desde=$io_fun_inventario->uf_obtenervalor_get("agro_desde","");
	$ls_tienda_hasta=$io_fun_inventario->uf_obtenervalor_get("agro_hasta","");
//print $ld_desde;

	$ls_titulo="Movimientos de Art�culos";
	if($ld_desde!="")
	{$ls_fecha="Periodo Desde: ".$ld_desde."  Hasta: ".$ld_hasta;}
	else
	{$ls_fecha="";}

	$ls_titulo     = "MOVIMIENTOS DE ARTICULOS";
	$ls_nombemp = "CVAL CORPORACION VENEZOLANA DE ALIMENTOS, S.A";

			$lo_encabezado= &$lo_libro->addformat();
			$lo_encabezado->set_bold();
			$lo_encabezado->set_font("Verdana");
			$lo_encabezado->set_align('center');
			$lo_encabezado->set_size('11');

			$lo_titulo= &$lo_libro->addformat();
			$lo_titulo->set_bold();
			$lo_titulo->set_font("Verdana");
			$lo_titulo->set_align('center');
			$lo_titulo->set_size('9');

			$lo_total= &$lo_libro->addformat();
			$lo_total->set_bold();
			$lo_total->set_font("Verdana");
			$lo_total->set_align('right');
			$lo_total->set_size('9');

			$lo_resumen= &$lo_libro->addformat();
			$lo_resumen->set_bold();
			$lo_resumen->set_font("Verdana");
			$lo_resumen->set_align('left');
			$lo_resumen->set_size('9');


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

			$lo_hoja->set_column(0,0,05);
			$lo_hoja->set_column(0,1,20);
			$lo_hoja->set_column(0,2,40);
			$lo_hoja->set_column(0,3,40);




			$lo_hoja->write(0,2,$ls_nombemp,$lo_encabezado);

			$lo_hoja->write(2,2,$ls_titulo,$lo_encabezado);

			$lo_hoja->write(3,2,$ls_fecha,$lo_encabezado);




	//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_codart=$io_fun_inventario->uf_obtenervalor_get("codart","");
	$ls_codalm=$io_fun_inventario->uf_obtenervalor_get("codalm","");
	$li_ordenart=$io_fun_inventario->uf_obtenervalor_get("ordenart","");
	$li_ordenfec=$io_fun_inventario->uf_obtenervalor_get("ordenfec","");

//	print $ls_codart.$ls_codalm;

	$li_existencia=0;
	$li_totcantent=0;
	$li_totmovsal=0;
	$li_totcansal=0;
	$li_totmovrev=0;
	$li_totcanrev=0;//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=$io_report->uf_select_articulos($ls_codemp,$ls_codalm,$ls_codart,$ld_desde,$ld_hasta,$li_total,$li_ordenart,$ls_tienda_desde,$ls_tienda_hasta); // Cargar el DS con los datos de la cabecera del reporte
	if($lb_valido==false) // Existe alg�n error � no hay registros
	{
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');");
		print(" close();");
		print("</script>");
	}
	else // Imprimimos el reporte
	{


		$li_totrow=$io_report->ds->getRowCount("codart");


	 $lo_hoja->set_column(5,0,10);
	 $lo_hoja->write(5,0,"FECHA ",$lo_titulo);

	 $lo_hoja->set_column(5,1,30);
	 $lo_hoja->write(5,1,"OPERACION ",$lo_titulo);

	 $lo_hoja->set_column(5,2,15);
	 $lo_hoja->write(5,2,"DOCUMENTO ",$lo_titulo);

	 $lo_hoja->set_column(5,3,20);
	 $lo_hoja->write(5,3,"ALMACEN ",$lo_titulo);

	  $lo_hoja->set_column(5,4,10);
	 $lo_hoja->write(5,4,"CANTIDAD ",$lo_titulo);

	 $lo_hoja->set_column(5,5,10);
	 $lo_hoja->write(5,5,"COSTO ",$lo_titulo);

	 $lo_hoja->set_column(5,6,10);
	 $lo_hoja->write(5,6,"EXISTENCIA ",$lo_titulo);

	 $lo_hoja->set_column(5,7,15);
	 $lo_hoja->write(5,7,"TOTAL COSTO Bs. ",$lo_titulo);


		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{

			$li_totent=0;
			$li_totsal=0;
			$ls_codart=  $io_report->ds->data["codart"][$li_i];
			$ls_denart=  $io_report->ds->data["denart"][$li_i];

			$lb_valido=$io_report->uf_select_movimientosxarticulos($ls_codemp,$ls_codalm,$ls_codart,$ld_desde,$ld_hasta,
																   $li_total,$li_ordenart,$li_ordenfec,$ls_tienda_desde,$ls_tienda_hasta); // Obtenemos el detalle del reporte
			//print $lb_valido;
			if($lb_valido)
			{

				//$li_existencia=0;
				$li_totmovent=0;
				$li_mov=5;
				$li_totrow_det=$io_report->ds_detalle->getRowCount("nummov");
				for($li_s=1;$li_s<=$li_totrow_det;$li_s++)
				{

					$li_mov++;

					$ld_fecmov=     $io_report->ds_detalle->data["fecmov"][$li_s];
					$ls_opeinv=     $io_report->ds_detalle->data["opeinv"][$li_s];
					$ls_numdoc=     $io_report->ds_detalle->data["numdoc"][$li_s];
					$ls_nomfisalm=  $io_report->ds_detalle->data["nomfisalm"][$li_s];
					$li_canart=     $io_report->ds_detalle->data["canart"][$li_s];
					$li_cosart=     $io_report->ds_detalle->data["cosart"][$li_s];
					$ld_fecmov=     $io_funciones->uf_convertirfecmostrar($ld_fecmov);
					$ls_coddoc=     $io_report->ds_detalle->data["codprodoc"][$li_s];



					if($ls_opeinv=="ENT")
					{
						$ls_opeinv="Entrada de Inventario";
						$li_totmovent=$li_totmovent + 1;
						$li_totcantent=$li_totcantent + $li_canart;
						$li_existencia=$li_existencia + $li_canart;
					}
					elseif(($ls_opeinv=="SAL") and ($ls_coddoc=="FAC"))
					{
						$ls_opeinv="Salida de Inventario por Facturacion";
						$li_totmovsal=$li_totmovsal + 1;

						$li_totcansal=$li_totcansal + $li_canart;
						$li_existencia=$li_existencia - $li_canart;

					}
					elseif(($ls_opeinv=="SAL") and ($ls_coddoc=="REV"))
					{
						$ls_opeinv="Reverso de Inventario";
						$li_totmovrev=$li_totmovrev + 1;
						$li_totcanrev=$li_totcanrev + $li_canart;
						$li_existencia=$li_existencia - $li_canart;

					}
					elseif(($ls_opeinv=="SAL") and ($ls_coddoc=="SAL"))
					{
						$ls_opeinv="Salida de Inventario por Despacho";
						$li_totmovsal=$li_totmovsal + 1;
						$li_totcansal=$li_totcansal + $li_canart;
						$li_existencia=$li_existencia - $li_canart;

					}

					$li_totalcosto=$li_cosart*$li_canart;
					//$li_cosart=number_format($li_cosart,2,",",".");
					///$li_canart=number_format($li_canart,2,",",".");
					$li_existenciapan=$li_existencia;
					//$li_existenciapan=number_format($li_existencia,2,",",".");
					$li_totalcosto=number_format($li_totalcosto,2,",",".");


		 $lo_hoja->write($li_mov, 0 , $ld_fecmov ,$lo_dataleft);
		 $lo_hoja->write($li_mov, 1 , $ls_opeinv ,$lo_dataleft);
		 $lo_hoja->write($li_mov, 2 , $ls_numdoc ,$lo_dataleft);
		 $lo_hoja->write($li_mov, 3 , $ls_nomfisalm ,$lo_dataleft);
		 $lo_hoja->write($li_mov, 4 , $li_canart,$lo_dataleft);
		 $lo_hoja->write($li_mov, 5 , $li_cosart ,$lo_dataright);
		 $lo_hoja->write($li_mov, 6 , $li_existenciapan,$lo_dataright);
		 $lo_hoja->write($li_mov, 7 , $li_totalcosto,$lo_dataright);



				}

				$li_totmovent=number_format($li_totmovent,2,",",".");
				$li_totcantent=number_format($li_totcantent,2,",",".");
				$li_totmovsal=number_format($li_totmovsal,2,",",".");
				$li_totcansal=number_format($li_totcansal,2,",",".");
				$li_totmovrev=number_format($li_totmovrev,2,",",".");
				$li_totcanrev=number_format($li_totcanrev,2,",",".");



			}


		}

		$li_resumen=$li_mov+6;



			$lo_hoja->write($li_resumen, 1,"RESUMEN MOVIMIENTOS ",$lo_titulo);
			$li_resumen++;
			$lo_hoja->write($li_resumen, 1 , "Total Movimientos de Entrada " , $lo_titulo);
			$lo_hoja->write($li_resumen, 2 , $li_totmovent , $lo_dataright);

			$li_resumen++;
			$lo_hoja->write($li_resumen, 1 ,"Total Entradas (Cantidad)  " , $lo_titulo);
			$lo_hoja->write($li_resumen, 2 , $li_totcantent , $lo_dataright);

			$li_resumen++;
			$lo_hoja->write($li_resumen, 1 ,"Total Movientos de Salida  " , $lo_titulo);
			$lo_hoja->write($li_resumen, 2 , $li_totmovsal , $lo_dataright);

			$li_resumen++;
			$lo_hoja->write($li_resumen, 1 ,"Total Salidas (Cantidad)  " , $lo_titulo);
			$lo_hoja->write($li_resumen, 2 , $li_totcansal , $lo_dataright);

			$li_resumen++;
			$lo_hoja->write($li_resumen, 1 ,"Total Movimientos de Reverso  " , $lo_titulo);
			$lo_hoja->write($li_resumen, 2 , $li_totmovrev , $lo_dataright);

			$li_resumen++;
			$lo_hoja->write($li_resumen, 1 ,"Total Reversos (Cantidad)  " , $lo_titulo);
			$lo_hoja->write($li_resumen, 2 , $li_totcanrev , $lo_dataright);

			$li_resumen++;
			$lo_hoja->write($li_resumen, 1 ,"- TOTAL EXISTENCIA (Cantidad) - " , $lo_titulo);
			$lo_hoja->write($li_resumen, 2 , $li_existenciapan , $lo_dataright);



for($li=$li_total;$li>1;$li--)
				{
					$li_ant=$ia_niveles_scg[$li-1];
					$li_act=$ia_niveles_scg[$li];
					$li_fila=$li_act-$li_ant;
					$li_len=strlen($ls_cuenta);
					$li_totfil=$li_totfil+$li_fila;
					$li_inicio=$li_len-$li_totfil;

				}

	$li_n++;
				$li_fila=$ia_niveles_scg[1]+1;

			$lo_libro->close();
			header("Content-Type: application/x-msexcel; name=\"movimientos.xls\"");
			header("Content-Disposition: inline; filename=\"movimientos.xls\"");
			$fh=fopen($lo_archivo, "rb");
			fpassthru($fh);
			unlink($lo_archivo);
			print("<script language=JavaScript>");
			print(" close();");
			print("</script>");

	}

?>