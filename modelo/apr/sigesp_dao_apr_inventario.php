<?php
/***********************************************************************************
 * @Modelo para el movimiento inicial de existencias de inventario.
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

class MovimientoInventario extends ADODB_Active_Record
{
	var $_table = 'siv_movimiento';
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
	
	
/**********************************************************************************	
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
* @Función para insertar los movimientos iniciales.
* @parametros:
* @retorno:
* @fecha de creación: 15/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	public function insertarMovimientoInicial()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$conexionbdorigen = conectarBD($_SESSION['sigesp_servidor'], $_SESSION['sigesp_usuario'], $_SESSION['sigesp_clave'],
												 $_SESSION['sigesp_basedatos'], $_SESSION['sigesp_gestor']);
		
		$this->mensaje = 'Inserto el movimiento inicial de existencias de inventario';
		//$conexionbd->StartTrans();
		try
		{
			//$this->seleccionarConexion(&$conexionbd);
				
			$consulta = " SELECT codemp,codart,codalm,SUM(existencia) AS existencia, 				".
					   	"		(SELECT ultcosart FROM siv_articulo 								".
                        "        WHERE siv_articuloalmacen.codemp=siv_articulo.codemp 				".
					   	"        AND siv_articuloalmacen.codart=siv_articulo.codart) AS ultcosart 	".
                        " FROM siv_articuloalmacen 													".
					   	" WHERE existencia > 0 														".
                        " GROUP BY codemp,codart,codalm 											";			
			$result = $conexionbdorigen->Execute($consulta);
			if ($result===false)
			{
				escribirArchivo($this->archivo,'* Error al Seleccionar los Artículos por Almacen. '.$conexionbd->ErrorMsg());
				$this->valido = false;
			}
			elseif (!$result->EOF)
			{
				$comprobante = '000000000000001';
				//$fecha       = '';
				$this->periodo = '';
				$solicitante = 'Apertura';
				
				/*$this->servidor  = $_SESSION['sigesp_servidor'];
				$this->usuario   = $_SESSION['sigesp_usuario'];
				$this->clave 	 = $_SESSION['sigesp_clave'];
				$this->basedatos = $_SESSION['sigesp_basedatos'];
				$this->gestor 	 = $_SESSION['sigesp_gestor'];
				$this->tipoconexionbd = 'ALTERNA';*/
								
				$this->seleccionarPeriodo();				
												
				//$this->seleccionarConexion(&$conexionbd);
				$conexionbd->StartTrans();
				
				$consultamov = " INSERT INTO siv_movimiento (nummov,fecmov,nomsol,codusu) ".
				 			"  VALUES ('".$comprobante."','".$this->periodo."','".$solicitante."','".$this->codusu."')";
				$resultmov = $conexionbd->Execute($consultamov);
				
				if (!is_object($resultmov))
				{
					escribirArchivo($this->archivo,'* Error al Insertar el Movimiento Inicial '.$conexionbd->ErrorMsg());
					$this->valido = false;
				}
			}
			while (!$result->EOF)
			{
				$codemp = validarTexto($result->fields['codemp'],0,4,'');
				$nummov = $comprobante;
				$fecmov = $this->periodo;
				$codart = validarTexto($result->fields['codart'],0,20,'');
				$codalm = validarTexto($result->fields['codalm'],0,10,'');
				$opeinv = 'ENT';
				$codprodoc = 'APR';
				$numdoc = $comprobante;
				$canart = $result->fields['existencia']; //validar monto!!!!
				$cosart = $result->fields['ultcosart'];
				$promov = 'APE';
				$numdocori = $comprobante;
				$candesart = $result->fields['existencia'];
				$fecdesart = $this->periodo;
				$cosart = $result->fields['ultcosart'];
				if ($canart>0)
				{
					$consulta = " INSERT INTO siv_dt_movimiento (codemp, nummov, fecmov, codart, 	".
								"		codalm, opeinv, codprodoc, numdoc, canart, cosart, 			".
								"		promov, numdocori, candesart, fecdesart) 					".
								" VALUES ('".$codemp."','".$nummov."','".$fecmov."','".$codart."', 	".
								"		'".$codalm."','".$opeinv."','".$codprodoc."','".$numdoc."', ".
								"		".$canart.",".$cosart.",'".$promov."','".$numdocori."', 	".
								"		".$candesart.",'".$fecdesart."')							";
					$resultdt = $conexionbd->Execute($consulta);
					if (!is_object($resultdt))
					{
						escribirArchivo($this->archivo,'* Error al Insertar los Detalles del movimiento inicial '.$conexionbd->ErrorMsg());
						$this->valido = false;
					}
					$consulta = " INSERT INTO siv_articuloalmacen (codemp, codart, codalm, existencia) ".
								" VALUES ('".$codemp."','".$codart."','".$codalm."',".$canart.") ";
					$resultdt = $conexionbd->Execute($consulta);	
					if (!is_object($resultdt))
					{
						escribirArchivo($this->archivo,'* Error al Insertar los Artículos por almacén. '.$conexionbd->ErrorMsg());
						$this->valido = false;
					}
				}
				$result->MoveNext();
			}
			escribirArchivo($this->archivo,'*******************************************************************************************************');
			escribirArchivo($this->archivo,'El Movimiento Inicial de Inventario se Creo con Exito');
			escribirArchivo($this->archivo,'*******************************************************************************************************');
				
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
		$consultap = " SELECT periodo 					".
					 " 	FROM sigesp_empresa 			".
					 " 	WHERE codemp='$this->codemp'	";
		$resultp = $conexionbd->Execute($consultap);
		if ($resultp===false)
		{
			$this->mensaje = 'Error al seleccionar el periodo '.$conexionbd->ErrorMsg();
			return false;
		}
		elseif (!$resultp->EOF)
		{
			$this->periodo = $resultp->fields['periodo'];
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