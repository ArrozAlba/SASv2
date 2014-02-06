<?php
	session_start();
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	header("X-LIGHTTPD-SID: ".session_id());
   	ini_set('memory_limit','1024M');
 	ini_set('max_execution_time ','0');

	//--------------Declaraciones e Inicializaciones-----------------------//
	require_once("sigesp_scb_report.php");
	require_once('../../shared/class_folder/class_pdf.php');
	require_once("../../shared/class_folder/class_mensajes.php");
	require_once("../../shared/class_folder/class_funciones.php");
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_sql.php");
	require_once("../../shared/class_folder/class_sigesp_int.php");
	require_once("../../shared/class_folder/class_fecha.php");
	require_once("../../shared/class_folder/class_sigesp_int_scg.php");
	require_once("../../shared/class_folder/class_sigesp_int_spg.php");

	$int_spg	  =	new class_sigesp_int_spg();
	$in			  = new sigesp_include();
	$con		  = $in->uf_conectar();
	$io_sql		  = new class_sql($con);
	$io_report    = new sigesp_scb_report($con);
	$io_funciones = new class_funciones();
	$io_msg       = new class_mensajes();
	$io_fecha     = new class_fecha();
	$io_pdf       = new class_pdf("LETTER","landscape");

	$io_pdf->selectFont('../../shared/ezpdf/fonts/Times-Roman.afm');
	$io_pdf->numerar_paginas(10);
	$io_pdf->set_margenes(8,20,12,12);
	//-------------------------------------------------//

	//------------Data-----------------//
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_nomemp=$_SESSION["la_empresa"]["nombre"];
	$ls_rifemp=$_SESSION["la_empresa"]["rifemp"];
	if(array_key_exists("numlicemp",$_SESSION["la_empresa"]))
		$ls_licemp=$_SESSION["la_empresa"]["numlicemp"];
	else
		$ls_licemp="";
	$ls_diremp		 = $_SESSION["la_empresa"]["direccion"];
	$ls_telemp		 = $_SESSION["la_empresa"]["telemp"];
	$ls_codcom		 = $_GET["documentos"];
	$ls_mes			 = $_GET["mes"];
	$ls_agno		 = $_GET["agno"];
	$la_comprobantes = explode("-",$ls_codcom);
	$ls_fechainicio  = $ls_agno."-".$ls_mes."-01";
	$ls_fechafin     = $io_funciones->uf_convertirdatetobd($io_fecha->uf_last_day($ls_mes,$ls_agno));
	$ls_tipbol       = 'Bs.';
	$ls_tiporeporte  = 0;
	$ls_tiporeporte  = $_GET["tiporeporte"];
	global $ls_tiporeporte;
	if($ls_tiporeporte==1)
	{
		require_once("sigesp_scb_reportbsf.php");
		$io_report = new sigesp_scb_reportbsf($con);
		$ls_tipbol = 'Bs.F.';
	}


	//------------------------------------------------//
for($li_k=0;$li_k<count($la_comprobantes);$li_k++)
{

//--------------------------Data de cada uno de los comprobantes----------------------------------------//
$la_datacomprobantes=array();
$la_datacomprobantes=$io_report->uf_comprobante_retencion($la_comprobantes[$li_k],$ls_fechainicio,$ls_fechafin);
//------------------------------------------------------------------------------------------------------//

	if (substr($la_datacomprobantes["rif"][1],0,1)=='J')
	{
		$ls_tipper='NATURAL                                       JURIDICA       <b>X</b>';
	}
	else
	{
		$ls_tipper='NATURAL    <b>X</b>                                   JURIDICA';
	}

 //-------------Encabezado----------------------//
 $li_ancho=$_SESSION["ls_width"];

 $io_pdf->convertir_valor_px_mm($li_ancho);
 //$io_pdf->add_imagen('../../shared/imagebank/'.$_SESSION["ls_logo"],0,0,250);
 $io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],30,530,720,60);
 $io_pdf->add_texto($li_ancho+50,20,12,"<b>COMPROBANTE DE RETENCION DE IMPUESTO 1/1000 $ls_tipbol</b>");
 $io_pdf->add_texto($li_ancho+85,26,9,"SEGÚN GACETA OFICIAL N°0015");
 $io_pdf->add_texto($li_ancho+47,30,9,"Consagrada en el art. 9 de la Ordenanza de Timbre Fiscal del Distrito Metropolitano de Caracas");
 $li_anchotitulo=$io_pdf->getTextWidth(9," DE RETENCION DE IMPUESTO 1/1000");//Calculando posicion del Titulo
 $io_pdf->convertir_valor_px_mm($li_anchotitulo);
 $la_data=array();//Nro de Comprobante
 $la_data[0]["1"]="";
 $la_data[1]["1"]="";
 $la_anchos_col = array(50);
 $la_justificaciones = array("center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>2);
 $io_pdf->ezSetDy(-100);
 $io_pdf->add_tabla($li_anchotitulo+16+$li_ancho,$la_data,$la_opciones);
/* $io_pdf->ezSetDy(28.5);
 $la_data=array();//Fecha
 $la_data[0]["1"]="<b>FECHA</b>";
 $la_data[1]["1"]=date("d/m/Y");
 $la_anchos_col = array(30);
 $la_justificaciones = array("center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>2);
 $io_pdf->add_tabla($li_anchotitulo+16+$li_ancho+55,$la_data,$la_opciones);*/
 //--------------------Anulado--------------------------------//
 $ls_estcmpret=$la_datacomprobantes["estcmpret"][1];
 if($ls_estcmpret==2)
 {
 	$io_pdf->add_texto(90,18,15,"<b>-----ANULADO-----<b>");
 }
 //-------------------------Fila 1---------------------------//
 $io_pdf->ezSetDy(-10);
/* $li_posi=$io_pdf->get_alto_disponible()+20;
 $io_pdf->convertir_valor_mm_px($li_posi);
 $la_data=array();//Columna 1
 $la_data[0]["1"]="<b>AGENTE DE RETENCION</b> ".$ls_nomemp;
 $la_data[1]["1"]='';
 $la_anchos_col = array(300);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>3,
					   "margen_vertical"=>0.5);
 $io_pdf->add_tabla(0,$la_data,$la_opciones);
 $li_pos=$io_pdf->y-8;*/
/* $la_data=array();//Columna 2
 $io_pdf->ezSetY($li_posi);
 $la_data[0]["1"]="<b>N° DE R.I.F.</b> ".$ls_rifemp;
 $la_data[1]["1"]='';
 $la_anchos_col = array(120);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "lineas"=>0,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>1,
					   "margen_vertical"=>0.5);
 $io_pdf->add_tabla(102,$la_data,$la_opciones);*/

 $io_pdf->addText(580,470,9,"<b>N° CORRELATIVO</b> ");
 $io_pdf->addText(680,470,9,"$la_comprobantes[$li_k]");

 $io_pdf->addText(34,465,9,"<b>AGENTE DE RETENCION</b> ");
 $io_pdf->addText(165,465,9,$ls_nomemp);

 $io_pdf->addText(34,452,9,"<b>N° DE R.I.F.</b> ");
 $io_pdf->addText(165,452,9,$ls_rifemp);

 $io_pdf->addText(34,439,9,"<b>DOMICILIO FISCAL</b> ");
 $io_pdf->addText(165,439,9,$ls_diremp);

 $io_pdf->addText(34,426,9,"<b>TELEFONO</b> ");
 $io_pdf->addText(165,426,9,$ls_telemp);

 $io_pdf->addText(34,413,9,"<b>PERIODO DE IMPOSICION</b> ");
 $io_pdf->addText(165,413,9,$ls_agno.$ls_mes);


 $io_pdf->addText(34,397,9,"<b>CONTRIBUYENTE</b> ");
 $io_pdf->addText(165,397,9,$la_datacomprobantes["nomsujret"][1]);

 $io_pdf->addText(34,384,9,"<b>PERSONA</b> ");
 $io_pdf->addText(165,384,9,$ls_tipper);

 $io_pdf->addText(34,371,9,"<b>N° DE R.I.F.</b> ");
 $io_pdf->addText(165,371,9,$la_datacomprobantes["rif"][1]);

/* $la_data=array();//Columna 3
 $io_pdf->ezSetY($li_posi);
 $la_data[0]["1"]="<b>PERIODO FISCAL</b>";
 $la_data[1]["1"]=$ls_agno.$ls_mes;
 $la_anchos_col = array(35);
 $la_justificaciones = array("center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>1,
					   "margen_vertical"=>0.5);
 $io_pdf->add_tabla(224,$la_data,$la_opciones);*/
//-------------------------Fila 2---------------------------//
/* $la_data=array();//Columna 1
 //$li_pos=$io_pdf->y-8;
 $io_pdf->ezSetY($li_pos);
 $la_data[0]["1"]="<b>LICENCIA FUNCIONAMIENTO AGENTE DE RETENCION</b>";
 $la_data[1]["1"]=$ls_licemp;
 $la_anchos_col = array(100);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>3,
					   "margen_vertical"=>0.5);
 $io_pdf->add_tabla(0,$la_data,$la_opciones);*/
 //-------------------------Fila 3---------------------------//
/* $la_data=array();//Columna 1
 $li_pos=$io_pdf->y-8;
 $io_pdf->ezSetY($li_pos);
 $la_data[0]["1"]="<b>DIRECCION FISCAL DEL AGENTE DE RETENCION</b>";
 $la_data[1]["1"]=$ls_diremp;
 $la_anchos_col = array(260);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>3,
					   "margen_vertical"=>0.5);
 $io_pdf->add_tabla(0,$la_data,$la_opciones);*/

 //-------------------------Fila 4---------------------------//
 $li_pos=$io_pdf->y-8;
/* $io_pdf->ezSetY($li_pos);
 $la_data=array();//Columna 1
 $la_data[0]["1"]="<b>NOMBRE O RAZON SOCIAL DEL SUJETO RETENIDO</b>";
 $la_data[1]["1"]=$la_datacomprobantes["nomsujret"][1];
 $la_anchos_col = array(100);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>3,
					   "margen_vertical"=>0.5);
 $io_pdf->add_tabla(0,$la_data,$la_opciones);*/
 $li_pos2=$io_pdf->y-8;
/* $la_data=array();//Columna 2
 $io_pdf->ezSetY($li_pos);
 $la_data[0]["1"]="<b>REGISTRO DE INFORMACION FISCAL DEL SUJETO RETENIDO (R.I.F.)</b>";
 $la_data[1]["1"]=$la_datacomprobantes["rif"][1];
 $la_anchos_col = array(120);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>1,
					   "margen_vertical"=>0.5);
 $io_pdf->add_tabla(102,$la_data,$la_opciones);*/
 //-------------------------Fila 5---------------------------//
 $la_data=array();//Columna 1
 //$li_pos=$io_pdf->y-8;
 //$io_pdf->ezSetY($li_pos2);
 $io_pdf->ezSetY(360);
/* $la_data[0]["1"]="<b>LICENCIA FUNCIONAMIENTO SUJETO RETENIDO</b>";
 $la_data[1]["1"]=$la_datacomprobantes["numlic"][1];
 $la_anchos_col = array(100);
 $la_justificaciones = array("left");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>3,
					   "margen_vertical"=>0.5);
 $io_pdf->add_tabla(0,$la_data,$la_opciones);*/
 //-------------------------Encabezado de la Tabla---------------------------//
 $la_data=array();
 $li_pos=$io_pdf->y-8;
 $io_pdf->ezSetY($li_pos);
 $la_data[0]["1"]="<b>Fecha de la Retención</b>";
 $la_data[0]["2"]="<b>Descripción</b>";
 $la_data[0]["3"]="<b>N° Factura</b>";
 $la_data[0]["4"]="<b>N° de la Orden de Pago</b>";
 $la_data[0]["5"]="<b>Monto Bruto de la Operación</b>";
 $la_data[0]["6"]="<b>Monto Sujeto a Retención</b>";
 $la_data[0]["7"]="<b>Alícuota</b>";
 $la_data[0]["8"]="<b>Monto Retenido</b>";
 $la_anchos_col = array(18,43.12,22.83,39.32,39.78,45.66,20.29,39);
 $la_justificaciones = array("center","center","center","center","center","center","center","center");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "grosor_lineas_externas"=>1,
					   "grosor_lineas_internas"=>1,
					    "margen_vertical"=>2,
						"color_fondo"=>array(237,237,239));
  $io_pdf->add_tabla(-1,$la_data,$la_opciones);

  //--------------------------------Detalle de la Tabla----------------------------------------//
 $li_totalfilas=floor((count($la_datacomprobantes, COUNT_RECURSIVE) / count($la_datacomprobantes)) - 1);
 $li_pos=$io_pdf->y-2;
 $io_pdf->ezSetY($li_pos);
 $la_data=array();
 $la_anchos_col="";
 $la_opciones="";
 $li_aux=0;
 for($li_i=1;$li_i<=$li_totalfilas;$li_i++)
 {
	$la_data[$li_aux]["1"]=$io_funciones->uf_convertirfecmostrar($la_datacomprobantes["fecrep"][$li_i]);;
	$la_data[$li_aux]["2"]='Servicios';		//trim($la_datacomprobantes["numsop"][$li_i])
	$la_data[$li_aux]["3"]=$la_datacomprobantes["numfac"][$li_i];
	$la_data[$li_aux]["4"]=$la_datacomprobantes["numsop"][$li_i];
	$la_data[$li_aux]["5"]=number_format($la_datacomprobantes["totcmp_con_iva"][$li_i],2,",",".");
	$la_data[$li_aux]["6"]=number_format($la_datacomprobantes["basimp"][$li_i],2,",",".");
	$la_data[$li_aux]["7"]=number_format(($la_datacomprobantes["porimp"][$li_i]),2,",",".");
	$la_data[$li_aux]["8"]=number_format($la_datacomprobantes["iva_ret"][$li_i],2,",",".");
	$li_aux+=1;
 }
 $la_anchos_col = array(18,43.12,22.83,39.32,39.78,45.66,20.29,39);
 $la_justificaciones = array("center","center","center","center","center","right","center","right");
 $la_opciones = array("color_texto" => array(0,0,0),
				   "anchos_col"  => $la_anchos_col,
				   "tamano_texto"=> 9,
				   "alineacion_col"=>$la_justificaciones,
				   "margen_vertical"=>0,
				   "grosor_lineas_externas"=>0.1,
				   "grosor_lineas_internas"=>0.1,
					"margen_vertical"=>2,
					"color_fondo"=>array(255,255,255));
 $io_pdf->add_tabla(-1,$la_data,$la_opciones);
//--------------------------Agregar Totales-------------------------------//
 $la_data=array();
 $ls_totalmonto=array_sum($la_datacomprobantes["basimp"]);
 $ls_totaliva=array_sum($la_datacomprobantes["iva_ret"]);
 $ls_totalconiva=array_sum($la_datacomprobantes["totcmp_con_iva"]);
 $ls_totalalic=array_sum($la_datacomprobantes["porimp"]);
 $la_data[0]["1"]="<b>Totales...</b>";

 $la_data[0]["2"]="<b>".number_format($ls_totalconiva,2,",",".")."</b>";
$la_anchos_col = array(10,10);
 $la_justificaciones = array("center","right");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "grosor_lineas_externas"=>1,
					   "grosor_lineas_internas"=>1,
					    "margen_vertical"=>2,
						"color_fondo"=>array(237,237,239));
  //$io_pdf->add_tabla(83,$la_data,$la_opciones);

 $la_data[0]["3"]="<b>".number_format($ls_totalmonto,2,",",".")."</b>";
 $la_anchos_col = array(1,1,10);
 $la_justificaciones = array("center","right");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "grosor_lineas_externas"=>1,
					   "grosor_lineas_internas"=>1,
					    "margen_vertical"=>2,
						"color_fondo"=>array(237,237,239));

 $la_data[0]["4"]="<b>".number_format($ls_totalalic,2,",",".")."</b>";
 $la_anchos_col = array(39,39.8,46,20);
 $la_justificaciones = array("center","center","right","right");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "grosor_lineas_externas"=>1,
					   "grosor_lineas_internas"=>1,
					    "margen_vertical"=>2,
						"color_fondo"=>array(237,237,239));
  $io_pdf->add_tabla(83.1,$la_data,$la_opciones);

 $io_pdf->ezSetDy(14);
 $la_data=array();
 $la_data[0]["5"]="<b>".number_format($ls_totaliva,2,",",".")."</b>";
 $la_anchos_col = array(39);
 $la_justificaciones = array("right");
 $la_opciones = array("color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 9,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_vertical"=>0,
					   "grosor_lineas_externas"=>1,
					   "grosor_lineas_internas"=>1,
					    "margen_vertical"=>2,
						"color_fondo"=>array(237,237,239));
  $io_pdf->add_tabla(228,$la_data,$la_opciones);

  //--------------------------Agregar Firma-------------------------------//
  $li_pos=$io_pdf->get_alto_usado();
   $la_opciones = array("color_fondo" => array(0,0,0),//para que me pinte de nuevo las lineas color negro
					   "anchos_col"  => array(1),
					   "tamano_texto"=> 1);
  $io_pdf->add_tabla(-110,array(""),$la_opciones);
  $io_pdf->add_linea(40,$li_pos+20,110,$li_pos+20);
  $io_pdf->add_texto(45,$li_pos+21,9,"FIRMA DEL AGENTE DE RETENCION \n");
  $io_pdf->add_linea(150,$li_pos+20,220,$li_pos+20);
  $io_pdf->add_texto(160,$li_pos+21,9,"FIRMA DEL SUJETO RETENIDO \n");
  if(($li_k+1)<count($la_comprobantes))
  {
  		$io_pdf->ezNewPage();
  }
 }
 $io_pdf->ezStream();
?>