<?php
/*****************************************************************************
* @Modelo para las funciones de solicitudes de cuentas por pagar.
* @fecha de creación: 27/11/2008.
* @autor: Ing.Gusmary Balza
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
******************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registroeventos.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registrofallas.php');

class Solicitud extends ADOdb_Active_Record
{
	var $_table = 'cxp_solicitudes';
	public $valido = true;
	public $mensaje;
	public $seguridad = true;
	public $codsis;
	public $nomfisico;
	public $criterio;
	public $estatus;
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
* @Función que Busca uno o todas las solicitudes
* @parametros: 
* @retorno:
* @fecha de creación: 27/11/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
************************************************************************************/		
	public function leer() 
 	{	
 		global $conexionbd; 		
 		//try
		//{	
			$this->seleccionarConexion(&$conexionbd);			
			if ($this->estatus===C)
			{				
				$consulta = " SELECT 0 as marcado,numsol, fecemisol, consol, monsol, 	".
							" 0.00 as pagado,1 as valido 							 	".
							" FROM {$this->_table} 										".
							" WHERE codemp='{$this->codemp}'							";
			}
			else
			{					
				$consulta = " SELECT 0 as marcado,numsol, fecemisol, consol, monsol,1 as valido, 	".
							" 	(SELECT COALESCE(SUM(cxp_sol_banco.monto), 0.00) as pagado 			".
							"		FROM cxp_sol_banco 												".
							"		WHERE cxp_sol_banco.numsol=cxp_solicitudes.numsol 				".
							"		AND cxp_sol_banco.estmov<>'A' 									".
							"		AND cxp_sol_banco.estmov<>'O') as Pagado 						".
							" FROM {$this->_table} 													".
							" WHERE codemp='{$this->codemp}'										".
							" AND monsol>(SELECT COALESCE(SUM(cxp_sol_banco.monto), 0.00) as pagado 			".
							"		FROM cxp_sol_banco 												".
							"		WHERE cxp_sol_banco.numsol=cxp_solicitudes.numsol 				".
							"		AND cxp_sol_banco.estmov<>'A' 									".
							"		AND cxp_sol_banco.estmov<>'O')";
			}				
			$cadena=" ";
            $total = count($this->criterio);
            for ($contador = 0; $contador < $total; $contador++)
			{
            	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 			               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
            }
            $consulta.= $cadena;            
            $consulta.= " ORDER BY numsol";
            $result = $conexionbd->Execute($consulta);           	  
		 	return $result;
		/*}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar la Solicitud '.$consulta.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
	   	}*/ 
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