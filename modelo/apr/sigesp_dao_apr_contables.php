<?php
/***********************************************************************************
 * @Modelo para el actualizar cuentas contables
 * @fecha de creación: 09/12/2008.
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

class ActCuentasContables extends ADODB_Active_Record
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
 * @Función para cargar las cuentas contables utilizadas
 * @parametros:
 * @retorno:
 * @fecha de creación: 09/12/2008.
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
		
		$consulta = " SELECT c_resultad as sc_cuentaorigen".
					"  FROM sigesp_empresa ".
					" WHERE trim(c_resultad)<>'' ".
					"UNION ".
					"SELECT c_resultan as sc_cuentaorigen".
					"  FROM sigesp_empresa ".
					" WHERE trim(c_resultan)<>'' ".
					"UNION ".
					"SELECT scctaben as sc_cuentaorigen".
					"  FROM sigesp_empresa ".
					" WHERE trim(scctaben)<>'' ".
					"UNION ".
					"SELECT c_financiera as sc_cuentaorigen".
					"  FROM sigesp_empresa ".
					" WHERE trim(c_financiera)<>'' ".
					"UNION ".
					"SELECT c_fiscal as sc_cuentaorigen".
					"  FROM sigesp_empresa ".
					" WHERE trim(c_fiscal)<>'' ".
					"UNION ".
					"SELECT sc_cuenta as sc_cuentaorigen".
					"  FROM rpc_proveedor ".
					" WHERE trim(sc_cuenta)<>'' ".
					"UNION ".
					"SELECT sc_cuenta as sc_cuentaorigen".
					"  FROM rpc_beneficiario ".
					" WHERE trim(sc_cuenta)<>'' ".
					"UNION ".
					"SELECT sc_cuenta as sc_cuentaorigen".
					"  FROM saf_activo ".
					" WHERE trim(sc_cuenta)<>'' ".
					"UNION ".
					"SELECT sc_cuenta as sc_cuentaorigen".
					"  FROM sigesp_deducciones ".
					" WHERE trim(sc_cuenta)<>'' ".
					"UNION ".
					"SELECT sc_cuenta as sc_cuentaorigen".
					"  FROM siv_articulo ".
					" WHERE trim(sc_cuenta)<>'' ".
					"UNION ".
					"SELECT sc_cuenta as sc_cuentaorigen".
					"  FROM scb_ctabanco ".
					" WHERE trim(sc_cuenta)<>'' ".
					"UNION ".
					"SELECT sc_cuenta as sc_cuentaorigen".
					"  FROM scb_colocacion ".
					" WHERE trim(sc_cuenta)<>'' ".
					"UNION ".
					"SELECT sc_cuenta as sc_cuentaorigen".
					"  FROM sno_beneficiario ".
					" WHERE trim(sc_cuenta)<>'' ".
					"UNION ".
					"SELECT cueconnom as sc_cuentaorigen".
					"  FROM sno_nomina ".
					" WHERE trim(cueconnom)<>'' ".
					"UNION ".
					"SELECT cueaboper as sc_cuentaorigen".
					"  FROM sno_personalnomina ".
					" WHERE trim(cueaboper)<>'' ".
					"UNION ".
					"SELECT cueconcon as sc_cuentaorigen".
					"  FROM sno_concepto ".
					" WHERE trim(cueconcon)<>'' ".
					"UNION ".
					"SELECT cueconpatcon as sc_cuentaorigen".
					"  FROM sno_concepto ".
					" WHERE trim(cueconpatcon)<>'' ".
					"UNION ".
					"SELECT cueconnom as sc_cuentaorigen".
					"  FROM sno_hnomina ".
					" WHERE trim(cueconnom)<>'' ".
					"UNION ".
					"SELECT cueaboper as sc_cuentaorigen".
					"  FROM sno_hpersonalnomina ".
					" WHERE trim(cueaboper)<>'' ".
					"UNION ".
					"SELECT cueconcon as sc_cuentaorigen".
					"  FROM sno_hconcepto ".
				" WHERE trim(cueconcon)<>'' ".
				"UNION ".
				"SELECT cueconpatcon as sc_cuentaorigen".
				"  FROM sno_hconcepto ".
				" WHERE trim(cueconpatcon)<>'' ".		
				" GROUP BY sc_cuentaorigen ".
				" ORDER BY sc_cuentaorigen ";		
		$result = $conexionbd->Execute($consulta);
		if ($result===false)
		{
			$this->valido = false;
			$cadena = 'Error al Seleccionar las Cuentas Contables.'.''.$conexionbd->ErrorMsg();
		}
		else
		{			
			$arreglo = array ();
			$j=0;
			while (!$result->EOF)
			{				
				$this->sccuentaorigen  = validarTexto($result->fields['sc_cuentaorigen'],0,25,'');
				$this->sccuentadestino = '';
				$arreglo[$j]['origen'] = $result->fields['sc_cuentaorigen'];
				$resultDestino = $this->cargarCuentaDestino(); 								
					
				
				if (TRIM($arreglo[$j]['origen'])==TRIM($resultDestino->fields['scg_cuentaorigen']))
				{
					$arreglo[$j]['destino']=$resultDestino->fields['scg_cuentadestino'];					
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
* @Función para cargar las cuentas actuales.(nuevas)
* @parametros:
* @retorno:
* @fecha de creación: 10/12/2008.
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function cargarCuentaDestino() 
	{
		global $conexionbd;		
		$this->servidor = $_SESSION['sigesp_servidor'];
		$this->usuario 	= $_SESSION['sigesp_usuario'];
		$this->clave 	= $_SESSION['sigesp_clave'];
		$this->basedatos= $_SESSION['sigesp_basedatos'];
		$this->gestor 	= $_SESSION['sigesp_gestor'];
		$this->tipoconexionbd = 'ALTERNA';
				
		$this->seleccionarConexion(&$conexionbd);
		
		$consulta = " SELECT scg_cuentaorigen,scg_cuentadestino ".
					" FROM apr_contable ".
					" WHERE scg_cuentaorigen='{$this->sccuentaorigen}' ";
		
		$result = $conexionbd->Execute($consulta);
		if ($result===false)
		{
			$this->valido = false;
			$cadena = 'Error en la base de datos destino'.''.$conexionbd->ErrorMsg();
			$this->mensaje = '';
		}
		else
		{			
			return $result;		
		}
	}
	
	
/***********************************************************************************
 * @Función para crear la tabla apr_contable.
 * @parametros:
 * @retorno:
 * @fecha de creación: 10/12/2008.
 * @autor: Ing. Gusmary Balza.
 ************************************************************************************
 * @fecha modificación:
 * @descripción:
 * @autor:
 ***********************************************************************************/	
	public function crearTabla()
	{
		global $conexionbd;
		$this->tabla = 'apr_contable';
		$this->existe = $this->verificarExistenciaTabla();		
		if (!$this->existe)
		{
			switch($_SESSION["ls_gestor"])
			{
				case 'MYSQLT':
				$consulta = "CREATE TABLE  apr_contable ( ".
							"  scg_cuentaorigen varchar(25) NOT NULL, ".
							"  scg_cuentadestino varchar(25) NOT NULL, ".
							"  PRIMARY KEY  (scg_cuentaorigen) ".
							") ENGINE=InnoDB;";
				break;
				
				case 'POSTGRES':
				$consulta = " CREATE TABLE  apr_contable ( ".
							"  scg_cuentaorigen varchar(25) NOT NULL, ".
							"  scg_cuentadestino varchar(25) NOT NULL, ".
							"  PRIMARY KEY  (scg_cuentadestino));";
				break;
			}
			$result = $conexionbd->Execute($consulta);
			if ($result===false)
			{
				$this->valido = false;
			}
		}
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
* @Función para insertar las cuenta anterior y actual.
* @parametros:
* @retorno:
* @fecha de creación: 10/12/2008.
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
		//$this->consulta = " DELETE FROM apr_contable ";
		//$this->valido = $this->procesarConsulta();
		$total = count($this->cuenta);
		for ($i=0; ($i <= $total) && ($this->valido); $i++)
		{
			$sccuentaant = trim($this->cuenta[$i]->sccuentaant);
			$sccuentaact = trim($this->cuenta[$i]->sccuentaact);			
			if($sccuentaact!='')
			{
				$this->consulta = " INSERT INTO apr_contable (scg_cuentaorigen, scg_cuentadestino)
							  		VALUES ('".$sccuentaant."','".$sccuentaact."') ";
				$this->valido = $this->procesarConsulta();
				if($this->valido)
				{
					$this->mensaje = 'Asocio la cuenta Contable Origen '.$sccuentaact.' con la Cuenta Contable Destino '.$sccuentaact;
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