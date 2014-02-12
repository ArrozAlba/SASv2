<?php
/***********************************************************************************
 * @Modelo para el procesar cuentas contables y de presupuesto.
 * @fecha de creación: 15/12/2008.
 * @autor: Ing. Gusmary Balza B.
 * **************************
 * @fecha modificacion
 * @autor
 * @descripcion
 ***********************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_registroeventos.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_registrofallas.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_funciones.php');


class ProcesoCuentas extends ADODB_Active_Record
{
	var $_table = 'spg_cuentas';
	public $mensaje;
	public $valido = true;
	public $existe;
	public $criterio;
		
	public $servidor;
	public $usuario;
	public $clave;
	public $basedatos;
	public $gestor;
	public $tipoconexionbd = 'DEFECTO';
	
	public $archivo;
	
/***********************************************************************************
* @Función para actualizar las cuentas contables.
* @parametros:
* @retorno:
* @fecha de creación: 15/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	public function procesarCuentasScg()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->mensaje = 'Proceso la actualización de las cuentas contables';
		$conexionbd->StartTrans();
		try
		{
			$consulta = " SELECT scg_cuentaorigen, scg_cuentadestino ".
						" FROM apr_contable ";
			$resultcontable = $conexionbd->Execute($consulta);
			if ($resultcontable===false)
			{
				$this->valido = false;
				$cadena = 'Error al Seleccionar las Cuentas Contables.'.''.$conexionbd->ErrorMsg();
			}
			else
			{
				while (!$resultcontable->EOF)
				{
					$sccuentaorigen  = validarTexto($resultcontable->fields['scg_cuentaorigen'],0,25,'');
					$sccuentadestino = validarTexto($resultcontable->fields['scg_cuentadestino'],0,25,'');
							
					
					$consulta = " UPDATE sigesp_empresa ".
							    " SET c_resultad='".$sccuentadestino."' ".
							    " WHERE c_resultad='".$sccuentaorigen."' ";					
					$result = $conexionbd->Execute($consulta);					
					if (is_object($result)) //corregir					
					{
						$consulta = " UPDATE sigesp_empresa ".
								    " SET c_resultan='".$sccuentadestino."' ".
									" WHERE c_resultan='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sigesp_empresa ".
									" SET scctaben='".$sccuentadestino."' ".
									" WHERE scctaben='".$sccuentaorigen."' ";
						$resultempresa = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sigesp_empresa ".
									" SET c_financiera='".$sccuentadestino."' ".
								    " WHERE c_financiera='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sigesp_empresa ".
									" SET c_fiscal='".$sccuentadestino."' ".
									" WHERE c_fiscal='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE rpc_proveedor ".
									" SET sc_cuenta='".$sccuentadestino."' ".
									" WHERE sc_cuenta='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE rpc_proveedor ".
									" SET sc_cuenta='".$sccuentadestino."' ".
									" WHERE sc_cuenta='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE saf_activo ".
									" SET sc_cuenta='".$sccuentadestino."' ".
									" WHERE sc_cuenta='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sigesp_deducciones ".
										  	   " SET sc_cuenta='".$sccuentadestino."' ".
										       " WHERE sc_cuenta='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE siv_articulo ".
									" SET sc_cuenta='".$sccuentadestino."' ".
									" WHERE sc_cuenta='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE scb_ctabanco ".
									" SET sc_cuenta='".$sccuentadestino."' ".
									" WHERE sc_cuenta='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE scb_colocacion ".
									" SET sc_cuenta='".$sccuentadestino."' ".
									" WHERE sc_cuenta='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE scb_colocacion ".
								    " SET sc_cuenta='".$sccuentadestino."' ".
									" WHERE sc_cuenta='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_beneficiario ".
									" SET sc_cuenta='".$sccuentadestino."' ".
								    " WHERE sc_cuenta='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_nomina ".
									" SET cueconnom='".$sccuentadestino."' ".
								    " WHERE cueconnom='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_hnomina ".
									" SET cueconnom='".$sccuentadestino."' ".
								    " WHERE cueconnom='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_personalnomina ".
									" SET cueaboper='".$sccuentadestino."' ".
								    " WHERE cueaboper='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_hpersonalnomina ".
									" SET cueaboper='".$sccuentadestino."' ".
								    " WHERE cueaboper='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_concepto ".
									" SET cueconcon='".$sccuentadestino."' ".
								    " WHERE cueconcon='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_hconcepto ".
									" SET cueconcon='".$sccuentadestino."' ".
								    " WHERE cueconcon='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_concepto ".
									" SET cueconpatcon='".$sccuentadestino."' ".
								    " WHERE cueconpatcon='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_hconcepto ".
									" SET cueconpatcon='".$sccuentadestino."' ".
								    " WHERE cueconpatcon='".$sccuentaorigen."' ";
						$result = $conexionbd->Execute($consulta);
					}
					$resultcontable->MoveNext();
				}
			}
		}
		catch (exception $e) 
		{
			$this->valido = false;
			$this->mensaje='Ocurrio un error en la Transferencia. '.$conexionbd->ErrorMsg();
			escribirArchivo($this->archivo,'* Ocurrio un error en la Transferencia. ');
			escribirArchivo($this->archivo,'* Error  '.$conexionbd->ErrorMsg());
			escribirArchivo($this->archivo,'*******************************************************************************************************');
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('PROCESAR',$this->valido);				
	}
	
	
/***********************************************************************************
* @Función para actualizar las cuentas de presupuesto.
* @parametros:
* @retorno:
* @fecha de creación: 15/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	public function procesarCuentasSpg()
	{
		global $conexionbd;		
		$this->mensaje = 'Proceso la actualización de las cuentas presupuestarias';	
		$conexionbd->StartTrans();
		try
		{
			$consulta = " SELECT spg_cuentaorigen, spg_cuentadestino ".
						" FROM apr_presupuestario ";
			$resultpresupuestario = $conexionbd->Execute($consulta);
			if ($resultpresupuestario===false)
			{
				$this->valido = false;
				$cadena = 'Error al Seleccionar las Cuentas Presupuestarias.'.''.$conexionbd->ErrorMsg();
			}
			else
			{
				while (!$resultpresupuestario->EOF)
				{
					$spgcuentaant  = validarTexto($resultpresupuestario->fields['spg_cuentaorigen'],0,25,'');
					$spgcuentaact = validarTexto($resultpresupuestario->fields['spg_cuentadestino'],0,25,'');
					
					$consulta = " UPDATE saf_catalogo ".
								" SET spg_cuenta='".$spgcuentaact."'".
								" WHERE spg_cuenta='".$spgcuentaant."'";
					$result = $conexionbd->Execute($consulta);
					if (is_object($result))
					{
						$consulta = " UPDATE saf_activo ".
								    " SET spg_cuenta_act='".$spgcuentaact."'".
								    " WHERE spg_cuenta_act='".$spgcuentaant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE saf_activo ".
								    " SET spg_cuenta_dep='".$spgcuentaact."'".
								    " WHERE spg_cuenta_dep='".$spgcuentaant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sigesp_cargos ".
								    " SET spg_cuenta='".$spgcuentaact."'".
								    " WHERE spg_cuenta='".$spgcuentaant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE siv_articulo ".
								    " SET spg_cuenta='".$spgcuentaact."'".
								    " WHERE spg_cuenta='".$spgcuentaant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sep_conceptos ".
								    " SET spg_cuenta='".$spgcuentaact."'".
								    " WHERE spg_cuenta='".$spgcuentaant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE soc_servicios ".
								    " SET spg_cuenta='".$spgcuentaact."'".
								    " WHERE spg_cuenta='".$spgcuentaant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_concepto ".
								    " SET cueprecon='".$spgcuentaact."'".
								    " WHERE cueprecon='".$spgcuentaant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_concepto ".
								    " SET cueprepatcon='".$spgcuentaact."'".
								    " WHERE cueprepatcon='".$spgcuentaant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_hconcepto ".
								    " SET cueprecon='".$spgcuentaact."'".
								    " WHERE cueprecon='".$spgcuentaant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_hconcepto ".
								    " SET cueprepatcon='".$spgcuentaact."'".
								    " WHERE cueprepatcon='".$spgcuentaant."'";
						$result = $conexionbd->Execute($consulta);
					}
					
					$resultpresupuestario->MoveNext();
				}
			}			
		}
		catch (exception $e) 
		{
			$this->valido = false;
			$this->mensaje='Ocurrio un error en la Transferencia. '.$conexionbd->ErrorMsg();
			escribirArchivo($this->archivo,'* Ocurrio un error en la Transferencia. ');
			escribirArchivo($this->archivo,'* Error  '.$conexionbd->ErrorMsg());
			escribirArchivo($this->archivo,'*******************************************************************************************************');
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('PROCESAR',$this->valido);		
	}
	
	
/***********************************************************************************
* @Función para actualizar las estructuras presupuestarias.
* @parametros:
* @retorno:
* @fecha de creación: 15/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function procesarEstructuras()
	{
		global $conexionbd;		
		$this->mensaje = 'Proceso la actualización de las estructuras presupuestarias';	
		$conexionbd->StartTrans();
		try
		{
			$consulta = " SELECT ep1origen, ep2origen, ep3origen, ep4origen, ep5origen, ep1destino, ep2destino, ep3destino, ep4destino, ep5destino ".
						"  FROM apr_estructurapresupuestaria ";
			$resultestructura = $conexionbd->Execute($consulta);
			if ($resultestructura===false)
			{
				$this->valido = false;
				$cadena = 'Error al Seleccionar las las Estructuras Presupuestarias.'.''.$conexionbd->ErrorMsg();
			}
			else
			{
				while (!$resultestructura->EOF)
				{
					$ep1ant = validarTexto($resultestructura->fields['ep1origen'],0,20,'');
					$ep2ant = validarTexto($resultestructura->fields['ep2origen'],0,6,'');
					$ep3ant = validarTexto($resultestructura->fields['ep3origen'],0,3,'');
					$ep4ant = validarTexto($resultestructura->fields['ep4origen'],0,2,'');
					$ep5ant = validarTexto($resultestructura->fields['ep5origen'],0,2,'');
					$ep1act = validarTexto($resultestructura->fields['ep1destino'],0,20,'');
					$ep2act = validarTexto($resultestructura->fields['ep2destino'],0,6,'');
					$ep3act = validarTexto($resultestructura->fields['ep3destino'],0,3,'');
					$ep4act = validarTexto($resultestructura->fields['ep1destino'],0,2,'');
					$ep5act = validarTexto($resultestructura->fields['ep1destino'],0,2,'');
					
					$consulta = " UPDATE saf_activo ".
								"   SET codestpro1='".$ep1act."',".
								"       codestpro2='".$ep2act."', ".
								"       codestpro3='".$ep3act."', ".
								"       codestpro4='".$ep4act."', ".
								"       codestpro5='".$ep5act."' ".
								" WHERE codestpro1='".$ep1ant."' ".
								"   AND codestpro2='".$ep2ant."' ".
								"   AND codestpro3='".$ep3ant."' ".
								"   AND codestpro4='".$ep4ant."' ".
								"   AND codestpro5='".$ep5ant."' ";
					$result = $conexionbd->Execute($consulta);
					if (is_object($result))
					{
						$consulta = " UPDATE sno_asignacioncargo ".
									"   SET codproasicar='".$ep1act.$ep2act.$ep3act.$ep4act.$ep5act."'".
									" WHERE codproasicar='".$ep1ant.$ep2ant.$ep3ant.$ep4ant.$ep5ant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sigesp_cargos ".
									"   SET codestpro='".$ep1act.$ep2act.$ep3act.$ep4act.$ep5act."'".
									" WHERE codestpro='".$ep1ant.$ep2ant.$ep3ant.$ep4ant.$ep5ant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_concepto ".
									"   SET codpro='".$ep1act.$ep2act.$ep3act.$ep4act.$ep5act."'".
									" WHERE codpro='".$ep1ant.$ep2ant.$ep3ant.$ep4ant.$ep5ant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_hconcepto ".
									"   SET codpro='".$ep1act.$ep2act.$ep3act.$ep4act.$ep5act."'".
									" WHERE codpro='".$ep1ant.$ep2ant.$ep3ant.$ep4ant.$ep5ant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_proyecto ".
									"   SET estproproy='".$ep1act.$ep2act.$ep3act.$ep4act.$ep5act."'".
									" WHERE estproproy='".$ep1ant.$ep2ant.$ep3ant.$ep4ant.$ep5ant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_hproyecto ".
									"   SET estproproy='".$ep1act.$ep2act.$ep3act.$ep4act.$ep5act."'".
									" WHERE estproproy='".$ep1ant.$ep2ant.$ep3ant.$ep4ant.$ep5ant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_unidadadmin ".
									"   SET codprouniadm='".$ep1act.$ep2act.$ep3act.$ep4act.$ep5act."'".
									" WHERE codprouniadm='".$ep1ant.$ep2ant.$ep3ant.$ep4ant.$ep5ant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE sno_hunidadadmin ".
									"   SET codprouniadm='".$ep1act.$ep2act.$ep3act.$ep4act.$ep5act."'".
									" WHERE codprouniadm='".$ep1ant.$ep2ant.$ep3ant.$ep4ant.$ep5ant."'";
						$result = $conexionbd->Execute($consulta);
					}
					if (is_object($result))
					{
						$consulta = " UPDATE spg_unidadadministrativa ".
									"   SET codestpro1='".$ep1act."',".
									"       codestpro2='".$ep2act."', ".
									"       codestpro3='".$ep3act."', ".
									"       codestpro4='".$ep4act."', ".
									"       codestpro5='".$ep5act."' ".
									" WHERE codestpro1='".$ep1ant."' ".
									"   AND codestpro2='".$ep2ant."' ".
									"   AND codestpro3='".$ep3ant."' ".
									"   AND codestpro4='".$ep4ant."' ".
									"   AND codestpro5='".$ep5ant."' ";
						$result = $conexionbd->Execute($consulta);
					}
					
					$resultestructura->MoveNext();
				}
			}
		}
		catch (exception $e) 
		{
			$this->valido = false;
			$this->mensaje='Ocurrio un error en la Transferencia. '.$conexionbd->ErrorMsg();
			escribirArchivo($this->archivo,'* Ocurrio un error en la Transferencia. ');
			escribirArchivo($this->archivo,'* Error  '.$conexionbd->ErrorMsg());
			escribirArchivo($this->archivo,'*******************************************************************************************************');
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('PROCESAR',$this->valido);			
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
************************************************************************************/
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