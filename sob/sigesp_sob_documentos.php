<?PHP
session_start();
?>
<?php

		/* --------------------------------------------------------------------------------------------------------------------
			Clase:	        			sigesp_sob_documentos																
		 	Descripción:				Clase que se encarga de generar documentos a partir de plantillas en OpenOffice		
		 	Fecha de Creación:          12/05/2006																			
			Fecha ultima modificación:	19/06/2006																			
		    Autora:          			Ing. Laura Cabré																			
	 	  --------------------------------------------------------------------------------------------------------------------*/
//----------------------Clases y objetos necesarios para generar los documentos OOo--//
	include_once('class_folder/tbs_class.php');
	include_once('class_folder/tbsooo_class.php');
	// instantiate a TBS OOo class
	$OOo = new clsTinyButStrongOOo;

//-------------------Otras clases---------------------------------------------------//
include_once('class_folder/sigesp_sob_c_funciones_sob.php');
$io_funsob=new sigesp_sob_c_funciones_sob();
require_once("../shared/class_folder/class_funciones.php");
$io_function=new class_funciones();

//-------------------Obteniendo arreglo y variables de datos basicos----------------------------//
$ls_documento=$_GET["documento"];
$ls_ruta=$_GET["ruta"];

//-----------Obteniedo la data a ser enviada al documento OpenOffice------------------------//

//--------------------------------------------------ACTA---------------------------------------------------------------//
if($ls_documento=="ACTA")
{ 
	//--------------------------Datos de la Obra--------------------------------------------//
	$la_obra=$io_funsob->uf_decodificar($_GET["obra"]);
	$CodigoObra=$la_obra["codobr"];	
	$DescripcionObra=$la_obra["desobr"];
	$UbicacionObra=$la_obra["dirobr"];
	//--------------------------Datos del Contrato------------------------------------------//
	$la_contrato=$io_funsob->uf_decodificar($_GET["contrato"]);
	$EmpresaContratista=$la_contrato["nompro"];
	$CodigoContrato=$la_contrato["codcon"];
	$MontoContrato=$io_funsob->uf_convertir_numerocadena($la_contrato["monto"]);
	$MontoContratoLetras=$io_funsob->convertir($la_contrato["monto"]);
	$li_pos=strpos($MontoContrato,",");
	$ls_decimales=substr($MontoContrato,($li_pos+1),2);
	$MontoContratoLetras=$MontoContratoLetras." con ".$ls_decimales."/100";	
	//--------------------------Datos del Acta---------------------------------------------//
	$la_acta=$io_funsob->uf_decodificar($_GET["acta"]);
	$la_inspector=$io_funsob->uf_decodificar($_GET["inspector"]);
	$la_residente=$io_funsob->uf_decodificar($_GET["residente"]);	
	$CodigoActa=$la_acta["codact"];
	$NombreInspector=$la_inspector["nomsup"];
	$NombreResidente=$la_residente["nomsup"];
	$CedulaInspector=$la_inspector["cedsup"];
	$CedulaResidente=$la_residente["cedsup"];
	$CivResidente=$la_residente["civ"];
	$CivInspector=$la_inspector["civ"];
	$FechaEmisionActa=$io_function->uf_convertirfecmostrar($la_acta["fecact"]);
	$FechaInicioActa=$io_function->uf_convertirfecmostrar($la_acta["feciniact"]);
	$FechaRecepcionObra=$io_function->uf_convertirfecmostrar($la_acta["fecrecact"]);
	$MotivoSuspension=$la_acta["motact"];
	$Observacion=$la_acta["obsact"];
}//end del if de documento "ACTA"

//-------------------------------------------------------CONTRATO--------------------------------------------------------//
elseif($ls_documento=="CONTRATO")
{
	//--------------------------Datos del Contrato------------------------------------------//
	$la_contrato=$io_funsob->uf_decodificar($_GET["contrato"]);
	$la_unidadmulta=$io_funsob->uf_decodificar($_GET["unidadmulta"]);
	$la_unidadgarantia=$io_funsob->uf_decodificar($_GET["unidadgarantia"]);
	$la_condiciones=$io_funsob->uf_decodificar_arreglosdobles($_GET["condiciones"]);
	$CodigoContrato=$la_contrato["codcon"];
	$FechaEmisionContrato=$io_function->uf_convertirfecmostrar($la_contrato["feccon"]);
	$FechaInicioContrato=$io_function->uf_convertirfecmostrar($la_contrato["fecinicon"]);
	$FechaFinalizacionContrato=$io_function->uf_convertirfecmostrar($la_contrato["fecfincon"]);
	$PlazoDuracionContrato=$io_funsob->uf_convertir_decimalentero($la_contrato["placon"]);
	$ls_unidad=$io_funsob->uf_convertir_letraunidad($la_contrato["placonuni"]);
	$PlazoDuracionContrato=$PlazoDuracionContrato." ".$ls_unidad;
	$MontoContrato=$io_funsob->uf_convertir_numerocadena($la_contrato["monto"]);
	$MontoContratoLetras=$io_funsob->convertir($la_contrato["monto"]);
	$li_pos=strpos($MontoContrato,",");
	$ls_decimales=substr($MontoContrato,($li_pos+1),2);
	$MontoContratoLetras=$MontoContratoLetras." con ".$ls_decimales."/100";	
	$MontoMultaIncumplimiento=$io_funsob->uf_convertir_numerocadena($la_contrato["mulcon"]);
	$TiempoMultaInclumplimiento=$io_funsob->uf_convertir_decimalentero($la_contrato["tiemulcon"]);
	$ls_unidad=$la_unidadmulta["nomuni"];
	$TiempoMultaInclumplimiento=$TiempoMultaInclumplimiento." ".$ls_unidad;
	$LapsoGarantia=$io_funsob->uf_convertir_decimalentero($la_contrato["lapgarcon"]);
	$ls_unidad=$la_unidadgarantia["nomuni"];	
	$LapsoGarantia=$LapsoGarantia." ".$ls_unidad;
	$MontoMaximoContrato=$io_funsob->uf_convertir_numerocadena($la_contrato["monmaxcon"]);
	$PorcentajeMaximoContrato=$io_funsob->uf_convertir_numerocadena($la_contrato["pormaxcon"]);
	$ObservacionContrato=$la_contrato["obscon"];
	//---------------------------------Datos de la Obra-------------------------------------------//
	$la_obra=$io_funsob->uf_decodificar($_GET["obra"]);
	$CodigoObra=$la_obra["codobr"];
	$DescripcionObra=$la_obra["desobr"];
	$DireccionObra=$la_obra["dirobr"];
	$EstadoObra=$la_obra["desest"];
	$MunicipioObra=$la_obra["denmun"];
	$ParroquiaObra=$la_obra["denpar"];
	$ComunidadObra=$la_obra["nomcom"];
	$Responsable=$la_obra["resobr"];
	
	//---------------------------------Datos de la Asignacion----------------------------------------//
	$la_asignacion=$io_funsob->uf_decodificar($_GET["asignacion"]);
	$la_inspector=$io_funsob->uf_decodificar($_GET["inspector"]);
	$la_contratista=$io_funsob->uf_decodificar($_GET["contratista"]);
	$NombreEmpresaInspectora=$la_inspector["nompro"];
	$NombreEmpresaContratista=$la_contratista["nompro"];
	$PuntoCuenta=$la_asignacion["puncueasi"];
	$FechaAsignacion=$io_function->uf_convertirfecmostrar($la_asignacion["fecasi"]);
	$ObservacionAsignacion=$la_asignacion["obsasi"];
	$MontoParcialAsignacion=$io_funsob->uf_convertir_numerocadena($la_asignacion["monparasi"]);
	$BaseImponibleAsignacion=$io_funsob->uf_convertir_numerocadena($la_asignacion["basimpasi"]);
	$MontoTotalAsignacion=$io_funsob->uf_convertir_numerocadena($la_asignacion["montotasi"]);
		
}//end del if de documento "CONTRATO"
//--------------------------Datos Generales--------------------------------------------//
$Dia=date("d");
//$dia=$io_funsob->convertir(700850390);
$Mes=date("m");
$Mes=$io_funsob->uf_convertir_numeromes($Mes);
$Ano=date("Y");
$Fecha=date("d/m/Y");


// setting the object

$OOo->SetZipBinary('zip');
$OOo->SetUnzipBinary('unzip');
$OOo->SetProcessDir('tmp/');

// create a new openoffice document from the template with an unique id 
$lb_valido=$OOo->NewDocFromTpl($ls_ruta);

//merge data with openoffice file named 'content.xml'
$OOo->LoadXmlFromDoc('content.xml',$ls_ruta);
$OOo->MergeBlock('tabla',$la_condiciones);
$OOo->SaveXmlToDoc($ls_ruta);

// display
header('Content-type: '.$OOo->GetMimetypeDoc());
header('Content-Length: '.filesize($OOo->GetPathnameDoc()));
$OOo->FlushDoc();
$OOo->RemoveDoc();
?>