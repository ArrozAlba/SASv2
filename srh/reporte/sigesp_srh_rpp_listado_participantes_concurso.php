<?php

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Listado de Participantes por Concurso
//  ORGANISMO: IPSFA
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-----------------------------------------------------------------------------------------------------------------------------------
///Elaborado por: María Beatriz Unda
//-----------------------------------------------------------------------------------------------------------------------------------
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

//-----------------------------------------------------------------------------------------------------------------------------------
	function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 05/03/2008
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_desconcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_r_participantes_concurso.php",$ls_desconcion);
		return $lb_valido;
	}
	
//-----------------------------------------------------------------------------------------------------------------------------------	
	
		function uf_print_encabezado_pagina($as_titulo,$io_pdf)
	    {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: as_titulo // Título del Reporte
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		//	   Creado Por: Ing. Yesenia Moreno / Ing. Luis Lang
		// Fecha Creación: 11/03/2007
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();
		$io_pdf->line(15,40,585,40);
        
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],25,705,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo
		

		$io_pdf->addText(540,770,7,date("d/m/Y")); // Agregar la Fecha
		$io_pdf->addText(546,764,6,date("h:i a")); // Agregar la Hora
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
		
		 $io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();		
	
	    $io_pdf->ezSetY(677);
	    
		$la_data=array(array('titulo1'=>'<b>'.$as_titulo.'</b>'));
					
		$la_columnas=array('titulo1'=>'');
					
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 12, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				      	 'cols'=>array('titulo1'=>array('justification'=>'center','width'=>570))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
        unset($la_data);
		unset($la_columnas);
		unset($la_config);		
	
		$io_pdf->restoreState();
	    $io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
	}// end function uf_print_encabezado_pagina	
	
//---------------------------------------------------------------------------------------------------------------------------------//
  function uf_print_encabezado_detalle($la_datacon,&$io_pdf)
	 {
		
	   $io_pdf->ezSetY(640);
	   $la_datap[1]=array(   'codcon'=>'<b>Código del Concurso</b>',
		                     'descon'=>'<b>Descripción del Concurso</b>',
							 'fechaaper'=>'<b>Fecha Inicio</b>',
							 'fechacie'=>'<b>Fecha Culminación</b>',
							 'descar'=>'<b>Cargo</b>',
							 'cantcar'=>'<b>Cantidad</b>',
							 'tipo'=>'<b>Tipo</b>');
							 
		$la_columnas=array(  'codcon'=>'',
						   	 'descon'=>'',
						     'fechaaper'=>'',
						     'fechacie'=>'',
						     'descar'=>'',
						     'cantcar'=>'',
						     'tipo'=>'');
							 
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codcon'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'descon'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'fechaaper'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'fechacie'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'descar'=>array('justification'=>'center','width'=>90),

									   'cantcar'=>array('justification'=>'center','width'=>60),

									   'tipo'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datap,$la_columnas,'',$la_config);
	
		
	    $io_pdf->ezSetY(615);
		$la_columnas=array(  'codcon'=>'',
						   	 'descon'=>'',
						     'fechaaper'=>'',
						     'fechacie'=>'',
						     'descar'=>'',
						     'cantcar'=>'',
						     'tipo'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'cols'=>array('codcon'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'descon'=>array('justification'=>'left','width'=>140), // Justificación y ancho de la columna
						 			   'fechaaper'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'fechacie'=>array('justification'=>'center','width'=>70), // Justificación y ancho de la columna
						 			   'descar'=>array('justification'=>'center','width'=>90),

									   'cantcar'=>array('justification'=>'center','width'=>60),

									   'tipo'=>array('justification'=>'center','width'=>70))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_datacon,$la_columnas,'',$la_config);	
		
	    unset($la_datacon);
		unset($la_columnas);
		unset($la_config);	
				
		}
//---------------------------------------------------------------------------------------------------------------------------------//

 function uf_print_detalle($la_data,$ai_i,&$io_pdf)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		   Access: private 
		//	    Arguments: la_data // arreglo de información
		//				   as_titcuentas // titulo de estructura presupuestaria
		//				   ai_i // total de registros
		//	    		   io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime el detalle del reporte
		//	   Creado Por: María Beatriz Unda	
		// Fecha Creación: 05/03/2008 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    
		
		$io_pdf->ezSetY(575);
		$la_data_titulo[1]=array('codper'=>'Código de Personal',
						   'nomper'=>'Nombre',
						   'total'=>'Puntaje',
						   'posicion'=>'Resultado');
		$la_columnas=array('codper'=>'',
						   'nomper'=>'',
						   'total'=>'',
						   'posicion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>0, // Mostrar Líneas
						 'shaded'=>2, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codper'=>array('justification'=>'center','width'=>100),
						               'nomper'=>array('justification'=>'center','width'=>200),
									   'total'=>array('justification'=>'center','width'=>100),
									   'posicion'=>array('justification'=>'center','width'=>170))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data_titulo,$la_columnas,'',$la_config);
		
		$la_columnas=array('codper'=>'',
						   'nomper'=>'',
						   'total'=>'',
						   'posicion'=>'');
		$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 9, // Tamaño de Letras
						 'titleFontSize' => 12,  // Tamaño de Letras de los títulos
						 'showLines'=>1, // Mostrar Líneas
						 'shaded'=>0, // Sombra entre líneas
						 'width'=>570, // Ancho de la tabla
						 'maxWidth'=>570, // Ancho Máximo de la tabla
						 'xOrientation'=>'center', // Orientación de la tabla
				         'outerLineThickness'=>0.5,
						 'innerLineThickness' =>0.5,
						 'cols'=>array('codper'=>array('justification'=>'center','width'=>100),
						               'nomper'=>array('justification'=>'center','width'=>200),
									   'total'=>array('justification'=>'center','width'=>100),
									   'posicion'=>array('justification'=>'center','width'=>170))); // Justificación y ancho de la columna
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
					
	}// end function uf_print_detalle		


//-----------------------------------------------------------------------------------------------------------------------------------

   require_once("../../shared/ezpdf/class.ezpdf.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_funciones=new class_funciones();
	require_once("../../shared/class_folder/class_fecha.php");
	$io_fecha = new class_fecha();	
	require_once("class_folder/sigesp_srh_class_report.php");
	$io_report  = new sigesp_srh_class_report('../../');
	require_once("../class_folder/utilidades/class_funciones_srh.php");
	$io_fun_srh=new class_funciones_srh("../../");	
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];	
		
	//----------------------------------------------------  Parámetros del encabezado ---------------------------------------------
	$ls_titulo="<b>Listado de Participantes por Concurso</b>";
	
//--------------------------------------------------  Parámetros para Filtar el Reporte  -----------------------------------------
    
	$ls_tiporeporte=$io_fun_srh->uf_obtenervalor_get("tiporeporte",0);
	global $ls_tiporeporte;
 	$ls_codcon=$io_fun_srh->uf_obtenervalor_get("codcon","");
	$ls_orden=$io_fun_srh->uf_obtenervalor_get("orden","");
		
//--------------------------------------------------------------------------------------------------------------------------------
	$lb_valido=uf_insert_seguridad($ls_titulo); // Seguridad de Reporte
	if($lb_valido)
	{
        $lb_valido=$io_report->uf_select_registro_concurso($ls_codcon, $rs_data);
      
		if ($lb_valido==false)
		{
		    print("<script language=JavaScript>");
			print(" alert('No hay nada que reportar');"); 
			print(" close();");
			print("</script>");
		}
		   
		else  // Imprimimos el reporte
		{
     		
		 	error_reporting(E_ALL);
			set_time_limit(1800);
			$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->ezSetCmMargins(3.6,5,3,3); // Configuración de los margenes en centímetros
			$io_pdf->ezStartPageNumbers(570,47,8,'','',1); // Insertar el número de página			
			uf_print_encabezado_pagina($ls_titulo,&$io_pdf);
			
			$lp_totrow=$io_report->io_sql->num_rows($rs_data);
			$li_aux=0;
			$lp_p=0;
		   while ($row=$io_report->io_sql->fetch_row($rs_data))
		    {
		   
				$ls_codigo=$row["codcon"];
				$ls_descon=$row["descon"];
				$ld_fecaper=$row["fechaaper"];
				$ld_feccie=$row["fechacie"];
				
				$ls_codcar1=$row["codcar"];
				$ls_codcar2=$row["codasicar"];
				
				if ($ls_codcar1=="")
				{
					$ls_descar= $row["denasicar"];
				}
				else
				{
					$ls_descar= $row["descar"];
				}
				
				
				$li_cantcar=$row["cantcar"];
				$ls_estatus=$row["estatus"];
			 	$ls_tipo=$row["tipo"];
				$li_aux++;		 
			
			 
				$ld_fecaper=$io_funciones->uf_formatovalidofecha($ld_fecaper);
				$ld_fecaper=$io_funciones->uf_convertirfecmostrar($ld_fecaper);
					
				$ld_feccie=$io_funciones->uf_formatovalidofecha($ld_feccie);
				$ld_feccie=$io_funciones->uf_convertirfecmostrar($ld_feccie);
			 
			 
			 $la_datacon[$lp_p]=array('codcon'=>$ls_codcon,'descon'=>$ls_descon,'fechaaper'=>$ld_fecaper,
			                          'fechacie'=>$ld_feccie,'descar'=>$ls_descar,
									  'cantcar'=>$li_cantcar,'tipo'=>$ls_tipo); 
		
			$lp_p++;	
		    

			}//end del while
			
			 uf_print_encabezado_detalle($la_datacon,&$io_pdf); 
			 unset($la_datacon);
			
			 $lb_valido=$io_report->uf_select_participantes_concurso($ls_codcon, $ls_orden, $rs_data2);
			
			 if (($lb_valido) && ($io_report->io_sql->num_rows($rs_data2)!=0))
		     {
			    $li_i=0;
				$li_totrow=0; 
				
				while ($row=$io_report->io_sql->fetch_row($rs_data2))
		    	{
					
					$li_totrow=$li_totrow+1;
					$ls_codper=$row["codper"];					
					$ls_nomper=$row["nombre1"];					
					$li_total=$row["total"];
					$ls_posicion=trim($row["posicion"]);
					
					
					$la_data1[$li_i]=array('codper'=>$ls_codper,'nomper'=>$ls_nomper,
				                      'total'=>$li_total,'posicion'=>$ls_posicion);
			  		$li_i=$li_i+1;	  
			       }
				  
				 
				     uf_print_detalle($la_data1,$li_totrow,&$io_pdf);
			         unset($la_data1);
				 
				   
			    
		     }
			 else 
			 {
			 	$lb_valido=false;
			 }
			 
			 
			 if($li_aux<$lp_totrow)		
			 {
			 	$io_pdf->ezNewPage(); // Insertar una nueva página
			 }
						
		   
					
		}	//end del else
		   
		      
		  if($lb_valido) // Si no ocurrio ningún error
		   {
			$io_pdf->ezStopPageNumbers(1,1); // Detenemos la impresión de los números de página
			$io_pdf->ezStream(); // Mostramos el reporte
		   }
         else // Si hubo algún error
		{
			print("<script language=JavaScript>");
			print(" alert('Ocurrio un error al generar el reporte o No Hay Ganadores asignados a el concurso seleccionado. Intente de Nuevo');"); 
		    print(" close();");
			print("</script>");	
		}

}

?>	

	
	
