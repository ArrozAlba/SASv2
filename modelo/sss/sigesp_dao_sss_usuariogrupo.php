<?php
/***********************************************************************************
* @Modelo para proceso de asignación de usuarios a grupo.
* @fecha de creación: 30/09/2008.
* @autor: Ing.Gusmary Balza
* **************************
* @fecha modificacion  
* @autor 
* @descripcion  
***********************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once('sigesp_dao_sss_registroeventos.php');
require_once('sigesp_dao_sss_registrofallas.php');

class UsuarioGrupo extends ADOdb_Active_Record
{
	var $_table='sss_usuarios_en_grupos';
	public $mensaje;
	public $valido = true;
	public $existe = true;
	public $codsis;
	public $nomfisico;
	
	
/***********************************************************************************
* @Función que incluye un usuario en un grupo
* @parametros: 
* @retorno:
* @fecha de creación: 08/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación: 10/10/2008
* @descripción: Se agrego la seguridad
* @autor: Ing. Yesenia Moreno de Lang
***********************************************************************************/	
	public function incluir()
	{
		global $conexionbd;
		$this->mensaje='Incluyo el Usuario '.$this->codusu.' en el Grupo '.$this->nomgru;
		$this->save();
		if($conexionbd->HasFailedTrans())
		{
			$this->valido  = false;	
			$this->mensaje=$conexionbd->ErrorMsg();
		}
		$this->incluirSeguridad('INSERTAR',$this->valido);
	}

	
/***********************************************************************************
* @Función que Elimina el usuario de un Grupo
* @parametros: 
* @retorno:
* @fecha de creación: 10/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	public function eliminar()
	{
		global $conexionbd;
		$this->mensaje='Elimino el Usuario '.$this->codusu.' del Grupo '.$this->nomgru;
		$this->delete();
		if($conexionbd->HasFailedTrans())
		{
			$this->valido  = false;	
			$this->mensaje=$conexionbd->ErrorMsg();
		}
		$this->incluirSeguridad('ELIMINAR',$this->valido);
	}	
	

/***********************************************************************************
* @Función que Elimina todos los usuarios de un Grupo
* @parametros: 
* @retorno:
* @fecha de creación: 10/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	public function eliminarTodos()
	{
		global $conexionbd;
		$this->mensaje='Elimino los Usuarios del Grupo '.$this->nomgru;
		$consulta = "DELETE FROM $this->_table ".
					" WHERE codemp = '$this->codemp' ".
					"   AND nomgru = '$this->nomgru' ";
		$result = $conexionbd->Execute($consulta);
		if($conexionbd->HasFailedTrans())
		{
			$this->valido  = false;	
			$this->mensaje=$conexionbd->ErrorMsg;
		}
		$this->incluirSeguridad('ELIMINAR',$this->valido);
	}

	
/***************************************************************************
* @Función que Busca si un usuario esta asignado a un grupo
* @parametros: 
* @retorno:
* @fecha de creación: 03/09/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
******************************************************************************/			
	function buscarUsuarioGrupo()
	{
		global $conexionbd;
		try
		{
			$consulta = " SELECT nomgru,codusu ".
						"   FROM $this->_table ".
						"  WHERE codemp = '{$this->codemp}' ".
						"    AND codusu = '{$this->codusu}' ";	
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
			$this->mensaje='Error al consultar el Usuario'.$this->codusu.' en los Grupos '.' '.$conexionbd->ErrorMsg();
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
		if($tipotransaccion) // Transacción Exitosa
		{
			$objEvento = new RegistroEventos();
		}
		else // Transacción fallida
		{
			$objEvento = new RegistroFallas();
		}
		// Registro del Evento
		$objEvento->codemp = $this->codemp;
		$objEvento->codsis = $this->codsis;
		$objEvento->nomfisico = $this->nomfisico;
		$objEvento->evento = $evento;
		$objEvento->desevetra = $this->mensaje;
		$objEvento->incluir();
		unset($objEvento);
	}
}
?>
