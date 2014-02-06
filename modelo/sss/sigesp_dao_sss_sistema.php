<?php
/***********************************************************************************
* @Modelo para la definición de Sistema. 
* @fecha de creación: 30/09/2008.
* @autor: Ing.Gusmary Balza
* **************************
* @fecha modificacion  10/10/2008
* @autor  Ing. Yesenia Moreno de Lang
* @descripcion  Se agrego la seguridad y manejo de errores
***********************************************************************************/

require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once('sigesp_dao_sss_registroeventos.php');
require_once('sigesp_dao_sss_registrofallas.php');

class Sistema extends ADOdb_Active_Record
{
	var $_table = 'sss_sistemas';
	public $valido=true;
	public $existe=true;
	public $codemp;
	public $mensaje;
	public $cadena;
	public $criterio;	
	public $codsis;
	public $nomfisico;
	var $admin = array();
	var $usuarioeliminar = array();
		
/***********************************************************************************
* @Función que  valida si un sistema ya existe
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	public function verificarCodigo()
	{
		global $conexionbd;
		try 
		{ 
			$consulta="SELECT codsis ".
					  "  FROM {$this->_table} ".
					  " WHERE codsis = '{$this->codsis}' ";
			$result = $conexionbd->Execute($consulta);
			if ($result->EOF)
			{		
				$this->existe = false;		
			}
			$result->Close(); 
		}
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al consultar el Sistema '.$this->codsis.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
		}
	}

	
/***********************************************************************************
* @Función para insertar un sistema.
* @parametros: 
* @retorno:
* @fecha de creación: 30/09/2008.
* @autor: Ing.Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function incluir()
	{
		global $conexionbd;
		$this->mensaje='Incluyo el Sistema '.$this->codsis;
		$conexionbd->StartTrans();
		try 
		{ 
			$consulta = " INSERT INTO {$this->_table} ".
						"	(codsis,nomsis,estsis,imgsis,tipsis,ordsis) ".
						" 	values ('{$this->codsis}','{$this->nomsis}','1','','',0)";
			$result = $conexionbd->Execute($consulta);
			$total=	count($this->admin);
			for ($contador=0; $contador < $total; $contador++)
			{	
				$this->admin[$contador]->codemp = $this->codemp;
				$this->admin[$contador]->codsis = $this->codsis;
				$this->admin[$contador]->nomfisico = $this->nomfisico;
				$this->admin[$contador]->incluir();			
			}
		}
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al Incluir el Sistema '.$this->codsis.' '.$conexionbd->ErrorMsg();
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('INSERTAR',$this->valido);
	}	

	
/***********************************************************************************
*  @Función que  actualiza el sistema y su detalle
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing.Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	public function modificar()
	{
		global $conexionbd;
		$this->mensaje='Modifico el sistema '.$this->codsis;
		$conexionbd->StartTrans();
		try 
		{ 
			$consulta = "UPDATE {$this->_table} ".
						"  SET nomsis = '{$this->nomsis}'".
						" WHERE codsis = '{$this->codsis}' ";
			$result = $conexionbd->Execute($consulta);
			$total=	count($this->usuarioeliminar);
			for ($contador=0; $contador < $total; $contador++)
			{	
				$this->usuarioeliminar[$contador]->codemp = $this->codemp;
				$this->usuarioeliminar[$contador]->codsis = $this->codsis;
				$this->usuarioeliminar[$contador]->nomfisico = $this->nomfisico;
				$this->usuarioeliminar[$contador]->eliminar();
			}
			$total=	count($this->admin);
			for ($contador=0; $contador < $total; $contador++)
			{	
				$this->admin[$contador]->codemp = $this->codemp;
				$this->admin[$contador]->codsis = $this->codsis;
				$this->admin[$contador]->nomfisico = $this->nomfisico;
				$this->admin[$contador]->incluir();
			}
		}
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al Modificar el Sistema '.$this->codsis.' '.$conexionbd->ErrorMsg();
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('MODIFICAR',$this->valido);	
	}	

	
/***********************************************************************************
*  @Función que  elimina el sistema y su detalle
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing.Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function eliminar()
	{
		global $conexionbd;
		$this->mensaje='Modifico el sistema a Inactivo '.$this->codsis;
		$conexionbd->StartTrans(); 
		try 
		{ 
			$this->usuarioeliminar[0]->codemp = $this->codemp;
			$this->usuarioeliminar[0]->codsis = $this->codsis;
			$this->usuarioeliminar[0]->nomfisico = $this->nomfisico;
			$this->usuarioeliminar[0]->eliminarTodos();		
			$consulta = "UPDATE {$this->_table} ".
						"  SET estsis = '0'".
						" WHERE codsis = '{$this->codsis}' ";
			$result = $conexionbd->Execute($consulta);
		} 
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje=' Error al Eliminar el Sistema '.$this->codsis.' '.$conexionbd->ErrorMsg();
	   	} 
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('ELIMINAR',$this->valido);
		
	}

	
/***********************************************************************************
* @Función que busca los usuarios de un sistema
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing.Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	public function obtenerUsuarios()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		try 
		{ 
			$consulta = " SELECT {$this->_table}.codsis, {$this->_table}.nomsis as nomsistema, ".
						" 		 sss_usuarios.codusu, sss_usuarios.nomusu, sss_usuarios.apeusu,".
						"  		 sss_usuarios.email, 1 as valido ".
						"  FROM {$this->_table} ".
						" INNER JOIN (sss_usuario_sistema ".
						" 			  INNER JOIN sss_usuarios ".
						"	 			 ON sss_usuarios.codemp = sss_usuario_sistema.codemp ".
						"				AND sss_usuarios.codusu = sss_usuario_sistema.codusu) ".
						"    ON sss_usuario_sistema.codemp = '{$this->codemp}' ".
						"   AND sss_usuario_sistema.codsis = {$this->_table}.codsis ".
						" WHERE {$this->_table}.codsis = '{$this->codsis}' ";
			$result = $conexionbd->Execute($consulta);
			return $result;
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar los usuarios del sistema '.$consulta.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
		}
	}

	
/***********************************************************************************
* @Función que busca uno todos los sistemas
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing.Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	public function leer() 
 	{		
		global $conexionbd;
		try 
		{ 
			$consulta = "SELECT codsis,nomsis, 1 as valido ".
						"  FROM {$this->_table} ".
						" WHERE estsis = '1' ";
			if (($this->criterio=='')&&(($this->cadena!='')))
			{
				$consulta .= " AND codsis ='{$this->cadena}'";
			}
			elseif ($this->criterio!='')
			{
				$consulta .= " AND {$this->criterio} like '{$this->cadena}%'";
		  	}
		  	$consulta.= "ORDER BY codsis";
		  	$result = $conexionbd->Execute($consulta);
			return $result; 
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar el Sistema '.$consulta.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
	   	} 
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
		if($tipotransaccion)
		{
			$objEvento = new RegistroEventos();
			$tiponotificacion = 'NOTIFICACION';
		}
		else
		{
			$objEvento = new RegistroFallas();
			$tiponotificacion = 'ERROR';
		}
		// Registro del Evento
		$objEvento->codemp = $this->codemp;
		$objEvento->codsis = 'SSS';
		$objEvento->nomfisico = $this->nomfisico;
		$objEvento->evento = $evento;
		$objEvento->desevetra = $this->mensaje;
		$objEvento->incluir();
		// Envío de Notificación
		$objEvento->objNotificacion->codemp=$this->codemp;
		$objEvento->objNotificacion->sistema='SSS';
		$objEvento->objNotificacion->tipo=$tiponotificacion;
		$objEvento->objNotificacion->titulo='DEFINICIÓN DE SISTEMA';
		$objEvento->objNotificacion->usuario=$_SESSION['la_logusr'];
		$objEvento->objNotificacion->operacion=$this->mensaje;
		$objEvento->objNotificacion->enviarNotificacion();
		unset($objEvento);
	}	
}	
?>