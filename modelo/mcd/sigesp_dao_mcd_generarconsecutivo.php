<?php
/************************************************************
* @Clase para generar un número consecutivo
* @fecha de creación: 29/08/2008
* @autor: Ing. Gusmary Balza.
* **************************
* @fecha modificacion 
* @autor   
* @descripcion 
***************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_funciones.php');

class GenerarConsecutivo extends ADOdb_Active_Record
{
	var $_table = 'mcdprefijom';	
	public $valido;
	public $mensaje;
	
	function GenerarConsecutivo()
	{
	
	}
	
	
/***********************************************************************************
* @Función que obtiene el prefijo del numero de documento (en caso de poseerlo)
* @parametros: 
* @retorno:
* @fecha de creación: 29/08/2008
* @autor: Ing. Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	function cargarPrefijo() //revisar adaptar
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$prefijo = '00';
		$consulta = " SELECT codempresa,codsistema,codusuario,procede,codprefijo,prefijo FROM {$this->_table}";
		$result = $conexionbd->Execute($consulta);
		if ($conexionbd->CompleteTrans())
		{
			while (!$result->EOF)
			{
				$codusuario = $result->fields['codusuario']; //revisar 
				if ($_SESSION['sigesp_codusuario']!='--------------------')
				{
					if ($codusuario==$_SESSION['sigesp_codusuario'])
					{
						$prefijo = $result->fields['prefijo'];
					}
					else
					{
						$this->mensaje = "Este documento está configurado para el manejo de Prefijos: Ud. No tiene acceso a ninguno. Por favor diríjase al Administrador del Sistema";
						$this->valido  = false;
					}
				}
				else
				{
					$prefijo = $result->fields['prefijo'];
				}
				$result->MoveNext();
			}
			return $prefijo;  //revisar
		}
		else
		{			
			$this->valido = false;
		}
	}


/*********************************************************
* @Función que obtiene el valor inicial del documento.
* @parametros: 
* @retorno:
* @fecha de creación: 29/08/2008
* @autor: Ing. Gusmary Balza.
***********************************************************
* @fecha modificación:
* @descripción:
* @autor:
**********************************************************/	
	function cargarNumeroInicial()
	{
		global $conexionbd;
		$this->valido = true;
		$numinicial   = '';
		if ($this->campo=='')  //parametro
		{
			return $numinicial;
		}
		else
		{
			$consulta = " SELECT {$this->campo} FROM mcdempresam WHERE codempresa='{$this->codempresa}'";
			$result = $conexionbd->Execute($consulta);
			if ($conexionbd->CompleteTrans())
			{
				while (!$result->EOF)
				{
					$numinicial = $result->fields[$this->campo]; //revisar
					$result->MoveNext();
				}
			}
			else
			{
				$this->valido = false;
			}
		}
		return $numinicial;
	
	}
	
/************************************************************
* @Función que obtiene el último valor de la tabla indicada.
* @parametros: 
* @retorno:
* @fecha de creación: 29/08/2008
* @autor: Ing. Gusmary Balza.
**************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***************************************************************/	
	function cargarDatosExistentes() //parametros: tabla,campo,filtro,valor, prefijo
	{
		$this->valido = true;
		$numactual = '';
		$criterio  = '';
		global $conexionbd;
	//	$conexionbd->StartTrans(); 
		if (!empty($this->filtro)) //se elimino la consulta por empresa
		{
			$criterio = $criterio. " AND {$this->filtro}='{$this->valor}'";
		}
		$criterio = $criterio. " AND {$this->campo} LIKE '{$this->prefijo}%'";
		$consulta = " SELECT {$this->campo} as campo FROM {$this->tabla} ".
					" WHERE codempresa='{$this->codempresa}' {$criterio} ".
					" ORDER BY {$this->campo} DESC LIMIT 1"; //evaluar por gestor
		$result = $conexionbd->Execute($consulta);
	//	if ($conexionbd->CompleteTrans())
	//	{
			while (!$result->EOF)
			{
				$numactual = $result->fields['campo'];
				$result->MoveNext();
			}
		
	/*	}
		else
		{
			$this->valido = false;
		}*/	
		return $numactual;
	}


/**************************************************************
* @Función que verifica si un numero generado esta disponible
* @parametros: 
* @retorno:
* @fecha de creación: 29/08/2008
* @autor: Ing. Gusmary Balza.
***************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************/		
	function verificarNumeroGenerado(/*$as_codsis,$as_tabla,$as_campo,$as_procede,$ai_loncam,$as_camini,$as_filtro,
										  $as_valor,&$as_numero*/) 
	{
		$this->valido = false;
		$numactual = '';
		$numeroGenerado = '';
		$numactual = $this->numero; //revisar
		$criterio = "";
		if (!empty($this->filtro)) //revisar
		{
			$criterio = " AND ".$this->filtro."='".$this->valor."'";
		}
		global $conexionbd;
		//$conexionbd->StartTrans(); //se elimino la consulta por empresa
		$consulta = " SELECT {$this->campo} FROM {$this->tabla} WHERE codempresa='{$this->codempresa}' ".
						" AND {$this->campo}='{$this->numero}' {$criterio}";
		$result = $conexionbd->Execute($consulta);
		//if ($conexionbd->CompleteTrans())
		//{
			while (!$result->EOF)
			{
				$this->valido = true;
				$numeroGenerado = $this->generarNumeroNuevo();
				$result->MoveNext();
			}
			if ($numeroGenerado!='')
			{
				 $this->numero=$numeroGenerado;
			}
			if($numactual!=$numeroGenerado)
			{
				$this->mensaje = "Se le Asignó un nuevo número de documento el cual es :".$numeroGenerado;
			}
			
	/*	}
		else
		{
			$this->valido = false;
		}*/
		//return $this->valido;
	}

	
	
/***********************************************
* @Función que genera un numero nuevo
* @parametros: 
* @retorno:
* @fecha de creación: 29/08/2008
* @autor: Ing. Gusmary Balza.
*************************************************
* @fecha modificación:
* @descripción:
* @autor:
*************************************************/	
	function generarNumeroNuevo() 
	{
		$numnuevo = '';
		$prefijo = $this->cargarPrefijo();
		if ($prefijo->valido==false)
		{
			$this->valido = false;
		}
		$numactual = $this->cargarDatosExistentes();
		if ($numactual!='')
		{
			if ($prefijo!='0000')
			{
				$numlongitud = $this->longcampo -6;  //revisar no se cumple para grupo sustraer 6
				$numpre      = substr($numactual,0,6);
				$numero      = substr($numactual,6,$numlongitud);
			}
			else
			{
				$numero      = $numactual;
				$numlongitud = $this->longcampo;
			}
		}
		else
		{
			//$numero = $this->cargarNumeroInicial(); //espera por la definición de empresa
			if ($numero=="")
			{
				$numero = 0;
			}
		}
		settype($numero,'int');
		$numnuevo = $numero + 1;
		if ($prefijo!="000000")
		{
			$numnuevo = cerosIzquierda($numnuevo,$this->longcampo-6); 
			$numnuevo = $prefijo.$numnuevo;
		}
		else
		{
			$numnuevo = cerosIzquierda($numnuevo,$this->longcampo);
		}
		$this->valido = $this->verificarNumeroGenerado();
		return $numnuevo;
	}


}
?>
