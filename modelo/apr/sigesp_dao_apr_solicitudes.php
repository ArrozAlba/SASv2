<?php
/***********************************************************************************
 * @Modelo para el traspaso de solicitudes
 * @fecha de creación: 28/11/2008.
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

class  TraspasoSolicitud extends ADODB_Active_Record
{
	var $_table = 'cxp_rd';
	var $solicitud = array();
	public $mensaje;
	public $valido = true;
	public $existe;
	public $criterio;
	public $numsolorigen;
	public $numsol;
	public $archivo;


/***********************************************************************************
 * @Función que pasa las solicitudes Contabilizadas ó por Pagar del Año Anterior para el año Actual
 * @parametros:
 * @retorno:
 * @fecha de creación: 01/12/2008.
 * @autor: Ing. Gusmary Balza.
 ************************************************************************************
 * @fecha modificación:
 * @descripción:
 * @autor:
 ***********************************************************************************/	
	public function procesarSolicitudes() 
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		try
		{
			$conexionbdorigen = conectarBD($_SESSION['sigesp_servidor'], $_SESSION['sigesp_usuario'], $_SESSION['sigesp_clave'],
												 $_SESSION['sigesp_basedatos'], $_SESSION['sigesp_gestor']);
			$conexionbd->StartTrans();
			$total = count($this->solicitud);
			for ($i=0; $i < $total; $i++)
			{
				$this->numsolorigen = $this->solicitud[$i]->numsol;
				$longpre    = strlen(trim($this->prefijo));
				$longsol    = 15 - $longpre;
				$this->numsol = $this->prefijo.substr($this->solicitud[$i]->numsol,$longpre,$longsol);
				$existe =$this->existeEnDestino();
				if ( $existe == false)
				{
					escribirArchivo($this->archivo,''.$this->numsol);
					escribirArchivo($this->archivo,'Solicitud de Pago Origen '.$this->numsolorigen.' - Solicitud de Pago Destino '.$this->numsol);
					$montoaux = $this->solicitud[$i]->monsol;					
					$pagado = $this->solicitud[$i]->pagado;
					$montoaux = str_replace(',','.',$montoaux);
					$pagado = str_replace(',','.',$pagado);
					$resta = $montoaux - $pagado;
					
					$this->factor = $resta /$montoaux;
										
					$this->procesar($conexionbdorigen);
					if ($this->valido)
					{
						$this->mensaje = 'El proceso se ejecutó satisfactoriamente.';
						escribirArchivo($this->archivo,'El proceso se ejecutó satisfactoriamente.');
					}
				}
				else
				{
					$this->mensaje = 'La solicitud de Pago '.$this->numsolorigen.' ya Existe';
					escribirArchivo($this->archivo,'La solicitud de Pago '.$this->numsolorigen.' ya Existe');
				}
			}
			$conexionbd->CompleteTrans();
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;
			$this->mensaje = 'Ocurrio un error en la Transferencia. '.$conexionbd->ErrorMsg();
			escribirArchivo($this->archivo,'* Ocurrio un error en la Transferencia. ');
			escribirArchivo($this->archivo,'* Error  '.$conexionbd->ErrorMsg());
			escribirArchivo($this->archivo,'*******************************************************************************************************');
	   	} 
		//incluirSeguridad('PROCESAR',$this->valido);
	}

	
/***********************************************************************************
 * @Función para procesar la solicitud y su detalle.
 * @parametros:
 * @retorno:
 * @fecha de creación: 01/12/2008.
 * @autor: Ing. Gusmary Balza.
 ************************************************************************************
 * @fecha modificación:
 * @descripción:
 * @autor:
 ***********************************************************************************/
	public function procesar($conexionbdorigen)
	{
		if (!$this->existeEnDestino()) // Se verifica que la solicitud de pago no se haya procesado
		{
			$this->copiarSolicitud($conexionbdorigen);
			if ($this->valido)
			{
				$this->copiarDetalleSolicitud($conexionbdorigen);
			}
		}
	}

	
/***********************************************************************************
 * @Función para insertar la solicitud.
 * @parametros:
 * @retorno:
 * @fecha de creación: 01/12/2008.
 * @autor: Ing. Gusmary Balza.
 ************************************************************************************
 * @fecha modificación:
 * @descripción:
 * @autor:
 ***********************************************************************************/
	public function copiarSolicitud($conexionbdorigen)
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		// Se seleccionan la Solicitu de Pago del Origen
		$consulta = "SELECT * ".
					"  FROM cxp_solicitudes ".
					" WHERE codemp = '{$this->codemp}' ".
					"   AND numsol='{$this->numsolorigen}'";
		$result = $conexionbdorigen->Execute($consulta);
		if (!$result->EOF)
		{
			$this->nuevomonto = $result->fields['monsol'] * $this->factor; 
			if ($result->fields['fecpagsol']=='')
			{
				$this->fecpagsol = '1900-01-01';
			}
			else
			{
				$this->fecpagsol = convertirFechaBd($result->fields['fecpagsol']);
			}
			if ($result->fields['feccmp']=='')	
			{
				$this->feccmp = '1900-01-01';
			}
			else
			{
				$this->feccmp = convertirFechaBd($result->fields['feccmp']);
			}				
			$concepto = $this->consol.' '.$result->fields['consol'];
			$this->codpro    = $result->fields['cod_pro'];
			if (strlen(trim($this->codpro)) < 10 )
			{
				$this->codpro=str_pad($this->codpro,8,'0',0).'00';
			}
			
			// Se inserta la solicitud de Pago en el destino con el monto multiplicado por el factor.
			$consulta = " INSERT INTO cxp_solicitudes (codemp, numsol,cod_pro,ced_bene,codfuefin,tipproben, 			".
						"			fecemisol, fecpagsol,consol, estprosol, monsol,obssol, procede, numcmp, 			".
						"			feccmp, estaprosol, fecaprosol, usuaprosol, numpolcon) 								".
						" VALUES ('{$this->codemp}','{$this->numsol}','".$this->codpro."', 				".
						" 		'".$result->fields['ced_bene']."','".$result->fields['codfuefin']."',					".
						"		'".$result->fields['tipproben']."','{$this->fecemisol}','$this->fecpagsol', 			".
						"		'$concepto','E',{$this->nuevomonto},'".$result->fields['obssol']."',".
						"		'".$result->fields['procede']."','".$result->fields['numcmp']."','$this->feccmp', 		".
						"		'0','1900-01-01','','".$result->fields['numpolcon']."') 								";

			$result = $conexionbd->Execute($consulta);
			if ($result === false)
			{			
				$this->mensaje = 'Error al Insertar La Solicitud de Pago';
				$this->valido = false;
				escribirArchivo($this->archivo,'Error al Insertar La Solicitud de Pago'.''.$conexionbd->ErrorMsg());
			}
			else
			{
				$consulta = " UPDATE cxp_solicitudes ".
							"    SET estapesolpag='1' ".
							"  WHERE codemp='$this->codemp' ".
							"    AND numsol='$this->numsolorigen'";
				$result = $conexionbdorigen->Execute($consulta);
				if ($result === false)
				{			
					$this->mensaje = 'Error al Actualizar La Solicitud de Pago';					
					$this->valido = false;
					escribirArchivo($this->archivo,'Error al Actualizar La Solicitud de Pago '.''.$conexionbd->ErrorMsg());
				}
			}
		}
		else
		{
			$this->mensaje = 'La Solicitud de Pago Origen no existe';
			escribirArchivo($this->archivo,'La Solicitud de Pago Origen no existe ');
		}	
	}


/***********************************************************************************
* @Función para copiar el detalle de la solicitud.
* @parametros:
* @retorno:
* @fecha de creación: 01/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	public function copiarDetalleSolicitud($conexionbdorigen)
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$consulta = "SELECT * ".
					"  FROM cxp_dt_solicitudes ".
					" WHERE codemp = '{$this->codemp}' ".
					"   AND numsol='{$this->numsolorigen}'";
		$result = $conexionbdorigen->Execute($consulta);
		if (!$result->EOF)
		{
			while (!$result->EOF)
			{
				$this->numrecdoc    = $result->fields['numrecdoc']; 
				$this->cod_pro     = $result->fields['cod_pro'];
				$this->ced_bene    = $result->fields['ced_bene'];
				$this->tccodtipdoc = $result->fields['codtipdoc'];
				
				$this->copiarRecepcionesDocumento($conexionbdorigen);
				if ($this->valido)
				{
					$this->nuevomonto = $this->factor*$result->fields['monto']; 
	
					$consulta = " INSERT INTO cxp_dt_solicitudes (codemp, numsol, numrecdoc, codtipdoc, ".
								   " 			ced_bene, cod_pro, monto) ".
								   " VALUES ('{$this->codemp}','{$this->numsol}','{$result->fields['numrecdoc']}', ".
								   " 		'{$this->codtipodoc}','{$result->fields['ced_bene']}', ".
								   "		'".cerosIzquierda($result->fields['cod_pro'],10)."','{$this->nuevomonto}') ";
					$result_rd = $conexionbd->Execute($consulta);
					if ($result_rd==false)
					{
						$this->mensaje = 'No se incluyo el detalle de la solicitud';	
						$this->valido = false;
						escribirArchivo($this->archivo,'* Error: No se incluyo el detalle de la solicitud '.$conexionbd->ErrorMsg());
						escribirArchivo($this->archivo,'*******************************************************************************************************');
					}
				}
				$result->MoveNext();
			}
		}
	}

	
/***********************************************************************************
 * @Función para copiar la recepción de documentos.
 * @parametros:
 * @retorno:
 * @fecha de creación: 01/12/2008.
 * @autor: Ing. Gusmary Balza.
 ************************************************************************************
 * @fecha modificación:
 * @descripción:
 * @autor:
 ***********************************************************************************/
	public function copiarRecepcionesDocumento($conexionbdorigen)
	{
		global $conexionbd;
		$consulta = " SELECT * ".
					"   FROM cxp_rd ".
					"  INNER JOIN cxp_documento ".
					"     ON cxp_rd.codtipdoc = cxp_documento.codtipdoc ".
			   		"  WHERE cxp_rd.codemp = '{$this->codemp}'  ".
			   		"    AND cxp_rd.numrecdoc = '{$this->numrecdoc}' ".
			   		"    AND cxp_rd.cod_pro =  '{$this->cod_pro}'  ".
			   		"    AND cxp_rd.ced_bene = '{$this->ced_bene}' ".
			   		"    AND cxp_rd.codtipdoc ='{$this->tccodtipdoc}' ";
		$result = $conexionbdorigen->Execute($consulta);
		if ($result==false)
		{
			$this->mensaje = '';
			$this->valido = false;
		}
		else
		{
			if (!$result->EOF)
			{
				if ($result->fields['estlibcom']==1)
				{
					$this->nuevolc = 2;
				}
				else
				{
					$this->nuevolc = 0;
				}	
						
				$this->numrecdoc = $result->fields['numrecdoc'];
				$this->codpro    = $result->fields['cod_pro'];
				if (strlen(trim($this->codpro)) < 10 )
				{
					$this->codpro=str_pad($this->codpro,8,'0',0).'00';
				}
				$this->cedbene   = $result->fields['ced_bene'];
				$this->concepto  = $result->fields['dencondoc'];
				$this->codcla    = $result->fields['codcla'];
				$this->tipproben = $result->fields['tipproben'];
				$this->referencia= $result->fields['numref'];
				$this->estprodoc = $result->fields['estprodoc'];
				$this->procede   = $result->fields['procede'];
				$this->estaprord = $result->fields['estaprord'];
				$this->fecaprord = $result->fields['fecaprord'];
				$this->usuaprord = $result->fields['usuaprord'];
				$this->numpolcon = $result->fields['numpolcon'];
				$this->estimpmun = $result->fields['estimpmun'];
				$this->montot    = $result->fields['montotdoc'];
				$this->deducciones = $result->fields['mondeddoc'];
				$this->cargos    = $result->fields['moncardoc'];
				$montocargo = $this->obtenerMontoCargos($conexionbdorigen); 
				$this->nuevomonto     = $this->factor * $this->montot;
				$this->nuededucciones = $this->factor * $this->deducciones;
				$this->nuecargos      = $this->factor * $this->cargos;
				$this->fecaprord = convertirFechaBd($this->fecaprord);
				$this->fecemisol = convertirFechaBd($this->fecemisol);
					
				$consulta = " INSERT INTO cxp_rd (codemp, numrecdoc, codtipdoc, cod_pro, ced_bene, ".
								"			codcla, dencondoc,fecemidoc, fecregdoc, fecvendoc,montotdoc, ".
								"  			mondeddoc, moncardoc,tipproben, numref, estprodoc, procede, ".
								" 			estlibcom,estaprord, fecaprord,usuaprord, numpolcon,estimpmun, ".
								" 			montot) ".
								" VALUES  ('{$this->codemp}','{$this->numrecdoc}','{$this->codtipodoc}', ".
								"		'{$this->codpro}','{$this->cedbene}','{$this->codcla}','{$this->concepto}', ".
								" 		'{$this->fecemisol}','{$this->fecemisol}','{$this->fecemisol}',".
								"		'".$this->nuevomonto."',$this->nuededucciones,$this->nuecargos, ".
								"		 '{$this->tipproben}','{$this->referencia}','E','{$this->procede}', ".
								"		{$this->nuevolc},{$this->estaprord},'{$this->fecaprord}', ".
								"		'{$this->usuaprord}',{$this->numpolcon},'{$this->estimpmun}', ".
								"		'".$this->nuevomonto."')";
				escribirArchivo($this->archivo,$consulta);
				$result_cxp_rd = $conexionbd->Execute($consulta);
				if ($result_cxp_rd==false)
				{
					$this->valido = false;
					$this->mensaje = 'Error: No se incluyo la recepcion';					
					escribirArchivo($this->archivo,'* Error: No se incluyo la recepcion '.$conexionbd->ErrorMsg());
					escribirArchivo($this->archivo,'*******************************************************************************************************');
				}
				$this->ccontable = rtrim($this->obtenerCuentaContable($conexionbdorigen)); 
				if ($this->estconpre==0)
				{
					if ($this->ccontable!='')
					{
						$this->debhab = 'D';
						$this->CopiarDetalleContable(); 
						if ($this->valido)
						{
							$this->debhab = 'H';
							$this->CopiarDetalleContable(); 
						}
					}
				}
				else
				{
					$this->sccuenta = rtrim($this->obtenerContablePresupuesto($conexionbdorigen));
					if ($this->sccuenta!='' && $this->ccontable!='')
					{
						$this->copiarDetallePresupuestario(); 
						if ($this->valido)
						{
							$this->debhab = 'H';
							$this->CopiarDetalleContable(); 
							if ($this->valido)
							{
								$this->debhab = 'D';
								$this->CopiarDetalleContable(); 
							}
						}					  							
					}
					else
					{
						$this->valido = false;
						$this->mensaje = 'No se procesaron las solicitudes, Favor verifique las cuentas contables de la recepcion';
						escribirArchivo($this->archivo,'No se procesaron las solicitudes, Favor verifique las cuentas contables de la recepcion ');
					}																
				}
			}
		}
	}

	
/***********************************************************************************
 * @Función para el monto de los cargos.
 * @parametros:
 * @retorno:
 * @fecha de creación: 01/12/2008.
 * @autor: Ing. Gusmary Balza.
 ************************************************************************************
 * @fecha modificación:
 * @descripción:
 * @autor:
 ***********************************************************************************/
	public function obtenerMontoCargos($conexionbdorigen)
	{
		$totalcargos = 0.00;
		$consulta = "SELECT SUM(cxp_rd_cargos.monret) as tcargos ".
					"  FROM cxp_rd_cargos  ".
					" INNER JOIN sigesp_cargos ".
					"    ON cxp_rd_cargos.codemp = sigesp_cargos.codemp ".
					"   AND cxp_rd_cargos.codcar =  sigesp_cargos.codcar ".
					" WHERE cxp_rd_cargos.codemp = '{$this->codemp}' ".
					"   AND numrecdoc = '{$this->numrecdoc}' ".
					"   AND	codtipdoc = '{$this->codtipodoc}' ".
					"   AND	cod_pro	= '{$this->codpro}' ".
					"   AND	ced_bene = '{$this->cedbene}' ";
		$result = $conexionbdorigen->Execute($consulta);
		if (!$result->EOF)
		{
			$totalcargos = $result->fields['tcargos'];
		}
		return $totalcargos;
	}

	
/***********************************************************************************
 * @Función para obtener la cuenta contable del proveedor de la recepción.
 * @parametros:
 * @retorno:
 * @fecha de creación: 01/12/2008.
 * @autor: Ing. Gusmary Balza.
 ************************************************************************************
 * @fecha modificación:
 * @descripción:
 * @autor:
 ***********************************************************************************/
	public function obtenerCuentaContable($conexionbdorigen)
	{
		global $conexionbd;
		$sccuenta = '';
		if ($this->tipproben=='P')
		{
			$consulta = " SELECT sc_cuenta ".
				 		"   FROM rpc_proveedor ".
				 		"  WHERE codemp = '{$this->codemp}' ".
				 		"    AND cod_pro = '{$this->codpro}' ";
				
		}
		else
		{
			$consulta = " SELECT sc_cuenta ".
				 		"   FROM rpc_beneficiario ".
				 		"  WHERE codemp = '{$this->codemp}' ".
				 		"    AND ced_bene = '{$this->cedbene}' ";
		}
		$result = $conexionbdorigen->Execute($consulta);
		if (!$result->EOF)
		{
			$sccuenta = $result->fields['sc_cuenta'];
		}
		else
		{
			escribirArchivo($this->archivo,' No existe la cuenta contable del proveedor de la recepcion ');
			escribirArchivo($this->archivo,'*******************************************************************************************************');
		}
		return $sccuenta;
	}


/***********************************************************************************
 * @Función para buscar la cuenta contable de la cuenta de gastos.
 * @parametros:
 * @retorno:
 * @fecha de creación: 01/12/2008.
 * @autor: Ing. Gusmary Balza.
 ************************************************************************************
 * @fecha modificación:
 * @descripción:
 * @autor:
 ***********************************************************************************/
	public function obtenerContablePresupuesto($conexionbdorigen)
	{
		global $conexionbd;
		
		$sccuenta = '';
	
		$consulta = " SELECT sc_cuenta ".
					"  FROM spg_cuentas".
					" WHERE codemp = '{$this->codemp}'".
					"   AND codestpro1='{$this->codestpro1}' ".
					"   AND codestpro2='{$this->codestpro2}' ".
					"   AND codestpro3='{$this->codestpro3}' ".
					"   AND codestpro4='{$this->codestpro4}' ".
					"   AND codestpro5='{$this->codestpro5}' ".
					"  	AND spg_cuenta = '{$this->cuenta}' ";
	
		$result = $conexionbdorigen->Execute($consulta);
		if (!$result->EOF)
		{
			$sccuenta = $result->fields['sc_cuenta'];
		}
		else
		{
			escribirArchivo($this->archivo,'No existe la cuenta contable de la cuenta de gastos');
			escribirArchivo($this->archivo,'*******************************************************************************************************');
		}
		return $sccuenta;
	}

	
/***********************************************************************************
 * @Función para copiar el detalle contable de spg.
 * @parametros:
 * @retorno:
 * @fecha de creación: 01/12/2008.
 * @autor: Ing. Gusmary Balza.
 ************************************************************************************
 * @fecha modificación:
 * @descripción:
 * @autor:
 ***********************************************************************************/
	public function CopiarDetalleContable()
	{
		global $conexionbd;
		$consulta = " INSERT INTO cxp_rd_scg (codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, ".
					"			               procede_doc, numdoccom, debhab, sc_cuenta, monto, estgenasi) ".
					" VALUES ('{$this->codemp}','{$this->numrecdoc}','{$this->codtipodoc}', ".
					" 		'{$this->ced_bene}','{$this->cod_pro}','CXPSOP','{$this->numrecdoc}', ".
					" 		'{$this->debhab}','{$this->ccontable}','{$this->nuevomonto}',0)";
		$result = 	$conexionbd->Execute($consulta);
		if ($result===false)
		{
			$this->valido = false;
			$this->mensaje = '';
			escribirArchivo($this->archivo,'* Error: No se incluyo el detalle contable a la base de datos destino '.$conexionbd->ErrorMsg());
			escribirArchivo($this->archivo,'*******************************************************************************************************');
		}
	}

	
/***********************************************************************************
 * @Función para copiar el detalle presupuestario a la base de datos destino.
 * @parametros:
 * @retorno:
 * @fecha de creación: 01/12/2008.
 * @autor: Ing. Gusmary Balza.
 ************************************************************************************
 * @fecha modificación:
 * @descripción:
 * @autor:
 ***********************************************************************************/
	public function copiarDetallePresupuestario() //se agrego el estcla
	{
		global $conexionbd;
		
		//$codestpro = $conexionbd->Concat($this->codestpro1,$this->codestpro2,$this->codestpro3,$this->codestpro4,$this->codestpro5);
		
		$codestpro = $this->codestpro1.$this->codestpro2.$this->codestpro3.$this->codestpro4.$this->codestpro5;
				
		$consulta = " INSERT INTO cxp_rd_spg(codemp, numrecdoc, codtipdoc, ced_bene, cod_pro, ".
					"			procede_doc, numdoccom, codestpro,estcla,spg_cuenta, codfuefin, monto) ".
					" VALUES ('{$this->codemp}','{$this->numrecdoc}','{$this->codtipodoc}',".
					"		'{$this->cedbene}','$this->codpro','CXPSOP','{$this->numrecdoc}', ".
					" 		'{$codestpro}','{$this->estcla}','{$this->cuenta}','--',$this->nuevomonto) ";
		$result = 	$conexionbd->Execute($consulta);
		if ($result===false)
		{
			$this->valido = false;
			$this->mensaje = 'No se incluyo el detalle presupuestario a la base de datos destino ';			
			escribirArchivo($this->archivo,'* Error: No se incluyo el detalle presupuestario a la base de datos destino '.$conexionbd->ErrorMsg());
			escribirArchivo($this->archivo,'*******************************************************************************************************');
		}
	}

	
/***********************************************************************************
 * @Función para buscar si la solicitud existe en la base de datos destino.
 * @parametros:
 * @retorno:
 * @fecha de creación: 01/12/2008.
 * @autor: Ing. Gusmary Balza.
 ************************************************************************************
 * @fecha modificación:
 * @descripción:
 * @autor:
 ***********************************************************************************/
	public function existeEnDestino()
	{
		global $conexionbd;		
		$existe = false;
		$veces = 0;

		$consulta="SELECT count(*) as nveces ".
				  "  FROM cxp_solicitudes ".
				  " WHERE codemp='{$this->codemp}' ".
				  "   AND numsol='{$this->numsol}'";

		$result = $conexionbd->Execute($consulta);
		if ($result===false)
		{
			$this->mensaje = '';
			$this->valido = false;
		}
		else
		{
			$veces = number_format($result->fields['nveces'],0);
			if ($veces > 0)
			{
				$existe = true;
			}
		}
		$result->Close();
		return $existe;
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