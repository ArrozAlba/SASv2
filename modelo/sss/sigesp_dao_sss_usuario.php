<?php
/*******************************************************************************
* @Clase compartida para manejar la definición de Uusario
* @fecha de creación: 07/10/2008.
* @autor: Ing.Gusmary Balza
*************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*********************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_registroeventos.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/modelo/sss/sigesp_dao_sss_registrofallas.php');

class Usuario extends ADOdb_Active_Record
{
	var $_table = 'sss_usuarios';
	public $mensaje;
	public $evento;
	public $valido = true;
	public $existe = true;
	public $seguridad = true;
	public $cadena;
	public $criterio;
	public $nuevopassword;
	public $codsis;
	public $nomfisico;
	public $admin = array();
	public $usuarioeliminar = array();
	public $constante = array();
	public $nomina = array();
	public $unidad = array();
	public $estpre = array();
	public $derechos;
	var $usuariopersonal = array();
	var $usuarioconstante = array();
	var $usuarionomina = array();
	var $usuariounidad = array();
	var $usuarioestpre = array();
	var $usuariodetalle = array();
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
	public function seleccionarConexion (&$conexionbd)
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
		/*if($db->CompleteTrans())
		{
			return "1";
		}	
		else
		{
			return "0";
		}*/
	}
	
	
	
/***********************************************************************************
* @Función que inserta los detalles para un usuario.
* @parametros: 
* @retorno: 
* @fecha de creación: 30/09/2008.
* @autor: Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function incluir()
	{
		global $conexionbd;		
		$this->seleccionarConexion(&$conexionbd);		
		
		$conexionbd->StartTrans();
		$this->mensaje = 'Incluyo el Usuario '.$this->codusu;
		try 
		{ 
			//$this->save();
			$consulta="INSERT INTO sss_usuarios(codemp, codusu, cedusu, nomusu, apeusu, pwdusu, telusu, nota, ".
                  			"						  email, estatus, admusu, ultingusu, fotousu) ".
    						"     VALUES ('".$this->codemp."','".$this->codusu."','".$this->cedusu."','".$this->nomusu."','".$this->apeusu."',".
							"             '".$this->pwdusu."','".$this->telusu."','".$this->nota."','".$this->email."',".$this->estatus.",".
							"             ".$this->admusu.",'1900-01-01','".$this->fotousu."')";	
			$result = $conexionbd->Execute($consulta);
			$total = count($this->admin);
			for ($i=0; $i < $total; $i++)
			{	
				$this->admin[$i]->codemp = $this->codemp;
				$this->admin[$i]->nomfisico = $this->nomfisico;	
				$this->admin[$i]->incluirPermisosInternos();
				if ($this->admin[$i]->valido)
				{					
					$this->derechos[$i] = new DerechosUsuario();
					$this->derechos[$i]->nomfisico = $this->nomfisico;
					$this->derechos[$i]->codemp = $this->codemp;
					$this->derechos[$i]->codusu = $this->codusu;
					$this->derechos[$i]->codsis = $this->codsis;
					$this->derechos[$i]->codintper = $this->codtippersss;
					$this->derechos[$i]->cargarDerechos();
					//$this->admin[$i]->cargarDerechos();
				}				
			}
			$total = count($this->constante);
			for ($i=0; $i < $total; $i++)
			{				
				$this->constante[$i]->codemp = $this->codemp;
				$this->constante[$i]->nomfisico = $this->nomfisico;	
				$this->constante[$i]->incluirPermisosInternos();
				if ($this->constante[$i]->valido)
				{
					$this->derechos[$i] = new DerechosUsuario();
					$this->derechos[$i]->nomfisico = $this->nomfisico;
					$this->derechos[$i]->codemp = $this->codemp;
					$this->derechos[$i]->codusu = $this->codusu;
					$this->derechos[$i]->codsis = $this->codsis;
					$this->derechos[$i]->codintper = $this->codcons;
					$this->derechos[$i]->cargarDerechos();
					//$this->const[$i]->cargarDerechos();
				}					
			}
			$total = count($this->nomina);
			for ($i=0; $i < $total; $i++)
			{				
				$this->nomina[$i]->codemp = $this->codemp;
				$this->nomina[$i]->nomfisico = $this->nomfisico;	
				$this->nomina[$i]->incluirPermisosInternos();	
				if ($this->nomina[$i]->valido)
				{
					$this->derechos[$i] = new DerechosUsuario();
					$this->derechos[$i]->nomfisico = $this->nomfisico;
					$this->derechos[$i]->codemp = $this->codemp;
					$this->derechos[$i]->codusu = $this->codusu;
					$this->derechos[$i]->codsis = $this->codsis;
					$this->derechos[$i]->codintper = $this->codnom;
					$this->derechos[$i]->cargarDerechos();
					//$this->nomina[$i]->cargarDerechos();
				}				
			}
			$total = count($this->unidad);
			for ($i=0; $i < $total; $i++)
			{				
				$this->unidad[$i]->codemp = $this->codemp;
				$this->unidad[$i]->nomfisico = $this->nomfisico;	
				$this->unidad[$i]->incluirPermisosInternos();
				if ($this->unidad[$i]->valido)
				{
					$this->derechos[$i] = new DerechosUsuario();
					$this->derechos[$i]->nomfisico = $this->nomfisico;
					$this->derechos[$i]->codemp = $this->codemp;
					$this->derechos[$i]->codusu = $this->codusu;
					$this->derechos[$i]->codsis = $this->codsis;
					$this->derechos[$i]->codintper = $this->coduniadm;
					$this->derechos[$i]->cargarDerechos();
					//$this->unidad[$i]->cargarDerechos();
				}								
			}
			$total = count($this->estpre);
			for ($i=0; $i < $total; $i++)
			{				
				$this->estpre[$i]->codemp = $this->codemp;
				$this->estpre[$i]->nomfisico = $this->nomfisico;	
				$this->estpre[$i]->incluirPermisosInternos();	
				if ($this->estpre[$i]->valido)
				{
					$this->derechos[$i] = new DerechosUsuario();
					$this->derechos[$i]->nomfisico = $this->nomfisico;
					$this->derechos[$i]->codemp = $this->codemp;
					$this->derechos[$i]->codusu = $this->codusu;
					$this->derechos[$i]->codsis = $this->codsis;
					$this->derechos[$i]->codintper = $this->codest;
					$this->derechos[$i]->cargarDerechos();
					//$this->estpre[$i]->cargarDerechos();
				}			
			}
		}
		catch (exception $e) 
		{	
			$this->valido  = false;	
			$this->mensaje='Error al Incluir el Usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('INSERTAR',$this->valido);
	}	
	
	
	
/***********************************************************************************
* @Función que verifica que los datos del usuario sean correctos.
* @parametros: 
* @retorno: 
* @fecha de creación: 17/07/2008.
* @autor: Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function verificarUsuario()
	{
		global $conexionbd;
		$this->verificarBloqueo();
		if ($this->verificarBloqueo())
		{
			$this->valido = false;
			$this->mensaje = 'Usuario bloqueado: Contacte al administrador del sistema';
		}
		else
		{									
			$consulta = " SELECT codusu, cedusu, nomusu, apeusu, pwdusu ".
					    "   FROM {$this->_table} ".
					    "  WHERE codemp='".$this->codemp."'".
					    "    AND codusu='".$this->codusu."' ".
					    "    AND pwdusu='".$this->pwdusu."' ". 
					    "    AND estatus=1";
			$result = $conexionbd->Execute($consulta);
			if($result===false)
			{
				$this->valido = false;
				$this->mensaje = 'Ocurrio un error: '.$conexionbd->ErrorMsg();
			}
			else
			{
				if (!$result->EOF)
				{	
					$this->actualizarAcceso();
					$_SESSION['la_cedusu']=$result->fields['cedusu'];
					$_SESSION['la_nomusu']=$result->fields['nomusu'];
					$_SESSION['la_apeusu']=$result->fields['apeusu'];
					$_SESSION['la_codusu']=$result->fields['codusu'];
					$_SESSION['la_pasusu']=$result->fields['pwdusu'];
					$_SESSION['la_logusr']=$result->fields['codusu'];
					unset($_SESSION['sigesp_intentos']);
					$this->valido = true;
				}			
				else
				{	
					$intentos = $_SESSION['sigesp_intentos']++;
					if ($intentos > 3)
					{
						$this->bloquearUsuario();
						$this->valido = false;
						$this->mensaje = 'Usuario fue bloqueado.';
					}
					else
					{	
						$this->valido = false;
						$this->mensaje = 'Usuario o password incorrectos.';
					}
				}
			}
		}
	}
	
	
	
/***********************************************************************************
* @Función que verifica si un usuario está bloqueado.
* @parametros:
* @retorno: 
* @fecha de creación: 01/08/2008.
* @autor: Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function verificarBloqueo()
	{
		global $conexionbd;		
		$bloqueado = true;
		try
		{
			$consulta = " SELECT codusu ".
				  	    "   FROM {$this->_table} ".
		 		 	    "  WHERE codemp = '".$this->codemp."' ".
				 	    "    AND codusu = '".$this->codusu."' ".
				 	    "    AND estatus = 2 ";	
			$result = $conexionbd->Execute($consulta);			
			if ($result->EOF)
			{
				$bloqueado = false;
			}
		}
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al consultar el estatus del Usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
		}
		$result->Close();
		return $bloqueado;
	}
	
	
/***********************************************************************************
* @Función que bloquea un usuario.
* @parametros: 
* @retorno: 
* @fecha de creación: 01/08/2008.
* @autor: Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function bloquearUsuario()
	{
		global $conexionbd;
		$this->mensaje = 'Actualizo el estatus a Bloqueado al Usuario '.$this->codusu;
		$conexionbd->StartTrans();
		try
		{
			$consulta = " UPDATE {$this->_table} ".
					    "    SET estatus=2 ".
					    "  WHERE codemp = '".$this->codemp."' ".
					    "    AND codusu = '".$this->codusu."' ";
			$result = $conexionbd->Execute($consulta);
		}	
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al actualizar el estatus a bloqueado al Usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('MODIFICAR',$this->valido);
	}
	

/***********************************************************************************
* @Función que actualiza el último acceso de un usuario.
* @parametros: 
* @retorno: 
* @fecha de creación: 17/07/2008.
* @autor: Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function actualizarAcceso()
	{
		global $conexionbd;
		$fecha = date('Y/m/d');
		$this->mensaje = 'Actualizo la fecha de ingreso del Usuario '.$this->codusu;
		$conexionbd->StartTrans();
		try 
		{
			$consulta = " UPDATE {$this->_table} ".
					    "    SET ultingusu = '".$fecha."' ".
				  	    "  WHERE codemp =  '".$this->codemp."'".
				  	    "    AND codusu = '".$this->codusu."' ";
			$result = $conexionbd->Execute($consulta);
		}	
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al actualizar la fecha de ingreso al Usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('MODIFICAR',$this->valido);
	}
	
	
/****************************************************************************
* @Función que busca un usuario
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
******************************************************************************/		
	function leer() 
 	{		
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->seleccionarConexion(&$conexionbd);
		
		try
		{
			$consulta = " SELECT codemp,codusu,cedusu,nomusu,apeusu,pwdusu,fecnacusu,telusu, ".
						" 		 email,estatus,admusu,ultingusu,nota,1 as valido ".
						"   FROM {$this->_table} ".
						"  WHERE codemp='{$this->codemp}' ".
						"    AND codusu<>'--------------------'";
			$cadena=" ";
            $total = count($this->criterio);
            for ($contador = 0; $contador < $total; $contador++)
			{
            	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 			               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
            }
            $consulta.= $cadena;
            $consulta.= "ORDER BY codusu";
			$result = $conexionbd->Execute($consulta);
			return $result;
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar el Usuario '.$consulta.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);			
		}
 		   	
 	} 
 	

/****************************************************************************
* @Función que busca los usuarios que están activos
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
**************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
******************************************************************************/		
	function leerActivos() //para controlador de usuariogrupo
	{
		global $conexionbd;
		try
		{
			$consulta = " SELECT codemp,codusu,cedusu,nomusu,apeusu,fecnacusu,telusu,".
						"        email,estatus,admusu,ultingusu,nota, 1 as valido ". 
						"   FROM {$this->_table} ".
					    "  WHERE codemp='{$this->codemp}' ".
						"    AND estatus=1 ";
			$consulta.= "ORDER BY codusu";
			$result = $conexionbd->Execute($consulta);
			return $result;
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar el Usuario '.$consulta.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
	   	} 
	}

	
/*****************************************************************************
* @Función que  valida si un usuario ya existe
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
******************************************************************************
* @fecha modificación:
* @descripción:
* @autor: 
****************************************************************************/		
	function buscarCodigo()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->seleccionarConexion(&$conexionbd);
		
		try
		{
			$consulta = " SELECT codusu ".
						"   FROM {$this->_table} ".
						"  WHERE codemp='{$this->codemp}' ".
						"    AND codusu='{$this->codusu}' ";
			$result = $conexionbd->Execute($consulta);
			if ($result->EOF)
			{
				$this->existe = false;
			}
		}
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al consultar el usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
		}
	}
		
	
/***********************************************************************************
* @Función que actualiza los detalles de un Usuario.
* @parametros: 
* @retorno: 
* @fecha de creación: 30/09/2008.
* @autor: Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	function modificar()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->mensaje='Modifico el Usuario '.$this->codusu;
		$conexionbd->StartTrans();
		try 
		{ 
			$consulta = " UPDATE {$this->_table} ".
						"    SET cedusu='{$this->cedusu}',".
						"        nomusu='{$this->nomusu}', ".
						"        apeusu='{$this->apeusu}',".
						"        telusu='{$this->telusu}',".
						"        email='{$this->email}', ".
						"        estatus={$this->estatus},".
						"        admusu={$this->admusu},".
						"        nota='{$this->nota}' ".
						" WHERE codemp='{$this->codemp}' ".
						"   AND codusu='{$this->codusu}'";
			$result = $conexionbd->Execute($consulta);
			
			$total=	count($this->usuariopersonal);
			for ($i=0; $i < $total; $i++)
			{	
				$this->usuariopersonal[$i]->codemp = $this->codemp;
				$this->usuariopersonal[$i]->nomfisico = $this->nomfisico;
				/*$this->usuariopersonal[$i]->criterio[0]['operador'] = "WHERE";
				$this->usuariopersonal[$i]->criterio[0]['criterio'] = "codemp";
				$this->usuariopersonal[$i]->criterio[0]['condicion'] = "=";
				$this->usuariopersonal[$i]->criterio[0]['valor'] = "'".$this->codemp."'";*/
				
				$this->usuariopersonal[$i]->criterio[0]['operador'] = "AND";
				$this->usuariopersonal[$i]->criterio[0]['criterio'] = "codusu";
				$this->usuariopersonal[$i]->criterio[0]['condicion'] = "=";
				$this->usuariopersonal[$i]->criterio[0]['valor'] = "'".$this->codusu."'";
				
				$this->usuariopersonal[$i]->criterio[1]['operador'] = "AND";
				$this->usuariopersonal[$i]->criterio[1]['criterio'] = "codsis";
				$this->usuariopersonal[$i]->criterio[1]['condicion'] = "=";
				$this->usuariopersonal[$i]->criterio[1]['valor'] = "'".$this->usuariopersonal[$i]->codsis."'";
				
				$this->usuariopersonal[$i]->criterio[2]['operador'] = "AND";
				$this->usuariopersonal[$i]->criterio[2]['criterio'] = "codintper";
				$this->usuariopersonal[$i]->criterio[2]['condicion'] = "=";
				$this->usuariopersonal[$i]->criterio[2]['valor'] = "'".$this->usuariopersonal[$i]->codintper."'";
				
				//$this->usuariopersonal[$i]->eliminar();
				$this->usuariopersonal[$i]->eliminarTodosPrueba();
			}
			$total=	count($this->usuarioconstante);
			for ($i=0; $i < $total; $i++)
			{	
				$this->usuarioconstante[$i]->codemp = $this->codemp;
				$this->usuarioconstante[$i]->nomfisico = $this->nomfisico;
				/*$this->usuarioconstante[$i]->criterio[0]['operador'] = "WHERE";
				$this->usuarioconstante[$i]->criterio[0]['criterio'] = "codemp";
				$this->usuarioconstante[$i]->criterio[0]['condicion'] = "=";
				$this->usuarioconstante[$i]->criterio[0]['valor'] = "'".$this->codemp."'";*/
				
				$this->usuarioconstante[$i]->criterio[0]['operador'] = "AND";
				$this->usuarioconstante[$i]->criterio[0]['criterio'] = "codusu";
				$this->usuarioconstante[$i]->criterio[0]['condicion'] = "=";
				$this->usuarioconstante[$i]->criterio[0]['valor'] = "'".$this->codusu."'";
				
				$this->usuarioconstante[$i]->criterio[1]['operador'] = "AND";
				$this->usuarioconstante[$i]->criterio[1]['criterio'] = "codsis";
				$this->usuarioconstante[$i]->criterio[1]['condicion'] = "=";
				$this->usuarioconstante[$i]->criterio[1]['valor'] = "'".$this->usuarioconstante[$i]->codsis."'";
				
				$this->usuarioconstante[$i]->criterio[2]['operador'] = "AND";
				$this->usuarioconstante[$i]->criterio[2]['criterio'] = "codintper";
				$this->usuarioconstante[$i]->criterio[2]['condicion'] = "=";
				$this->usuarioconstante[$i]->criterio[2]['valor'] = "'".$this->usuarioconstante[$i]->codintper."'";
				
				//$this->usuariopersonal[$i]->eliminar();
				$this->usuarioconstante[$i]->eliminarTodosPrueba();
			}
			$total=	count($this->usuarionomina);
			for ($i=0; $i < $total; $i++)
			{	
				$this->usuarionomina[$i]->codemp = $this->codemp;
				$this->usuarionomina[$i]->nomfisico = $this->nomfisico;
				/*$this->usuarionomina[$i]->criterio[0]['operador'] = "WHERE";
				$this->usuarionomina[$i]->criterio[0]['criterio'] = "codemp";
				$this->usuarionomina[$i]->criterio[0]['condicion'] = "=";
				$this->usuarionomina[$i]->criterio[0]['valor'] = "'".$this->codemp."'";*/
				
				$this->usuarionomina[$i]->criterio[0]['operador'] = "AND";
				$this->usuarionomina[$i]->criterio[0]['criterio'] = "codusu";
				$this->usuarionomina[$i]->criterio[0]['condicion'] = "=";
				$this->usuarionomina[$i]->criterio[0]['valor'] = "'".$this->codusu."'";
				
				$this->usuarionomina[$i]->criterio[1]['operador'] = "AND";
				$this->usuarionomina[$i]->criterio[1]['criterio'] = "codsis";
				$this->usuarionomina[$i]->criterio[1]['condicion'] = "=";
				$this->usuarionomina[$i]->criterio[1]['valor'] = "'".$this->usuarionomina[$i]->codsis."'";
				
				$this->usuarionomina[$i]->criterio[2]['operador'] = "AND";
				$this->usuarionomina[$i]->criterio[2]['criterio'] = "codintper";
				$this->usuarionomina[$i]->criterio[2]['condicion'] = "=";
				$this->usuarionomina[$i]->criterio[2]['valor'] = "'".$this->usuarionomina[$i]->codintper."'";
				
				//$this->usuariopersonal[$i]->eliminar();
				$this->usuarionomina[$i]->eliminarTodosPrueba();
			}
			$total=	count($this->usuariounidad);
			for ($i=0; $i < $total; $i++)
			{	
				$this->usuariounidad[$i]->codemp = $this->codemp;
				$this->usuariounidad[$i]->nomfisico = $this->nomfisico;
			/*	$this->usuariounidad[$i]->criterio[0]['operador'] = "WHERE";
				$this->usuariounidad[$i]->criterio[0]['criterio'] = "codemp";
				$this->usuariounidad[$i]->criterio[0]['condicion'] = "=";
				$this->usuariounidad[$i]->criterio[0]['valor'] = "'".$this->codemp."'";*/
				
				$this->usuariounidad[$i]->criterio[0]['operador'] = "AND";
				$this->usuariounidad[$i]->criterio[0]['criterio'] = "codusu";
				$this->usuariounidad[$i]->criterio[0]['condicion'] = "=";
				$this->usuariounidad[$i]->criterio[0]['valor'] = "'".$this->codusu."'";
				
				$this->usuariounidad[$i]->criterio[1]['operador'] = "AND";
				$this->usuariounidad[$i]->criterio[1]['criterio'] = "codsis";
				$this->usuariounidad[$i]->criterio[1]['condicion'] = "=";
				$this->usuariounidad[$i]->criterio[1]['valor'] = "'".$this->usuariounidad[$i]->codsis."'";
				
				$this->usuariounidad[$i]->criterio[2]['operador'] = "AND";
				$this->usuariounidad[$i]->criterio[2]['criterio'] = "codintper";
				$this->usuariounidad[$i]->criterio[2]['condicion'] = "=";
				$this->usuariounidad[$i]->criterio[2]['valor'] = "'".$this->usuariounidad[$i]->codintper."'";
				
				//$this->usuariopersonal[$i]->eliminar();
				$this->usuariounidad[$i]->eliminarTodosPrueba();
			}
			$total=	count($this->usuarioestpre);
			for ($i=0; $i < $total; $i++)
			{	
				$this->usuarioestpre[$i]->codemp = $this->codemp;
				$this->usuarioestpre[$i]->nomfisico = $this->nomfisico;
				/*$this->usuarioestpre[$i]->criterio[0]['operador'] = "WHERE";
				$this->usuarioestpre[$i]->criterio[0]['criterio'] = "codemp";
				$this->usuarioestpre[$i]->criterio[0]['condicion'] = "=";
				$this->usuarioestpre[$i]->criterio[0]['valor'] = "'".$this->codemp."'";*/
				
				$this->usuarioestpre[$i]->criterio[0]['operador'] = "AND";
				$this->usuarioestpre[$i]->criterio[0]['criterio'] = "codusu";
				$this->usuarioestpre[$i]->criterio[0]['condicion'] = "=";
				$this->usuarioestpre[$i]->criterio[0]['valor'] = "'".$this->codusu."'";
				
				$this->usuarioestpre[$i]->criterio[1]['operador'] = "AND";
				$this->usuarioestpre[$i]->criterio[1]['criterio'] = "codsis";
				$this->usuarioestpre[$i]->criterio[1]['condicion'] = "=";
				$this->usuarioestpre[$i]->criterio[1]['valor'] = "'".$this->usuarioestpre[$i]->codsis."'";
				
				$this->usuarioestpre[$i]->criterio[2]['operador'] = "AND";
				$this->usuarioestpre[$i]->criterio[2]['criterio'] = "codintper";
				$this->usuarioestpre[$i]->criterio[2]['condicion'] = "=";
				$this->usuarioestpre[$i]->criterio[2]['valor'] = "'".$this->usuarioestpre[$i]->codintper."'";
				
				//$this->usuariopersonal[$i]->eliminar();
				$this->usuarioestpre[$i]->eliminarTodosPrueba();
			}
			$total = count($this->admin);
			for ($i=0; $i<$total; $i++)
			{	
				$this->admin[$i]->codemp = $this->codemp;
				$this->admin[$i]->nomfisico = $this->nomfisico;
				$this->admin[$i]->incluirPermisosInternos();
			}
			$total = count($this->constante);
			for ($i=0; $i<$total; $i++)
			{	
				$this->constante[$i]->codemp = $this->codemp;
				$this->constante[$i]->nomfisico = $this->nomfisico;
				$this->constante[$i]->incluirPermisosInternos();
			}
			$total = count($this->nomina);
			for ($i=0; $i<$total; $i++)
			{	
				$this->nomina[$i]->codemp = $this->codemp;
				$this->nomina[$i]->nomfisico = $this->nomfisico;
				$this->nomina[$i]->incluirPermisosInternos();
			}
			$total = count($this->unidad);
			for ($i=0; $i<$total; $i++)
			{	
				$this->unidad[$i]->codemp = $this->codemp;
				$this->unidad[$i]->nomfisico = $this->nomfisico;
				$this->unidad[$i]->incluirPermisosInternos();
					
			}
			$total = count($this->estpre);
			for ($i=0; $i<$total; $i++)
			{	
				$this->estpre[$i]->codemp = $this->codemp;
				$this->estpre[$i]->nomfisico = $this->nomfisico;
				$this->estpre[$i]->incluirPermisosInternos();
			}
		}
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al Modificar el Usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('MODIFICAR',$this->valido);
	}
	
	
/****************************************************************************
* @Función que elimina un usuario actualizando su estatus a suspendido
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
* ************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*****************************************************************************/		
	function eliminar()
	{
		global $conexionbd;
		$this->mensaje='Elimino el Usuario '.$this->codusu;
		$conexionbd->StartTrans();
		try 
		{
			$this->usuariodetalle[0]->codemp = $this->codemp;
			$this->usuariodetalle[0]->codusu = $this->codusu;
			$this->usuariodetalle[0]->nomfisico = $this->nomfisico;
			
			$this->usuariodetalle[0]->criterio[0]['operador']  = " AND";
			$this->usuariodetalle[0]->criterio[0]['criterio']  = "codusu";
			$this->usuariodetalle[0]->criterio[0]['condicion'] = "=";
			$this->usuariodetalle[0]->criterio[0]['valor']     = "'".$this->codusu."'";
			
			$this->usuariodetalle[0]->eliminarTodosPrueba();	
						
			$consulta = " UPDATE {$this->_table} ".
						"    SET estatus=3 ".
						"  WHERE codemp='{$this->codemp}' ".
						"    AND codusu='{$this->codusu}'";
			$result = $conexionbd->Execute($consulta);
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje=' Error al Eliminar el Usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
	   	} 
	   	$conexionbd->CompleteTrans();
		$this->incluirSeguridad('ELIMINAR',$this->valido);	
	}	
	
	
/*******************************************************************************
* @Función que verifica un campo de usuario
* @parametros: 
* @retorno:
* @fecha de creación: 29/10/2008
* @autor: Ing. Gusmary Balza
***************************************************************
* @fecha modificación:
* @descripción:
* @autor:
********************************************************************************/		
	function verificarExistenciaDato() 
	{
		global $conexionbd;
		try
		{
			$consulta = " SELECT codusu,nomusu,apeusu,1 as valido ".
						" FROM {$this->_table} ".
						" WHERE codemp='{$this->codemp}' ".
						"  AND codusu='{$this->codusu}' ";			
			$cadena=" ";
            $total = count($this->criterio);
            for ($contador = 0; $contador < $total; $contador++)
			{
            	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 			               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
            }
            $consulta.= $cadena;
			$result = $conexionbd->Execute($consulta);
			if ($result->EOF)
			{
				$this->existe = false;
			}
			return $result;
			
		}
		catch (exception $e)
		{
			$this->valido  = false;	
			$this->mensaje='Error al consultar el usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
		}	
	}
	
	
/*****************************************************************************************
* @Función que actualiza la contraseña de un usuario
* @parametros: 
* @retorno:
* @fecha de creación: 06/08/2008
* @autor: Ing. Gusmary Balza
*************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*******************************************************************************************/		
	function actualizarPassword()   //para controlador cambio de password
	{
		global $conexionbd;
		$this->mensaje = 'Modifico el password al usuario: '.$this->codusu;
		$conexionbd->StartTrans();
		try
		{
			$consulta = " UPDATE {$this->_table} ".
						"    SET pwdusu='{$this->nuevopassword}' ".
						"  WHERE codemp='{$this->codemp}' ".
						"    AND codusu='{$this->codusu}'";
			$result = $conexionbd->Execute($consulta);
		}
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al Modificar el password al Usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('MODIFICAR',$this->valido);
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
		if($tipotransaccion)
		{
			$objEvento = new RegistroEventos();
			$tiponotificacion = 'NOTIFICACION';
		}
		else
		{
			$objEvento = new RegistroFallas();
			$tiponotificacion = 'ERROR';
		}
		// Registro del Evento
		$objEvento->codemp = $this->codemp;
		$objEvento->codsis = 'SSS';
		$objEvento->nomfisico = $this->nomfisico;
		$objEvento->evento = $evento;
		$objEvento->desevetra = $this->mensaje;
		$objEvento->incluir();
		// Envío de Notificación
		$objEvento->objNotificacion->codemp=$this->codemp;
		$objEvento->objNotificacion->sistema=$this->codsis;
		$objEvento->objNotificacion->tipo=$tiponotificacion;
		$objEvento->objNotificacion->titulo='DEFINICIÓN DE USUARIO';
		$objEvento->objNotificacion->usuario=$_SESSION['la_logusr'];
		$objEvento->objNotificacion->operacion=$this->mensaje;
		$objEvento->objNotificacion->enviarNotificacion();
		unset($objEvento);
	}
	
}
?>