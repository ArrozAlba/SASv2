<?php
/*****************************************************************************
* @Modelo para las funciones de cuentas spg.
* @fecha de creación: 03/10/2008.
* @autor: Ing.Gusmary Balza
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
******************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registroeventos.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registrofallas.php');

class Cuenta extends ADOdb_Active_Record
{
	var $_table = 'spg_cuentas';
	public $valido = true;
	public $mensaje;
	public $seguridad = true;
	public $codsis;
	public $nomfisico;
	public $criterio;
	public $tipoconsulta;
	
	
/***********************************************************************************
* @Función que Busca uno o todas las cuentas spg
* @parametros: 
* @retorno:
* @fecha de creación: 26/11/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
************************************************************************************/		
	public function leer() 
 	{	
 		global $conexionbd;
 		$conexionbdorigen = conectarBD($_SESSION['sigesp_servidor'], $_SESSION['sigesp_usuario'], $_SESSION['sigesp_clave'],
												 $_SESSION['sigesp_basedatos'], $_SESSION['sigesp_gestor']);
 		try
		{	
			if ($this->tipoconsulta=='todos')
			{
				//esta consulta asi ya que se repite por denominación
				$consulta = " SELECT TRIM(spg_cuenta) as spg_cuenta, sigesp_plan_unico_re.denominacion,1 as valido ".
							" FROM {$this->_table} ".
							" INNER JOIN sigesp_plan_unico_re ON sigesp_plan_unico_re.sig_cuenta=spg_cuentas.spg_cuenta ".
							" WHERE codemp='{$this->codemp}'".
							" AND {$this->_table}.status='C'";
				$agrupar = " GROUP BY spg_cuenta,sigesp_plan_unico_re.denominacion ";
				
			}
			else
			{	
				$consulta = " SELECT TRIM(spg_cuenta) as spg_cuenta, denominacion, codestpro1,codestpro2, ".
							" 	codestpro3, codestpro4, codestpro5, status, ".
							"	SUM((asignado-(comprometido+precomprometido)+aumento-disminucion)) as disponible, ".
							"	sc_cuenta,1 as valido ".
							" FROM {$this->_table} ".
							" WHERE codemp='{$this->codemp}'".
							" AND status='C'";
				//$agrupar = '';
				$agrupar = " GROUP BY spg_cuenta,denominacion,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,status,sc_cuenta ";
				
			}
			$cadena=" ";
            $total = count($this->criterio);            
            for ($contador = 0; $contador < $total; $contador++)
			{
            	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 			               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
            }
            $consulta.= $cadena;
            $consulta.= $agrupar;
            $consulta.= " ORDER BY spg_cuenta ASC ";
            $result = $conexionbdorigen->Execute($consulta);
		 	return $result;
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar la Estructura Presupuestaria '.$consulta.' '.$conexionbd->ErrorMsg();
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