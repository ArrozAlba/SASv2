<?Php
session_start();

require_once("../../../sps/class_folder/dao/sps_pro_antiguedad_dao.php");
require_once("../../../sps/reports/documents/sps_reporte_base.php");
require_once("../../../shared/class_folder/class_funciones.php");

$lo_antiguedad_dao = new sps_pro_antiguedad_dao();
$lo_function       = new class_funciones();

$lo_reporte_base = new sps_reporte_base("Detalles de Antigüedad");
$lo_pdf = $lo_reporte_base->getPdf();

//Obtenemos el orden de los campos
$la_orden = explode(",",$_GET["orden"]);

//Subtitulo con los Datos y Orden
$lo_titulo = $lo_pdf->openObject();
for ($i=0; $i<count($la_orden); $i++)
{
   $ls_palabra = $la_orden[$i];
   $ls_subtitulo="Período";
   $lo_pdf->add_texto('left',-2+($i*5),12,"<b><i>".strtoupper($ls_subtitulo).":</i></b>");
   $ls_dato = ($ls_palabra == "fecant")?"Del ".$_GET["fechainicio"]." al ".$_GET["fechafin"]:$_GET[$la_orden[$i]];
   $lo_pdf->add_texto(22,-2+($i*5),12,$ls_dato);
}
$lo_pdf->closeObject();
$lo_pdf->addObject($lo_titulo,'all');
$lo_pdf->set_margenes(50,15,25,15);
    
//Cabecera de la tabla de detalle
/*$la_detalle      = array("Fecha","Salario","Inc.Vacacional","Inc.Fin de Año","S. Integral","Día Antig.","Día Comp","Antigüedad","Antig Acum","Anticipo","Saldo Parcial","% Interés","Día Interés","Interés","Interés Acum","Saldo Total");
$la_anchos       = array("Fecha"=>16,"Salario"=>16,"Inc.Vacacional"=>16,"Inc.Fin de Año"=>16,"S. Integral"=>16,"Día Antig."=>13,"Día Comp"=>13,"Antigüedad"=>16,"Antig Acum"=>18,"Anticipo"=>16,"Saldo Parcial"=>17,"% Interés"=>14,"Día Interés"=>14,"Interés"=>15,"Interés Acum"=>16,"Saldo Total"=>18);
$la_alineaciones = array("Fecha"=>'center',"Salario"=>'right',"Inc.Vacacional"=>'right',"Inc.Fin de Año"=>'right',"S. Integral"=>'right',"Día Antig."=>'center',"Día Comp"=>'center',"Antigüedad"=>'right',"Antig Acum"=>'right',"Anticipo"=>'right',"Saldo Parcial"=>'right',"% Interés"=>'center',"Día Interés"=>'center',"Interés"=>'right',"Interés Acum"=>'right',"Saldo Total"=>'right');*/

$la_detalle      = array("fecant","salbas","incbonvac","incbonnav","salintdia","diabas","diacom","monant","monacuant","monantant","salparant","porint","diaint","monint","monacuint","saltotant");
$la_anchos       = array("fecant"=>16,"salbas"=>16,"incbonvac"=>16,"incbonnav"=>16,"salintdia"=>16,"diabas"=>13,"diacom"=>13,"monant"=>16,"monacuant"=>18,"monantant"=>16,"salparant"=>17,"porint"=>14,"diaint"=>14,"monint"=>15,"monacuint"=>16,"saltotant"=>18);
$la_alineaciones = array("fecant"=>'center',"salbas"=>'right',"incbonvac"=>'right',"incbonnav"=>'right',"salintdia"=>'right',"diabas"=>'center',"diacom"=>'center',"monant"=>'right',"monacuant"=>'right',"monantant"=>'right',"salparant"=>'right',"porint"=>'center',"diaint"=>'center',"monint"=>'right',"monacuint"=>'right',"saltotant"=>'right');

$lo_subtitulo = $lo_pdf->openObject();  
  for ($j=0; $j<count($la_detalle); $j++)
  {
    $li_x = -6.9;
    for ($m=($j-1); $m>=0; $m--)
      $li_x += $la_anchos[$la_detalle[$m]];
    $lo_pdf->add_rectangulo($li_x,5.5,$la_anchos[$la_detalle[$j]]-0.3,5,$lo_reporte_base->getColorCabeceraTabla());
    $li_x += ($la_anchos[$la_detalle[$j]]/2);
    $ls_palabra = $la_detalle[$j];              
    $li_ancho_palabra = $lo_pdf->getTextWidth(10,strtoupper($ls_palabra));
    $lo_pdf->convertir_valor_px_mm($li_ancho_palabra);
    $li_x -= $li_ancho_palabra/2;
    $lo_pdf->add_texto($li_x,0.5,8,"<b>".strtoupper($ls_palabra)."</b>");
  }
$lo_pdf->closeObject();
$lo_pdf->addObject($lo_subtitulo,'all');
$lo_pdf->set_margenes(55,15,25,15);


$la_opciones = array();
$la_opciones["tamano_texto"] = 7;
$la_opciones["color_fondo"]  = $lo_reporte_base->getColorDetalleTabla();

$lb_hay = $lo_antiguedad_dao->getDetalleAntiguedad("ORDER BY ".$_GET["orden"],$_GET["codper"],$_GET["fechainicio"],$_GET["fechafin"],$la_array);
if ($lb_hay)
{
  $la_opciones["anchos_col"]    = array($la_anchos[$la_detalle[0]],$la_anchos[$la_detalle[1]],$la_anchos[$la_detalle[2]],$la_anchos[$la_detalle[3]],$la_anchos[$la_detalle[4]],$la_anchos[$la_detalle[5]],$la_anchos[$la_detalle[6]],$la_anchos[$la_detalle[7]],$la_anchos[$la_detalle[8]],$la_anchos[$la_detalle[9]],$la_anchos[$la_detalle[10]],$la_anchos[$la_detalle[11]],$la_anchos[$la_detalle[12]],$la_anchos[$la_detalle[13]],$la_anchos[$la_detalle[14]],$la_anchos[$la_detalle[15]],$la_anchos[$la_detalle[15]]);
  $la_opciones["alineacion_col"]= array($la_alineaciones[$la_detalle[0]],$la_alineaciones[$la_detalle[1]],$la_alineaciones[$la_detalle[2]],$la_alineaciones[$la_detalle[3]],$la_alineaciones[$la_detalle[4]],$la_alineaciones[$la_detalle[5]],$la_alineaciones[$la_detalle[6]],$la_alineaciones[$la_detalle[7]],$la_alineaciones[$la_detalle[8]],$la_alineaciones[$la_detalle[9]],$la_alineaciones[$la_detalle[10]],$la_alineaciones[$la_detalle[11]],$la_alineaciones[$la_detalle[12]],$la_alineaciones[$la_detalle[13]],$la_alineaciones[$la_detalle[14]],$la_alineaciones[$la_detalle[15]]);
  $la_datos = array();
  for ($e=0; $e<count($la_array["fecant"]); $e++)
  {  
    for ($p=0; $p<count($la_detalle); $p++)
    {
		if ($p==0)
		{ $la_datos[$e][$la_detalle[$p]] = $lo_function->uf_convertirfecmostrar($la_array[$la_detalle[$p]][$e]);}
		else
		{ $la_datos[$e][$la_detalle[$p]] = $lo_function->uf_ntoc($la_array[$la_detalle[$p]][$e], 2);  }
	}
  }
  $lo_pdf->add_tabla('center',$la_datos,$la_opciones);
}
else
{
  $la_opciones["anchos_col"]   = array(250);
  $la_opciones["alineacion_col"]= array("center");  
  $la_datos = array("<i>No existen Registros que cumplan con los Parámetros.</i>");
  $lo_pdf->add_tabla('center',$la_datos,$la_opciones);
}

//Mostramos el archivo pdf
$lo_pdf->ezStream();
?>