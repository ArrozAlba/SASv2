<?Php
/******************************************/
/* FECHA: 13/08/2007                      */
/* AUTOR: ING. ZULHEYMAR RODR�GUEZ        */
/******************************************/
session_start();
require_once("sigesp_sfc_c_reportes.php");
$reporte = new sigesp_sfc_c_reportes('LETTER','portrait','REPORTE 1');
$io_pdf  = new class_pdf('LETTER','portrait');
$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');
$io_pdf->set_margenes(10,10,10,10);
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_fecha.php");
$io_fecha=new class_fecha();
$io_datastore= new class_datastore();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_msg=new class_mensajes();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_funcion=new class_funciones();
$io_pdf = $reporte->get_pdf();
$io_pdf->numerar_paginas(6);
$reporte->add_titulo('center',10,15,"COTIZACION");
$ls_sql=$_GET["sql"];
$ls_sql=str_replace("\\","",$ls_sql);
$ls_sql=str_replace("/","",$ls_sql);
$rs_datauni=$io_sql->select($ls_sql);
if($rs_datauni==false&&($io_sql->message!=""))
{
	$io_msg->message("No hay registros");
}
else
{
   $la_cotizacion=$io_sql->obtener_datos($rs_datauni);
}
$reporte->add_titulo("left",25,7,utf8_decode("RIF: ".$la_cotizacion["riftie"][1].", ".$la_cotizacion["dirtie"][1].",
Telófonos: ".$la_cotizacion["teltie"][1]));
$reporte->add_titulo("right",20,7,"No. ".$la_cotizacion["numcot"][1]);
$ls_fecha="".substr($la_cotizacion["feccot"][1],8,2)."/".substr($la_cotizacion["feccot"][1],5,2)."/".substr($la_cotizacion["feccot"][1],0,4)."";
$reporte->add_titulo("right",30,7,"Fecha: ".$ls_fecha);
$reporte->add_titulo("left",34,7,"Cliente: ".utf8_encode($la_cotizacion["razcli"][1]));
$reporte->add_titulo("left",37,7,htmlspecialchars(utf8_decode("Dirección: ".$la_cotizacion["dircli"][1])));
$reporte->add_titulo("left",40,7,"Entidad: ".utf8_encode($la_cotizacion["dentie"][1]));
$reporte->add_titulo("right",41,7,"Asesor: ".htmlspecialchars(utf8_decode($la_cotizacion["nomusu"][1])));
$reporte->add_titulo("left",43,7,utf8_decode("Teléfonos: ".$la_cotizacion["telcli"][1]));
$reporte->add_titulo("left",46,7,"Rif: ".$la_cotizacion["cedcli"][1]);
$reporte->add_titulo("left",50,7,utf8_decode("                     Artículo                                              Descripción                                               Precio                Cantidad                              Monto "));
$li_cuotas=(count($la_cotizacion,COUNT_RECURSIVE)/count($la_cotizacion)) - 1;
$la_total=0;
$la_iva=0;
for($i=0;$i<=$li_cuotas-1;$i++)
{
	 $la_datos[$i][utf8_decode("<b>Código</b>")]= $la_cotizacion["codart"][$i+1];
	 $la_datos[$i][utf8_decode("<b>Descripción</b>")]= utf8_decode($la_cotizacion["denart"][$i+1]." ".$la_cotizacion["denunimed"][$i+1]);
	 $la_datos[$i]["<b>Precio Unit</b>"]= number_format($la_cotizacion["precot"][$i+1],2, ',', '.');
	 $la_datos[$i]["<b>Cantidad</b>"]= number_format($la_cotizacion["cancot"][$i+1],2, ',', '.');
	 $la_datos[$i]["<b>Sub-total</b>"]=number_format($la_cotizacion["precot"][$i+1]*$la_cotizacion["cancot"][$i+1],2, ',', '.');
	 $la_total= $la_total+$la_cotizacion["precot"][$i+1]*$la_cotizacion["cancot"][$i+1];
	 $la_iva=$la_iva+(($la_cotizacion["precot"][$i+1]*$la_cotizacion["cancot"][$i+1])*($la_cotizacion["impcot"][$i+1]/100));
}
$la_total=number_format($la_total,2, ',', '.');
$la_iva=number_format($la_iva,2, ',', '.');
$io_pdf->ezSetY(555);
$la_anchos_col = array(35,60,20,20,20);
$la_justificaciones = array('center','left','right','right','right');
$la_opciones = array(  "color_fondo" => array(229,229,229),
					   "color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 7,
					   "lineas"=>1,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>1);
$io_pdf->add_tabla(5,$la_datos,$la_opciones);
$la_datos2[0]["totales"]= "<b>I.V.A</b>";
$la_datos2[1]["totales"]= "<b>Total Bs.</b>";
$la_datos2[0]["resultados"]= '<b>'.$la_iva.'</b>';  //IVA
$la_datos2[1]["resultados"]= number_format($la_cotizacion["monto"][1],2, ',', '.');
$la_datos2[1]["resultados"]='<b>'. $la_datos2[1]["resultados"].'</b>';
$la_anchos_col = array(20,20);
$la_justificaciones = array('left','right');
$la_opciones2 = array(  "color_fondo" => array(229,229,229),
					   "color_texto" => array(0,0,0),
					   "anchos_col"  => $la_anchos_col,
					   "tamano_texto"=> 7,
					   "lineas"=>2,
					   "alineacion_col"=>$la_justificaciones,
					   "margen_horizontal"=>1);
$io_pdf->add_tabla2(120,$la_datos2,$la_opciones2);
$ls_observacion= htmlspecialchars($la_cotizacion["obscot"][1],ENT_QUOTES);
$ls_obsservacion=preg_replace("/\s+/"," ",$ls_observacion);
$reporte->add_titulo("left",215,6,"OBSERVACIÓN: ".$ls_observacion);
$io_pdf->ezStream();
?>



