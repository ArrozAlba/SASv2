<?php
session_start();
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("X-LIGHTTPD-SID: ".session_id()); 

 //--------------Declaraciones e Inicializaciones-----------------------//
  require_once ('class_folder/class_pdf.php');
  require_once("../shared/class_folder/class_mensajes.php");
  $io_msg      = new class_mensajes();
  $io_pdf = new class_pdf("LETTER","portrait");
  $io_pdf->selectFont('../shared/ezpdf/fonts/Helvetica.afm');
  //$io_pdf->numerar_paginas(10);
  $io_pdf->set_margenes(0,0,0,0);
 //-------------------------------------------------//
 
 //------------Data de la Orden de Pago-----------------// 
 $lb_valido=true;
 //------------------------------------------------//
if($lb_valido)
{
 
 //-------------Marco de la Pagina y encabezado----------------------//
 $io_pdf->add_rectangulo(20,254.3,180,241.2,array(255,255,255));//marco 
 $io_pdf->add_linea(25,22,194,22,2);//Linea 1
 $io_pdf->add_texto(27,14,6,"<b>REPUBLICA BOLIVARIANA DE VENEZUELA\n              MINISTERIO DE FINANZAS</b>");
 $li_ancho=$io_pdf->getTextWidth(8,"RETENCIONES");//Calculando posicion del Titulo
 $io_pdf->convertir_valor_px_mm($li_ancho);
 $li_pos=(215.9-$li_ancho)/2;
 $io_pdf->add_texto($li_pos,14,8,"<b>RETENCIONES</b>");//Titulo
 $io_pdf->add_texto(160,13,6,"<b>Nro:</b>         549");//Nro
 $io_pdf->add_texto(160,18,6,"<b>Fecha:</b>    15-06-2006");//Fecha
 //--------------------------Fila 1-------------------------------------//
 $la_data=array();//Primera fila del encabezado
 $la_data[0]["1"]="Año   <b>2006</b>                       Organismo:     <b>46</b>    Ministerio de la Cultura";
 $la_anchos_col = array(160);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>0);
 $io_pdf->ezSetY(729);
 $io_pdf->add_tabla(26,$la_data,$la_opciones);
                //--------------------------Tabla-------------------------------------//
                //--------------------------------------------------------------------//
 //-----------------------------Titulos de la Tabla-------------------------------------------------//
 //-----------------(Se Agregan aparte para permitir las distintas justificaciones del texto)-------//
 $la_data=array();
 $la_data[0]["1"]="<b>Código</b>";
 $la_data[0]["2"]="<b>Descripción</b>";
 $la_data[0]["3"]="<b>Monto</b>";
 $la_anchos_col = array(22,110,38);
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 7,
					   "grosor_lineas_internas"=>0.5,
					   "grosor_lineas_externas"=>0.5);
 $io_pdf->ezSetY(710);
 $io_pdf->add_tabla(26,$la_data,$la_opciones);
  
  //-----------------------------Datos de la tabla-------------------------------------------------//
	$li_filas=2;//(count($la_datapartidas, COUNT_RECURSIVE) / count($la_datapartidas)) - 1;
	$la_datadetalle=array();	
	for($li_i=0;$li_i<$li_filas;$li_i++)
	{
	  	$la_datadetalle[$li_i]["codigo"]="1";
	  	$la_datadetalle[$li_i]["descripcion"]="RETENCIONES DE IVA";
	  	$la_datadetalle[$li_i]["monto"]="130.643,60";
	}
	//-------------------Este Codigo que sigue es para permitir que se cree una
						//pagina nueva en caso de que sean demasiadas filas----------------------------//
						
  $li_i=0;  
  $la_indices=array("codigo","descripcion","monto");
  $la_anchos_col = array(22,110,38);
  $la_lineasfila=$io_pdf->obtener_lineas_por_fila(7,$la_anchos_col,$la_datadetalle,$la_indices);
  while($li_i<count($la_lineasfila))
  {	  
	  $la_aux=array();
	  $li_j=0;
	  $li_altotabla=0;
	  $li_disponible=$io_pdf->get_alto_disponible()-30;
	  while(($li_altotabla<$li_disponible) && $li_i<count($la_lineasfila))
	  {
	   		 $li_altofila=$io_pdf->get_alto_fila(7,$la_lineasfila[$li_i]);
	    	 if(($li_altofila+$li_altotabla) < $li_disponible)
	    	 {
	    	    $la_aux[$li_j]=$la_datadetalle[$li_i];
			  	$li_i++;
				$li_j++;		   
			 }
			 $li_altotabla += $li_altofila; 
			// print_r($la_aux);	 
	  }
	 $la_alineacion=array("center","left","right");	 
  	 $la_opciones = array("color_texto" => array(0,0,0),
					   "tamano_texto"=> 7,
					   "anchos_col" => $la_anchos_col,
					   "alineacion_col"=>$la_alineacion,
					   "grosor_lineas_internas"=>0.5,
					   "grosor_lineas_externas"=>0.5);
	 $io_pdf->add_tabla(26,$la_aux,$la_opciones);	
	 //break;
	 if($li_i+1<count($la_lineasfila))
	 {
		  //-------------Marco de la Pagina y encabezado----------------------//
		 $io_pdf->ezNewPage();
		 $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 0.1);   
 		 $io_pdf->add_tabla(-110,array(""),$la_opciones);
		 $io_pdf->add_rectangulo(20,254.3,180,241.2,array(255,255,255));//marco 
		 $io_pdf->add_linea(25,22,194,22,2);//Linea 1
		 $io_pdf->add_texto(27,14,6,"<b>REPUBLICA BOLIVARIANA DE VENEZUELA\n              MINISTERIO DE FINANZAS</b>");
		 $li_ancho=$io_pdf->getTextWidth(8,"RETENCIONES");//Calculando posicion del Titulo
		 $io_pdf->convertir_valor_px_mm($li_ancho);
		 $li_pos=(215.9-$li_ancho)/2;
		 $io_pdf->add_texto($li_pos,14,8,"<b>RETENCIONES</b>");//Titulo
		 $io_pdf->add_texto(160,13,6,"<b>Nro:</b>         549");//Nro
		 $io_pdf->add_texto(160,18,6,"<b>Fecha:</b>    15-06-2006");//Fecha
		 //--------------------------Fila 1-------------------------------------//
		 $la_data=array();//Primera fila del encabezado
		 $la_data[0]["1"]="Año   <b>2006</b>                       Organismo:     <b>46</b>    Ministerio de la Cultura";
		 $la_anchos_col = array(160);
		 $la_justificaciones = array("left");
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 6,
							   "lineas"=>0,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>0);
		 $io_pdf->ezSetY(729);
		 $io_pdf->add_tabla(26,$la_data,$la_opciones);
		                //--------------------------Tabla-------------------------------------//
		                //--------------------------------------------------------------------//
		 //-----------------------------Titulos de la Tabla-------------------------------------------------//
		 //-----------------(Se Agregan aparte para permitir las distintas justificaciones del texto)-------//
		 $la_data=array();
		 $la_data[0]["1"]="<b>Código</b>";
		 $la_data[0]["2"]="<b>Descripción</b>";
		 $la_data[0]["3"]="<b>Monto</b>";
		 $la_anchos_col = array(22,110,38);
		 $la_opciones = array("color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "grosor_lineas_internas"=>0.5,
							   "grosor_lineas_externas"=>0.5);
		 $io_pdf->ezSetY(710);
		 $io_pdf->add_tabla(26,$la_data,$la_opciones);
	  }
	}    
	
  //----------------------------------------Total-----------------------------------------------------------//
  $li_puntero=$io_pdf->get_alto_usado();
  $io_pdf->add_texto(143,$li_puntero+1.5,7,"<b>Total=></b>");//Total
  $la_data=array();
  $la_data[0]["1"]="155.528,10";
  $la_anchos_col = array(38);
  $la_alineacion=array("right");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 7,
					   "grosor_lineas_internas"=>0.5,
					   "grosor_lineas_externas"=>0.5,
					   "alineacion_col"=>$la_alineacion);
  $io_pdf->add_tabla(158,$la_data,$la_opciones);
  
  //---------------------------------------------------------//  
  $io_pdf->ezStream();
 }
 else
 {
 	$io_msg->message("Error al cargar la data!!!");
	print"<script> ";
	print"close();";
	print"</script>";  
 } 
?>