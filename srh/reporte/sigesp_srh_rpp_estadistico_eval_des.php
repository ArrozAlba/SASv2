<?php  
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//    REPORTE: Reporte Estadístico de los resultados de Evaluación de Desempeño
//  ORGANISMO: 
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
	
//---------------------------------------------------------------------------------------------------------------------------------


function uf_insert_seguridad($as_titulo)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_insert_seguridad
		//		   Access: private 
		//	    Arguments: as_titulo // Título del reporte
		//    Description: función que guarda la seguridad de quien generó el reporte
		//	   Creado Por: María Beatriz Unda
		// Fecha Creación: 14/05/2008		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $io_fun_srh;
		
		$ls_descripcion="Generó el Reporte ".$as_titulo;
		$lb_valido=$io_fun_srh->uf_load_seguridad_reporte("SRH","sigesp_srh_r_reporte_estadistico.php",$ls_descripcion);
		return $lb_valido;
	}
	
//---------------------------------------------------------------------------------------------------------------------------------	
  
function uf_print_encabezado_pagina(&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		   Access: private 
		//	    Arguments: io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime los encabezados por página
		// Fecha Creación: 13/05/2008	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_encabezado=$io_pdf->openObject();
		$io_pdf->saveState();        
		$io_pdf->setStrokeColor(0,0,0);
		$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],52,680,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo		
		
	  
		$io_pdf->restoreState();
		$io_pdf->closeObject();
		$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_encabezado_pagina


function uf_print_primera_pagina($ad_fecini, $ad_fecfin, &$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_primera_pagina
		//		   Access: private 
		//	    Arguments:  io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime la primera página del reporte
		// Fecha Creación: 13/05/2008		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		$li_tm=$io_pdf->getTextWidth(20,"<b>INFORME DE RESULTADOS</b>");
		$tm=320-($li_tm/2);
		$io_pdf->addText($tm,395,20,"<b>INFORME DE RESULTADOS</b>");
		$li_tm=$io_pdf->getTextWidth(20,"<b>PROCESO DE EVALUACIÓN DE DESEMPEÑO</b>");
		$tm=300-($li_tm/2);
		$io_pdf->addText($tm,355,20,"<b>PROCESO DE EVALUACIÓN DE DESEMPEÑO</b>");
		
		
		
		$texto= "<b> DESDE ".$ad_fecini." HASTA ".$ad_fecfin."</b>";
		$li_tm=$io_pdf->getTextWidth(20,$texto);
		$tm=320-($li_tm/2);
		$io_pdf->addText($tm,315,20,$texto);
		
		
}// end function uf_print_primera_pagina

function uf_print_grafica($titulo, $titulo2,$la_renglones,$la_valores, &$io_pdf,$io_grafica)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_primera_pagina
		//		   Access: private 
		//	    Arguments:  io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime la primera página del reporte
		// Fecha Creación: 13/05/2008		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		 $imagen = $io_grafica->graficar_barras($la_renglones,$la_valores);
  		 $io_pdf->add_imagen($imagen,'center',55,180);
		 $li_tm=$io_pdf->getTextWidth(14,$titulo);
		 $tm=310-($li_tm/2);
   		 $io_pdf->addText($tm,640,14,$titulo);
		 $io_pdf->addText($tm,620,14,$titulo2);
	
   }// end function uf_print_primera_pagina
   

function uf_print_tabla($la_titulos,$la_datos,$alineacion,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_primera_pagina
		//		   Access: private 
		//	    Arguments:  io_pdf // Instancia de objeto pdf
		//    Description: Función que imprime la primera página del reporte
		// Fecha Creación: 13/05/2008		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	 ///////////////////////////////////
	//    TITULOS EN LAS COLUMNAS    //
	///////////////////////////////////
	
	$la_color_fondo = array(251,165,151);
	$la_anchos_col = array(75,30,30);
	$la_opciones = array("color_fondo" => $la_color_fondo,
						 "color_texto" => array(0,0,0),
						 "anchos_col"  => $la_anchos_col);
	$io_pdf->add_tabla($alineacion,$la_titulos,$la_opciones);
	
	///////////////////////////////////
	//      DATOS EN LAS COLUMNAS    //
	///////////////////////////////////
	$la_color_fondo = array(255,255,255); // blanco				  
	$la_anchos_col = array(75,30,30);
	$la_alineacion_col = array('left','center', 'center');	
	$la_opciones = array("color_fondo"   => $la_color_fondo,
						 "anchos_col"    => $la_anchos_col,
						 "alineacion_col"=> $la_alineacion_col);
	$io_pdf->add_tabla($alineacion,$la_datos,$la_opciones);
	
   }// end function uf_print_primera_pagina


function uf_calcular_porcentaje ($valor, $total, &$la_arreglo)
{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_calcular_porcentaje
		//		   Access: private 
		//	    Arguments:  io_pdf // Instancia de objeto pdf
		//    Description: Función que calcula los porcentajes de los resultados de evaluación
		// Fecha Creación: 13/05/2008		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	  $aux=0;
	  for ($n=0; $n<=$valor; $n++)
	  {
		$aux =  $la_arreglo[$n]["valores"]*100;
		$porc =  $aux/$total;	
		$la_arreglo[$n]["porcentaje"] = round($porc * 100) / 100;
		$aux=0;
		$porc=0;
	  }

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
require_once("../class_folder/utilidades/class_funciones_srh.php");
$io_fun_srh=new class_funciones_srh('../../');
require_once("class_folder/sigesp_srh_class_report.php");
$io_report=new sigesp_srh_class_report();
require_once("class_folder/class_graficas.php");
$io_grafica = new class_graficas();
require_once("../../shared/class_folder/class_funciones.php");
$io_funciones=new class_funciones();
require_once("class_folder/class_pdf.php");
$io_pdf = new class_pdf();
$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////// PARAMETROS PARA FILTRAR EL REPORTE ///////////////////////////
$ld_fecini=$_GET["fecini"];
$ld_fecfin=$_GET["fecfin"];
$ls_coduniadm1=$_GET["coduniadm1"];
$ls_coduniadm2=$_GET["coduniadm2"];
///////////////////////////////////////////////////////////////////////////////////////////
  
  $lb_valido=uf_insert_seguridad("Reporte Estadístico de Evaluación de Desempeño"); // Seguridad de Reporte
	if($lb_valido)
	{       
		 $lb_valido=$io_report->uf_select_evaluacion_desempeno($ld_fecini, $ld_fecfin, $rs_data);
		 $total_evaluaciones=$io_report->io_sql->num_rows($rs_data);	
		
	
	}
	if (($lb_valido==false)||($total_evaluaciones==0)) // Existe algún error ó no hay registros
	 {
		print("<script language=JavaScript>");
		print(" alert('No hay nada que Reportar');"); 
		print(" close();");
		print("</script>");
	 }
	 else
	 { 
		  $la_renglones= array();
		  $la_valores= array();
		  $la_datos= array();
		  $la_valores2= array();
		  $la_datos2= array();
		  $la_puntos= array();
		  
		    
		  
		  $io_report->uf_select_renglones($rs_data2);
		  $j=0;
		  while ($row=$io_report->io_sql->fetch_row($rs_data2))
		   { 
		  	   $la_datos[$j]["renglones"] = $row["dendetesc"];
			   $la_datos2[$j]["renglones"] = $row["dendetesc"];		  
			   $la_renglones[$j] = $row["dendetesc"];
			   $la_puntos[$j]["valini"] = $row["valinidetesc"];
			   $la_puntos[$j]["valfin"] = $row["valfindetesc"];
			   $j=$j+1;
			}
		 $la_datos[$j]["renglones"] = "Fuera del Proceso";	
		 $la_datos2[$j]["renglones"] = "Fuera del Proceso";		  
	     $la_renglones[$j] = "Fuera del Proceso";
		 
		
		 for ($i=1; $i<=count($la_renglones); $i++)
		 {
		  $la_datos[$i-1]["valores"] = 0;
		  $la_valores[$i-1] = 0;
		  $la_datos2[$i-1]["valores"] = 0;
		  $la_valores2[$i-1] = 0;
		 
		 }
		 
		
		  while ($row=$io_report->io_sql->fetch_row($rs_data))
		   { 
		   	
			    $totalodi=$row["totalodi"];
				$totalcompe=$row["totalcompe"];
				$total = $totalodi + $totalcompe;
				$fecha= $row["fecha"];
				
				for ($k=1; $k<= count($la_puntos); $k++)
				{
				  if (($total>$la_puntos[$k-1]["valini"]) && ($total<=$la_puntos[$k-1]["valfin"]))
				   {  $la_datos[$k-1]["valores"] = $la_datos[$k-1]["valores"] + 1;
				      $la_valores[$k-1] = $la_valores[$k-1]+1;
					}
							
				}
			}
				  
		 	  
		  
		 
		  $aux=0;
		  
// Para calcular el porcentaje
	 uf_calcular_porcentaje (count($la_puntos), $total_evaluaciones, $la_datos);
		
		  	  
//Para imprimir el encabezado y la primera página del reporte
		   uf_print_encabezado_pagina(&$io_pdf);
		   $ld_fecini1=$io_funciones->uf_convertirfecmostrar($ld_fecini);
		   $ld_fecfin1=$io_funciones->uf_convertirfecmostrar($ld_fecfin);
		   uf_print_primera_pagina($ld_fecini1,$ld_fecfin1,&$io_pdf);
		   $io_pdf->ezNewPage();
		  
//Para imprimir la primera gráfica con su tabla del resumen de evaluaciones de desempeño
		 
		   uf_print_grafica("<b>RESUMEN GENERAL EVALUACIÓN DESEMPEÑO</b>",'',$la_renglones,$la_valores, &$io_pdf,$io_grafica);  
		   $io_pdf->ezSetDy(-575);
		   
		   $la_titulos = array("RANGO","Nº EVALUADOS","PORCENTAJE (%)");
		   uf_print_tabla($la_titulos,$la_datos,'center',&$io_pdf);
		  
// Para hacer los reportes por Unidades Administrativas
		   
		   if (($ls_coduniadm1!="")&&($ls_coduniadm2!=""))
		   {
		     $lb_valido=$io_report->uf_select_evaldes_uniadm($ld_fecini, $ld_fecfin,$ls_coduniadm1, $ls_coduniadm2,$rs_data3);
			 $unidades = array();
			 $totales = array();	 
			if ($lb_valido)
			{
			   $desuniaux="";
			   $desuniaux2="";
			   $totalodi=0;
			   $totalcompe=0;
			   $total=0;
			   
               $m=0;
			   $n=0;
			   while ($row=$io_report->io_sql->fetch_row($rs_data3))
		  	  {	 
		  		$desuniadm = $row["desuniadm"];
				$desuniaux = $desuniadm;		
			    $unidades[$m] = $desuniadm;	
			    $m=$m+1;	
			   
			   
				if ($desuniaux2!=$desuniaux)
				{
				  $n=0;
				}
				
			    if ($desuniadm == $desuniaux)
				{	
				  $total = $row["totalcompe"]+$row["totalodi"];
				  $totales [$desuniadm][$n] = $total;
				  $n=$n+1;				  
				}
								
				$desuniaux2=$desuniadm;
				
			  }//fin del while
			 
						 
			$desuniaux="";
			$desuniaux2="";
			  
		 	for ($h=0; $h<count($unidades); $h++)
			{
			  $desuniadm = $unidades[$h];
			  $desuniaux = $desuniadm;
			
			  if ($desuniaux2!=$desuniaux)
				{
				  $p=0;
				   
				}
				
			if (($p==0) && ($h!=0))
				{
				  $io_pdf->ezNewPage();
				  //Para imprimir las gráfica con su tabla  según la unidad administrativa
		 		uf_print_grafica("<b>RESUMEN EVALUACIÓN DESEMPEÑO</b>","<b>".$desuniaux2."</b>" ,$la_renglones,$la_valores2, &$io_pdf,$io_grafica);  
					   $io_pdf->ezSetDy(-575);
					   
					  // Para calcular el porcentaje
					   uf_calcular_porcentaje (count($la_puntos), count ($totales[$desuniaux2]), $la_datos2);
					
				      $la_titulos = array("RANGO","Nº EVALUADOS","PORCENTAJE (%)");
					   uf_print_tabla($la_titulos,$la_datos2,'center',&$io_pdf);
				
				}
			
			if ($desuniaux2!=$desuniaux)
				{
				   //Para blanquear los arreglos
				   for ($k=1; $k<= count($la_puntos); $k++)
				    {
				      $la_datos2[$k-1]["valores"] = 0;
					  $la_valores2[$k-1] = 0;
					}
				}
			
			 for ($k=1; $k<= count($la_puntos); $k++)
			 {
			   if (($totales[$desuniadm][$p]>$la_puntos[$k-1]["valini"]) && ($totales[$desuniadm][$p]<=$la_puntos[$k-1]["valfin"]))
			   {  $la_datos2[$k-1]["valores"] = $la_datos2[$k-1]["valores"] + 1;
				  $la_valores2[$k-1] = $la_valores2[$k-1]+1;
				}
			 }
			  $p=$p+1;
			  $desuniaux2=$desuniadm;
			}	 
			
			$io_pdf->ezNewPage();
		   //Para imprimir las gráfica con su tabla  según la unidad administrativa
			uf_print_grafica("<b>RESUMEN EVALUACIÓN DESEMPEÑO</b>","<b>".$desuniadm."</b>" ,$la_renglones,$la_valores2, &$io_pdf,$io_grafica);  
				   $io_pdf->ezSetDy(-575);
				   
				  // Para calcular el porcentaje
				     uf_calcular_porcentaje (count($la_puntos), count ($totales[$desuniadm]), $la_datos2);
				   
				  $la_titulos = array("RANGO","Nº EVALUADOS","PORCENTAJE (%)");
				  uf_print_tabla($la_titulos,$la_datos2,'center',&$io_pdf);
			  
			} //fin del if 
		   else
		   {
				print("<script language=JavaScript>");
				print(" alert('Error al imprimir detalle del reporte.');"); 
				print(" close();");
				print("</script>");
		 }
		  }
	}	   
   
////////////////////////////////////////////////////
$io_pdf->ezStream();
////////////////////////////////////////////////////

?>