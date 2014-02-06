<?Php
session_start();
require_once("../../../shared/class_folder/class_pdf.php");
require_once("../../../sfv/class_folder/dao/sfv_solicitud_dao.php");
require_once("../../../sfv/class_folder/dao/sfv_beneficiario_dao.php");
require_once("../../../sfv/class_folder/dao/sfv_municipio_dao.php");

$io_pdf  = new class_pdf('RECIBO','portrait');
$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');
$io_pdf->set_margenes(0,0,0,0);
$ls_prilin="";
$ls_seglin="";
$ls_terlin="";
$ls_conexion = $_SESSION["conexion_activa"];
$ls_numsol  = $_GET["numsol"];
$ls_monrec  = $_GET["monrec"];
$ls_monletrec  = $_GET["monletrec"];
$ls_numdep  = $_GET["numdep"];
$ls_nomban  = $_GET["nomban"];
$ls_conrec  = $_GET["conrec"];
$ls_fecrec  = $_GET["fecrec"];
$ls_dia     = substr($ls_fecrec, 0, 2);
$ls_mes     = substr($ls_fecrec, 3, 2);
$ls_nomes="";
if($ls_mes=="01")
 {
   $ls_nomes="ENERO";
 }
 elseif($ls_mes=="02")
 {
   $ls_nomes="FEBRERO";
 }
 elseif($ls_mes=="03")
 {
   $ls_nomes="MARZO";
 }
 elseif($ls_mes=="04")
 {
   $ls_nomes="ABRIL";
 }
 elseif($ls_mes=="05")
 {
   $ls_nomes="MAYO";
 }
 elseif($ls_mes=="06")
 {
   $ls_nomes="JUNIO";
 }
 elseif($ls_mes=="07")
 {
   $ls_nomes="JULIO";
 }
 elseif($ls_mes=="08")
 {
   $ls_nomes="AGOSTO";
 }
 elseif($ls_mes=="09")
 {
   $ls_nomes="SEPTIEMBRE";
 }
 elseif($ls_mes=="10")
 {
   $ls_nomes="OCTUBRE";
 }
 elseif($ls_mes=="11")
 {
   $ls_nomes="NOVIEMBRE";
 }
 elseif($ls_mes=="12")
 {
   $ls_nomes="DICIEMBRE";
 }
$ls_ano     = substr($ls_fecrec, 8, 2);
$largo=strlen($ls_conrec);
if ($largo>95)
{
  $ls_prilin  = substr($ls_conrec, 0, 96);
  if($largo>192)
   {
     $ls_seglin  = substr($ls_conrec, 96, 192);
     $ban=$largo-192;
     if($ban>0)
      {
        $ls_terlin  = substr($ls_conrec, 192, $largo);
      }
   }
   else
   {
     $ls_seglin  = substr($ls_conrec, 96, $largo);
   }
}
else
{
   $ls_prilin  = $ls_conrec;
}
$lo_solicitud_dao = new sfv_solicitud_dao($ls_conexion);
$lo_beneficiario_dao = new sfv_beneficiario_dao($ls_conexion);
$lo_inmueble_dao = new sfv_inmueble_dao($ls_conexion);
$lo_municipio_dao = new sfv_municipio_dao($ls_conexion);


$lo_solicitud_dao->getSolicitud($ls_numsol,$la_datos_solicitud);
$lo_beneficiario_dao->getBeneficiario($la_datos_solicitud["cedben"][0],$la_datos_beneficiario);



//////////////////////////

$io_pdf->add_texto(145,20,10,"<b>**".$ls_monrec."**</b>");
$io_pdf->add_texto(37,55,10,"<b>".$la_datos_beneficiario["nomben"][0]." ".$la_datos_beneficiario["apeben"][0]."</b>");
$io_pdf->add_texto(165,55,10,"<b>".$la_datos_beneficiario["cedben"][0]."</b>");
$io_pdf->add_texto(37,67,10,"<b>".$ls_monletrec."</b>");
$io_pdf->add_texto(37,77,10,"<b>".$ls_numdep."</b>");
$io_pdf->add_texto(125,77,10,"<b>".$ls_nomban."</b>");
$io_pdf->add_texto(33,87,10,"<b>".$ls_prilin."</b>");
$io_pdf->add_texto(15,97,10,"<b>".$ls_seglin."</b>");
$io_pdf->add_texto(15,107,10,"<b>".$ls_terlin."</b>");
$io_pdf->add_texto(33,121,10,"<b>".$ls_dia."</b>");
$io_pdf->add_texto(53,121,10,"<b>".$ls_nomes."</b>");
$io_pdf->add_texto(110,121,10,"<b>".$ls_ano."</b>");


//////////////////////////
$io_pdf->ezStream();
?>