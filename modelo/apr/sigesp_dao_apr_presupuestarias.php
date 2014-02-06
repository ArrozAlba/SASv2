<?php
/***********************************************************************************
 * @Modelo para el actualizar cuentas presupuestarias.
 * @fecha de creación: 11/12/2008.
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

class ActCuentasPresupuestarias extends ADODB_Active_Record
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
	var $cuenta = array();

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
 * @Función para cargar las cuentas presupuestarias utilizadas
 * @parametros:
 * @retorno:
 * @fecha de creación: 11/12/2008.
 * @autor: Ing. Gusmary Balza.
 ************************************************************************************
 * @fecha modificación:
 * @descripción:
 * @autor:
 ***********************************************************************************/	
	public function cargarCuentas() //&$ai_totrows,&$ao_object
	{
		global $conexionbd;
		
		$this->servidor = $_SESSION['sigesp_servidor_apr'];
		$this->usuario 	= $_SESSION['sigesp_usuario_apr'];
		$this->clave 	= $_SESSION['sigesp_clave_apr'];
		$this->basedatos= $_SESSION['sigesp_basedatos_apr'];
		$this->gestor 	= $_SESSION['sigesp_gestor_apr'];
		$this->tipoconexionbd = 'ALTERNA';
		
		$this->seleccionarConexion(&$conexionbd);
		
		$consulta = " SELECT spg_cuenta ".
					"  FROM saf_catalogo ".
					" WHERE trim(spg_cuenta)<>'' ".
					"UNION ".
					"SELECT spg_cuenta_act as spg_cuenta ".
					"  FROM saf_activo ".
					" WHERE trim(spg_cuenta_act)<>'' ".
					"UNION ".
					"SELECT spg_cuenta_dep as spg_cuenta ".
					"  FROM saf_activo ".
					" WHERE trim(spg_cuenta_dep)<>'' ".
					"UNION ".
					"SELECT spg_cuenta ".
					"  FROM sigesp_cargos ".
					" WHERE trim(spg_cuenta)<>'' ".
					"UNION ".
					"SELECT spg_cuenta ".
					"  FROM siv_articulo ".
					" WHERE trim(spg_cuenta)<>'' ".
					"UNION ".
					"SELECT spg_cuenta ".
					"  FROM sep_conceptos ".
					" WHERE trim(spg_cuenta)<>'' ".
					"UNION ".
					"SELECT spg_cuenta ".
					"  FROM soc_servicios ".
					" WHERE trim(spg_cuenta)<>'' ".
					"UNION ".
					"SELECT cueprecon as spg_cuenta ".
					"  FROM sno_concepto ".
					" WHERE trim(cueprecon)<>'' ".
					"UNION ".
					"SELECT cueprepatcon as spg_cuenta ".
					"  FROM sno_concepto ".
					" WHERE trim(cueprepatcon)<>'' ".
					"UNION ".
					"SELECT cueprecon as spg_cuenta ".
					"  FROM sno_hconcepto ".
					" WHERE trim(cueprecon)<>'' ".
					"UNION ".
					"SELECT cueprepatcon as spg_cuenta ".
					"  FROM sno_hconcepto ".
					" WHERE trim(cueprepatcon)<>'' ".
					" GROUP BY spg_cuenta ".
					" ORDER BY spg_cuenta ";
		$result = $conexionbd->Execute($consulta);
		if ($result===false)
		{
			$this->valido = false;
			$cadena = 'Error al Seleccionar las Cuentas Presupuestarias.'.''.$conexionbd->ErrorMsg();
		}
		else
		{			
			$arreglo = array ();
			$j=0;
			while (!$result->EOF)
			{				
				$this->spgcuentaorigen  = validarTexto($result->fields['spg_cuenta'],0,25,'');
				$this->spgcuentadestino = '';
				$arreglo[$j]['origen'] = $result->fields['spg_cuenta'];
				$resultDestino = $this->cargarCuentaDestino();
				
				if (TRIM($arreglo[$j]['origen'])==TRIM($resultDestino->fields['spg_cuentaorigen']))
				{
					$arreglo[$j]['destino']=$resultDestino->fields['spg_cuentadestino'];					
				}
				else
				{
					$arreglo[$j]['destino']='';
				}
				$j++;	
				$result->MoveNext();
			}
							
		}
		return $arreglo;
	}
	
	
/***********************************************************************************
 * @Función para crear la tabla apr_presupuestario.
 * @parametros:
 * @retorno:
 * @fecha de creación: 11/12/2008.
 * @autor: Ing. Gusmary Balza.
 ************************************************************************************
 * @fecha modificación:
 * @descripción:
 * @autor:
 ***********************************************************************************/	
	public function crearTabla()
	{
		global $conexionbd;			
		$this->tabla = 'apr_presupuestario';
		$this->existe = $this->verificarExistenciaTabla();
		if (!$this->existe)
		{			
			switch($_SESSION["ls_gestor"])
			{
				case 'MYSQLT':
				$consulta = " CREATE TABLE  apr_presupuestario ( ".
							"  spg_cuentaorigen varchar(25) NOT NULL, ".
							"  spg_cuentadestino varchar(25) NOT NULL, ".
							"  PRIMARY KEY  (spg_cuentaorigen) ".
							") ENGINE=InnoDB;";
				break;
				
				case 'POSTGRES': //CORREGIDO
				$consulta = " CREATE TABLE  apr_presupuestario ( ".
							"  spg_cuentaorigen varchar(25) NOT NULL, ".
							"  spg_cuentadestino varchar(25) NOT NULL, ".
							"  PRIMARY KEY  (spg_cuentaorigen));";
				break;
			}			
			$result = $conexionbd->Execute($consulta);
			if ($result===false)
			{
				$this->valido = false;
			}
		}
	}
	
	
/***********************************************************************************
* @Función para cargar las cuentas actuales.(nuevas)
* @parametros:
* @retorno:
* @fecha de creación: 11/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function cargarCuentaDestino() //$as_cuentaorigen,&$as_cuentadestino
	{
		global $conexionbd;
		
		$this->servidor = $_SESSION['sigesp_servidor'];
		$this->usuario 	= $_SESSION['sigesp_usuario'];
		$this->clave 	= $_SESSION['sigesp_clave'];
		$this->basedatos= $_SESSION['sigesp_basedatos'];
		$this->gestor 	= $_SESSION['sigesp_gestor'];
		$this->tipoconexionbd = 'ALTERNA';
				
		$this->seleccionarConexion(&$conexionbd);
		
		$consulta = " SELECT spg_cuentaorigen,spg_cuentadestino ".
					"  FROM apr_presupuestario ".
					" WHERE spg_cuentaorigen='$this->spgcuentaorigen' ";
		$result = $conexionbd->Execute($consulta);
		if ($result===false)
		{
			$this->valido = false;
			$cadena = 'Error en la base de datos destino'.''.$conexionbd->ErrorMsg();
			$this->mensaje = '';
		}
		else
		{
			/*while (!$result->EOF)
			{
				$this->sccuentadestino = validarTexto($result->fields['scg_cuentadestino'],0,25,'');
				$result->MoveNext();
			}*/
			return $result;
		}
		
	}
	
	
/***********************************************************************************
* @Función para insertar las cuenta anterior y actual.
* @parametros:
* @retorno:
* @fecha de creación: 11/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	public function incluirCuentas()
	{
		global $conexionbd;
		
		$this->crearTabla(); //revisar si coloocar aqui
		//$this->consulta = " DELETE FROM apr_presupuestario ";
		//$this->valido = $this->procesarConsulta();
		$total = count($this->cuenta);
		for ($i=0; ($i <= $total) && ($this->valido); $i++)
		{
			$spgcuentaant = trim($this->cuenta[$i]->spgcuentaant);
			$spgcuentaact = trim($this->cuenta[$i]->spgcuentaact);	
			if($spgcuentaact!='')
			{
				$this->consulta = " INSERT INTO apr_presupuestario (spg_cuentaorigen, spg_cuentadestino)
							  		VALUES ('".$spgcuentaant."','".$spgcuentaact."') ";
				$this->valido = $this->procesarConsulta();
				if($this->valido)
				{
					$this->mensaje = 'Asocio la cuenta Presupuestaria Origen '.$spgcuentaant.' con la Cuenta Presupuestaria Destino '.$spgcuentaact;
					$this->incluirSeguridad('INSERTAR',$this->valido);
				}
				else
				{
					$this->valido = false;
				}
			}
		}
		return $this->valido;		
	}
	
	
	
/***********************************************************************************
* @Función para ejecutar una consulta sql.
* @parametros:
* @retorno:
* @fecha de creación: 09/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function procesarConsulta() //as_sql
	{
		global $conexionbd;
		
		$result = $conexionbd->Execute($this->consulta);
		if ($result===false)
		{
			$this->valido = false;
			$cadena = 'Error en la base de datos destino.'.''.$conexionbd->ErrorMsg();
			$this->mensaje = 'Error en la base de datos destino.'.''.$conexionbd->ErrorMsg();
		}
		return $this->valido;				
	}
		
	
	public function verificarExistenciaTabla()
	{
		global $conexionbd;
		
		$tablas = $conexionbd->MetaTables('TABLES');
		$clave  = array_search($this->tabla, $tablas);
		if (!is_numeric($clave))
		{				
			$this->valido=false;				
		}
		return $this->valido;
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