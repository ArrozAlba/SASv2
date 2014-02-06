<?php
session_start();
/***********************************************************************************
* @Clase para el inicio de Sessión del sistema
* @fecha creación: 07/07/2008
* @autor: Ing. Gusmary Balza
* * **************************
* @fecha modificacion  08/10/2008
* @autor   Ing. Yesenia Moreno de Lang
* @descripcion Se agregaron las variable de Sessión session_activa y tiempo_session
***********************************************************************************/

require_once('../../base/librerias/php/general/sigesp_lib_funciones.php');
require_once('../../base/librerias/php/general/sigesp_lib_validaciones.php');
if ($_POST['objdata'])	
{	
	$objdata = str_replace('\\','',$_POST['objdata']);
	$objdata = json_decode($objdata,false);	
	$ruta = '../../base/xml/';
	$archivoconfig = 'sigesp_xml_configuracion.xml';
	switch ($objdata->operacion)
	{
		case 'obtenerbd':
			$documentoxml = abrirArchivoXml($ruta,$archivoconfig);
			if ($documentoxml != null)
			{
				$datos = array();
				obtenerConexionbd($documentoxml,$datos);
				$datos  = array('raiz'=>$datos);
				$textJson = json_encode($datos);
				echo $textJson;
			}
		break;
			
		case 'obtenerempresa':
			$documentoxml = abrirArchivoXml($ruta,$archivoconfig);	
			if (!is_null($documentoxml))
			{
				$basededatos = obtenerEmpresa($documentoxml,$objdata->basedatos);
				require_once('../../modelo/cfg/sigesp_dao_cfg_empresa.php');
				$objEmpresa = new Empresa();
				if ($basededatos !='')
				{
					$datos = $objEmpresa->filtrarEmpresas();
					if ($datos->EOF)
					{
						/*require_once('../../modelo/cfg/sigesp_dao_cfg_cargar_datos.php');
						$objCarga = new CargarDatos();						
						$objCarga->obtenerDatos();
						$datos = array();
						unset($objCarga);*/
						$_SESSION['la_empresa']['codemp'] = '0001';
						$_SESSION['sigesp_sitioweb'] = 'sigesp_v2';
						$_SESSION['tiempo_session'] = 600;
					}
					else
					{
						$_SESSION['la_empresa']['codemp'] = $datos->fields['codemp'];
						$_SESSION['la_empresa']['nombre'] = $datos->fields['nombre'];
						$_SESSION['la_empresa']['titulo'] = $datos->fields['titulo'];
						$_SESSION['la_empresa']['sigemp'] = $datos->fields['sigemp'];
						$_SESSION['la_empresa']['faxemp'] = $datos->fields['faxemp'];
						$_SESSION['la_empresa']['email'] = $datos->fields['email'];
						$_SESSION['la_empresa']['ingreso'] = $datos->fields['ingreso'];
						$_SESSION['la_empresa']['gasto'] = $datos->fields['gasto'];
						$_SESSION['la_empresa']['activo'] = $datos->fields['activo'];
						$_SESSION['la_empresa']['pasivo'] = $datos->fields['pasivo'];
						$_SESSION['la_empresa']['resultado'] = $datos->fields['resultado'];
						$_SESSION['la_empresa']['capital'] = $datos->fields['capital'];
						$_SESSION['la_empresa']['c_resultad'] = $datos->fields['c_resultad'];
						$_SESSION['la_empresa']['c_resultan'] = $datos->fields['c_resultan'];
						$_SESSION['la_empresa']['orden_d'] = $datos->fields['orden_d'];
						$_SESSION['la_empresa']['orden_h'] = $datos->fields['orden_h'];
						$_SESSION['la_empresa']['soc_gastos'] = $datos->fields['soc_gastos'];
						$_SESSION['la_empresa']['soc_servic'] = $datos->fields['soc_servic'];
						$_SESSION['la_empresa']['orden_h'] = $datos->fields['orden_h'];
						$_SESSION['la_empresa']['activo_h'] = $datos->fields['activo_h'];
						$_SESSION['la_empresa']['pasivo_h'] = $datos->fields['pasivo_h'];
						$_SESSION['la_empresa']['resultado_h'] = $datos->fields['resultado_h'];
						$_SESSION['la_empresa']['ingreso_f'] = $datos->fields['ingreso_f'];
						$_SESSION['la_empresa']['gasto_f'] = $datos->fields['gasto_f'];
						$_SESSION['la_empresa']['ingreso_p'] = $datos->fields['ingreso_p'];
						$_SESSION['la_empresa']['direccion'] = $datos->fields['direccion'];
						$_SESSION['la_empresa']['telemp'] = $datos->fields['telemp'];
						$_SESSION['la_empresa']['periodo'] = date('Y-m-d',strtotime($datos->fields['periodo']));
						$_SESSION['la_empresa']['vali_nivel'] = $datos->fields['vali_nivel'];
						$_SESSION['la_empresa']['esttipcont'] = $datos->fields['esttipcont'];
						$_SESSION['la_empresa']['formpre'] = $datos->fields['formpre'];
						$_SESSION['la_empresa']['formcont'] = $datos->fields['formcont'];
						$_SESSION['la_empresa']['formplan'] = $datos->fields['formplan'];
						$_SESSION['la_empresa']['formspi'] = $datos->fields['formspi'];
						$_SESSION['la_empresa']['numniv'] = $datos->fields['numniv'];						
						$_SESSION['la_empresa']['estmodest'] = $datos->fields['estmodest'];						
						$_SESSION['la_empresa']['nomestpro1'] = $datos->fields['nomestpro1'];
						$_SESSION['la_empresa']['nomestpro2'] = $datos->fields['nomestpro2'];
						$_SESSION['la_empresa']['nomestpro3'] = $datos->fields['nomestpro3'];
						$_SESSION['la_empresa']['nomestpro4'] = $datos->fields['nomestpro4'];
						$_SESSION['la_empresa']['nomestpro5'] = $datos->fields['nomestpro5'];
						$_SESSION['la_empresa']['rifemp'] = $datos->fields['rifemp'];
						$_SESSION['la_empresa']['loncodestpro1'] = $datos->fields['loncodestpro1'];
						$_SESSION['la_empresa']['loncodestpro2'] = $datos->fields['loncodestpro2'];
						$_SESSION['la_empresa']['loncodestpro3'] = $datos->fields['loncodestpro3'];
						$_SESSION['la_empresa']['loncodestpro4'] = $datos->fields['loncodestpro4'];
						$_SESSION['la_empresa']['loncodestpro5'] = $datos->fields['loncodestpro5'];
						$_SESSION['la_empresa']['estvaldis'] = $datos->fields['estvaldis'];
						$_SESSION['la_empresa']['estintcred'] = $datos->fields['estintcred'];
						$_SESSION['la_empresa']['estciespg'] = $datos->fields['estciespg'];
						$_SESSION['la_empresa']['estciespi'] = $datos->fields['estciespi'];
						$_SESSION['la_empresa']['estciescg'] = $datos->fields['estciescg'];
						$_SESSION['la_empresa']['confinstr'] = $datos->fields['confinstr'];
						$_SESSION['la_empresa']['gasto_p'] = $datos->fields['gasto_p'];
						$_SESSION['la_empresa']['estvaltra'] = $datos->fields['estvaltra'];
						$_SESSION['la_empresa']['nitemp'] = $datos->fields['nitemp'];
						$_SESSION['la_empresa']['estemp'] = $datos->fields['estemp'];
						$_SESSION['la_empresa']['ciuemp'] = $datos->fields['ciuemp'];
						$_SESSION['la_empresa']['zonpos'] = $datos->fields['zonpos'];
						$_SESSION['la_empresa']['estmodape'] = $datos->fields['estmodape'];
						$_SESSION['la_empresa']['estdesiva'] = $datos->fields['estdesiva'];
						$_SESSION['la_empresa']['estprecom'] = $datos->fields['estprecom'];
						$_SESSION['la_empresa']['estmodsepsoc'] = $datos->fields['estmodsepsoc'];
						$_SESSION['la_empresa']['codorgsig'] = $datos->fields['codorgsig'];
						$_SESSION['la_empresa']['socbieser'] = $datos->fields['socbieser'];
						$_SESSION['la_empresa']['estmodest'] = $datos->fields['estmodest'];
						$_SESSION['la_empresa']['salinipro'] = $datos->fields['salinipro'];
						$_SESSION['la_empresa']['salinieje'] = $datos->fields['salinieje'];
						$_SESSION['la_empresa']['numordcom'] = $datos->fields['numordcom'];
						$_SESSION['la_empresa']['numordser'] = $datos->fields['numordser'];
						$_SESSION['la_empresa']['numsolpag'] = $datos->fields['numsolpag'];
						$_SESSION['la_empresa']['nomorgads'] = $datos->fields['nomorgads'];
						$_SESSION['la_empresa']['numlicemp'] = $datos->fields['numlicemp'];
						$_SESSION['la_empresa']['modageret'] = $datos->fields['modageret'];
						$_SESSION['la_empresa']['nomres'] = $datos->fields['nomres'];
						$_SESSION['la_empresa']['concomiva'] = $datos->fields['concomiva'];
						$_SESSION['la_empresa']['cedben'] = $datos->fields['cedben'];
						$_SESSION['la_empresa']['nomben'] = $datos->fields['nomben'];
						$_SESSION['la_empresa']['scctaben'] = $datos->fields['scctaben'];
						$_SESSION['la_empresa']['estmodiva'] = $datos->fields['estmodiva'];
						$_SESSION['la_empresa']['activo_t'] = $datos->fields['activo_t'];
						$_SESSION['la_empresa']['pasivo_t'] = $datos->fields['pasivo_t'];
						$_SESSION['la_empresa']['resultado_t'] = $datos->fields['resultado_t'];
						$_SESSION['la_empresa']['c_financiera'] = $datos->fields['c_financiera'];
						$_SESSION['la_empresa']['c_fiscal'] = $datos->fields['c_fiscal'];
						$_SESSION['la_empresa']['diacadche'] = $datos->fields['diacadche'];
						$_SESSION['la_empresa']['codasiona'] = $datos->fields['codasiona'];
						$_SESSION['la_empresa']['conrecdoc'] = $datos->fields['conrecdoc'];
						$_SESSION['la_empresa']['estvaldis'] = $datos->fields['estvaldis'];
						$_SESSION['la_empresa']['nroivss'] = $datos->fields['nroivss'];
						$_SESSION['la_empresa']['nomrep'] = $datos->fields['nomrep'];
						$_SESSION['la_empresa']['cedrep'] = $datos->fields['cedrep'];
						$_SESSION['la_empresa']['telfrep'] = $datos->fields['telfrep'];
						$_SESSION['la_empresa']['cargorep'] = $datos->fields['cargorep'];
						$_SESSION['la_empresa']['estretiva'] = $datos->fields['estretiva'];
						$_SESSION['la_empresa']['clactacon'] = $datos->fields['clactacon'];
						$_SESSION['la_empresa']['estempcon'] = $datos->fields['estempcon'];
						$_SESSION['la_empresa']['codaltemp'] = $datos->fields['codaltemp'];
						$_SESSION['la_empresa']['basdatcon'] = $datos->fields['basdatcon'];
						$_SESSION['la_empresa']['estcamemp'] = $datos->fields['estcamemp'];
						$_SESSION['la_empresa']['estparsindis'] = $datos->fields['estparsindis'];
						$_SESSION['la_empresa']['basdatcmp'] = $datos->fields['basdatcmp'];
						$_SESSION['la_empresa']['estciespg'] = $datos->fields['estciespg'];
						$_SESSION['la_empresa']['estciespi'] = $datos->fields['estciespi'];
						$_SESSION['la_empresa']['confinstr'] = $datos->fields['confinstr'];
						$_SESSION['la_empresa']['estintcred'] = $datos->fields['estintcred'];
						$_SESSION['la_empresa']['estciescg'] = $datos->fields['estciescg'];
						$_SESSION['la_empresa']['estvalspg'] = $datos->fields['estvalspg'];
						$_SESSION['la_empresa']['ctaspgrec'] = $datos->fields['ctaspgrec'];
						$_SESSION['la_empresa']['ctaspgced'] = $datos->fields['ctaspgced'];
						$_SESSION['la_empresa']['estmodpartsep'] = $datos->fields['estmodpartsep'];
						$_SESSION['la_empresa']['estmodpartsoc'] = $datos->fields['estmodpartsoc'];
						$_SESSION['la_empresa']['estmanant'] = $datos->fields['estmanant'];
						$_SESSION['la_empresa']['estpreing'] = $datos->fields['estpreing'];
						$_SESSION['la_empresa']['concommun'] = $datos->fields['concommun'];
						$_SESSION['la_empresa']['confiva'] = $datos->fields['confiva'];
						$_SESSION['la_empresa']['confi_ch'] = $datos->fields['confi_ch'];
						$_SESSION['la_empresa']['casconmov'] = $datos->fields['casconmov'];
						$_SESSION['sigesp_sitioweb'] = $datos->fields['dirvirtual'];
						$_SESSION['tiempo_session'] = $datos->fields['tiesesact'];
						$arJson = generarJson($datos);
						echo $arJson;
					}
					/*else
					{					
						$arreglo['valido']  = $objEmpresa->valido;
						$arreglo['mensaje'] = $objEmpresa->mensaje;
						$textJso  = array('raiz'=>$arreglo);
						$textJson = json_encode($textJso);
						echo $textJson;
					}*/
					$datos->close();
				}
				else
				{
					$arreglo['valido']  = $objEmpresa->valido;
					$arreglo['mensaje'] = $objEmpresa->mensaje;
					$textJso  = array('raiz'=>$arreglo);
					$textJson = json_encode($textJso);
					echo $textJson;
				}
				unset($objEmpresa);
			}
			else
			{
				$arreglo['valido']  = true;
				$arreglo['mensaje'] = 'Error al abrir el archivo de configuración';
				$textJso  = array('raiz'=>$arreglo);
				$textJson = json_encode($textJso);
				echo $textJson;
			}		
		break;
			
		case 'iniciarsesion':					
			require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_usuario.php');
			$objUsuario = new Usuario();
			$objUsuario->codemp = $_SESSION['la_empresa']['codemp'];
			$objUsuario->codusu = $objdata->codusuario;
			$objUsuario->pwdusu   = $objdata->pasusuario;			
			$objUsuario->verificarUsuario();
			$arreglo['valido']  = $objUsuario->valido;
			$arreglo['mensaje'] = $objUsuario->mensaje;
			$_SESSION['session_activa']=time();
			$textJso  = array('raiz'=>$arreglo);
			$textJson = json_encode($textJso);
			echo $textJson;
			unset($objUsuario);		
		break;

		case 'cambiarbd':
			require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_usuario.php');
			$objUsuario = new Usuario();
			$objUsuario->codemp = $_SESSION['la_empresa']['codemp'];
			$objUsuario->codusu = $_SESSION['la_logusr'];
			$objUsuario->pwdusu = $_SESSION['la_pasusu'];			
			$objUsuario->verificarUsuario();
			$arreglo['valido']  = $objUsuario->valido;
			$arreglo['mensaje'] = $objUsuario->mensaje;
			$_SESSION['session_activa']=time();
			$textJso  = array('raiz'=>$arreglo);
			$textJson = json_encode($textJso);
			echo $textJson;
			unset($objUsuario);	
		break;	
	}
}	
?>
