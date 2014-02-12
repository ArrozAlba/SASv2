<?Php
session_start();
require_once("../../../shared/class_folder/class_pdf.php");
require_once("../../../sfv/class_folder/dao/sfv_configuracion_dao.php");
require_once("../../../sfv/class_folder/dao/sfv_solicitud_dao.php");
require_once("../../../sfv/class_folder/dao/sfv_beneficiario_dao.php");

$io_pdf  = new class_pdf('LETTER','landscape');
$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');
$io_pdf->set_margenes(23,14,16,32);
$ls_conexion = $_SESSION["conexion_activa"];
$lo_configuracion_dao = new sfv_configuracion_dao($ls_conexion);
$lo_beneficiario_dao = new sfv_beneficiario_dao($ls_conexion);
$lo_configuracion_dao->getDatosConfiguracion($la_datos_configuracion);
//////////////////////////
$ls_numsol  = $_GET["numsol"];
$io_pdf->add_imagen("../imagenes/gobernacion_cojedes.jpg",'left',0,25);
$io_pdf->add_imagen("../imagenes/logo_indhur.jpg",'center',0,35);
$io_pdf->add_imagen("../imagenes/mision_habitat.jpg",'right',0,40);
$io_pdf->add_linea(0,38,230,38,2);
$io_pdf->add_texto('center',46,18,"<b><i>CERTIFICADO DE ADJUDICACION</i></b>");

$ls_escrito  = "Quien suscribe, ";
$ls_escrito .= $la_datos_configuracion["titpreorg"][0]." ";
$ls_escrito .= $la_datos_configuracion["nompreorg"][0]." ";
$ls_escrito .= $la_datos_configuracion["apepreorg"][0].", ";
$ls_escrito .= "en su carácter de Presidente del Instituto de Desarrollo Habitacional Urbano y Rural del Estado Cojedes, plenamente facultado por la ley, hace entrega del presente certificado de adjudicación de vivienda al ciudadano (a)";

$la_opciones = array("color_fondo"    => array(255,255,255),
                     "anchos_col"     => array(205),
					 "lineas"         => 0,
					 "tamano_texto"   => 12,
					 "alineacion_col" => array("full"));
$io_pdf->add_lineas(14);
$io_pdf->add_tabla('center',array($ls_escrito),$la_opciones);

$lo_solicitud_dao = new sfv_solicitud_dao($ls_conexion);
$lo_solicitud_dao->getSolicitud($ls_numsol,$la_datos_solicitud);
$lo_beneficiario_dao->getBeneficiario($la_datos_solicitud["cedben"][0],$la_datos_beneficiario);


$io_pdf->add_texto('center',92,18,"<b>".$la_datos_beneficiario["nomben"][0]." ".$la_datos_beneficiario["apeben"][0]."</b>");
$io_pdf->add_linea(76,125,155,125,1);
$io_pdf->add_texto('center',128,12,"<b>".$la_datos_configuracion["titpreorg"][0]." ".$la_datos_configuracion["nompreorg"][0]." ".$la_datos_configuracion["apepreorg"][0]."</b>");
$io_pdf->add_texto('center',135,12,"<b>Presidente</b>");
$io_pdf->add_texto('center',175,10,"Al dorso del presente Certificado se encuentran las condiciones y/o restricciones debidamente identificadas");

//////////////////////////
$io_pdf->ezStream();
?>