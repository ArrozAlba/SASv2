<?php
/***********************************************************************************
 * @Modelo para el traspaso de saldos y movimientos en tránsito
 * @fecha de creación: 04/12/2008.
 * @autor: Ing. Gusmary Balza B.
 * **************************
 * @fecha modificacion
 * @autor
 * @descripcion
 ***********************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_registroeventos.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_registrofallas.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_sistemaventana.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_funciones.php');

class  TraspasoSaldos extends ADODB_Active_Record
{
	var $_table = 'scb_movbco';
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
	public $resulttransito;
	public $resultcol;

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
	
	
	public function iniciarTransaccion()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
	}
	public function completarTransaccion()
	{
		global $conexionbd;
		$conexionbd->CompleteTrans();
	}
		
	
/***********************************************************************************
* @Función para procesar el traspaso de los saldos y movimientos en tránsito.   
* @parametros: 
* @retorno:
* @fecha de creación: 05/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function procesarSaldos() 
	{
		global $conexionbd;
		
		//$conexionbd->debug = 1;
		$conexionbdorigen = conectarBD($_SESSION['sigesp_servidor'], $_SESSION['sigesp_usuario'], $_SESSION['sigesp_clave'],
											 $_SESSION['sigesp_basedatos'], $_SESSION['sigesp_gestor']);
		
		$this->mensaje='Realizó el traspaso de saldos '.$this->sistema;		
		$conexionbd->StartTrans();
		try 
		{ 							 
			
			
			$consultaCuenta = " SELECT ctaban 		". //modificada
				   			  " FROM scb_ctabanco 				".
							  " WHERE codemp='{$this->codemp}' 	".
							  " AND codban='{$this->codban}' 	".							
							  " AND ctaban='{$this->ctaban}' 	".
							  " GROUP BY codban,ctaban,codtipcta ".
							  " ORDER BY codban,ctaban 			";			
			$resultCuenta = $conexionbdorigen->Execute($consultaCuenta);
			if ($resultCuenta==false)
			{
				escribirArchivo($this->archivo,'* "Error al trasladar saldos - Obtención de la cuenta bancaria '.''.$conexionbd->ErrorMsg());
				$this->valido = false; //return false;
			}
			elseif (!$resultCuenta->EOF)
			{				
				$this->ctaban = $resultCuenta->fields['ctaban']; 
			}
			//obtener colocaciones
		
			$consultacol = " SELECT numcol,codtipcol,codban,ctaban, 0.0000  AS saldo 	".
			   			   " FROM scb_colocacion 										".
			  			   " GROUP by codban,ctaban,numcol,codtipcol 					";			
			$this->resultcol = $conexionbdorigen->Execute($consultacol);
			if ($this->resultcol===false)
			{
				escribirArchivo($this->archivo,'* Error al trasladar saldos - Obtención de las colocaciones.'.''.$conexionbd->ErrorMsg());
				$this->valido = false; 
			}
			
			if ($this->valido)
			{				
				//calcular los saldos de los documentos
				$saldodocumentosaux = 0;
				$saldodocumentosaux = $this->calcularSaldoDocumento($conexionbdorigen);
				$this->saldo = 	$saldodocumentosaux;		
				if ($saldodocumentosaux===false)
					return false;					
				
				while (!$this->resultcol->EOF)
				{
					$this->banco  = $this->resultcol->fields['codban'];
					$this->numcol = $this->resultcol->fields['numcol'];
					$this->cuenta = $this->resultcol->fields['ctaban'];					
					$this->saldo  = $this->calcularSaldoColocacion($conexionbdorigen);
					
					if ($this->saldo===false)
						return false;
					else													
									
					$this->resultcol->MoveNext();	
				}
				
				//chequear los movimientos en tránsito
				if ($this->movtransito===true)
				{			
					
					$consultatransito = " SELECT *
						   		  		  FROM scb_movbco 
						  		 		  WHERE codemp='$this->codemp' 
										  AND codban='$this->codban' 
										  AND ctaban='$this->ctaban' 
										  AND estcon=0 
										  AND (estmov='C' OR estmov='L')
						  				  ORDER BY codban,ctaban,numdoc		";								  				  				  						  				  
					$this->resulttransito = $conexionbdorigen->Execute($consultatransito);
										
					if ($this->resulttransito===false)
					{
						escribirArchivo($this->archivo,'* Error al Trasladar saldos - Obtención de los movimientos en tránsito.'.''.$conexionbd->ErrorMsg());
						$this->valido = false; 
					}	
										
					// Movimientos en transito en forma resumida								
					$consulta = " SELECT codban,ctaban,codope, 
									SUM(monto-monret) AS total,estmov 
							  	  FROM scb_movbco 
							  	  WHERE codemp='$this->codemp' 
								  AND codban='$this->codban' 
							      AND ctaban='$this->ctaban' 
								  AND estcon=0 
								  AND (estmov='C' OR estmov='L')
							      GROUP by codban,ctaban,codope,estmov 
							      ORDER BY codban,ctaban				";
					
					$result = $conexionbdorigen->Execute($consulta);
					if ($result===false)
					{
						escribirArchivo($this->archivo,'*Error al Trasladar saldos -Obtención de los movimientos en tránsito resumidos.'.''.$conexionbd->ErrorMsg());
						$this->valido = false; 
					}	
					else
					{
						$debitosaux  = 0;
						$creditosaux = 0;
						$debitosnegativosaux  = 0;
						$creditosnegativosaux = 0;
					
						while (!$result->EOF)
						{
							$this->operacion = $result->fields['codope'];
							$this->estado    = $result->fields['estmov'];
							$this->monto     = $result->fields['total'];
							if((($this->operacion=='CH') || ($this->operacion=='ND') || ($this->operacion=='RE')) && ($this->estado!='A'))
							{	
								$creditosaux += $this->monto;
							}	
							elseif((($this->operacion=='CH') || ($this->operacion=='ND') || ($this->operacion=='RE')) && ($this->estado=='A'))
							{	
								$creditosnegativosaux += $this->monto;
							}	
							elseif((($this->operacion=='DP') || ($this->operacion=='NC')) && ($this->estado!='A'))
							{	
								$debitosaux += $this->monto;
							}
							elseif((($this->operacion=='DP') || ($this->operacion=='NC')) && ($this->estado=='A'))
							{
								$debitosnegativosaux += $this->monto;
							}
							$result->MoveNext();
						}
					}  		  
					if ($this->valido) //Si los mov en transito se encontraron sin error
					{
						
						$debitos  = $debitosaux  - $debitosnegativosaux;
						$creditos = $creditosaux - $creditosnegativosaux;						
						$saldoaux = $saldodocumentosaux;					
						$this->saldo = $saldoaux + $creditos - $debitos;										
					} 
				}
				
				//traspaso de los saldos de banco
				$this->existe = $this->verificarCuenta();
				if ($this->existe===true)
				{
					
					//$saldo = $saldodocumentosaux; 					
					$saldo = $this->saldo;
					if ($saldo>=0)
					{
						$this->operacion = 'NC';
					}
					else
					{
						$this->operacion = 'ND';
					}
					
					$this->saldo = abs($saldo);
					$this->numdoc = '0000000APERTURA';
					$esta = $this->verificarApertura();
					if ($esta===false)
					{
										
						$conexionbd->StartTrans();
						$this->fecmov = $this->fecfin;
						$this->codope = $this->operacion;						
						$this->conmov = 'SALDO INICIAL DE LA CUENTA';
						$this->nomproben = '';
						$this->codconmov = '---';
						$this->tipo_destino = '';
						$this->estmov    = 'L';
						$this->monto     = $this->saldo;
						$this->estbpd    = 'M';
						$this->estcon    = 0;
						$this->estcobing = 0;
						$this->esttra    = 0;
						$this->estimpche = 1;
						$this->monret    = 0.0000;
						$this->cod_pro   = '----------';
						$this->ced_bene  = '----------';
						$this->feccon    = '1900-01-01';
						$this->monobjret = $this->saldo;
						$this->codbansig = '';
						$this->numsolmin = '';
						$exito = $this->Save();
						$conexionbd->CompleteTrans();
										
						if ($exito===false)
						{
							escribirArchivo($this->archivo,'* Error al Insertar apertura -Inserción del documento de la Apertura- .'.''.$conexionbd->ErrorMsg());
							$this->valido = false; 
						}
					}
				}
				else
				{
					$this->valido = false;
					if ($this->existe===false)
					{
						escribirArchivo($this->archivo,'* La cuenta No. '.$this->ctaban.' no existe en la nueva Base de Datos.'.''.$conexionbd->ErrorMsg());
						$this->valido = false;

						$this->mensaje = 'La cuenta No. '. $this->ctaban.' no existe en la nueva Base de Datos';
					}
					else
					{
						$this->mensaje = 'Error al buscar la cuenta';
					}
				}
				//insertar los movimientos en tránsito				
				$indicesrepetidos = array();
				$indiceresult = 0;
				if ($this->movtransito && $this->valido)
				{ 
					//repetir para obtener los datos
					$consultatransito = " SELECT *
						   		  		  FROM scb_movbco 
						  		 		  WHERE codemp='$this->codemp' 
										  AND codban='$this->codban' 
										  AND ctaban='$this->ctaban' 
										  AND estcon=0 
										  AND (estmov='C' OR estmov='L')
						  				  ORDER BY codban,ctaban,numdoc";	
					  				  					  				  						  				  
					$this->resulttransito = $conexionbdorigen->Execute($consultatransito);
					$pos = 0;
					$numdoctransito = array();
					while (!$this->resulttransito->EOF)
					{	
								
						$this->banco    = $this->resulttransito->fields['codban'];
						$this->cuenta    = $this->resulttransito->fields['ctaban'];
						$this->numdoc    = $this->resulttransito->fields['numdoc'];
						escribirArchivo($this->archivo,'Movimiento en transito  Banco '.$this->banco.' - Cuenta '.$this->cuenta.' - Número de Documento '.$this->numdoc);
						$numdoctransito[$pos] = $this->numdoc;
						
						$this->codope    = $this->resulttransito->fields['codope'];
						$this->estmov    = $this->resulttransito->fields['estmov'];
						$this->fecmov    = $this->resulttransito->fields['fecmov'];
						$this->conmov    = $this->resulttransito->fields['conmov'].' .Fecha Original del Documento: '.$this->fecmov;
						$this->nomproben = $this->resulttransito->fields['nomproben'];
						$this->codpro    = $this->resulttransito->fields['cod_pro'];
						if (strlen(trim($this->codpro)) < 10 )
						{
							$this->codpro=str_pad($this->codpro,8,'0',0).'00';
						}
						$this->cedbene   = $this->resulttransito->fields['ced_bene'];
						$this->chevau    = $this->resulttransito->fields['chevau'];
						$this->tipodestino = $this->resulttransito->fields['tipo_destino'];
						$this->codconmov   = $this->resulttransito->fields['codconmov'];
						$this->monto     = $this->resulttransito->fields['monto'];
						$this->estcon    = $this->resulttransito->fields['estcon'];
						$this->estcobing = $this->resulttransito->fields['estcobing'];
						$this->esttra    = $this->resulttransito->fields['esttra'];
						$this->estimpche = $this->resulttransito->fields['estimpche'];
						$this->monobjret = $this->resulttransito->fields['monobjret'];
						$this->monret    = $this->resulttransito->fields['monret'];
						$this->procede   = $this->resulttransito->fields['procede'];
						$this->comprobante = $this->resulttransito->fields['comprobante'];
						$this->fecha     = $this->resulttransito->fields['fecha'];
						$this->id_mco    = $this->resulttransito->fields['id_mco'];
						$this->emicheproc = $this->resulttransito->fields['emicheproc'];
						$this->emicheced = $this->resulttransito->fields['emicheced'];
						$this->emichenom = $this->resulttransito->fields['emichenom'];
						$this->emichefec = $this->resulttransito->fields['emichefec'];
						if ($this->emichefec=='')
						{
							$this->emichefec = '1900-01-01';
						}
						$this->estmovint = $this->resulttransito->fields['estmovint'];
						$this->codusu    = $this->resulttransito->fields['codusu'];
						$this->codopeidb = $this->resulttransito->fields['codopeidb'];
						
						$this->aliidb    = $this->resulttransito->fields['aliidb'];
						
						$this->feccon    = $this->resulttransito->fields['feccon'];
						$this->estreglib = $this->resulttransito->fields['estreglib'];
						$this->numcarord = $this->resulttransito->fields['numcarord'];
						$this->numpolcon = $this->resulttransito->fields['numpolcon'];
						$this->coduniadmsig = $this->resulttransito->fields['coduniadmsig'];
						$this->codbansig    = $this->resulttransito->fields['codbansig'];
						$this->fecordpagsig = $this->resulttransito->fields['fecordpagsig'];
						if ($this->fecordpagsig=='')
						{
							$this->fecordpagsig = '1900-01-01';
						}
						$this->tipdocressig = $this->resulttransito->fields['tipdocressig'];
						$this->numdocressig = $this->resulttransito->fields['numdocressig'];
						$this->estmodordpag = $this->resulttransito->fields['estmodordpag'];
						$this->codfuefin = $this->resulttransito->fields['codfuefin'];
						$this->forpagsig = $this->resulttransito->fields['forpagsig'];
						$this->medpagsig = $this->resulttransito->fields['medpagsig'];
						$this->codestprosig = $this->resulttransito->fields['codestprosig'];
						$this->nrocontrolop = $this->resulttransito->fields['nrocontrolop'];
						$this->fechaconta = $this->resulttransito->fields['fechaconta'];
						$this->fechaanula = $this->resulttransito->fields['fechaanula'];
						
						$this->codban = $this->banco;
						$this->ctaban = $this->cuenta;
						$this->existe = $this->verificarCuenta();						
						if ($this->existe===true) 
						{
							$this->codban = $this->banco;
							$this->ctaban = $this->cuenta;
							$this->existe = $this->verificarDocumento();
							if ($this->existe===true)  //Si el documento ya existe en la bd, guardamos su indice para luego imprimirlo.
							{
								array_push($indicesrepetidos,$indiceresult);	
							}
							else	
							{	
								$this->codban = $this->banco;
								$this->ctaban = $this->cuenta;
								$consulta = " INSERT INTO scb_movbco (codemp, codban, ctaban, numdoc, codope, estmov, cod_pro, ced_bene, 
						                                   tipo_destino, codconmov, fecmov, conmov, nomproben, monto, estbpd, estcon, 
														   estcobing, esttra, chevau, estimpche, monobjret, monret, procede, comprobante,
														   fecha, id_mco, emicheproc, emicheced, emichenom, emichefec, estmovint,
														   codusu, codopeidb, aliidb, feccon, estreglib, numcarord, numpolcon,
														   coduniadmsig, codbansig, fecordpagsig, tipdocressig, numdocressig, estmodordpag, 
														   codfuefin, forpagsig, medpagsig, codestprosig, nrocontrolop, fechaconta,  
														   fechaanula) 														   
												    VALUES ('$this->codemp','$this->codban','$this->ctaban','$this->numdoc','$this->codope',
													       	'L','$this->codpro','$this->cedbene','$this->tipodestino','$this->codconmov',
															'$this->fecmov','$this->conmov','$this->nomproben',$this->monto,'D',
															$this->estcon,$this->estcobing,$this->esttra,'$this->chevau',
															'$this->estimpche',$this->monobjret,$this->monret,
															'$this->procede','$this->comprobante','$this->fecha','$this->id_mco',
															$this->emicheproc,'$this->emicheced','$this->emichenom','$this->emichefec',$this->estmovint,
								                            '$this->codusu','$this->codopeidb',0,'$this->feccon',
															'$this->estreglib','$this->numcarord',$this->numpolcon,'$this->coduniadmsig',
															'$this->codbansig','$this->fecordpagsig','$this->tipdocressig','$this->numdocressig',
															'$this->estmodordpag','$this->codfuefin','$this->forpagsig','$this->medpagsig',
															'$this->codestprosig','$this->nrocontrolop','$this->fechaconta','$this->fechaanula'
															) ";
								$exito = $conexionbd->Execute($consulta);
								if ($exito==false)
								{
									escribirArchivo($this->archivo,'Error al Insertar apertura -Inserción de los movimientos en tránsito-.'.''.$conexionbd->ErrorMsg());
									$this->valido = false;
								}
							}
						}
						else
						{
							$this->valido = false;
							if ($this->existe===false) //no existe la cuenta
							{
								escribirArchivo($this->archivo,'La cuenta No. '.$this->ctaban.' no existe en la nueva Base de Datos');
								$this->mensaje = 'La cuenta No. '.$this->ctaban.' no existe en la nueva Base de Datos';
							}
							else
							{
								$this->mensaje = 'Error al buscar la cuenta';
							}							
						}
						$indiceresult++;
						$pos++;
						$this->resulttransito->MoveNext();
					}
				}
				//se insertar los movimientos de colocación
				if ($this->valido)
				{					
					$this->resultcol->MoveFirst();
					while (!$this->resultcol->EOF)
					{
						$this->banco = $this->resultcol->fields['codban'];
						$this->numcol = $this->resultcol->fields['numcol'];
						$this->cuenta = $this->resultcol->fields['ctaban'];	
						escribirArchivo($this->archivo,'Movimiento en Colocación  Banco '.$this->banco.' - Cuenta '.$this->cuenta.' - Número de Colocación '.$this->numcol);
						$this->saldo  = $this->resultcol->fields['saldo'];	
						$this->codban = $this->banco;
						$this->ctaban = $this->cuenta;			
						$this->existe = $this->verificarColocacion();
						if ($this->existe===true) //Si la colocacion existe en la nueva bd
						{
							if ($this->saldo >= 0)
							{
								$this->operacion = 'NC';
							}
							else
							{
								$this->operacion = 'ND';
							}
							$this->saldo = abs($this->saldo);
							
							$this->codban = $this->banco;
							$this->ctaban = $this->cuenta;
							$this->numdoc = '0000000APERTURA';	
							$this->codope = $this->operacion;
							$this->estcol = 'L';
							$this->existe = $this->verificarMovimientoColocacion();
							if ($this->existe===false)
							{
								$consulta = " INSERT INTO scb_movcol (codemp,codban,ctaban,numcol, 			".
											"			numdoc,codope,estcol,fecmovcol,monmovcol, 			".
											"			tasmovcol,conmov,estcob,esttranf) 					".
											" VALUES ('$this->codemp','$this->banco','$this->cuenta', 		".
											"		'$this->numcol','0000000APERTURA','$this->operacion',	".
											"		 'L','$this->fecfin','$this->saldo',0, 					".
											"		'SALDO INICIAL DE LA COLOCACION',0,0)					";
								
								$result = $conexionbd->Execute($consulta);
								if ($result===false)
								{
									escribirArchivo($this->archivo,'Error al Insertar apertura - Inserción de movimientos de colocación'.''.$conexionbd->ErrorMsg());
									$this->valido = false;
								}					 
							}
							else
							{
								if($this->existe===false)
									$this->valido = false;
							}
						}
						else
						{
							if ($this->existe===false)
							{
								escribirArchivo($this->archivo,'La colocación No. '.$this->numcol.' NO existe en la nueva Base de Datos');
								
								$this->mensaje = '';
							}
							else
							{
								$this->mensaje = 'Error al buscar la colocacion';
							}
						}
						
						
						$this->resultcol->MoveNext();
					}
				}
								
			}
			if ($this->valido===true)
			{
				$total = count($indicesrepetidos);
				if ($total > 0)
				{
					$fecha = date('Y_m_d_H_i');
					$nombre   = '../../vista/apr/resultados/documentos_repetidos'.$fecha.'.txt';
					$this->archivo2 = @fopen($nombre,'a+');
					$this->mensaje = 'Se ha generado un archivo (documentos_repetidos.txt), el cual contiene los documentos que no se pueden traspasar, debido a que ya se encuentran registrados en la nueva Base de Datos.';
					
					for ($i=0; $i<$total; $i++)
					{
						$indice    = $indicesrepetidos[$i];
						$this->numdoc = $numdoctransito[$indice];
						escribirArchivo($this->archivo2,'* El Movimiento No. '.$this->numdoc.' no pudo ser traspasado debido a que ya existía en la nueva Base de Datos .');
					}
				}
				escribirArchivo($this->archivo,'*Proceso ejecutado sin errores!!! ');
			}
		}
		catch (exception $e) 
		{
			$this->valido = false;
			$this->mensaje='Ocurrio un error en la Transferencia. '.$conexionbd->ErrorMsg();
			escribirArchivo($this->archivo,'* Ocurrio un error en la Transferencia. ');
			escribirArchivo($this->archivo,$consulta);
			escribirArchivo($this->archivo,'* Error  '.$conexionbd->ErrorMsg());
			escribirArchivo($this->archivo,'*******************************************************************************************************');
		}			
		$conexionbd->CompleteTrans();
		//$this->incluirSeguridad('PROCESAR',$this->valido);			
	}
	
	

/*********************************************************************************
* @Funcion que calcula el saldo de las colocaciones.
* @parametros:
* @retorno: el saldo si se ejecuto correctamente, de lo contrario retorna falso.
* @fecha de creación: 05/12/2008.
* @autor: Ing. Gusmary Balza B.
**********************************************************
* @fecha modificacion
* @autor
* @descripcion
**********************************************************************************/			
	public function calcularSaldoColocacion($conexionbdorigen)
	{
		global $conexionbd;
				
		$consulta = " SELECT COALESCE(SUM(monmovcol),0) AS monto
			   		  FROM scb_movcol
			  		  WHERE codemp='{$this->codemp}' 
			    	  AND codban='{$this->banco}' 
					  AND numcol='{$this->numcol}' 
					  AND (codope='CH' OR codope='ND' OR codope='RE')	
					  AND estcol<>'A'";
			  
		$result = $conexionbdorigen->Execute($consulta);
		if ($result===false)
		{
			escribirArchivo($this->archivo,'* Error al Calcular el saldo de las colocaciones. (Creditos no anulados). '.$conexionbd->ErrorMsg());
			$this->valido = false;
		}
		elseif (!$result->EOF)
		{
			$this->creditosaux = $result->fields['monto'];
		}			  
		if ($this->valido) //// Calculando el monto de los Creditos negativos (anulados)
		{	
					
			$consulta = " SELECT COALESCE(SUM(monmovcol),0) AS monto
				   		  FROM scb_movcol
				  		  WHERE codemp='{$this->codemp}' 
				   		  AND codban='{$this->codban}' 
						  AND numcol='{$this->numcol}' 
						  AND (codope='CH' OR codope='ND' OR codope='RE') 
						  AND estcol='A'";
						  
			$result = $conexionbdorigen->Execute($consulta);
			if ($result===false)
			{
				escribirArchivo($this->archivo,'* Error al Calcular el saldo de las colocaciones. (Creditos anulados). '.$conexionbd->ErrorMsg());
				$this->valido = false;
			}	
			elseif (!$result->EOF)
			{
				$this->creditonegativosaux = $result->fields['monto'];
			}
		}				  
		if ($this->valido) //  Calculando el monto de los Debitos positivos (no anulados)
		{			
			
			$consulta = " SELECT COALESCE(SUM(monmovcol),0) AS monto  
						  FROM scb_movcol
						  WHERE codemp='{$this->codemp}' 
						  AND codban='{$this->codban}'
						  AND numcol='{$this->numcol}'
						  AND (codope='DP' OR codope='NC') AND estcol<>'A'";
						  
			$result = $conexionbdorigen->Execute($consulta);
			if ($result===false)
			{
				escribirArchivo($this->archivo,'* Error al Calcular el saldo de las colocaciones (Debitos no anulados). '.$conexionbd->ErrorMsg());
				$this->valido = false;
			}	
			elseif (!$result->EOF)
			{
				$this->debitosaux = $result->fields['monto'];
			}			  
		}
		if ($this->valido) //  Calculando el monto de los Debitos positivos (no anulados)
		{	
			$consulta = " SELECT COALESCE(SUM(monmovcol),0) AS monto
				   		  FROM scb_movcol
				  		  WHERE codemp='{$this->codemp}' 
				    	  AND codban='{$this->codban}' 
						  AND numcol='{$this->numcol}'
				    	  AND (codope='DP' OR codope='NC') 
						  AND estcol='A'";
			$result = $conexionbdorigen->Execute($consulta);
			if ($result===false)
			{
				escribirArchivo($this->archivo,'* Error al Calcular el saldo de las colocaciones (Debitos anulados). '.$conexionbd->ErrorMsg());
				$this->valido = false;
			}	
			elseif (!$result->EOF)
			{
				$this->debitosnegativosaux = $result->fields['monto'];
			}
		}
		if ($this->valido)
		{
			$debitos  = $this->debitosaux - $this->debitosnegativosaux;
			$creditos = $this->creditosaux - $this->creditonegativosaux;
			$saldo    = $creditos    - $debitos; 
			return ($debitos - $creditos);
		}				  			  
	}
	
	
/*********************************************************************************
* @Funcion que calcula el saldo de los documentos
* @parametros:
* @retorno: el saldo si se ejecuto correctamente, de lo contrario retorna falso.
* @fecha de creación: 05/12/2008.
* @autor: Ing. Gusmary Balza B.
**********************************************************
* @fecha modificacion
* @autor
* @descripcion
**********************************************************************************/				
	public function calcularSaldoDocumento($conexionbdorigen) 
	{
		global $conexionbd;
		
		
		$consulta = " SELECT codope AS operacion, (monto-monret) AS monto, estmov AS estado
			   		FROM scb_movbco
			  		WHERE codemp='{$this->codemp}' 
					AND codban='{$this->codban}' 
					AND ctaban='{$this->ctaban}'";
				
		$result = $conexionbdorigen->Execute($consulta);			  
		if ($result==false)
		{
			escribirArchivo($this->archivo,'* Error al Calcular el saldo de los documentos (Debitos anulados). '.$conexionbd->ErrorMsg());
			$this->valido = false; 
		}	
		else
		{
			$debitosaux  = 0;
			$creditosaux = 0;
			$debitos_negativosaux = 0;
			$creditosnegativosaux = 0;
			
			while (!$result->EOF)
			{
				
				$this->operacion = $result->fields['operacion'];
				$this->estado    = $result->fields['estado'];
				$this->monto     = $result->fields['monto'];
				
				if (($this->operacion=='CH' || $this->operacion=='ND' || $this->operacion=='RE') && ($this->estado!='A'))
				{
					$creditosaux += $this->monto;
				}	
				elseif(($this->operacion=='CH' || $this->operacion=='ND' || $this->operacion=='RE') && ($this->estado=='A'))
			  	{	
					$creditosnegativosaux += $this->monto;
				} 	
				elseif(($this->operacion=='DP' || $this->operacion=='NC') && ($this->estado!='A'))
				{	
					$debitosaux += $this->monto;
				}
				elseif(($this->operacion=='DP' || $this->operacion=='NC') && ($this->estado=='A'))
				{
					$debitos_negativosaux += $this->monto;
				}				
				$result->MoveNext();
			}	
		}
		if ($this->valido)
		{
			$debitos  = $debitosaux  - $debitosnegativosaux;
	 		$creditos = $creditosaux - $creditosnegativosaux;
	  		$saldo    = number_format($debitos,2,'.','') - number_format($creditos,2,'.','');
	  		return $saldo;
		}		
	}
	
	
/*********************************************************************************
* @ Funcion que determina si ya se realizo la apertura,
* @parametros:
* @retorno: 0 si no existe, de lo contrario retorna 1.
* @fecha de creación: 04/12/2008.
* @autor: Ing. Gusmary Balza B.
**********************************************************
* @fecha modificacion
* @autor
* @descripcion
**********************************************************************************/			
	public function verificarApertura()
	{
		global $conexionbd;
		$consulta = " SELECT COUNT(numdoc) as cantidad
			   		  FROM scb_movbco
				 	  WHERE codemp='{$this->codemp}' AND codban='{$this->codban}' 					 
					  AND ctaban='{$this->ctaban}' AND numdoc='{$this->numdoc}'";
		$result = $conexionbd->Execute($consulta);			  
		if ($result==false)
		{
			escribirArchivo($this->archivo,'* Error al Verificar Apertura. '.$conexionbd->ErrorMsg());
			$this->valido = false; 
		}
		elseif (!$result->EOF)
		{
			if ($result->fields['cantidad']>0)
				return true;
			else
				return false;	
		}		  			  
	}
	
	
/*********************************************************************************
* @ Funcion que determina si existen colocaciones.
* @parametros:
* @retorno: 0 si no existe, de lo contrario retorna 1.
* @fecha de creación: 04/12/2008.
* @autor: Ing. Gusmary Balza B.
**********************************************************
* @fecha modificacion
* @autor
* @descripcion
**********************************************************************************/		
	public function verificarColocacion()
	{
		global $conexionbd;
		$consulta = " SELECT COUNT(numcol) AS cantidad
			     	  FROM scb_colocacion
			    	  WHERE codemp='{$this->codemp}' 
			    	  AND ctaban='{$this->ctaban}' 
					  AND codban='{$this->codban}' 
					  AND numcol='{$this->numcol}'";
		$result = $conexionbd->Execute($consulta);	
		if ($result==false)
		{
			escribirArchivo($this->archivo,'* Error al Verificar Colocación. '.$conexionbd->ErrorMsg());
			$this->valido = false; //return false;
		}
		elseif (!$result->EOF)
		{
			if ($result->fields['cantidad']>0)
				return true;
			else
				return false;	
		}		  
	}
	
	
/*********************************************************************************
* @ Funcion que determina si determina si existe la cuenta.
* @parametros:
* @retorno: 0 si no existe, de lo contrario retorna 1.
* @fecha de creación: 04/12/2008.
* @autor: Ing. Gusmary Balza B.
**********************************************************
* @fecha modificacion
* @autor
* @descripcion
**********************************************************************************/			
	public function verificarCuenta()
	{
		global $conexionbd;
		$consulta = " SELECT ctaban ".
					" FROM scb_ctabanco ".
					" WHERE codemp='{$this->codemp}' ".
					" AND codban='{$this->codban}' ".
					" AND ctaban='{$this->ctaban}'";
		$result = $conexionbd->Execute($consulta);
		if ($result==false)
		{
			escribirArchivo($this->archivo,'* Error al Verificar Cuenta. '.$conexionbd->ErrorMsg());
			$this->valido = false;
		}
		elseif (!$result->EOF)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
/*********************************************************************************
* @ Funcion que determina si determina si existe el documento.
* @parametros:
* @retorno: 0 si no existe, de lo contrario retorna 1.
* @fecha de creación: 04/12/2008.
* @autor: Ing. Gusmary Balza B.
**********************************************************
* @fecha modificacion
* @autor
* @descripcion
**********************************************************************************/		
	public function verificarDocumento() 
	{
		global $conexionbd;
		$consulta = " SELECT numdoc ".
	                " FROM scb_movbco ".
			 	 	" WHERE codemp='{$this->codemp}' ".
				    " AND codban='{$this->codban}' ".
					" AND ctaban='{$this->ctaban}' ".
			 	    " AND numdoc='{$this->numdoc}' ".
					" AND codope='{$this->codope}'";
		$result = $conexionbd->Execute($consulta);
		if ($result==false)
		{
			escribirArchivo($this->archivo,'* Error al Verificar Documento. '.$conexionbd->ErrorMsg());
			$this->valido = false; 
		}
		elseif (!$result->EOF)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	

/*********************************************************************************
* @Funcion que determina si existen el movimiento de colocacion en la nueva BD
* @parametros:
* @retorno: Retorna 0 si no existe, de lo contrario retorna 1
* @fecha de creación: 04/12/2008.
* @autor: Ing. Gusmary Balza B.
**********************************************************
* @fecha modificacion
* @autor
* @descripcion
**********************************************************************************/	
	public function verificarMovimientoColocacion() 
	{
		global $conexionbd;
		$consulta = " SELECT numcol ".
			   		" FROM scb_movcol ".
			  		" WHERE codemp='{$this->codemp}' ".
			    	" AND codban='{$this->codban}' AND ctaban='{$this->ctaban}' ".
					" AND numcol='{$this->numcol}' AND numdoc='{$this->numdoc}' ".
					" AND codope='{$this->codope}' AND estcol='{$this->estcol}' ";
		
		$result = $conexionbd->Execute($consulta);
		if ($result==false)
		{
			escribirArchivo($this->archivo,'* Error al Verificar Movimiento de Colocación. '.$conexionbd->ErrorMsg());
			$this->valido = false; 
		}
		elseif (!$result->EOF)
		{
			return true;
		}
		else
		{
			return false;
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