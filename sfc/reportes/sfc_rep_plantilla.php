<?Php
session_start();
require_once("../../class_folder/sigesp_sfc_c_reportes.php");
$reporte = new sigesp_sfc_c_reportes('LETTER','portrait',"sdf");

$ls_contenido="Entre el Insituto de Desarrollo Habitacional Urbano y Rural del Estado Cojedes (INDHUR), ente gubernamental con autonomia propia creado por Ley en fecha 12/01/99 publicado en Gaceta Oficial Edicion Extraordinaria Nº, reformada parcialmente,segun Gaceta Oficial del Estado Cojedes, Edicion Extraordinaria Nº 208, de fecha 31/12/2002, representado en este acto por su Presidente";
$reporte->cuerpo_reporte($ls_contenido);

$io_pdf = $reporte->get_pdf();
$io_pdf->ezStream();









?>