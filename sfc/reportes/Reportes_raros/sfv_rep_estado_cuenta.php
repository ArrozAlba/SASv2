<?Php
session_start();

require_once("../../../shared/class_folder/class_pdf.php");
require_once("../../../sfv/class_folder/dao/sfv_solicitud_dao.php");
require_once("../../../sfv/class_folder/dao/sfv_beneficiario_dao.php");
require_once("../../../sfv/class_folder/dao/sfv_municipio_dao.php");
require_once("../../class_folder/dao/sfv_contrato_dao.php");
require_once("../../class_folder/dao/sfv_cuota_dao.php");
require_once("../../class_folder/dao/sfv_recibo_dao.php");

$io_pdf  = new class_pdf('LETTER','portrait');
$io_pdf->selectFont('../../../shared/ezpdf/fonts/Helvetica.afm');
$io_pdf->set_margenes(10,10,10,10);
$ls_conexion = $_SESSION["conexion_activa"];
$ls_numsol  = $_GET["numsol"];
$ls_numcon  = $_GET["numcon"];
$lo_solicitud_dao = new sfv_solicitud_dao($ls_conexion);
$lo_beneficiario_dao = new sfv_beneficiario_dao($ls_conexion);
$lo_contarto_dao = new sfv_contrato_dao($ls_conexion);
$lo_cuota_dao = new sfv_cuota_dao($ls_conexion);
$lo_recibo_dao = new sfv_recibo_dao($ls_conexion);


$lo_solicitud_dao->getSolicitud($ls_numsol,$la_datos_solicitud);
$lo_beneficiario_dao->getBeneficiario($la_datos_solicitud["cedben"][0],$la_datos_beneficiario);
$lo_contarto_dao->getContrato($ls_numcon,$la_datos_contrato);
$lo_cuota_dao ->getCuotas($ls_numcon,"","ORDER BY numcuo",$la_datos_cuotas);

//////////////////////////
$io_pdf->add_imagen("../imagenes/logo_indhur.jpg",'right',0,35);
$io_pdf->add_texto('left',0,10,"<b>Nombre del Beneficiario    ".$la_datos_beneficiario["nomben"][0]." ".$la_datos_beneficiario["apeben"][0]."</b>");
$io_pdf->add_texto('left',5,10,"<b>Cedula de Identidad        ".$la_datos_beneficiario["cedben"][0]."</b>");
$io_pdf->add_texto('left',10,10,"<b>Nombre del Conyuge        ".$la_datos_beneficiario["nomcon"][0]." ".$la_datos_beneficiario["apecon"][0]."</b>");
$io_pdf->add_texto('left',15,10,"<b>Cedula de Identidad       ".$la_datos_beneficiario["cedcon"][0]."</b>");

$io_pdf->add_texto('left',25,10,"<b>Sueldo Integral Mensual del Beneficiario</b>");
$io_pdf->add_texto('center',25,10,"<b>".$la_datos_beneficiario["monsalben"][0]."</b>");
$io_pdf->add_texto('left',30,10,"<b>Sueldo Integral Mensual del Conyuge</b>");
$io_pdf->add_texto('center',30,10,"<b>".$la_datos_beneficiario["monsalcon"][0]."</b>");
$io_pdf->add_texto('left',35,10,"<b>Otros Ingresos Fijos Mensuales</b>");
$io_pdf->add_texto('center',35,10,"<b>".$la_datos_beneficiario["moningfamben"][0]."</b>");
$io_pdf->add_texto('left',40,10,"<b>Total Ingreso Mensual (Bs)</b>");
$ld_mbe=str_replace(".","",$la_datos_beneficiario["monsalben"][0]);
$ld_mbe=str_replace(",",".",$ld_mbe);
$ld_mco=str_replace(".","",$la_datos_beneficiario["monsalcon"][0]);
$ld_mco=str_replace(",",".",$ld_mco);
$ld_mif=str_replace(".","",$la_datos_beneficiario["moningfamben"][0]);
$ld_mif=str_replace(",",".",$ld_mif);
$ld_mit=$ld_mbe+$ld_mco+$ld_mif;
$numero = number_format($ld_mit,2, ',', '.');
$io_pdf->add_texto('center',40,10,"<b>".$numero."</b>");

$io_pdf->add_texto('center',55,12,"<b>Calculo del Credito</b>");

$io_pdf->add_texto(65,65,10,"<b>Cantidad UT</b>");
$io_pdf->add_texto(90,65,10,"<b>Monto UT</b>");

$io_pdf->add_texto('left',70,12,"<b>Tasa de Interes</b>");
$io_pdf->add_texto(120,70,12,"<b>".$la_datos_contrato["tasint"][0]."</b>");
$io_pdf->add_texto('left',75,12,"<b>Precio del Inmueble</b>");
$io_pdf->add_texto(120,75,12,"<b>".$la_datos_contrato["preinm"][0]."</b>");
$io_pdf->add_texto('left',80,12,"<b>Monto del Subsidio</b>");
$io_pdf->add_texto(68,80,12,"<b>".$la_datos_contrato["canunitrisub"][0]."</b>");
$io_pdf->add_texto(93,80,12,"<b>".$la_datos_contrato["monunitri"][0]."</b>");
$io_pdf->add_texto(120,80,12,"<b>".$la_datos_contrato["monsub"][0]."</b>");
$io_pdf->add_texto('left',85,12,"<b>Monto de la Inicial</b>");
$io_pdf->add_texto(120,85,12,"<b>0,00</b>");
$io_pdf->add_texto('left',90,12,"<b>Saldo a Financiar</b>");
$io_pdf->add_texto(120,90,12,"<b>".$la_datos_contrato["salfin"][0]."</b>");
$io_pdf->add_texto('left',95,12,"<b>Plazo años</b>");
$io_pdf->add_texto(120,95,12,"<b>".$la_datos_contrato["placreano"][0]."</b>");
$io_pdf->add_texto('left',100,12,"<b>Ingreso minimo requerido</b>");
$io_pdf->add_texto(120,100,12,"<b>".$la_datos_contrato["monsalben"][0]."</b>");
$io_pdf->add_texto('left',105,12,"<b>Cuota Mensual</b>");
$io_pdf->add_texto(120,105,12,"<b>".$la_datos_contrato["moncuomen"][0]."</b>");
$io_pdf->add_texto(93,105,12,"<b>100,00 %</b>");
$io_pdf->add_texto('left',110,12,"<b>Cuota Financiera</b>");
$io_pdf->add_texto(120,110,12,"<b>".$la_datos_contrato["moncuofin"][0]."</b>");
$io_pdf->add_texto(93,110,12,"<b>".$la_datos_contrato["porcuofin"][0]." %</b>");
$io_pdf->add_texto('left',115,12,"<b>Cuota Fondo de Garantia</b>");
$io_pdf->add_texto(120,115,12,"<b>".$la_datos_contrato["monfongar"][0]."</b>");
$io_pdf->add_texto(93,115,12,"<b>".$la_datos_contrato["porfongar"][0]." %</b>");
$io_pdf->add_texto('left',120,12,"<b>Cuota Fondo de Rescate</b>");
$io_pdf->add_texto(120,120,12,"<b>".$la_datos_contrato["monfonres"][0]."</b>");
$io_pdf->add_texto(93,120,12,"<b>".$la_datos_contrato["porfonres"][0]." %</b>");


 /*$titulos=array('numcon'=>'Numero'1,'numcuo'=>'Cuota'2,'anocuo'=>'Año'3,'mescuo'=>'Mes'4,'moncap'=>'Capital'5,'moncuo'=>'Monto Cuota'6,'moncuofin'=>'Monto Cuota Financiera'7,'monint'=>'Intereses'8,'monabocap'=>'Abono a Capital'9,'monfongar'=>'Fondo de Garantia'10,'monfonres'=>'Fondo de Rescate'11,'estcan'=>'estatus'12,'numrec'=>'Recibo'13);
 $io_pdf->ezTable($la_datos_cuotas,$titulos,"",array('showHeadings'=>1,'shaded'=>0,'showLines'=>1,'maxWidth'=>460));*/

$li_cuotas=(count($la_datos_cuotas,COUNT_RECURSIVE)/count($la_datos_cuotas)) - 1;
for($i=0;$i<$li_cuotas;$i++)
{
 $la_datos[$i]["numcuo"]=$la_datos_cuotas["numcuo"][$i];
 $la_datos[$i]["moncap"]=$la_datos_cuotas["moncap"][$i];
 $la_datos[$i]["moncuo"]=$la_datos_cuotas["moncuo"][$i];
 $la_datos[$i]["moncuofin"]=$la_datos_cuotas["moncuofin"][$i];
 $la_datos[$i]["monint"]=$la_datos_cuotas["monint"][$i];
 $la_datos[$i]["monabocap"]=$la_datos_cuotas["monabocap"][$i];
 $la_datos[$i]["monfongar"]=$la_datos_cuotas["monfongar"][$i];
 $la_datos[$i]["monfonres"]=$la_datos_cuotas["monfonres"][$i];
 $la_datos[$i]["numrec"]=$la_datos_cuotas["numrec"][$i];
 $ls_fecha="Por Pagar";
 if($la_datos_cuotas["numrec"][$i]!="")
 {
   $lo_recibo_dao->getRecibo($la_datos_cuotas["numrec"][$i],"",$la_recibo);
   $ls_fecha=$la_recibo["fecrec"][0];
 }
 $la_datos[$i]["mescuo"]=$ls_fecha;
}

$io_pdf->ezSetY(300);
        
        $la_anchos_col = array(10,20,20,20,20,20,20,15,35,20);
		$la_justificaciones = array('center','center','center','center','center','center','center','center','center','center');

        $la_titulos[0]["1"]="Cuota";
		$la_titulos[0]["2"]="Capital";
		$la_titulos[0]["3"]="Monto Cuota";
		$la_titulos[0]["4"]="Cuota Financiera";
		$la_titulos[0]["5"]="Intereses";
		$la_titulos[0]["6"]="Abono a Capital";
		$la_titulos[0]["7"]="Fondo de Garantia";
		$la_titulos[0]["8"]="Fondo de Rescate";
		$la_titulos[0]["9"]="Nº Recibo";
		$la_titulos[0]["10"]="Fecha de Pago";
		$la_opciones = array(  "color_fondo" => array(201,249,200), 
		                       "color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 7,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1);
		$io_pdf->add_tabla(0,$la_titulos,$la_opciones);  
	  
		$la_opciones = array(  "color_fondo" => array(229,229,229), 
		                       "color_texto" => array(0,0,0),
							   "anchos_col"  => $la_anchos_col,
							   "tamano_texto"=> 8,
							   "lineas"=>1,
							   "alineacion_col"=>$la_justificaciones,
							   "margen_horizontal"=>1);
		$io_pdf->add_tabla(0,$la_datos,$la_opciones);


//////////////////////////
$io_pdf->ezStream();
?>