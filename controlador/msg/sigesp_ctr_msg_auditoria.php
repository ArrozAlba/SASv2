<?php
session_start(); 
/*********************************************	
* @Controlador para reporte de auditoria.
* @versión: 1.0    
* @fecha creación: 28/08/2008
* @autor: Ing. Gusmary Balza
* *****************************
* @fecha modificacion  
* @autor  
* @descripcion 
**********************************************/
$sessionvalida = false;
if(array_key_exists('sigesp_codempresa',$_SESSION))
{
	$sessionvalida = true;
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_menu.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_funciones.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_sistema.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_evento.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_crearreporte.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_perfilevento.php');
}
else
{
	$arreglo["mensaje"] = utf8_encode("Su Sessión ha Expirado. Ingrese nuevamente al sistema."); 
	$arreglo["valido"]  = false;
	$respuesta  = array('raiz'=>$arreglo);
	$respuesta  = json_encode($respuesta);
	echo $respuesta;
}
if (($_POST['objdata']) && ($sessionvalida))	
{	
	$objdata = str_replace("\\","",$_POST['objdata']);	
	$objdata = json_decode($objdata,false);	
	$objSistema      = new Sistema();
	$objEvento       = new Evento();
	$objPerfilEvento = new PerfilEvento();
//	pasarDatos(&$objPerfilEvento,$objdata,&$evento);
	$objPerfilEvento->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu = new Menu();		
	$objMenu->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu->codusuario = $_SESSION['sigesp_codusuario'];	
	$objMenu->codsistema = $objdata->sistema;
	$objMenu->nomfisico = $objdata->vista;
	$evento = $objdata->operacion;
	switch ($evento)
	{
		case 'obtenerSistema':
			$objSistema->codempresa = $_SESSION['sigesp_codempresa'];
			$datos = $objSistema->leer();
			if (count($datos)>0)
			{
				$varJson=generarJson($datos);
				echo $varJson;				
			}
			else 
			{	
				echo '';
			}
		break;		
		
		case 'obtenerEvento':
			$objEvento->codempresa = $_SESSION['sigesp_codempresa'];
			$datos = $objEvento->leer();
			if (count($datos)>0)
			{
				$varJson=generarJson($datos);
				echo $varJson;				
			}
			else 
			{	
				echo '';
			}
		break;
		
		case 'auditoria':
			$objMenu->campo = 'imprimir';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objReporte = new crearReporte();
				$objPerfilEvento->codusuario = $objdata->codusuario;
				$objPerfilEvento->codgrupo   = $objdata->codgrupo;
				$objPerfilEvento->codsistema = $objdata->codsistema;
				$objPerfilEvento->evento     = $objdata->evento;
				$objPerfilEvento->fecha      = convertirFechaBd($objdata->fecha);
				$data = $objPerfilEvento->leerReporte();
				if (count($data)>0)
				{
					$objReporte->crearXml2('auditoria',$data);
					$objReporte->nomRep = "auditoria";
					echo $objReporte->mostrarReporte();	
				}
				else
				{
					echo '';
				}
				unset($objReporte);
			}
			else
			{
				echo '';
			}							
				
		
	}
	unset($objMenu);
	unset($objSistema);
	unset($objEvento);
	unset($objPerfilEvento);
}
?>