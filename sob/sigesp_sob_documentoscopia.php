<?PHP
session_start();
?>
<?php
		/* --------------------------------------------------------------------------------------------------------------------
			Clase:	        			sigesp_sob_documentos																
		 	Descripción:				Clase que se encarga de generar documentos a partir de plantillas en OpenOffice		
		 	Fecha de Creación:          12/05/2006																			
			Fecha ultima modificación:	12/05/2006																			
		    Autora:          			Ing. Laura Cabré																			
	 	  --------------------------------------------------------------------------------------------------------------------*/
//----------------------Clases y objetos necesarios para generar los documentos OOo--//
include_once('class_folder/tbs_class.php');
include_once('class_folder/tbsooo_class.php');
$io_OOO=new clsTinyButStrongOOo;

//-------------------Otras clases---------------------------------------------------//
include_once('class_folder/sigesp_sob_c_funciones_sob.php');
$io_funsob=new sigesp_sob_c_funciones_sob();
require_once("../shared/class_folder/class_funciones.php");
$io_function=new class_funciones();

//-------------------Obteniendo arreglo y variables de datos basicos----------------------------//
$la_dataadicional=$_SESSION["dataadicional"];
$ls_documento=$la_dataadicional["documento"][1];
$ls_ruta=$la_dataadicional["ruta"][1];

//-----------Obteniedo la data a ser enviada al documento OpenOffice------------------------//
if($ls_documento=="ACTA")
{
  exec ("net send laura 'ruta: ".$ls_ruta."'");
  exec ("net send laura 'documento: ".$ls_documento."'");
	//--------------------------Datos de la Obra--------------------------------------------//
	$la_dataobra=$_SESSION["dataobra"];
	$CodigoObra="123";//$la_dataobra["codobr"][1];
	/*$DescripcionObra=$la_dataobra["desobr"][1];
	$UbicacionObra=$la_dataobra["dirobr"][1];
	//--------------------------Datos del Contrato------------------------------------------//
	$la_datacontrato=$_SESSION["datacontrato"];	
	$EmpresaContratista=$la_datacontrato["nompro"][1];
	$CodigoContrato=$la_datacontrato["codcon"][1];
	$MontoContrato=$io_funsob->uf_convertir_numerocadena($la_datacontrato["monto"][1]);
	$MontoContratoLetras=$io_funsob->convertir($la_datacontrato["monto"][1]);
	$li_pos=strpos($MontoContrato,",");
	$ls_decimales=substr($MontoContrato,($li_pos+1),2);
	$MontoContratoLetras=$MontoContratoLetras." con ".$ls_decimales."/100";	
	//--------------------------Datos del Acta---------------------------------------------//
	$la_dataacta=$_SESSION["dataacta"];
	$la_datainspector=$_SESSION["datainspector"];
	$la_dataresidente=$_SESSION["dataresidente"];
	$CodigoActa=$la_dataacta["codact"][1];
	$NombreInspector=$la_datainspector["nomsup"][1];
	$NombreResidente=$la_dataresidente["nomsup"][1];
	$CedulaInspector=$la_datainspector["cedsup"][1];
	$CedulaResidente=$la_dataresidente["cedsup"][1];
	$CivResidente=$la_dataresidente["civ"][1];
	$CivInspector=$la_datainspector["civ"][1];
	$FechaEmisionActa=$io_function->uf_convertirfecmostrar($la_dataacta["fecact"][1]);
	$FechaInicioActa=$io_function->uf_convertirfecmostrar($la_dataacta["feciniact"][1]);
	$FechaRecepcionObra=$io_function->uf_convertirfecmostrar($la_dataacta["fecrecact"][1]);
	$MotivoSuspension=$la_dataacta["motact"][1];
	$Observacion=$la_dataacta["obsact"][1];*/
}//end del if de documento "ACTA"
/*elseif($ls_documento=="CONTRATO")
{
	//--------------------------Datos del Contrato------------------------------------------//
	$la_datacontrato=$_SESSION["datacontrato"];
	$la_dataunidadmulta=$_SESSION["dataunidadmulta"];
	$la_dataunidadgarantia=$_SESSION["dataunidadgarantia"];
	$la_datacondiciones=$_SESSION["datacondiciones"];	
	$CodigoContrato=$la_datacontrato["codcon"][1];
	$FechaEmisionContrato=$io_function->uf_convertirfecmostrar($la_datacontrato["feccon"][1]);
	$FechaInicioContrato=$io_function->uf_convertirfecmostrar($la_datacontrato["fecinicon"][1]);
	$FechaFinalizacionContrato=$io_function->uf_convertirfecmostrar($la_datacontrato["fecfincon"][1]);
	$PlazoDuracionContrato=$io_funsob->uf_convertir_decimalentero($la_datacontrato["placon"][1]);
	$ls_unidad=$io_funsob->uf_convertir_letraunidad($la_datacontrato["placonuni"][1]);
	$PlazoDuracionContrato=$PlazoDuracionContrato." ".$ls_unidad;
	$MontoContrato=$io_funsob->uf_convertir_numerocadena($la_datacontrato["monto"][1]);
	$MontoContratoLetras=$io_funsob->convertir($la_datacontrato["monto"][1]);
	$li_pos=strpos($MontoContrato,",");
	$ls_decimales=substr($MontoContrato,($li_pos+1),2);
	$MontoContratoLetras=$MontoContratoLetras." con ".$ls_decimales."/100";	
	$MontoMultaIncumplimiento=$io_funsob->uf_convertir_numerocadena($la_datacontrato["mulcon"][1]);
	$TiempoMultaInclumplimiento=$io_funsob->uf_convertir_decimalentero($la_datacontrato["tiemulcon"][1]);
	$ls_unidad=$la_dataunidadmulta["nomuni"][1];
	$TiempoMultaInclumplimiento=$TiempoMultaInclumplimiento." ".$ls_unidad;
	$LapsoGarantia=$io_funsob->uf_convertir_decimalentero($la_datacontrato["lapgarcon"][1]);
	$ls_unidad=$la_dataunidadgarantia["nomuni"][1];	
	$LapsoGarantia=$LapsoGarantia." ".$ls_unidad;
	$MontoMaximoContrato=$io_funsob->uf_convertir_numerocadena($la_datacontrato["monmaxcon"][1]);
	$PorcentajeMaximoContrato=$io_funsob->uf_convertir_numerocadena($la_datacontrato["pormaxcon"][1]);
	$ObservacionContrato=$la_datacontrato["obscon"][1];	
	//---------------------------------Datos de la Obra-------------------------------------------//
	$la_dataobra=$_SESSION["dataobra"];
	$CodigoObra=$la_dataobra["codobr"][1];
	$DescripcionObra=$la_dataobra["desobr"][1];
	$DireccionObra=$la_dataobra["dirobr"][1];
	$EstadoObra=$la_dataobra["desest"][1];
	$MunicipioObra=$la_dataobra["denmun"][1];
	$ParroquiaObra=$la_dataobra["denpar"][1];
	$ComunidadObra=$la_dataobra["nomcom"][1];
	$Responsable=$la_dataobra["resobr"][1];
	
	//---------------------------------Datos de la Asignacion----------------------------------------//
	$la_dataasignacion=$_SESSION["dataasignacion"];
	$la_datainspector=$_SESSION["datainspector"];
	$la_datacontratista=$_SESSION["datacontratista"];
	$NombreEmpresaInspectora=$la_datainspector["nompro"][1];
	$NombreEmpresaContratista=$la_datacontratista["nompro"][1];
	$PuntoCuenta=$la_dataasignacion["puncueasi"][1];
	$FechaAsignacion=$io_function->uf_convertirfecmostrar($la_dataasignacion["fecasi"][1]);
	$ObservacionAsignacion=$la_dataasignacion["obsasi"][1];
	$MontoParcialAsignacion=$io_funsob->uf_convertir_numerocadena($la_dataasignacion["monparasi"][1]);
	$BaseImponibleAsignacion=$io_funsob->uf_convertir_numerocadena($la_dataasignacion["basimpasi"][1]);
	$MontoTotalAsignacion=$io_funsob->uf_convertir_numerocadena($la_dataasignacion["montotasi"][1]);
		
}//end del if de documento "CONTRATO"*/
//--------------------------Datos Generales--------------------------------------------//
$Dia=date("d");
//$dia=$io_funsob->convertir(700850390);
$Mes=date("m");
$Mes=$io_funsob->uf_convertir_numeromes($Mes);
$Ano=date("Y");
$Fecha=date("d/m/Y");
// instantiate a TBS OOo class
$OOo = new clsTinyButStrongOOo;
// setting the object
$OOo->SetZipBinary('zip');
$OOo->SetUnzipBinary('unzip');
$OOo->SetProcessDir('tmp/');

// create a new openoffice document from the template with an unique id 
$lb_valido=$OOo->NewDocFromTpl($ls_ruta);
	

//merge data with openoffice file named 'content.xml'
$OOo->LoadXmlFromDoc('content.xml');
$OOo->SaveXmlToDoc();

// display
header('Content-type: '.$OOo->GetMimetypeDoc());
header('Content-Length: '.filesize($OOo->GetPathnameDoc()));
$OOo->FlushDoc();
$OOo->RemoveDoc();
?>