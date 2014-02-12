<?php
/***********************************************************************************
* @Clase para Manejar  para la definición de Derechos Usuarios.
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
* **************************
* @fecha modificacion  03/09/2008
* @autor  Ing. Yesenia Moreno de Lang
* @descripcion  Se agrego la opción de seguridad
***********************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once('sigesp_dao_sss_registroeventos.php');
require_once('sigesp_dao_sss_registrofallas.php');

class DerechosUsuario extends ADOdb_Active_Record
{
	var $_table = 'sss_derechos_usuarios';
	public $valido = true;
	public $seguridad = true;
	public $mensaje;
	public $existe    = true;
	public $cadena;
	public $criterio;
	public $codsis;
	public $nomfisico;
	public $derechos;
	public $admin = array();
	public $servidor;
	public $usuario;
	public $clave;
	public $basedatos;
	public $gestor;
	public $tipoconexionbd = 'DEFECTO';
	
/***********************************************************************************
* @Función para seleccionar con que conexion a Base de Datos se va a trabajar
* @parametros: 
* @retorno:
* @fecha de creación: 06/11/2008.
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	public function seleccionarConexion(&$conexionbd)
	{
		global $conexionbd;
		
		if ($this->tipoconexionbd != 'DEFECTO')
		{
			$conexionbd = conectarBD($this->servidor, $this->usuario, $this->clave, $this->basedatos, $this->gestor);
		}
	}
	
	
/***********************************************************************************
* @Función que Inserta los permisos a todos los sistemas
* @parametros: 
* @retorno: 
* @fecha de creación: 11/09/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	public function insertarPermisosGlobales() 
	{
		global $conexionbd;
		$this->mensaje = 'Incluyo el perfil para el usuario '.$this->codusu.' en el sistema '.$this->codsis;
		$conexionbd->StartTrans();
		//$conexionbd->debug = 1;
		try
		{			
			$consulta = " INSERT INTO {$this->_table} 					".
						" 	(codemp,codusu,codsis,codmenu, 				".
						"	codintper,visible,enabled,leer, 			".
			            " 	incluir,cambiar,eliminar,imprimir,			".
			            "	anular,ejecutar,administrativo,				".
			            " 	ayuda,cancelar,enviarcorreo,descargar) 		".
						" 	SELECT '{$this->codemp}','{$this->codusu}', ".
						" 	codsis,codmenu,'$this->codintper',			".
						"	visible,enabled,leer,incluir, 				".
						" 	cambiar,eliminar,imprimir,anular,		 	".
						" 	ejecutar,administrativo,ayuda,			 	".
						" 	cancelar,enviarcorreo,descargar		 		".
						" FROM sss_sistemas_ventanas 					".
						" WHERE codsis='{$this->codsis}' 				".
						" AND hijo=0									". 
						" AND codmenu NOT IN 
							(SELECT codmenu 
								FROM {$this->_table}
								WHERE codemp='{$this->codemp}' 
								AND codusu='{$this->codusu}'
								AND codsis='{$this->codsis}')			";	
			
			$result = $conexionbd->Execute($consulta);
			
			$this->criterio[0]['operador'] = "WHERE";
			$this->criterio[0]['criterio'] = "codemp";
			$this->criterio[0]['condicion'] = "=";
			$this->criterio[0]['valor'] = "'".$this->codemp."'";
			
			$this->criterio[1]['operador'] = "AND";
			$this->criterio[1]['criterio'] = "codusu";
			$this->criterio[1]['condicion'] = "=";
			$this->criterio[1]['valor'] = "'".$this->codusu."'";
			
			$this->criterio[2]['operador'] = "AND";
			$this->criterio[2]['criterio'] = "codsis";
			$this->criterio[2]['condicion'] = "=";
			$this->criterio[2]['valor'] = "'".$this->codsis."'";
			
			$this->criterio[3]['operador'] = "AND";
			$this->criterio[3]['criterio'] = "codintper";
			$this->criterio[3]['condicion'] = "=";
			$this->criterio[3]['valor'] = "'".$this->codintper."'";
			
			//$this->modificar();
			//$this->modificarTodos();
		}	
		catch (exception $e) 
	   	{
			$this->valido  = false;				
			$this->mensaje='Error al Incluir el Perfil para todos los menus para el Usuario '.$this->codusu.' en el sistema '.$this->codsis.' '.$conexionbd->ErrorMsg();
		}  
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('INSERTAR',$this->valido);
	}
	
	
/***********************************************************************************
* @Función que busca las opciones de menu
* @parametros: 
* @retorno: 
* @fecha de creación: 11/09/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function obtenerEscritorioUsuario()
	{
		global $conexionbd;
		try
		{
			$consulta = "SELECT sss_sistemas.codsis, MAX(sss_sistemas.nomsis) AS nomsis, ".
						"       count(sss_usuarios.codusu) As Total, MAX(sss_sistemas.tipsis) AS tipsis, ".
						"		MAX(sss_sistemas.imgsis) AS imgsis, MAX(sss_sistemas.accsis) AS accsis, ".
						"       MAX(sss_sistemas.ordsis) AS ordsis, 1 as valido ".
						"  FROM $this->_table ".
						" INNER JOIN sss_sistemas ".
						"    ON sss_sistemas.estsis = '1' ".
						"   AND $this->_table.codsis = sss_sistemas.codsis ".
						" INNER JOIN sss_usuarios ".
						"    ON sss_usuarios.estatus=1 ".
						"   AND $this->_table.codemp = sss_usuarios.codemp ".
						"   AND $this->_table.codusu = sss_usuarios.codusu ".
						" WHERE $this->_table.codemp = '$this->codemp' ". 
						"   AND $this->_table.codusu = '$this->codusu' ".
						"   AND $this->_table.enabled = '1' ".
						" GROUP BY sss_sistemas.codsis   ".
						" ORDER BY tipsis, ordsis  ";
			$result = $conexionbd->Execute($consulta); 
			return $result;
		}
		catch (exception $e) 
	   	{
			$this->valido  = false;				
			$this->mensaje='Error al consultar el escritorio del usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
		}	
	}

	
/***********************************************************************************
* @Función que busca el sistema y el usuario válido
* @parametros: 
* @retorno: 
* @fecha de creación: 07/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function obtenerSistemaUsuario()
	{
		global $conexionbd;
		try
		{
			$consultafecha = $conexionbd->OffsetDate(0, $conexionbd->sysTimeStamp);
			$consultafecha = $conexionbd->SQLDate('d/m/Y h:i A', $consultafecha);
			$consultainactividad = (time()- $_SESSION['session_activa']) + 10;
			$consultainactividad = date('i', $consultainactividad);
			
			$consulta = "SELECT sss_sistemas.nomsis, sss_usuarios.nomusu, sss_usuarios.apeusu, ".
						"       (".$consultafecha.") AS fecha, (".$consultainactividad.") AS inactivo, ".
						"		1 as valido".
						"  FROM $this->_table ".
						" INNER JOIN sss_sistemas ".
						"    ON $this->_table.codsis = sss_sistemas.codsis ".
						" INNER JOIN sss_usuarios ".
						"    on sss_usuarios.actusu=1 ".
						"   AND $this->_table.codemp = sss_usuarios.codemp ".
						"   AND $this->_table.codusu = sss_usuarios.codusu ".
						" WHERE $this->_table.codemp = '$this->codemp' ". 
						"   AND $this->_table.codusu = '$this->codusu' ".
						"   AND $this->_table.codsis = '$this->codsis' ";
			$result = $conexionbd->SelectLimit($consulta,1); 		
			return $result;
		}
		catch (exception $e) 
	   	{
			$this->valido  = false;				
			$this->mensaje='Error al consultar el sistema '.$this->codsis.' y el usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
		}		
	}

	
/****************************************************************************************
* @Función que incluye un perfil
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
*************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
********************************************************************************************/	
	function incluir()
	{
		global $conexionbd;
		//$conexionbd->debug =1;
		
		$this->seleccionarConexion(&$conexionbd);
		
		$this->mensaje='Incluyo el perfil de menu '.$this->codmenu.' para el usuario '.$this->codusu.' en el sistema '.$this->codsis;
		$conexionbd->StartTrans();
		//try 
		//{ 		
			$consulta = " INSERT INTO {$this->_table} ".
						" 	(codemp,codusu,codsis,codmenu, ".
						" 	codintper,visible,enabled,leer,".
						"	incluir,cambiar,eliminar, ".
						" 	imprimir,anular,ejecutar, ".
						"	administrativo,ayuda,cancelar, ".
						" 	enviarcorreo,descargar) ".
						" SELECT '{$this->codemp}','{$this->codusu}',codsis,codmenu, ".
						" 	'{$this->codintper}',{$this->visible},1,{$this->leer}, ".
						" 	{$this->incluir},{$this->cambiar},{$this->eliminar}, ".
						" 	{$this->imprimir},{$this->anular},{$this->ejecutar}, ".
						" 	{$this->administrativo},{$this->ayuda},{$this->cancelar}, ".
						" 	{$this->enviarcorreo} ".
						" ,{$this->descargar} ".
						" FROM sss_sistemas_ventanas ".
						" WHERE codsis='{$this->codsis}' ".
						" AND codmenu={$this->codmenu} ".
						" AND hijo=0 ".
						" AND codmenu NOT IN 
								(SELECT codmenu FROM {$this->_table}
									WHERE codemp='{$this->codemp}' 
									AND codusu='{$this->codusu}'
									AND codsis='{$this->codsis}')";
									
			$result = $conexionbd->Execute($consulta);

			$this->nomfisico = $this->nomfisico;
			$this->criterio[0]['operador'] = "WHERE";
			$this->criterio[0]['criterio'] = "codemp";
			$this->criterio[0]['condicion'] = "=";
			$this->criterio[0]['valor'] = "'".$this->codemp."'";
			
			$this->criterio[1]['operador'] = "AND";
			$this->criterio[1]['criterio'] = "codusu";
			$this->criterio[1]['condicion'] = "=";
			$this->criterio[1]['valor'] = "'".$this->codusu."'";
			
			$this->criterio[2]['operador'] = "AND";
			$this->criterio[2]['criterio'] = "codsis";
			$this->criterio[2]['condicion'] = "=";
			$this->criterio[2]['valor'] = "'".$this->codsis."'";
			
			$this->criterio[3]['operador'] = "AND";
			$this->criterio[3]['criterio'] = "codintper";
			$this->criterio[3]['condicion'] = "=";
			$this->criterio[3]['valor'] = "'".$this->codintper."'";
			
			$this->criterio[4]['operador'] = "AND";
			$this->criterio[4]['criterio'] = "codmenu";
			$this->criterio[4]['condicion'] = "=";
			$this->criterio[4]['valor'] = $this->codmenu;
			
			$this->modificar();
	/*	}
		catch (exception $e) 
	   	{
			$this->valido  = false;				
			$this->mensaje = 'Error al incluir el perfil de menu '.$this->codmenu.' para el Usuario '.$this->codusu.' en el sistema '.$this->codsis.' '.$conexionbd->ErrorMsg();	
		}  */
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('INSERTAR',$this->valido);
	}
	
	
/*****************************************************************************************
* @Función que modifica un perfil de una funcionalidad
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
**************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***************************************************************************************/		
	public function modificar() 
	{
		global $conexionbd;
		$this->seleccionarConexion(&$conexionbd);
		//$conexionbd->debug = 1;
		$this->mensaje = 'Modifico el perfil de menu '.$this->codmenu.' para el usuario '.$this->codusu.' en el sistema '.$this->codsis;
		$conexionbd->StartTrans();
		try 
		{
			$consulta = " UPDATE {$this->_table} SET visible={$this->visible},enabled=1,".
						" 	leer={$this->leer},incluir={$this->incluir}, ".
						" 	cambiar={$this->cambiar},eliminar={$this->eliminar}, ".
						" 	imprimir={$this->imprimir},administrativo={$this->administrativo},".
						" 	anular={$this->anular},ejecutar={$this->ejecutar},".
						" 	ayuda={$this->ayuda},cancelar={$this->cancelar}, ".
						"   enviarcorreo={$this->enviarcorreo} ".
						"	,descargar={$this->descargar} ";
			$cadena=" ";
            $total = count($this->criterio);
            for ($contador = 0; $contador < $total; $contador++)
			{
            	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 			               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
            }
            $consulta.= $cadena;
           	$result = $conexionbd->Execute($consulta);
		}
		catch (exception $e) 
		{
			$this->mensaje = 'Error al modificar el perfil de menu '.$this->codmenu.' para el Usuario '.$this->codusu.' en el sistema '.$this->codsis.''.$conexionbd->ErrorMsg();	
			$this->valido = false;
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('MODIFICAR',$this->valido);
	}
		
	
/************************************************************************************
* @Función que verifica el perfil de una funcionalidad
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
*************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************************************/		
	public function verificarPerfilUno()
	{
		global $conexionbd;
		
		//$conexionbd->debug = 1;
		$this->seleccionarConexion(&$conexionbd);
	//	try
	//	{
			$consulta = " SELECT codemp,codsis,codusu,codmenu,codintper,visible,leer, ".
						" 	incluir,cambiar,eliminar,imprimir,anular,ejecutar,administrativo, ".
						" 	ayuda,cancelar,enviarcorreo,descargar ".
						" FROM {$this->_table} ". 
						" WHERE codemp='{$this->codemp}' ".
						" AND codusu='{$this->codusu}' ".
						" AND codmenu='{$this->codmenu}' ".
						" AND codintper='{$this->codintper}'";
			$result = $conexionbd->Execute($consulta);	
			if ($result->EOF)
			{		
				$this->existe = false;		
			}
			$result->Close(); 
	/*	}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar el Perfil '.$consulta.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);	
		}	*/	
	}	
	

/*****************************************************************************************
* @Función que elimina el perfil a una o todas las funcionalidades
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
**************************************************************************
* @fecha modificación: 28/10/2008
* @descripción: Se englobaron las funciones de eliminar para varios casos
* @autor: Ing. Gusmary Balza.
******************************************************************************************/			//para proceso asignar usuarios a personal
	public function eliminarTodosPrueba()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->mensaje = 'Elimino el perfil para el usuario '.$this->codusu.' en el sistema '.$this->codsis;
		$conexionbd->StartTrans();
		try
		{
			$consulta = " UPDATE {$this->_table} SET ".
						"	visible=0,enabled=0,leer=0,incluir=0, ".
						" 	cambiar=0,eliminar=0,imprimir=0,anular=0, ".
						" 	ejecutar=0,administrativo=0,ayuda=0, ".
						" 	cancelar=0,enviarcorreo=0,descargar=0 ".
						" WHERE codemp= '{$this->codemp}'";
			$cadena=" ";
            $total = count($this->criterio);
            for ($contador = 0; $contador < $total; $contador++)
			{
            	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 			               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
            }
            $consulta.= $cadena;
			$result = $conexionbd->Execute($consulta);
		}	
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje=' Error al Eliminar el perfil para el usuario '.$this->codusu.' en el sistema '.$this->codsis.$conexionbd->ErrorMsg();
	   	} 
	   	$conexionbd->CompleteTrans();
		$this->incluirSeguridad('ELIMINAR',$this->valido);
	}
		
	
/***************************************************************************************
* @Función que busca el perfil de una funcionalidad
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
**************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
******************************************************************************************/		
	public function leerUno()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		try
		{
			$consulta = " SELECT codemp,codusu,codmenu,codintper,visible,leer,incluir, 		".
						" 	cambiar,eliminar,imprimir,anular,ejecutar,administrativo,ayuda,	".
						" 	cancelar,enviarcorreo,descargar,1 as valido 					".
						" FROM {$this->_table} 												".
						" WHERE codemp='{$this->codemp}' 									".
						" AND codusu='{$this->codusu}' 										".
						" AND codsis='{$this->codsis}' 										".
						" AND codmenu='{$this->codmenu}' 									";
						//" AND codintper='{$this->codintper}' 								".
						
			$result = $conexionbd->Execute($consulta);
			return $result;
		}	
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar el Perfil '.$consulta.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
	   	} 
	}
		
	
/***************************************************************************************
* @Función que busca todos los perfiles
* @parametros:
* @retorno:
* @fecha de creación: 19/11/2008
* @autor: Ing. Gusmary Balza.
**************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
******************************************************************************************/		
	public function leerTodos()
	{
		global $conexionbd;
		//$conexionbd->debug =1;
	
		$this->seleccionarConexion(&$conexionbd);
		try
		{
			$consulta = " SELECT codemp,codusu,codsis,codmenu,codintper,visible,leer,incluir, ".
						" 	cambiar,eliminar,imprimir,anular,ejecutar,administrativo,ayuda, ".
						" 	cancelar,enviarcorreo,descargar,1 as valido ".
						" FROM {$this->_table} ".
						" WHERE codemp='{$this->codemp}' ".
						" AND codusu='{$this->codusu}' ";
						
			$result = $conexionbd->Execute($consulta);
			return $result;
		}	
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar el Perfil '.$consulta.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
	   	} 
	}
		
	
/*************************************************************************
* @Función que inserta los derechos de usuario (asignar permisos a usuarios)
* @parametros: 
* @retorno:
* @fecha de creación: 08/10/2008
* @autor: Ing. Gusmary Balza
**************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*************************************************************************/		
	function cargarDerechos()
	{
		global $conexionbd;
		$this->mensaje = 'Incluyo los derechos al Usuario '.$this->codusu. ' para el sistema '.$this->codsis;
		$conexionbd->StartTrans();
		try
		{
			$consulta = " INSERT INTO {$this->_table} ".
						" (codemp,codusu,codsis,codmenu,codintper,visible,enabled,leer, ". 
						" incluir,cambiar,eliminar,imprimir,administrativo,anular,ejecutar, ".
						" ayuda,cancelar,enviarcorreo,descargar) ".
						" SELECT '{$this->codemp}','{$this->codusu}',codsis,codmenu, ".
						" '{$this->codintper}',visible,enabled,leer,incluir,cambiar,".
						" eliminar, imprimir,administrativo,anular,ejecutar,ayuda,cancelar, ".
						" enviarcorreo,descargar ".
					    " FROM {$this->_table}".
						" WHERE codemp= '{$this->codemp}' ".
						"   AND codusu= '{$this->codusu}' ".
						"   AND codsis='{$this->codsis}' ";
			$result = $conexionbd->Execute($consulta);
		}	
		catch (exception $e) 
		{
			$this->mensaje='Error al eliminar los derechos al Usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
			$this->valido = false;
		}	
		$conexionbd->CompleteTrans();	
		$this->incluirSeguridad('INSERTAR',$this->valido);	
	}	

	
/*************************************************************************
* @Función que inserta los derechos de usuario al transferirlo
* @parametros: 
* @retorno:
* @fecha de creación: 20/11/2008
* @autor: Ing. Gusmary Balza
**************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*************************************************************************/		
	function incluirTraspaso()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->mensaje = 'Incluyo los derechos al Usuario '.$this->codusu. ' para el sistema '.$this->codsis;
		$conexionbd->StartTrans();
		try
		{
			$consulta = " INSERT INTO {$this->_table} ".
						" (codemp,codusu,codsis,codmenu,codintper,visible,enabled,leer, ". 
						" incluir,cambiar,eliminar,imprimir,administrativo,anular,ejecutar, ".
						" ayuda,cancelar,enviarcorreo,descargar) ".
						" values ('{$this->codemp}','{$this->codusu}','{$this->codsis}', ".
						" {$this->codmenu},'{$this->codintper}',{$this->visible},1, ".
						" {$this->leer},{$this->incluir},{$this->cambiar}, ".
						" {$this->eliminar},{$this->imprimir},{$this->anular}, ".
						" {$this->ejecutar},{$this->administrativo}, ".
						" {$this->ayuda},{$this->cancelar},{$this->enviarcorreo},{$this->descargar}) ";
					   	
			//echo $consulta;
			$result = $conexionbd->Execute($consulta);
		}	
		catch (exception $e) 
		{
			$this->mensaje='Error al eliminar los derechos al Usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
			$this->valido = false;
		}	
		$conexionbd->CompleteTrans();	
		$this->incluirSeguridad('INSERTAR',$this->valido);	
	}	

	
/*************************************************************************
* @Función que modifica los derechos de usuario al transferirlo
* @parametros: 
* @retorno:
* @fecha de creación: 20/11/2008
* @autor: Ing. Gusmary Balza
**************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*************************************************************************/		
	function modificarTraspaso()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->mensaje = 'Incluyo los derechos al Usuario '.$this->codusu. ' para el sistema '.$this->codsis;
		try
		{
			$consulta = " UPDATE {$this->_table} SET ".
						" incluir,cambiar,eliminar,imprimir,administrativo,anular,ejecutar, ".
						" ayuda,cancelar,enviarcorreo,descargar) ".
						" codemp= '{$this->codemp}',codusu='{$this->codusu}',codsis='{$this->codsis}', ".
						" codmenu={$this->codmenu},codintper='{$this->codintper}',visible={$this->visible}, ".
						" enabled=1,leer={$this->leer},{$this->incluir},{$this->cambiar}, ".
						" {$this->eliminar},{$this->imprimir},{$this->anular}, ".
						" {$this->ejecutar},{$this->administrativo}, ".
						" {$this->ayuda},{$this->cancelar},{$this->enviarcorreo},{$this->descargar}) ";
					   	
			//echo $consulta;
			$result = $conexionbd->Execute($consulta);
		}	
		catch (exception $e) 
		{
			$this->mensaje='Error al eliminar los derechos al Usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
			$this->valido = false;
		}	
		$this->incluirSeguridad('INSERTAR',$this->valido);	
	}	

	
	function modificarDerechos()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->mensaje = 'Incluyo los derechos al Usuario '.$this->codusu. ' para el sistema '.$this->codsis;
		$conexionbd->StartTrans();
		try
		{
			$consulta = " UPDATE {$this->_table}
							SET
      						visible=(SELECT visible FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis),
							enabled=(SELECT enabled FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis), 
							leer=(SELECT leer FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis),
							incluir=(SELECT incluir FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis),  
							cambiar=(SELECT cambiar FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis),
							eliminar=(SELECT eliminar FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis),
							imprimir=(SELECT imprimir FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis),
							administrativo=(SELECT administrativo FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis),
							anular=(SELECT anular FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis),
							ejecutar=(SELECT ejecutar FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis),
							ayuda=(SELECT ayuda FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis),
							cancelar=(SELECT cancelar FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis),
							enviarcorreo=(SELECT enviarcorreo FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis),
							descargar=(SELECT descargar FROM sss_sistemas_ventanas WHERE {$this->_table}.codmenu=sss_sistemas_ventanas.codmenu  
      									AND {$this->_table}.codsis=sss_sistemas_ventanas.codsis)
							 						
     					WHERE {$this->_table}.codemp='$this->codemp'
     					AND {$this->_table}.codsis='$this->codsis'
     					AND {$this->_table}.codusu= '$this->codusu'
     					AND {$this->_table}.codintper='$this->codintper'
     					";
			
			
			$result = $conexionbd->Execute($consulta);					
			
		}	
		catch (exception $e) 
		{
			$this->mensaje='Error al actualizar los derechos al Usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
			$this->valido = false;
		}	
		$conexionbd->CompleteTrans();	
		$this->incluirSeguridad('MODIFICAR',$this->valido);	
	}	
	
	
	function incluirDerechos()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->mensaje = 'Incluyo los derechos al Usuario '.$this->codusu. ' para el sistema '.$this->codsis;
		$conexionbd->StartTrans();
		try
		{
			$consulta = " INSERT INTO {$this->_table} (codemp,codusu,codsis,codmenu,codintper,
							visible,enabled,leer,incluir,cambiar,eliminar,imprimir,anular,
							ejecutar,administrativo,ayuda,cancelar,enviarcorreo,descargar) 	
							SELECT '$this->codemp','$this->codusu',codsis,codmenu,
							'$this->codintper',	visible,enabled,leer,incluir,cambiar,eliminar,
							imprimir,anular,ejecutar,administrativo,ayuda,cancelar,enviarcorreo,
							descargar		 		
 								FROM sss_sistemas_ventanas 	
 									WHERE codsis='$this->codsis' 
 									AND hijo=0	
 									AND codmenu NOT IN 
										(SELECT codmenu 
											FROM {$this->_table}
											WHERE codemp='{$this->codemp}' 
											AND codusu='{$this->codusu}'
											AND codsis='{$this->codsis}'
											AND codintper='$this->codintper') 												
																				
 									";
			$result = $conexionbd->Execute($consulta);				
		}	
		catch (exception $e) 
		{
			$this->mensaje='Error al actualizar los derechos al Usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
			$this->valido = false;
		}	
		$conexionbd->CompleteTrans();	
		$this->incluirSeguridad('INSERTAR',$this->valido);	
	}	
	
	
	
/***********************************************************************************
* @Función que Incluye el registro de la transacción exitosa
* @parametros: $evento
* @retorno:
* @fecha de creación: 10/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function incluirSeguridad($evento,$tipotransaccion)
	{
		if ($this->seguridad==true)
		{
		if($tipotransaccion) // Transacción Exitosa
		{
			$objEvento = new RegistroEventos();
		}
		else // Transacción fallida
		{
			$objEvento = new RegistroFallas();
		}
		// Registro del Evento
		$objEvento->codemp = $this->codemp;//'0001';
		$objEvento->codsis = 'SSS';
		$objEvento->nomfisico = $this->nomfisico;
		$objEvento->evento = $evento;
		$objEvento->desevetra = $this->mensaje;
		$objEvento->incluir();
		unset($objEvento);
		}
	}
		
}	
?>