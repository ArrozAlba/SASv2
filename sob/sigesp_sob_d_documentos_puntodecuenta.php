<?PHP
session_start();
?>
<?php

		/* --------------------------------------------------------------------------------------------------------------------
			Clase:	        			sigesp_sob_d_documentos_puntodecuenta
		 	Descripción:				Script que se encarga de generar Puntos de Cuenta a partir de plantillas en OpenOffice		
		 	Fecha de Creación:          29/06/2006																			
			Fecha ultima modificación:	29/06/2006																			
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
		$la_puntodecuenta=$io_funsob->uf_decodificar($_GET["puntodecuenta"]);
		$ls_codigopresupuestariomonto=$_GET["codigopresupuestariomonto"];
		$ls_cuentamonto=$_GET["cuentamonto"];
		$ls_codigopresupuestarioiva=$_GET["codigopresupuestarioiva"];
		$ls_cuentaiva=$_GET["cuentaiva"];
		$CodigoPuntodeCuenta=$la_puntodecuenta["codpuncue"];
		$Fecha=$io_function->uf_convertirfecmostrar($la_puntodecuenta["fecpuncue"]);
		$PresentadoA=$la_puntodecuenta["despuncue"]	;
		$PresentadoPor=$la_puntodecuenta["rempuncue"]	;
		$Asunto=$la_puntodecuenta["asupuncue"]	;
		$Concepto=$la_puntodecuenta["desobr"]	;		
		$Contratista=$la_puntodecuenta["nompro"]	;
		$RepresentanteContratista=$la_puntodecuenta["nomreppro"];
		$ls_lapso=$io_funsob->uf_convertir_decimalentero ($la_puntodecuenta["lapejepuncue"]);
		$ls_lapsoenletras=$io_funsob->convertir	($ls_lapso);
		$LapsodeEjecucion=$ls_lapsoenletras." (".$ls_lapso.") ".$la_puntodecuenta["nomuni"];
		$MontoNeto=$io_funsob->uf_convertir_numerocadena($la_puntodecuenta["monnetpuncue"]);
		$MontoIva=$io_funsob->uf_convertir_numerocadena($la_puntodecuenta["monivapuncue"]);
		$PorcentajeIva=$io_funsob->uf_convertir_numerocadena($la_puntodecuenta["porivapuncue"])."%";
		$MontoBruto=$io_funsob->uf_convertir_numerocadena($la_puntodecuenta["monbrupuncue"]);
		$MontoAnticipo=$io_funsob->uf_convertir_numerocadena($la_puntodecuenta["monantpuncue"]);
		$PorcentajeAnticipo=$io_funsob->uf_convertir_numerocadena($la_puntodecuenta["porantpuncue"])."%";
		$CodigoPresupuestarioCuentaMonto=$ls_codigopresupuestariomonto;
		$CuentaMonto=$ls_cuentamonto;
		$CodigoPresupuestarioCuentaIva=$ls_codigopresupuestarioiva;
		$CuentaIva=$ls_cuentaiva;

	//--------------------------Datos del Punto de Cuenta------------------------------------------//
	

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
$OOo->SaveXmlToDoc($ls_ruta);

// display
header('Content-type: '.$OOo->GetMimetypeDoc());
header('Content-Length: '.filesize($OOo->GetPathnameDoc()));
$OOo->FlushDoc();
$OOo->RemoveDoc();
?>
