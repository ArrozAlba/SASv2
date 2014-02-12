<?php
/***********************************************************************************
* @Clase para la efinición de grupo
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
* **************************
* @fecha modificacion  28/08/2008
* @autor   Ing. Yesenia Moreno
* @descripcion  Se agrego el método para que incluyera en seguridad y enviara correos
***********************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once('sigesp_dao_msg_perfilevento.php');

class Grupo extends ADOdb_Active_Record
{
	var $_table='msggrupom';
	
	public $mensaje;
	public $evento;
	public $valido;
	public $existe;
	public $cadena;
	public $criterio;
	public $seguridad=true;
	
/***********************************************************************************
* @Función que  valida si un grupo ya existe
* @parametros: 
* @retorno:
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function verificarCodigo()
	{
		global $conexionbd;
		$consulta="SELECT codgrupo ".
				  "  FROM {$this->_table} ".
				  " WHERE codempresa = '{$this->codempresa}' ".
				  "   AND codgrupo = '{$this->codgrupo}'";
		$result = $conexionbd->Execute($consulta); 
		if (!$result->EOF)
		{		
			$this->mensaje = "El codigo para grupo ya existe";
			$this->existe  = true;
		}
		else
		{			
			$this->existe = false;		
		}
		$result->Close(); 
	}
		
/***********************************************************************************
* @Función que Actualiza un grupo
* @parametros: 
* @retorno:
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function modificar()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->Replace();
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Modifico el Grupo: ".$this->nombre;
			$this->evento  = "UPDATE";
			$this->valido = true;	
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido  = false;
		}
		$this->incluirSeguridad('MODIFICAR');
	}
		
/***********************************************************************************
* @Función que Inserta un grupo
* @parametros: 
* @retorno:
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function incluir()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->save();
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Inserto nuevo Grupo: ".$this->nombre." con código ".$this->codgrupo;
			$this->evento  = "INSERT";
			$this->valido = true;				
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido  = false;
		}
		if($this->seguridad)
		{
			$this->incluirSeguridad('INSERTAR');	
		}
	}
	
	
/***********************************************************************************
* @Función que Elimina un grupo
* @parametros: 
* @retorno:
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function eliminar()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->delete();
		if ($conexionbd->CompleteTrans())
		{
			$this->mensaje = "Elimino el Grupo: ".$this->nombre;
			$this->evento = "DELETE";
			$this->valido = true;		
		}
		else
		{
			$this->mensaje = $conexionbd->ErrorMsg();
			$this->valido = false;
		}
		$this->incluirSeguridad('ELIMINAR');	
	}	
			
/***********************************************************************************
* @Función que Busca el código máximo
* @parametros: 
* @retorno:
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function buscarCodigo()
	{
		global $conexionbd;
		$cadena="SELECT max(codgrupo)  as cod FROM {$this->_table}";
		$result = $conexionbd->Execute($cadena); 
		if ($result->fields['cod']=='')
		{
			return "0001"; 
		}
		else
		{	
			$dato = $result->fields['cod'];
			return $dato;
		}
	}
		
/***********************************************************************************
* @Función que Busca uno o todos grupo
* @parametros: 
* @retorno:
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function leer() 
 	{		
		global $conexionbd;
		$consulta = "SELECT codempresa,codgrupo,nombre,nota FROM {$this->_table} WHERE codgrupo<>'-----'";
		if ($this->cadena=='')
		{
			$result = $conexionbd->Execute($consulta);
		}
		elseif ($this->criterio=='')
		{
			$consulta .= " AND codempresa='{$this->codempresa}' AND codgrupo ='{$this->cadena}'";
		}
		else
		{
			$consulta .= " AND {$this->criterio} like '%{$this->cadena}%'";
	  	}
		$result = $conexionbd->Execute($consulta);
		if ($result===false) 
		{
			echo "Ha ocurrido un error";
		}
		else
		{
			return $result;
		}
	}
	
/***********************************************************************************
* @Función que Busca si un grupo tiene usuarios asignados
* @parametros: 
* @retorno:
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function buscarUsuarioGrupo()
	{
		global $conexionbd;
		$conexionbd->StartTrans();		
		$result = $conexionbd->Execute(" SELECT codgrupo FROM msgusuariogrupod ".
									   " WHERE codempresa = '{$this->codempresa}' ".
									   " AND codgrupo='{$this->codgrupo}' "); 
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$this->existe = true;
				$this->mensaje = "No se puede eliminar el grupo: posee usuarios";
				$result->MoveNext();
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
	
/***********************************************************************************
* @Función que Busca si un grupo tiene un perfil asignado.
* @parametros: 
* @retorno:
* @fecha de creación: 02/09/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function buscarPerfilGrupo()
	{
		global $conexionbd;
		$conexionbd->StartTrans();		
		$result = $conexionbd->Execute(" SELECT DISTINCT codgrupo FROM msgperfild ".
									   " WHERE codempresa = '{$this->codempresa}' ".
									   " AND codgrupo='{$this->codgrupo}' "); 
		if ($conexionbd->CompleteTrans())
		{
			if (!$result->EOF)
			{
				$this->existe = true;
				$this->mensaje = "No se puede eliminar el grupo: posee un perfil";
				$result->MoveNext();
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
	
	
	function incluirSeguridad($evento)
	{
		/*// Registro del Evento
		$objEvento = new PerfilEvento();
		$objEvento->codempresa = $this->codempresa;
		$objEvento->codevento = 6;
		$objEvento->codsistema = 'MSG';
		$objEvento->codusuario = $_SESSION['sigesp_codusuario'];
		$objEvento->codgrupo = '-----';
		$objEvento->codmenu = 2;
		$objEvento->codintpermiso = '-----------';
		$objEvento->evento = $evento;
		$objEvento->tipo = '1';
		if($this->valido)
		{
			$objEvento->tipo = '0';
		}
		$objEvento->fecha = date("Y/m/d");
		$objEvento->hora  = date("h:i");
		$objEvento->obtenerEquipo();
		$objEvento->descripcion  = $this->mensaje;
		$objEvento->incluir();
		// Envío de Notificación
		$objEvento->objNotificacion->sistema="MSG";
		$objEvento->objNotificacion->tipo='ERROR';
		if($this->valido)
		{
			$objEvento->objNotificacion->tipo='NOTIFICACION';
		}
		$objEvento->objNotificacion->titulo='Grupo';
		$objEvento->objNotificacion->usuario=$_SESSION['sigesp_codusuario'];
		$objEvento->objNotificacion->operacion=$this->mensaje;
		$objEvento->objNotificacion->enviarNotificacion();
		unset($objEvento);*/
	}

}

?>
