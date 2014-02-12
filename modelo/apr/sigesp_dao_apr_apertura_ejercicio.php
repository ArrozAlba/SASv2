<?php
/***********************************************************************************
 * @Modelo para la apertura del ejercicio contable.
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

//require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/shared/class_folder/class_sigesp_int.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/shared/class_folder/class_sigesp_int_int.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/shared/class_folder/class_sigesp_int_spg.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/shared/class_folder/class_sigesp_int_scg.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/shared/class_folder/class_sigesp_int_spi.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/apr/class_folder/class_sigesp_int.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/apr/class_folder/class_sigesp_int_int.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/apr/class_folder/class_sigesp_int_spg.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/apr/class_folder/class_sigesp_int_scg.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/apr/class_folder/class_sigesp_int_spi.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/shared/class_folder/class_mensajes.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/shared/class_folder/class_fecha.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/apr/class_folder/class_fecha.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/shared/class_folder/sigesp_include.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/shared/class_folder/class_funciones.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/shared/class_folder/sigesp_c_seguridad.php');

class AperturaEjercicio extends ADODB_Active_Record
{
	var $_table = 'scg_cuentas';
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
	public $resultapertura;
	
/************************************************************************************
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
	public function procesarAperturaEjercicio()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$conexionbdorigen = conectarBD($_SESSION['sigesp_servidor'], $_SESSION['sigesp_usuario'], $_SESSION['sigesp_clave'],
									   $_SESSION['sigesp_basedatos'], $_SESSION['sigesp_gestor']);
		
		$this->mensaje = 'Proceso la apertura del ejercicio contable';
		
		$fecdesde = convertirFechaBd($this->periodo);
		$fecdesde = substr($fecdesde,0,10);
		$anno     = substr($this->periodo,0,4);
		$fechasta = '31/12/'.$anno;
		$fechasta = convertirFechaBd($fechasta);		
		$conexionbd->StartTrans();
		try
		{						
			//$this->seleccionarConexion(&$conexionbd);
		$this->ls_activo=trim($_SESSION["la_empresa"]["activo"]);
		$this->ls_pasivo=trim($_SESSION["la_empresa"]["pasivo"]);
		$this->ls_resultado=trim($_SESSION["la_empresa"]["resultado"]);
		$this->ls_capital=trim($_SESSION["la_empresa"]["capital"]);
		$this->ls_orden_d=trim($_SESSION["la_empresa"]["orden_d"]);
		$this->ls_orden_h=trim($_SESSION["la_empresa"]["orden_h"]);
		$this->ls_ingreso=trim($_SESSION["la_empresa"]["ingreso"]);
		$this->ls_gastos =trim($_SESSION["la_empresa"]["gasto"]);
		$this->ls_cta_resultado = trim($_SESSION["la_empresa"]["c_resultad"]);			
		 $consulta=" SELECT SC.sc_cuenta,SC.denominacion,SC.status,SC.nivel as rnivel, ".
              "        coalesce(curSaldo.T_Debe,0) as total_debe, ".
              "        coalesce(curSaldo.T_Haber,0) as total_haber,0 as nivel ".
              " FROM scg_cuentas SC LEFT OUTER JOIN (SELECT codemp,sc_cuenta, coalesce(sum(debe_mes),0)as T_Debe, ".
			  "                                             coalesce(sum(haber_mes),0) as T_Haber ".
              "                                      FROM   scg_saldos ".
              "                                      WHERE  codemp='".$this->codemp."' AND fecsal<='".$fechasta."' ".
              "                                      GROUP BY codemp,sc_cuenta) curSaldo ".
              " ON SC.sc_cuenta=curSaldo.sc_cuenta ".
              " WHERE SC.codemp=curSaldo.codemp AND  curSaldo.codemp='".$this->codemp."' AND ".
			  "       (SC.sc_cuenta like '".$this->ls_activo."%' OR SC.sc_cuenta like '".$this->ls_pasivo."%' OR ".
			  "        SC.sc_cuenta like '".$this->ls_resultado."%' OR  SC.sc_cuenta like '".$this->ls_capital."%' OR ".
			  "        SC.sc_cuenta like '".$this->ls_orden_d."%' OR SC.sc_cuenta like '".$this->ls_orden_h."%') ".
			  " and SC.status='C'".
              " ORDER BY trim(SC.sc_cuenta) ";  
			escribirArchivo($this->archivo,$consulta);
			$resultSCG = $conexionbdorigen->Execute($consulta);
			if ($resultSCG===false)
			{
				escribirArchivo($this->archivo,'* Error al Seleccionar los saldos de la origen '.''.$conexionbd->ErrorMsg());
				$this->valido = false; 
			}
			else
			{	
				$this->arrSaldosContables = array('sccuenta'=>array(),'denominacion'=>array(),'saldoant'=>array(),'debe'=>array(),'haber'=>array(),'saldoact'=>array());
				$i = 0;			
				while (!$resultSCG->EOF) 
				{
					$this->arrSaldosContables['sccuenta'][$i] 	= $resultSCG->fields['sc_cuenta'];
					$this->arrSaldosContables['denominacion'][$i] = $resultSCG->fields['denominacion'];	
					$this->arrSaldosContables['saldoant'][$i] 	= 0;//$resultSCG->fields['saldo_ant'];
					$this->arrSaldosContables['debe'][$i] 		= $resultSCG->fields['total_debe'];
					$this->arrSaldosContables['haber'][$i] 		= $resultSCG->fields['total_haber'];
					$i++;
					$resultSCG->MoveNext();
				}				
				$this->valido = true;				
			}
			if ($this->valido)
			{				
				$periodo = '';
				$this->seleccionarPeriodo();
				$anno     = substr($this->periodo,0,4);
				$periodo  = '31/12/2008';
				$fecdesde = convertirFechaBd($periodo);
				$autoconta = true;
				if ($this->tipo=='B')
				{
					$fuente = $this->ced_ben;
				}
				if ($this->tipo=='P')
				{
					$fuente = $this->cod_prov;
				}
				if ($this->tipo=='-')
				{
					$fuente = '----------';
				}
				$codban = '---';
				$ctaban = '-------------------------';
				
				$this->objInt = new class_sigesp_int_int();
				
				if ($this->valido)
				{
					//Insertar los Saldos Contables Iniciales
					$this->valido = $this->objInt->uf_int_init($this->codemp,$this->procede,$this->comprobante,$fecdesde,$this->descripcion,$this->tipo,
															   $fuente,$autoconta,$codban,$ctaban,$this->tipo_cmp); 
					//escribirArchivo($this->archivo,'INSERT INTO sigesp_cmp (codemp ,procede ,comprobante,fecha,codban,ctaban,descripcion,tipo_comp,tipo_destino,cod_pro,ced_bene,total) VALUES ("'.$this->codemp.'","'.$this->procede.'","'.$this->comprobante.'","'.$fecdesde.'","---","-------------------------","APERTURA DE SALDOS","2","-","----------","----------",0);');
				}																
				if ($this->valido)
				{					
					$total = count($this->arrSaldosContables['sccuenta']);					
					$j=0;
					while ($j<$total && $this->valido)
					{							
						$sccuenta 	  = $this->arrSaldosContables['sccuenta'][$j];
						$denominacion = $this->arrSaldosContables['denominacion'][$j];
						$saldoant     = number_format($this->arrSaldosContables['saldoant'][$j],2,".","");
						$debe		  = number_format($this->arrSaldosContables['debe'][$j],2,".","");
						$haber		  = number_format($this->arrSaldosContables['haber'][$j],2,".","");
						$saldoact	  = ($saldoant+$debe-$haber);
						$saldoact	  = number_format($saldoact,2,".","");
						//escribirArchivo($this->archivo,'O'.$sccuenta.','.$debe.','.$haber.','.$saldoact);
						if ($saldoact!=0)
						{
							$monto = abs($saldoact);		
							if ($saldoact>0)
							{
								$operacion = 'D';
							}
							if ($saldoact<0)
							{
								$operacion = 'H';
							}
							//escribirArchivo($this->archivo,'INSERT INTO scg_dt_cmp (codemp ,procede ,comprobante,fecha,codban,ctaban,sc_cuenta,procede_doc,documento,debhab,descripcion,monto,orden) VALUES ("'.$this->codemp.'","'.$this->procede.'","'.$this->comprobante.'","'.$fecdesde.'","---","-------------------------","'.$sccuenta.'","'.$this->procede.'","'.$this->comprobante.'","'.$operacion.'","APERTURA DE SALDOS",'.$monto.',0);');
							$this->valido = $this->objInt->uf_scg_insert_datastore($this->codemp,$sccuenta,$operacion,$monto,
																				 $this->comprobante,$this->procede,$this->descripcion);													 																	 
						}					
						$j++;						
					}				
				}				
				if ($this->valido)
				{
					$this->valido = $this->objInt->uf_init_end_transaccion_integracion('');
				}				
				$this->objInt->uf_sql_transaction($this->valido);				
				escribirArchivo($this->archivo,''.$this->objInt->is_msg_error);
			}
			if ($this->valido)
			{
				escribirArchivo($this->archivo,'*******************************************************************************************************');
				escribirArchivo($this->archivo,'La Apertura de Contabilidad se Creo con Exito');
				escribirArchivo($this->archivo,'*******************************************************************************************************');		
			}
			else
			{
				escribirArchivo($this->archivo,'*******************************************************************************************************');
				escribirArchivo($this->archivo,''.$this->objInt->is_msg_error);
				escribirArchivo($this->archivo,'*******************************************************************************************************');		
			
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
* @Función para obtener el periodo de la empresa.
* @parametros:
* @retorno:
* @fecha de creación: 15/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	public function seleccionarPeriodo()
	{
		global $conexionbd;
				
		$this->existe = false;
		$consulta = " SELECT periodo ".
					" FROM sigesp_empresa ".
					" WHERE codemp='$this->codemp'";
		$result = $conexionbd->Execute($consulta);
		if ($result===false)
		{
			$this->mensaje = 'Error al seleccionar el periodo '.$conexionbd->ErrorMsg();
			return false;
		}
		elseif (!$result->EOF)
		{
			$this->periodo = $result->fields['periodo'];
			$this->existe  = true;
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