<?php
session_start();
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("X-LIGHTTPD-SID: ".session_id()); 

 //--------------Declaraciones e Inicializaciones-----------------------//
  require_once('../../shared/class_folder/class_pdf.php');
  require_once("../../shared/class_folder/class_mensajes.php");
  $io_msg      = new class_mensajes();
  $io_pdf = new class_pdf("LETTER","portrait");
  $io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm');
  //$io_pdf->numerar_paginas(10);
  $io_pdf->set_margenes(0,0,0,0);
 //-------------------------------------------------//
 
 //------------Data de la Orden de Pago-----------------// 
 $lb_valido=true;
 //------------------------------------------------//
if($lb_valido)
{
 
 //-------------Marco de la Pagina y encabezado----------------------//
 $io_pdf->add_rectangulo(25,254.3,166,241.2,array(255,255,255));//marco 
 $io_pdf->add_linea(25,22,191,22,2);//Linea 1
 $io_pdf->add_texto(27,14,6,"<b>REPUBLICA BOLIVARIANA DE VENEZUELA\n              MINISTERIO DE FINANZAS</b>");
 $li_ancho=$io_pdf->getTextWidth(8,"ORDEN DE PAGO");//Calculando posicion del Titulo
 $io_pdf->convertir_valor_px_mm($li_ancho);
 $li_pos=(215.9-$li_ancho)/2;
 $io_pdf->add_texto($li_pos,14,8,"<b>ORDEN DE PAGO</b>");//Titulo
 $io_pdf->add_texto(160,13,6,"<b>Nro:</b>         549");//Nro
 $io_pdf->add_texto(160,18,6,"<b>Fecha:</b>    15-06-2006");//Fecha
 //--------------------------Fila 1-------------------------------------//
 $la_data=array();//Primera fila del encabezado
 $la_data[0]["1"]="Año   <b>2006</b>                       Organismo:     <b>46</b>    Ministerio de la Cultura";
 $la_data[1]["1"]="Unidad Administradora:   <b>00004</b>  OFICINA DE ADMINISTRACIÓN Y SERVICIOS";
 $la_data[2]["1"]="Ubicación Geográfica:     Región";
 $la_data[3]["1"]="Municipio                                                                     Ciudad";
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
  //--------------------------Fila 2-------------------------------------//
 $li_altousado=$io_pdf->get_alto_usado();
 if($li_altousado<42) $li_altousado=42;
 $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1);   
 $io_pdf->add_tabla(-110,array(""),$la_opciones);
 $io_pdf->add_linea(25,$li_altousado,191,$li_altousado,2);//Linea 2
 $la_data=array();//segunda fila del encabezado
 $la_data[0]["1"]="Código de la Acción Centralizada: <b>0002</b>         Tipo de Acción Centralizada: Gestión Administrativa";
 $la_data[1]["1"]="Código del Proyecto:                                        Nombre del Proyecto: ";
 $la_anchos_col = array(160);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>0);
 $io_pdf->ezSetDy(-7);
 $io_pdf->add_tabla(26,$la_data,$la_opciones);
//--------------------------Fila 3-------------------------------------//
 $li_altousado=$io_pdf->get_alto_usado();
 if($li_altousado<53) $li_altousado=53;
 $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1);   
 $io_pdf->add_tabla(-110,array(""),$la_opciones);
 $io_pdf->add_linea(25,$li_altousado,191,$li_altousado,2);//Linea 3
 $la_data=array();//tercera fila del encabezado 
 $la_data[0]["1"]="R.I.F.:  G-200003029-1                                                                                           Nombre del Beneficiario:\n";
 $la_data[1]["1"]="Abonese en Banco:    <b>102</b> BANCO DE VENEZUELA UNIVERSAL, GRUPO SANTANDER\n";
 $la_data[2]["1"]="La cantidad de : NUEVE MILLONES OCHOCIENTOS DIEZ MIL BOLIVARES";
 $la_data[0]["2"]=" FUNDACION LA CASA DE BELLO";
 $la_data[1]["2"]=" Cta. Nro. 0102-0221-31-0000001850";
 $la_data[2]["2"]="";
 
 $la_anchos_col = array(100,60);
 $la_justificaciones = array("left","left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>0);
 $io_pdf->ezSetDy(-7);
 $io_pdf->add_tabla(26,$la_data,$la_opciones);
 //------------------------------------------------// 
 //--------------------------Fila 4-------------------------------------//
 $li_altousado=$io_pdf->get_alto_usado();
 if($li_altousado<74) $li_altousado=74;
 $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1);   
 $io_pdf->add_tabla(-110,array(""),$la_opciones);
 $io_pdf->add_linea(25,$li_altousado,191,$li_altousado,2);//Linea 4
 $la_data=array();//cuarta fila (titulo) 
 $la_data[0]["1"]="Documento de Respaldo"; 
 $la_anchos_col = array(160);
 $la_justificaciones = array("center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0);
 $io_pdf->ezSetDy(-6);
 $io_pdf->add_tabla(28,$la_data,$la_opciones);
 
 $la_data[0]["1"]="Tipo de Registro: Causado";
 $la_data[0]["2"]="Tipo:  <b>58</b>";
 $la_data[0]["3"]="Nro: <b>122</b>";
 $la_data[0]["4"]="Fecha de Pago:  <b>15-06-2006</b>";
 $la_anchos_col = array(40,40,40,40);
 $la_justificaciones = array("center","center","center","lecenterft");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones);
 $io_pdf->add_tabla(28,$la_data,$la_opciones);
 $li_altousado=$io_pdf->get_alto_usado();
 if($li_altousado<84) $li_altousado=84;
 $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1);   
 $io_pdf->add_tabla(-110,array(""),$la_opciones);
 $io_pdf->add_linea(25,$li_altousado,191,$li_altousado,0.1);//Linea 5
 
 $la_data=array();
 $la_data[0]["1"]="Fuente de Financiamiento\n<b>1</b>   Ingresos Ordinarios";
 $la_data[0]["2"]="Orígen\nMonto Ley";
 $la_data[0]["3"]="Tipo de Pago\nDirecta";
 $la_data[0]["4"]="  Medio de Pago\n<b>1</b>    Abono en Cuenta";
 $la_anchos_col = array(40,40,40,40);
 $la_justificaciones = array("center","center","center","center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0);
 $io_pdf->ezSetDy(-4);
 $io_pdf->add_tabla(28,$la_data,$la_opciones);
 
  //-----------------------Datos del Detalle-------------------------//
 $la_data=array();
 $la_data[0]["1"]="<b>Imputación Presupuestaria</b>\n";
 $la_data[0]["2"]="<b>Denominación</b>";
 $la_data[0]["3"]="<b>Monto Bs.</b>";
 $la_anchos_col = array(37,97,31.75);
 $la_justificaciones = array("center","center","center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1);
  $io_pdf->ezSetDy(-4);
  $io_pdf->add_tabla(26.9,$la_data,$la_opciones);
  
 $li_altousado=$io_pdf->get_alto_usado(); 
 $la_data=array();
 $la_data[0]["1"]="<b>AE</b>";
 $la_data[0]["2"]="<b>UEL</b>";
 $la_data[0]["3"]="<b>PA</b>";
 $la_data[0]["4"]="<b>GE</b>";
 $la_data[0]["5"]="<b>ES</b>";
 $la_data[0]["6"]="<b>SE</b>";
 $la_anchos_col = array(8,9,4,4,4,8.04);
 $la_justificaciones = array("center","center","center","center","center","center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "margen_horizontal"=>0,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1);
  $io_pdf->ezSetDy(6.97);
  $io_pdf->add_tabla(25.1,$la_data,$la_opciones);  
  //-----------------------------------------------------------------------//
  //----------Tabla con la data, con un maximo de 30 filas, si son mas todo se descuadra--------------//
  $li_maxfilas=15;
  $li_totalfilas=30;//(count($la_datapartidas, COUNT_RECURSIVE) / count($la_datapartidas)) - 1;
  $la_data=array();
  for($li_i=0;$li_i<$li_totalfilas;$li_i++)
  {
  		$la_data[$li_i]["ae"]="\n"."003";
  		$la_data[$li_i]["uel"]="\n"."00055";
  		$la_data[$li_i]["pa"]="\n"."407";
  		$la_data[$li_i]["ge"]="\n"."01";
  		$la_data[$li_i]["es"]="\n"."03";
  		$la_data[$li_i]["se"]="\n"."02";
  		$la_data[$li_i]["denominacion"]="\n"." Transferencias corrientes a entes descentralizados sin fines empresariales";
  		$la_data[$li_i]["monto"]="\n"."9.810.000,00 ";		  				  
  }
 // print_r($la_data);
  $la_anchos_col = array(8,9,4,4,4,8.04,97,31.7);  
  $la_indices_claves=array("ae","uel","pa","ge","es","se","denominacion","monto");
  $la_filasusadas=$io_pdf->obtener_lineas_por_fila(6,$la_anchos_col,$la_data,$la_indices_claves,0);
  $li_totalfilasusadas=array_sum($la_filasusadas);
  if($li_totalfilasusadas<($li_maxfilas*2))
  {
  		for($li_i=$li_totalfilas;$li_i<$li_maxfilas;$li_i++)
		{
			$la_data[$li_i]["ae"]="\n";
	  		$la_data[$li_i]["uel"]="\n";
	  		$la_data[$li_i]["pa"]="\n";
	  		$la_data[$li_i]["ge"]="\n";
	  		$la_data[$li_i]["es"]="\n";
	  		$la_data[$li_i]["se"]="\n";
	  		$la_data[$li_i]["denominacion"]="\n";
	  		$la_data[$li_i]["monto"]="\n";
		}    
 }
 elseif($li_totalfilasusadas>($li_maxfilas*2))//Este if es en caso de que no quepan las filas en la tabla,
 											  //se eliminan los espacios en blanco de las filas y caben el doble
 {
   	  for($li_i=0;$li_i<$li_totalfilas;$li_i++)
	  {
	  		$la_data[$li_i]["ae"]="003";
	  		$la_data[$li_i]["uel"]="00055";
	  		$la_data[$li_i]["pa"]="407";
	  		$la_data[$li_i]["ge"]="01";
	  		$la_data[$li_i]["es"]="03";
	  		$la_data[$li_i]["se"]="02";
	  		$la_data[$li_i]["denominacion"]=" Transferencias corrientes a entes descentralizados sin fines empresariales";
	  		$la_data[$li_i]["monto"]="9.810.000,00 ";		  				  
	  }
	  $la_filasusadas=$io_pdf->obtener_lineas_por_fila(6,$la_anchos_col,$la_data,$la_indices_claves,0);
	  $li_totalfilasusadas=array_sum($la_filasusadas);
	  if($li_totalfilasusadas<($li_maxfilas*2))
	  {
	  		for($li_i=$li_totalfilas;$li_i<$li_maxfilas*2;$li_i++)
			{
				$la_data[$li_i]["ae"]="";
		  		$la_data[$li_i]["uel"]="";
		  		$la_data[$li_i]["pa"]="";
		  		$la_data[$li_i]["ge"]="";
		  		$la_data[$li_i]["es"]="";
		  		$la_data[$li_i]["se"]="";
		  		$la_data[$li_i]["denominacion"]="";
		  		$la_data[$li_i]["monto"]="";
			}    
	 }
 }
  $la_opciones=array();
  $la_justificaciones = array("center","center","center","center","center","center","left","right");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "margen_vertical"=>0,
					   "margen_horizontal"=>0,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones);
  $io_pdf->add_tabla(25.1,$la_data,$la_opciones); 
  //-----------------------------------------------------------------------//
  //------------------------------Totales----------------------------//
  $la_data=array();
  $la_data[0]["1"]="<b>R.C.O. Nro</b>";
  $la_data[0]["2"]="Total Bs.";
  $la_data[0]["3"]="9.810.000,00";
  $la_data[1]["1"]="\nConcepto de Pago/Observaciones\n\n";
  $la_data[1]["2"]="";
  $la_data[1]["3"]="";
  $la_anchos_col = array(109,25,31.7);
  $la_opciones=array();
  $la_justificaciones = array("center","center","right");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1,
					   "alineacion_col"=>$la_justificaciones);
  $io_pdf->add_tabla(26.9,$la_data,$la_opciones); 
  
  $la_data=array();//Tablita interna que contiene los datos de retenciones y neto a pagar
  $la_data[0]["1"]="Retenciones(1)\n";
  $la_data[0]["2"]="0,00";
  $la_data[1]["1"]="Neto a Pagar Bs.";
  $la_data[1]["2"]="9.810.000,00";
  $la_anchos_col = array(25,31.7);
  $la_opciones=array();
  $la_justificaciones = array("center","right");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "lineas"=>0);
  $io_pdf->ezSetDy(29);
  $io_pdf->add_tabla(135.9,$la_data,$la_opciones); 
  $li_alto=$io_pdf->get_alto_usado();  
  $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1); 
  $io_pdf->add_tabla(-110,array(""),$la_opciones);  
  $io_pdf->add_linea(134.2,$li_alto-2.5,191,$li_alto-2.5,0.1);//Linea entre retenciones y neto a pagar
  
  
  //------------------------Pie de pagina----------------------------------------//
  $la_data=array();//Tabla con el concepto u observacion
  $la_data[0]["1"]="TRANSFERENCIAS CORRIENTES A LOS ENTES DESCENTRALIZADOS PARA CANCELAR GASTOS DE FUNCIONAMIENTO POR ACCION CENTRALIZADA, CORRESPONDIENTE AL MES DE JUNIO. FUENTE DE FINANCIAMIENTO \"INGRESOS ORNINARIOS\"";
  $la_indices_claves=array("1");
  $la_anchos_col = array(165.7);
  $la_filasusadas=$io_pdf->obtener_lineas_por_fila(6,$la_anchos_col,$la_data,$la_indices_claves,0.2);
  if($la_filasusadas[0]<8)
  {
  		$ls_espacios="";
		for($li_i=$la_filasusadas[0]+1;$li_i<=8;$li_i++)
		{
		 	$ls_espacios=$ls_espacios."\n";   
		} 
		$la_data[0]["1"]=$la_data[0]["1"].$ls_espacios;
  }
  $la_opciones=array();
  $la_justificaciones = array("left");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0.5,
					   "margen_horizontal"=>1);
  $io_pdf->ezSetDy(-3);
  $io_pdf->add_tabla(25.5,$la_data,$la_opciones); 
  
  $la_data=array();//Tablita Autorizado
  $la_data[0]["1"]="<b>Autorizado:\n\n\n</b>";
  $la_anchos_col = array(164);
  $la_opciones=array();
  $la_justificaciones = array("left");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "margen_horizontal"=>0.5,
					   "lineas"=>0);
  $io_pdf->add_tabla(25.5,$la_data,$la_opciones);
  
  $la_data=array();//Tablita Abonese en banco
  $la_data[0]["1"]="<b>Abónese en Banco:\n</b>";
  $la_data[0]["2"]="<b>Cta. Nro.:</b>";
  $la_anchos_col = array(100,64);
  $la_opciones=array();
  $la_justificaciones = array("left","left");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "margen_horizontal"=>0.5,
					   "lineas"=>0);
  $io_pdf->add_tabla(25.5,$la_data,$la_opciones); 
  
    
  $la_data=array();//Tablita para uso de la tesoreria nacional
  $la_data[0]["1"]="<b>\nPara uso de la Tesorería Nacional</b>";
  $la_anchos_col = array(165);
  $la_opciones=array();
  $la_justificaciones = array("center");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "margen_horizontal"=>0.5,
					   "lineas"=>0);
  $io_pdf->add_tabla(25.5,$la_data,$la_opciones);
  $la_data=array();
  $la_data[0]["1"]="<b>Cta.Cte. TN: Banco  \n</b>";
  $la_data[0]["2"]="<b>Nro.</b>";
  $la_data[0]["3"]="<b>Fecha:</b>";
  $la_anchos_col = array(45,75,44);
  $la_opciones=array();
  $la_justificaciones = array("left","left","left");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 6,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>2,
					   "margen_horizontal"=>0.5,
					   "lineas"=>0);
  $io_pdf->add_tabla(25.5,$la_data,$la_opciones);
  
  $la_data=array();//Tabla firmas autorizadas
  $la_data[0]["1"]="<b>Firmas Autorizadas</b>";
  $la_anchos_col = array(165);
  $la_opciones=array();
  $la_justificaciones = array("center");
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 7,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "margen_horizontal"=>0,
					   "lineas"=>0);
  $io_pdf->add_tabla(25.5,$la_data,$la_opciones);
  $la_data=array();//Tabla firmas autorizadas
  $la_data[0]["1"]="<b>\n\n\n\n\nDirector Administración\n</b>";
  $la_data[0]["2"]="<b>\n\n\n\n\nOrdenador de Pago\n</b>";
  $la_data[0]["3"]="<b>\n\n\n\n\nContraloría General\n</b>";
  $la_data[0]["4"]="<b>\n\n\n\n\nOficina Nacional del Tesoro\n</b>";
  $la_anchos_col = array(41.5,41.5,41.5,41.3);
  $la_opciones=array();  
  $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 7,
					   "margen_vertical"=>2,
					   "margen_horizontal"=>0,
					   "lineas"=>2,
					   "grosor_lineas_externas"=>0.1,
					   "grosor_lineas_internas"=>0.1);
  $io_pdf->add_tabla(25.1,$la_data,$la_opciones); 
  
  //--------------Lineas ornamentales del Pie de pagina----------------------//
  $li_puntero=$io_pdf->get_alto_usado(); 
  $li_alto=$io_pdf->get_alto_usado();  
  $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1); 
  $io_pdf->add_tabla(-110,array(""),$la_opciones);  
  $io_pdf->add_linea(25,$li_puntero-33,191,$li_puntero-33,2);
  $io_pdf->add_linea(25,$li_puntero-34,191,$li_puntero-34,2);
  $io_pdf->add_linea(25,$li_puntero-24,191,$li_puntero-24,2);
  $io_pdf->add_linea(25,$li_puntero-25,191,$li_puntero-25,2);
  $io_pdf->add_linea(25,$li_puntero-50,191,$li_puntero-50,2);
  $io_pdf->add_linea(31,$li_puntero-6,62,$li_puntero-6,0.1);
  $io_pdf->add_linea(74,$li_puntero-6,100.5,$li_puntero-6,0.1);
  $io_pdf->add_linea(116,$li_puntero-6,142,$li_puntero-6,0.1);
  $io_pdf->add_linea(153,$li_puntero-6,188,$li_puntero-6,0.1);
  

  
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