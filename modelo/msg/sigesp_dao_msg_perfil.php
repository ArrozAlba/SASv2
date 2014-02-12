<?php
/**************************************************************** 	
* @Modelo para proceso de asignar perfil a los usuarios o grupos.
* @versión: 1.0      
* @fecha creación: 22/08/2008
* @autor: Ing. Gusmary Balza
*****************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*************************************************************/

require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once('sigesp_dao_msg_registroeventos.php');

class Perfil extends ADOdb_Active_Record
{
	var $_table = 'msgperfild';
	
	public $mensaje;
	public $evento;
	public $valido;
	public $existe;
	public $cadena;
	public $criterio;
	
	
/********************************************
* @Función que incluye un perfil
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
********************************************/	
	function incluir()
	{
		global $conexionbd;
		$conexionbd->StartTrans();		
		$this->save();
		if ($conexionbd->CompleteTrans())
		{
			$this->valido = true;
			$this->mensaje = "Inserto nuevo perfil: ";
			$this->evento = "INSERT";
			//	$this->incluirSeguridad();	
		}
		else
	   	{
			$this->valido  = false;				
			$this->mensaje = "Ha ocurrido un error: No se pudo insertar el perfil";	
		}  
	}
	
/******************************************************
* @Función que modifica un perfil de una funcionalidad
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
*******************************************************/		
	public function modificar() 
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->Replace();
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Modifico el perfil: ";
			$this->evento = "UPDATE";
			$this->valido = true;
		//	$this->incluirSeguridad();
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido = false;
		}
	}
	
/*************************************************************
* @Función que incluye el perfil de todas las funcionalidades.
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
**************************************************************/		
	public function modificarTodos() 
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		//$this->Replace();
		$consulta = " UPDATE {$this->_table} SET codempresa='{$this->codempresa}',codsistema='{$this->codsistema}', ".
					" codusuario='{$this->codusuario}',codgrupo='{$this->codgrupo}',codintpermiso='{$this->codintpermiso}', ".
					" visible='{$this->visible}',leer='{$this->leer}',incluir='{$this->incluir}', ".
					" actualizar='{$this->actualizar}',eliminar='{$this->eliminar}',imprimir='{$this->imprimir}', ".
					" anular='{$this->anular}',ejecutar='{$this->ejecutar}',administrativo='{$this->administrativo}' ".
					" WHERE codempresa='{$this->codempresa}' AND codsistema='{$this->codsistema}' ".
					" AND codusuario='{$this->codusuario}' AND codgrupo='{$this->codgrupo}' ". 
					" AND codintpermiso='{$this->codintpermiso}'";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Modifico el perfil: ";
			$this->evento = "UPDATE";
			$this->valido = true;
		//	$this->incluirSeguridad();
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido = false;
		}
	}
	
/*******************************************************
* @Función que verifica el perfil de una funcionalidad
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
*******************************************************/		
	public function verificarPerfilUno()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = " SELECT codempresa,codsistema,codusuario,codgrupo,codmenu,codintpermiso,visible,leer,incluir,actualizar, ".
					" eliminar,imprimir,anular,ejecutar,administrativo FROM {$this->_table} ". 
					" WHERE codempresa='{$this->codempresa}' AND codusuario='{$this->codusuario}' ".
					" AND codgrupo='{$this->codgrupo}' AND codmenu='{$this->codmenu}' AND codintpermiso='{$this->codintpermiso}'";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$this->mensaje = "El perfil ya existe";
				$this->existe = true;
			}
			else
			{
				$this->existe = false;
			}
		}
		else
		{
			echo "Ha ocurrido un error";
		}
	}	
	
/***************************************************************
* @Función que verifica el perfil de todas las funcionalidades.
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
****************************************************************/		
	public function verificarPerfilTodos()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = " SELECT codempresa,codsistema,codusuario,codgrupo,codmenu,codintpermiso,visible,leer,incluir, ".
					" actualizar,eliminar,imprimir,anular,ejecutar,administrativo ".
					" FROM {$this->_table} ". 
					" WHERE codempresa='{$this->codempresa}' AND codusuario='{$this->codusuario}' ".
					" AND codgrupo='{$this->codgrupo}' AND codintpermiso='{$this->codintpermiso}'";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$this->mensaje = "El perfil ya existe: para una o varias de las funcionalidades";
				$this->existe = true;
			}
			else
			{
				$this->existe = false;
			}
		}
		else
		{
			echo "Ha ocurrido un error";
		}
	
	}	
	
/***************************************************************
* @Función que incluye un perfil a todas las funcionalidades
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
****************************************************************/		
	public function insertarPermisosGlobales() 
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = " INSERT INTO {$this->_table} (codempresa,codsistema,codusuario,codgrupo,codmenu,codintpermiso, ".
		            " visible,leer,incluir,actualizar,eliminar,imprimir,anular,ejecutar,administrativo) ".
					" SELECT codempresa,codsistema,'{$this->codusuario}','{$this->codgrupo}',codmenu,'{$this->codintpermiso}', ".
					" {$this->visible},{$this->leer},{$this->incluir},{$this->actualizar},{$this->eliminar},{$this->imprimir}, ".
					" {$this->anular},{$this->ejecutar},{$this->administrativo} ".
					" FROM msgmenum WHERE codempresa='{$this->codempresa}' AND codsistema='{$this->codsistema}' AND hijo='0'";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Inserto perfil global ";
			$this->evento = "INSERT";
			$this->valido = true;
		//	$this->incluirSeguridad();
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido = false;
		}
	
	}
	
/***************************************************************
* @Función que elimina el perfil a una funcionalidad
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
****************************************************************/		
	public function eliminarUno() 
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = " UPDATE {$this->_table} SET visible='0',leer='0',incluir='0',actualizar='0',eliminar='0', ".
					" imprimir='0',anular='0',ejecutar='0',administrativo='0' ".
					" WHERE codempresa='{$this->codempresa}' AND codsistema='{$this->codsistema}' ".
					" AND codusuario='{$this->codusuario}' AND codgrupo='{$this->codgrupo}' ".
					" AND codmenu='{$this->codmenu}' AND codintpermiso='{$this->codintpermiso}'";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Elimino el perfil: ";
			$this->evento = "DELETE";
			$this->valido = true;
		//	$this->incluirSeguridad();
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido = false;
		}
	}
	
/***************************************************************
* @Función que elimina el perfil a todas las funcionalidades
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
****************************************************************/		
	public function eliminarTodos()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = " UPDATE {$this->_table} SET visible='0',leer='0',incluir='0',actualizar='0', ".
					" eliminar='0',imprimir='0',anular='0',ejecutar='0',administrativo='0' ".
					" WHERE codempresa='{$this->codempresa}' AND codsistema='{$this->codsistema}' ".
					" AND codusuario='{$this->codusuario}' AND codgrupo='{$this->codgrupo}' ". 
					" AND codintpermiso='{$this->codintpermiso}'";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Elimino el perfil: ";
			$this->evento = "DELETE";
			$this->valido = true;
		//	$this->incluirSeguridad();
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido = false;
		}
	}
	
/***************************************************************
* @Función que busca el perfil de una funcionalidad
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
****************************************************************/		
	public function leerUno()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = " SELECT codempresa,codusuario,codgrupo,codmenu,codintpermiso,visible,leer,incluir,actualizar,eliminar, ".
					" imprimir,anular,ejecutar,administrativo FROM {$this->_table} ".
					" WHERE codempresa='{$this->codempresa}' AND codusuario='{$this->codusuario}' ".
					" AND codgrupo='{$this->codgrupo}' AND codmenu='{$this->codmenu}' AND codintpermiso='{$this->codintpermiso}'";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			return $result;
		}
		else
		{
			echo "Ha ocurrido un error";
		}
	}
	
/***************************************************************
* @Función que busca el perfil de todas las funcionalidades
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
****************************************************************/		
	
	public function leerTodos()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = " SELECT codempresa,codusuario,codgrupo,codmenu,codintpermiso,visible,leer,incluir,actualizar,eliminar, ".
					" imprimir,anular,ejecutar,administrativo FROM {$this->_table} ".
					" WHERE codempresa='{$this->codempresa}' AND codusuario='{$this->codusuario}' ".
					" AND codgrupo='{$this->codgrupo}' AND codintpermiso='{$this->codintpermiso}'";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			return $result;
		}
		else
		{
			echo "Ha ocurrido un error";
		}
	}
	
/***************************************************************
* @Función que busca un perfil para mostrarlo en el reporte
* @parametros:
* @retorno:
* @fecha de creación: 22/08/2008
* @autor: Ing. Gusmary Balza.
****************************************************************/		
	function leerReporte()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$consulta = " SELECT codempresa,codsistema,codusuario,codgrupo,visible,leer,incluir,actualizar,eliminar,imprimir, ".
					" anular,ejecutar,administrativo, ".
					" (SELECT nombre FROM mcdempresam WHERE {$this->_table}.codempresa=mcdempresam.codempresa) as empresa, ".
					" (SELECT nombre FROM msgsistemam WHERE {$this->_table}.codsistema=msgsistemam.codsistema) as sistema, ".
					" (SELECT nombre FROM msgusuariom WHERE {$this->_table}.codusuario=msgusuariom.codusuario) as nombre, ".
					" (SELECT apellido FROM msgusuariom WHERE {$this->_table}.codusuario=msgusuariom.codusuario) as apellido, ".
					" (SELECT nombre FROM msggrupom WHERE {$this->_table}.codgrupo=msggrupom.codgrupo) as grupo, ".
					" (SELECT nomlogico FROM msgmenum WHERE {$this->_table}.codmenu=msgmenum.codmenu ".
					" AND {$this->_table}.codsistema=msgmenum.codsistema) as pantalla ".
					" FROM {$this->_table} WHERE {$this->_table}.codempresa='{$this->codempresa}' ".
					" AND {$this->_table}.codusuario='{$this->codusuario}' AND {$this->_table}.codgrupo='{$this->codgrupo}' ".
					" AND {$this->_table}.codsistema='{$this->codsistema}' ".		
					" ORDER BY {$this->orden}";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			return $result;
		}
		else
		{
			echo "Ha ocurrido un error";
		}
	}

	
	
	function incluirSeguridad()
	{
		$objRegistro = new RegistroEventos();
		$objRegistro->codempresa    = $this->codempresa;  //asi
		$objRegistro->codsistema    = 'MSG'; //obtener
		$objRegistro->codusuario    = $this->codusuario;           //obtener
		$objRegistro->evento        = $this->evento;
		$objRegistro->funcionalidad = 'sigesp_vis_msg_perfiles';    //obtener
		$objRegistro->desevento     = $this->mensaje;
		$objRegistro->codinterno    = $this->codintpermiso;	
		$objRegistro->insertarEvento();
	}
		
}
?>