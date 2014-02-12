<?PHP
session_start();
?>
<?php

		/* --------------------------------------------------------------------------------------------------------------------
			Clase:	        			sigesp_sob_d_documentos_contrato
		 	Descripción:				Clase que se encarga de generar contratos a partir de plantillas en OpenOffice		
		 	Fecha de Creación:          12/05/2006																			
			Fecha ultima modificación:	20/06/2006																			
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
$ls_ruta=$_GET["ruta"];

//-----------Obteniedo la data a ser enviada al documento OpenOffice------------------------//

	//--------------------------Datos del Contrato------------------------------------------//
	$la_contrato=$io_funsob->uf_decodificar($_GET["contrato"]);
	$la_unidadmulta=$io_funsob->uf_decodificar($_GET["unidadmulta"]);
	$la_unidadgarantia=$io_funsob->uf_decodificar($_GET["unidadgarantia"]);
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
	if($io_funsob->uf_convertir_decimalentero($la_contrato["tiemulcon"])=="0")
	{
		$TiempoMultaInclumplimiento="---";
	}
	else
	{
		$TiempoMultaInclumplimiento=$io_funsob->uf_convertir_decimalentero($la_contrato["tiemulcon"]);
		$ls_unidad=$la_unidadmulta["nomuni"];
		$TiempoMultaInclumplimiento=$TiempoMultaInclumplimiento." ".$ls_unidad;
	}	
	if($io_funsob->uf_convertir_decimalentero($la_contrato["lapgarcon"])=="0")
	{
		$LapsoGarantia="---";
	}
	else
	{
		$LapsoGarantia=$io_funsob->uf_convertir_decimalentero($la_contrato["lapgarcon"]);
		$ls_unidad=$la_unidadgarantia["nomuni"];	
		$LapsoGarantia=$LapsoGarantia." ".$ls_unidad;
	}	
	if($io_funsob->uf_convertir_numerocadena($la_contrato["monmaxcon"])=="0,00")
	{
		$MontoMaximoContrato="---";
		$PorcentajeMaximoContrato="---";
	}
	else
	{
		$MontoMaximoContrato=$io_funsob->uf_convertir_numerocadena($la_contrato["monmaxcon"]);
		$PorcentajeMaximoContrato=$io_funsob->uf_convertir_numerocadena($la_contrato["pormaxcon"]);
	}	
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

	//------------------------Datos de Tablas------------------------------------------//		
	$la_clavescondiciones=array('CodigoCondicionPago'=>'codconpag',
								'FechaCondicionPago'=>'fecconpag',
								'Monto'=>'monto',
								'PorcentajeMontoTotal'=>'porconpag');
	$la_clavesretenciones=array('CodigoRetencion'=>'codded',
								'Retencion'=>'dended',
								'Cuenta'=>'cuenta',
								'Deducible'=>'deducible');
	$la_clavesgarantias=array('CodigoGarantia'=>'codgar',
							  'Garantia'=>'desgar');
							  
	$la_condiciones=$io_funsob->uf_decodificar_arreglosdobles($_GET["condiciones"],$la_clavescondiciones);
	$la_retenciones=$io_funsob->uf_decodificar_arreglosdobles($_GET["retenciones"],$la_clavesretenciones);
	$la_garantias=$io_funsob->uf_decodificar_arreglosdobles($_GET["garantias"],$la_clavesgarantias);	
	$CondicionesPago=$io_funsob->uf_decodificar_encadena($_GET["condiciones"],$la_clavescondiciones);
	$Retenciones=$io_funsob->uf_decodificar_encadena($_GET["retenciones"],$la_clavesretenciones);
	$Garantias=$io_funsob->uf_decodificar_encadena($_GET["garantias"],$la_clavesgarantias);	
	

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
if(is_array($la_condiciones))
	$OOo->MergeBlock('tblCondicionPago',$la_condiciones);
if(is_array($la_retenciones))
	$OOo->MergeBlock('tblRetenciones',$la_retenciones);
if(is_array($la_garantias))
	$OOo->MergeBlock('tblGarantias',$la_garantias);
$OOo->SaveXmlToDoc($ls_ruta);

// display
header('Content-type: '.$OOo->GetMimetypeDoc());
header('Content-Length: '.filesize($OOo->GetPathnameDoc()));
$OOo->FlushDoc();
$OOo->RemoveDoc();
?>
