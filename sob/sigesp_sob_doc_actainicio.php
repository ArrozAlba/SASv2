<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
{
 print "<script language=JavaScript>";
 print "location.href='../../sigesp_inicio_sesion.php'";
 print "</script>";		
}
include_once('class_folder/tbs_class.php');
include_once('class_folder/tbsooo_class.php');
$io_OOO=new clsTinyButStrongOOo;
include_once('class_folder/sigesp_sob_c_funciones_sob.php');
$io_funsob=new sigesp_sob_c_funciones_sob();
require_once("class_folder/sigesp_sob_c_contrato.php");
$io_contrato=new sigesp_sob_c_contrato();
require_once("class_folder/sigesp_sob_class_obra.php");
$io_obra=new sigesp_sob_class_obra();
require_once("class_folder/sigesp_sob_c_acta.php");
$io_acta=new sigesp_sob_c_acta();
require_once("class_folder/sigesp_sob_c_supervisores.php");
$io_supervisores=new sigesp_sob_c_supervisores();
require_once("../shared/class_folder/class_funciones.php");
$io_function=new class_funciones();


// Obteniedo la data a ser enviada al documento OpenOffice.

$ls_ruta=$_GET["ruta"];
$ls_codcon=$_GET["codcon"];
$ls_codact=$_GET["codact"];
$ls_tipact=$_GET["tipact"];
$io_contrato->uf_select_contrato($ls_codcon,$la_datacontrato);
$io_obra->uf_select_obra($la_datacontrato["codobr"][1],$la_dataobra);
$io_acta->uf_select_acta($ls_codcon,$ls_codact,$ls_tipact,$la_dataacta);
$io_supervisores->uf_select_supervisor($la_dataacta["cedinsact"][1],$la_datainspector);
$io_supervisores->uf_select_supervisor($la_dataacta["cedresact"][1],$la_dataresidente);
//--------------------------Datos de la Obra--------------------------------------------//
$CodigoObra=$la_datacontrato["codobr"][1];
$descripcion=$la_dataobra["desobr"][1];
$ubicacion=$la_dataobra["dirobr"][1];
//--------------------------Datos del Contrato------------------------------------------//
$contratista=$la_datacontrato["nompro"][1];
$numerodecontrato=$ls_codcon;
$MontoContrato=$la_datacontrato["monto"][1];
//--------------------------Datos del Acta---------------------------------------------//
$NumeroActa=$ls_codact;
$nombreinspector=$la_datainspector["nomsup"][1];
$nombreresidente=$la_dataresidente["nomsup"][1];
$cedulainspector=$la_datainspector["cedsup"][1];
$cedularesidente=$la_dataresidente["cedsup"][1];
$civresidente=$la_dataresidente["civ"][1];
$civinspector=$la_datainspector["civ"][1];
$FechaEmisionActa=$io_function->uf_convertirfecmostrar($la_dataacta["fecact"][1]);
$FechaInicioActa=$io_function->uf_convertirfecmostrar($la_dataacta["feciniact"][1]);
$FechaRecepcionObra=$io_function->uf_convertirfecmostrar($la_dataacta["fecrecact"][1]);
$MotivoSuspension=$la_dataacta["motact"][1];
$Observacion=$la_dataacta["obsact"][1];

//--------------------------Datos Generales--------------------------------------------//
$dia=date("d");
$mes=date("m");
$mes=$io_funsob->uf_convertir_numeromes($mes);
$ano=date("Y");
$fecha=date("d/m/Y");

// instantiate a TBS OOo class
$OOo = new clsTinyButStrongOOo;

// setting the object
$OOo->SetZipBinary('zip');
$OOo->SetUnzipBinary('unzip');
$OOo->SetProcessDir('tmp/');


// create a new openoffice document from the template with an unique id 
$OOo->NewDocFromTpl($ls_ruta);

//merge data with openoffice file named 'content.xml'
$OOo->LoadXmlFromDoc('content.xml');
$OOo->SaveXmlToDoc();

// display
header('Content-type: '.$OOo->GetMimetypeDoc());
header('Content-Length: '.filesize($OOo->GetPathnameDoc()));
$OOo->FlushDoc();
$OOo->RemoveDoc();
//
?>