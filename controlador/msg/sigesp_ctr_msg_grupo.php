<?php
session_start();
/********************************************** 	
* @Controlador para la definición de Grupo.
* @versión: 1.0  
* @fecha creación: 07/07/2008
* @autor: Ing. Gusmary Balza
* **************************
* @fecha modificacion  03/09/2008
* @autor  Ing. Yesenia Moreno de Lang
* @descripcion  Se agrego la opción de seguridad
*********************************************/
$sessionvalida = false;
if(array_key_exists('sigesp_codempresa',$_SESSION))
{
	$sessionvalida = true;
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_grupo.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_menu.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_funciones.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_crearreporte.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_validaciones.php');
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
	$objGrupo = new Grupo();		
	pasarDatos(&$objGrupo,$objdata,&$evento);
	$objGrupo->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu = new Menu();		
	$objMenu->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu->codusuario = $_SESSION['sigesp_codusuario'];	
	$objMenu->codsistema = $objdata->sistema;
	$objMenu->nomfisico = $objdata->vista;
	$evento = $objdata->oper;
	switch ($evento)
	{
		case 'incluir':	 
			require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/mcd/sigesp_dao_mcd_generarconsecutivo.php');
			$objConsecutivo = new GenerarConsecutivo();
			$objMenu->campo = 'incluir';
			$accionvalida=$objMenu->verificarUsuario();
			$correcto=(validaciones($objGrupo->codgrupo,'5','novacio')) && (validaciones($objGrupo->nombre,'60','alfanumerico')) && (validaciones($objGrupo->nota,'3000','alfanumerico'));
			if ($accionvalida)
			{
				if ($correcto)
				{
					$objGrupo->verificarCodigo();
					if ($objGrupo->existe==false)
					{
						$objConsecutivo->codempresa = $_SESSION['sigesp_codempresa'];	
						$objConsecutivo->codsistema = $objdata->sistema;
						$objConsecutivo->tabla      = 'msggrupom';
						$objConsecutivo->campo      = 'codgrupo';
						$objConsecutivo->procede    = '';
						$objConsecutivo->longcampo  = '5';
						$objConsecutivo->campoini   = '';
						$objConsecutivo->filtro     = '';
						$objConsecutivo->valor      = '';
						$objConsecutivo->numero     = $objdata->codgrupo;
						$objConsecutivo->verificarNumeroGenerado();
						$objGrupo->codgrupo = $objConsecutivo->numero;
						$objGrupo->incluir();
						$arreglo["mensaje"] = $objGrupo->mensaje.$objConsecutivo->mensaje; 
						$arreglo["valido"]  = $objGrupo->valido;
					}
					else
					{				
						$arreglo["mensaje"] = $objGrupo->mensaje;
						$arreglo["existe"]  = $objGrupo->existe;
					}
				}
				else
				{
					$arreglo["mensaje"] = utf8_encode("Datos Inválidos"); 
					$arreglo["valido"]  = false;
				}
			}
			else
			{
				$arreglo["mensaje"] = utf8_encode("El Usuario no Tiene permiso para esta Acción. Comuníquese con el Administrador del sistema."); 
				$arreglo["valido"]  = false;
			}	
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;
			unset($objConsecutivo);											
		break;
		
		case 'actualizar':
			$objMenu->campo = 'actualizar';
			$accionvalida=$objMenu->verificarUsuario();
			$correcto=(validaciones($objGrupo->nombre,'60','alfanumerico') && (validaciones($objGrupo->nota,'3000','alfanumerico')));
			if ($accionvalida)
			{	
				if ($correcto)
				{
					$objGrupo->verificarCodigo();
					if ($objGrupo->existe==true)
					{
						$objGrupo->modificar();
						$arreglo["mensaje"] = $objGrupo->mensaje;
						$arreglo["valido"]  = $objGrupo->valido;
					}
					else
					{
						$arreglo["mensaje"] = $objGrupo->mensaje;
						$arreglo["valido"]  = $objGrupo->existe;
					}
				}
				else
				{
					$arreglo["mensaje"] = utf8_encode("Datos Inválidos"); 
					$arreglo["valido"]  = false;
				}
			}
			else
			{
				$arreglo["mensaje"] = utf8_encode("El Usuario no Tiene permiso para esta Acción. Comuníquese con el Administrador del sistema."); 
				$arreglo["valido"]  = false;
			}	
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;
		break;
		
		case 'buscarcodigo':
			require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/mcd/sigesp_dao_mcd_generarconsecutivo.php');
			$objConsecutivo = new GenerarConsecutivo();
			$objMenu->campo = 'incluir';
			$accionvalida=$objMenu->verificarUsuario();
			$cad = '';
			if ($accionvalida)
			{	
				$objConsecutivo->codempresa = $_SESSION['sigesp_codempresa'];	
				$objConsecutivo->codsistema = $objdata->sistema;
				$objConsecutivo->tabla      = 'msggrupom';
				$objConsecutivo->campo      = 'codgrupo';
				$objConsecutivo->procede    = '';
				$objConsecutivo->longcampo  = '5';
				$objConsecutivo->campoini   = '';
				$objConsecutivo->filtro     = '';
				$objConsecutivo->valor      = '';
				$cad = $objConsecutivo->generarNumeroNuevo();
			}
			unset($objConsecutivo);											
			echo "|{$cad}";
		break;	
				
		case 'catalogo':
			$objMenu->campo = 'leer';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$datos = $objGrupo->leer();
				if (count($datos)>0)
				{
					$varJson=generarJson($datos);
					echo $varJson;				
				}
				else 
				{	
					echo '';
				}
			}
			else
			{
				echo '';
			}
		break;		
			
		case 'eliminar':
			$objMenu->campo = 'eliminar';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objGrupo->buscarUsuarioGrupo();
				if ($objGrupo->existe==false)
				{
					$objGrupo->buscarPerfilGrupo();
					if ($objGrupo->existe==false)
					{
						$objGrupo->codempresa = $_SESSION['sigesp_codempresa'];	
						$objGrupo->eliminar();
						$arreglo["mensaje"] = $objGrupo->mensaje;
						$arreglo["valido"]  = $objGrupo->valido;
					}
					else
					{
						$arreglo["mensaje"] = $objGrupo->mensaje;
						$arreglo["existe"]  = $objGrupo->existe;
					}
				}
				else 
				{
					$arreglo["mensaje"] = $objGrupo->mensaje;
					$arreglo["existe"]  = $objGrupo->existe;
				}
			}	
			else
			{
				$arreglo["mensaje"] = utf8_encode("El Usuario no Tiene permiso para esta Acción. Comuníquese con el Administrador del sistema."); 
				$arreglo["valido"]  = false;
			}	
			$respuesta  = array('raiz'=>$arreglo);
			$respuesta  = json_encode($respuesta);
			echo $respuesta;
		break;			
					
		case 'buscarcadena':	
			$datos = $objGrupo->leer();
			$objSon = generarJson($datos);
			echo $objSon;
		break;
		
		case 'reporteficha':
			$objMenu->campo = 'imprimir';
			$accionvalida=$objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objReporte = new crearReporte();
				$objGrupo->cadena = $objdata->codgrupo; 
				$data = $objGrupo->leer();  
				if (count($data)>0)
				{
					$objReporte->crearXml('ficha_grupo',$data);
					$objReporte->nomRep="ficha_grupo";
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
	unset($objGrupo);
}	
?>
