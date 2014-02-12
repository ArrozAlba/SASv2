<?php
session_start(); 
/************************************************************** 	
* @Controlador para proceso de asignación de usuarios a grupo.
* @versión: 1.0      
* @autor: Ing. Gusmary Balza
* @fecha creación: 15/08/2008
* *****************************
* @fecha modificacion  
* @autor  
* @descripcion  
****************************************************************/
$sessionvalida = false;
if(array_key_exists('sigesp_codempresa',$_SESSION))
{
	$sessionvalida = true;
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_grupo.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_usuariogrupo.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_usuario.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/msg/sigesp_dao_msg_menu.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_funciones.php');
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
	$objUsuarioGrupo = new Usuariogrupo();	
	pasarDatos(&$objUsuarioGrupo,$objdata,&$evento);
	$objUsuarioGrupo->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu = new Menu();		
	$objMenu->codempresa = $_SESSION['sigesp_codempresa'];	
	$objMenu->codusuario = $_SESSION['sigesp_codusuario'];	
	$objMenu->codsistema = $objdata->sistema;
	$objMenu->nomfisico = $objdata->vista;
	$evento = $objdata->oper;
	if ($objdata->usuarios)
	{
		for ($j=0; $j<count($objdata->usuarios); $j++)
		{
			$objUsuarioGrupo->usuario[$j] = new Usuariogrupo();
			pasarDatos(&$objUsuarioGrupo->usuario[$j],$objdata->usuarios[$j]);	
		}
	}

	switch ($evento)
	{
		case 'incluir':	
			$objMenu->campo = 'incluir';
			$accionvalida = $objMenu->verificarUsuario();
			if ($accionvalida)
			{
				$objUsuarioGrupo->incluirTodos();
				$arreglo["mensaje"] = $objUsuarioGrupo->mensaje;
				$arreglo["valido"]  = $objUsuarioGrupo->valido;
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
			
		case 'catalogo':
			$objMenu->campo = 'leer';
			$accionvalida = $objMenu->verificarUsuario();
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
		
		case 'catalogodetalle': //revisar 
			$objUsuarioGrupo->codempresa = $_SESSION['sigesp_codempresa'];	
			$objSonUsu = generarJson($objUsuarioGrupo->obtenerUsuarios());
			/*if (is_null($objSonUsu.raiz)
			{
				echo "no existen usuarios asociados";
			}
			else
			{*/
				echo $objSonUsu;
			//}
		break;	
		
		case 'eliminardetalle':
			$objUsuarioGrupo->codempresa = $_SESSION['sigesp_codempresa'];
			$objUsuarioGrupo->codusuario = $objdata->codusuario;
			$objSonUsu = generarJson($objUsuarioGrupo->eliminarUsuarios());
			echo $objSonUsu;
		break;			
					
		case 'buscarcadena':	
			$datos = $objUsuarioGrupo->leer();
			$objSon = generarJson($datos);
			echo $objSon;
		break;
		
	}
	unset($objMenu);
	unset($objUsuarioGrupo);
}	

?>