<?php
/***********************************************************************************
* @Clase para Manejar  para la definición de Grupo
* @fecha de creación: 30/09/2008.
* @autor: Ing. Gusmary Balza
* **************************
* @fecha modificacion  14/10/2008
* @autor  Ing. Yesenia Moreno de Lang
* @descripcion  Se agrego la seguridad y manejo de errores
***********************************************************************************/

require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once('sigesp_dao_sss_registroeventos.php');
require_once('sigesp_dao_sss_registrofallas.php');

class Grupo extends ADOdb_Active_Record
{
	var $_table = 'sss_grupos';
	public $valido=true;
	public $existe=true;
	public $seguridad=true;
	public $mensaje;
	public $cadena;
	public $criterio;
	public $codsis;
	public $nomfisico;
	public $admin = array();
	public $usuarioeliminar = array();
	public $personal = array();
	public $constante = array();
	public $nomina = array();
	public $unidad = array();
	public $estpre = array();
	//public $admin = array();
	public $derechos;
	var $grupopersonal = array();
	var $grupoconstante = array();
	var $gruponomina = array();
	var $grupoounidad = array();
	var $grupoestpre = array();
	
	var $grupodetalle = array();

/***********************************************************************************
* @Función que  valida si un grupo ya existe
* @parametros: 
* @retorno:
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function verificarCodigo()
	{
		global $conexionbd;
		try 
		{ 
			$consulta="SELECT nomgru ".
					  "  FROM {$this->_table} ".
					  " WHERE codemp = '{$this->codemp}' ".
					  "   AND nomgru = '{$this->nomgru}' ".
					  "	AND estatus=1";
			$result = $conexionbd->Execute($consulta);
			if ($result->EOF)
			{		
				$this->existe = false;		
			}
			$result->Close(); 
		}
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al consultar el Grupo '.$this->nomgru.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
		}
	}

	
/***********************************************************************************
* @Función para insertar un grupo.
* @parametros: 
* @retorno:
* @fecha de creación: 30/09/2008.
* @autor: Ing.Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	public function incluir()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->mensaje='Incluyo el Grupo '.$this->nomgru;
		$conexionbd->StartTrans();
		try 
		{ 
			//$this->save();
			$consulta = " INSERT INTO {$this->_table} ".
						"	(codemp,nomgru,nota,estatus) ".
						" 	values ('{$this->codemp}','{$this->nomgru}','{$this->nota}',1)";
			$result = $conexionbd->Execute($consulta);
			$total=	count($this->admin);
			for ($contador=0; $contador < $total; $contador++)
			{	
				$this->admin[$contador]->codemp = $this->codemp;
				$this->admin[$contador]->codsis = $this->codsis;
				$this->admin[$contador]->nomfisico = $this->nomfisico;
				$this->admin[$contador]->nomgru = $this->nomgru;				
				$this->admin[$contador]->incluir();
			}
			$total = count($this->personal);
			for ($i=0; $i < $total; $i++)
			{	
				$this->personal[$i]->codemp = $this->codemp;
				$this->personal[$i]->nomfisico = $this->nomfisico;	
				$this->personal[$i]->incluirPermisosInternos();
				if ($this->personal[$i]->valido)
				{					
					$this->derechos[$i] = new DerechosGrupo();
					$this->derechos[$i]->nomfisico = $this->nomfisico;
					$this->derechos[$i]->codemp = $this->codemp;
					$this->derechos[$i]->nomgru = $this->nomgru;
					$this->derechos[$i]->codsis = $this->codsis;
					$this->derechos[$i]->codintper = $this->codintper;
					$this->derechos[$i]->cargarDerechos();
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
					$this->derechos[$i] = new DerechosGrupo();
					$this->derechos[$i]->nomfisico = $this->nomfisico;
					$this->derechos[$i]->codemp = $this->codemp;
					$this->derechos[$i]->nomgru = $this->nomgru;
					$this->derechos[$i]->codsis = $this->codsis;
					$this->derechos[$i]->codintper = $this->codcons;
					$this->derechos[$i]->cargarDerechos();					
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
					$this->derechos[$i] = new DerechosGrupo();
					$this->derechos[$i]->nomfisico = $this->nomfisico;
					$this->derechos[$i]->codemp = $this->codemp;
					$this->derechos[$i]->nomgru = $this->nomgru;
					$this->derechos[$i]->codsis = $this->codsis;
					$this->derechos[$i]->codintper = $this->codnom;
					$this->derechos[$i]->cargarDerechos();
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
					$this->derechos[$i] = new DerechosGrupo();
					$this->derechos[$i]->nomfisico = $this->nomfisico;
					$this->derechos[$i]->codemp = $this->codemp;
					$this->derechos[$i]->nomgru = $this->nomgru;
					$this->derechos[$i]->codsis = $this->codsis;
					$this->derechos[$i]->codintper = $this->coduniadm;
					$this->derechos[$i]->cargarDerechos();
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
					$this->derechos[$i] = new DerechosGrupo();
					$this->derechos[$i]->nomfisico = $this->nomfisico;
					$this->derechos[$i]->codemp = $this->codemp;
					$this->derechos[$i]->nomgru = $this->nomgru;
					$this->derechos[$i]->codsis = $this->codsis;
					$this->derechos[$i]->codintper = $this->codest;
					$this->derechos[$i]->cargarDerechos();
				}			
			}
		}
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al Incluir el Grupo '.$this->nomgru.' '.$conexionbd->ErrorMsg();
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('INSERTAR',$this->valido);
	}
	
	
/***********************************************************************************
* @Función que Actualiza un grupo
* @parametros: 
* @retorno:
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function modificar()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->mensaje='Modifico el Grupo '.$this->nomgru;
		$conexionbd->StartTrans();
		try 
		{ 			
			$consulta = " UPDATE {$this->_table} SET nota = '{$this->nota}', ".
						" estatus=1 WHERE nomgru='{$this->nomgru}'";
			$result = $conexionbd->Execute($consulta);	
			
			$total=	count($this->usuarioeliminar);
			for ($contador=0; $contador < $total; $contador++)
			{	
				$this->usuarioeliminar[$contador]->codemp = $this->codemp;
				$this->usuarioeliminar[$contador]->nomgru = $this->nomgru;				
				$this->usuarioeliminar[$contador]->codsis = $this->codsis;
				$this->usuarioeliminar[$contador]->nomfisico = $this->nomfisico;
				$this->usuarioeliminar[$contador]->eliminar();
			}
			$total=	count($this->admin);
			for ($contador=0; $contador < $total; $contador++)
			{	
				$this->admin[$contador]->codemp = $this->codemp;
				$this->admin[$contador]->codsis = $this->codsis;
				$this->admin[$contador]->nomfisico = $this->nomfisico;
				$this->admin[$contador]->nomgru = $this->nomgru;				
				$this->admin[$contador]->incluir();
			}
			$total=	count($this->grupopersonal);
			for ($i=0; $i < $total; $i++)
			{	
				$this->grupopersonal[$i]->codemp = $this->codemp;
				$this->grupopersonal[$i]->nomfisico = $this->nomfisico;
				
				$this->grupopersonal[$i]->criterio[0]['operador'] = "AND";
				$this->grupopersonal[$i]->criterio[0]['criterio'] = "nomgru";
				$this->grupopersonal[$i]->criterio[0]['condicion'] = "=";
				$this->grupopersonal[$i]->criterio[0]['valor'] = "'".$this->nomgru."'";
							
				$this->grupopersonal[$i]->criterio[1]['operador'] = "AND";
				$this->grupopersonal[$i]->criterio[1]['criterio'] = "codsis";
				$this->grupopersonal[$i]->criterio[1]['condicion'] = "=";
				$this->grupopersonal[$i]->criterio[1]['valor'] = "'".$this->grupopersonal[$i]->codsis."'";
				
				$this->grupopersonal[$i]->criterio[2]['operador'] = "AND";
				$this->grupopersonal[$i]->criterio[2]['criterio'] = "codintper";
				$this->grupopersonal[$i]->criterio[2]['condicion'] = "=";
				$this->grupopersonal[$i]->criterio[2]['valor'] = "'".$this->grupopersonal[$i]->codintper."'";
				
				//$this->grupopersonal[$i]->eliminar();
				$this->grupopersonal[$i]->eliminarGeneral();
			}
			$total=	count($this->grupoconstante);
			for ($i=0; $i < $total; $i++)
			{	
				$this->grupoconstante[$i]->codemp = $this->codemp;
				$this->grupoconstante[$i]->nomfisico = $this->nomfisico;

				$this->grupoconstante[$i]->criterio[0]['operador'] = "AND";
				$this->grupoconstante[$i]->criterio[0]['criterio'] = "nomgru";
				$this->grupoconstante[$i]->criterio[0]['condicion'] = "=";
				$this->grupoconstante[$i]->criterio[0]['valor'] = "'".$this->nomgru."'";
				
				$this->grupoconstante[$i]->criterio[1]['operador'] = "AND";
				$this->grupoconstante[$i]->criterio[1]['criterio'] = "codsis";
				$this->grupoconstante[$i]->criterio[1]['condicion'] = "=";
				$this->grupoconstante[$i]->criterio[1]['valor'] = "'".$this->grupoconstante[$i]->codsis."'";
				
				$this->grupoconstante[$i]->criterio[2]['operador'] = "AND";
				$this->grupoconstante[$i]->criterio[2]['criterio'] = "codintper";
				$this->grupoconstante[$i]->criterio[2]['condicion'] = "=";
				$this->grupoconstante[$i]->criterio[2]['valor'] = "'".$this->grupoconstante[$i]->codintper."'";
				
				$this->grupoconstante[$i]->eliminarGeneral();
			}
			$total=	count($this->gruponomina);
			for ($i=0; $i < $total; $i++)
			{	
				$this->gruponomina[$i]->codemp = $this->codemp;
				$this->gruponomina[$i]->nomfisico = $this->nomfisico;

				$this->gruponomina[$i]->criterio[0]['operador'] = "AND";
				$this->gruponomina[$i]->criterio[0]['criterio'] = "nomgru";
				$this->gruponomina[$i]->criterio[0]['condicion'] = "=";
				$this->gruponomina[$i]->criterio[0]['valor'] = "'".$this->nomgru."'";
				
				$this->gruponomina[$i]->criterio[1]['operador'] = "AND";
				$this->gruponomina[$i]->criterio[1]['criterio'] = "codsis";
				$this->gruponomina[$i]->criterio[1]['condicion'] = "=";
				$this->gruponomina[$i]->criterio[1]['valor'] = "'".$this->gruponomina[$i]->codsis."'";
				
				$this->gruponomina[$i]->criterio[2]['operador'] = "AND";
				$this->gruponomina[$i]->criterio[2]['criterio'] = "codintper";
				$this->gruponomina[$i]->criterio[2]['condicion'] = "=";
				$this->gruponomina[$i]->criterio[2]['valor'] = "'".$this->gruponomina[$i]->codintper."'";
								
				$this->gruponomina[$i]->eliminarGeneral();
			}
			$total=	count($this->grupounidad);
			for ($i=0; $i < $total; $i++)
			{	
				$this->grupounidad[$i]->codemp = $this->codemp;
				$this->grupounidad[$i]->nomfisico = $this->nomfisico;

				$this->grupounidad[$i]->criterio[0]['operador'] = "AND";
				$this->grupounidad[$i]->criterio[0]['criterio'] = "nomgru";
				$this->grupounidad[$i]->criterio[0]['condicion'] = "=";
				$this->grupounidad[$i]->criterio[0]['valor'] = "'".$this->nomgru."'";
				
				$this->grupounidad[$i]->criterio[1]['operador'] = "AND";
				$this->grupounidad[$i]->criterio[1]['criterio'] = "codsis";
				$this->grupounidad[$i]->criterio[1]['condicion'] = "=";
				$this->grupounidad[$i]->criterio[1]['valor'] = "'".$this->grupounidad[$i]->codsis."'";
				
				$this->grupounidad[$i]->criterio[2]['operador'] = "AND";
				$this->grupounidad[$i]->criterio[2]['criterio'] = "codintper";
				$this->grupounidad[$i]->criterio[2]['condicion'] = "=";
				$this->grupounidad[$i]->criterio[2]['valor'] = "'".$this->grupounidad[$i]->codintper."'";
				
				$this->grupounidad[$i]->eliminarGeneral();
			}
			$total=	count($this->grupoestpre);
			for ($i=0; $i < $total; $i++)
			{	
				$this->grupoestpre[$i]->codemp = $this->codemp;
				$this->grupoestpre[$i]->nomfisico = $this->nomfisico;

				$this->grupoestpre[$i]->criterio[0]['operador'] = "AND";
				$this->grupoestpre[$i]->criterio[0]['criterio'] = "nomgru";
				$this->grupoestpre[$i]->criterio[0]['condicion'] = "=";
				$this->grupoestpre[$i]->criterio[0]['valor'] = "'".$this->nomgru."'";
				
				$this->grupoestpre[$i]->criterio[1]['operador'] = "AND";
				$this->grupoestpre[$i]->criterio[1]['criterio'] = "codsis";
				$this->grupoestpre[$i]->criterio[1]['condicion'] = "=";
				$this->grupoestpre[$i]->criterio[1]['valor'] = "'".$this->grupoestpre[$i]->codsis."'";
								
				$this->grupoestpre[$i]->criterio[2]['operador'] = "AND";
				$this->grupoestpre[$i]->criterio[2]['criterio'] = "codintper";
				$this->grupoestpre[$i]->criterio[2]['condicion'] = "=";
				$this->grupoestpre[$i]->criterio[2]['valor'] = "'".$this->grupoestpre[$i]->codintper."'";
				
				$this->grupoestpre[$i]->eliminarGeneral();
			}
			$total = count($this->personal);
			for ($i=0; $i<$total; $i++)
			{	
				$this->personal[$i]->codemp = $this->codemp;
				$this->personal[$i]->nomfisico = $this->nomfisico;
				$this->personal[$i]->incluirPermisosInternos();
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
			$this->mensaje='Error al Modificar el Grupo '.$this->nomgru.' '.$conexionbd->ErrorMsg();
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('MODIFICAR',$this->valido);
	}
	
	
/***********************************************************************************
* @Función que Elimina un grupo
* @parametros: 
* @retorno:
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function eliminar()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->mensaje='Elimino el Grupo '.$this->nomgru;
		$conexionbd->StartTrans(); 
		try 
		{ 
			$this->grupodetalle[0]->codemp = $this->codemp;
			$this->grupodetalle[0]->nomgru = $this->nomgru;
			$this->grupodetalle[0]->nomfisico = $this->nomfisico;
			$this->grupodetalle[0]->eliminarGeneral();	
			//$this->grupodetalle[0]->eliminar();	
									
			$this->usuarioeliminar[0]->codemp = $this->codemp;
			$this->usuarioeliminar[0]->codsis = $this->codsis;
			$this->usuarioeliminar[0]->nomfisico = $this->nomfisico;
			$this->usuarioeliminar[0]->nomgru = $this->nomgru;							
			$this->usuarioeliminar[0]->eliminarTodos();		
			
			$consulta = " UPDATE {$this->_table} SET estatus = 0 ". 
						" WHERE nomgru='{$this->nomgru}'";
			$result = $conexionbd->Execute($consulta);
		} 
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje=' Error al Eliminar el Grupo '.$this->nomgru.' '.$conexionbd->ErrorMsg();
	   	} 
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('ELIMINAR',$this->valido);
	}
		
	
/***********************************************************************************
* @Función que Busca uno o todos grupo
* @parametros: 
* @retorno:
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
**************************************************************************************/		
	public function leer() 
 	{		
		global $conexionbd;
		try 
		{ 
			$consulta = " SELECT codemp,nomgru,nota, 1 as valido ".
						" FROM {$this->_table} WHERE nomgru<>'-----' ".
						" AND codemp='$this->codemp' AND estatus=1";
			/*$cadena=" ";
            $total = count($this->criterio);
            for ($contador = 0; $contador < $total; $contador++)
			{
            	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 			               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
            }
            $consulta.= $cadena;*/
			if (($this->criterio=='')&&(($this->cadena!='')))
			{
				$consulta .= " AND nomgru ='{$this->cadena}'";
			}
			elseif ($this->criterio!='')
			{
				$consulta .= " AND {$this->criterio} like '{$this->cadena}%'";
		  	}
		  	$consulta.= " ORDER BY nomgru";
			$result = $conexionbd->Execute($consulta);
			return $result;
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar el Grupo '.$consulta.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
	   	} 
 	}
	
	
/***********************************************************************************
* @Función que busca los usuarios de un grupo
* @parametros: 
* @retorno:
* @fecha de creación: 06/10/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/
	public function obtenerUsuarios()
	{
		global $conexionbd;
		try 
		{ 
			$consulta = " SELECT {$this->_table}.nomgru, sss_usuarios.codusu, sss_usuarios.nomusu,".
						"  		sss_usuarios.apeusu, sss_usuarios.email, 1 as valido ".
						"  FROM {$this->_table} ".
						" INNER JOIN  (sss_usuarios_en_grupos ".
						"      INNER JOIN sss_usuarios  ".
						"   	   ON sss_usuarios.codemp = sss_usuarios_en_grupos.codemp ".
						"         AND sss_usuarios.codusu = sss_usuarios_en_grupos.codusu) ".
						"    ON {$this->_table}.codemp = sss_usuarios_en_grupos.codemp ".
						"   AND {$this->_table}.nomgru = sss_usuarios_en_grupos.nomgru ".
						" WHERE {$this->_table}.nomgru = '{$this->nomgru}' ".
						"   AND {$this->_table}.codemp = '{$this->codemp}' ";
			$result = $conexionbd->Execute($consulta);
			return $result;
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar los usuarios del Grupo '.$consulta.' '.$conexionbd->ErrorMsg();
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
		$objEvento->objNotificacion->titulo='DEFINICIÓN DE GRUPO';
		$objEvento->objNotificacion->usuario=$_SESSION['la_logusr'];
		$objEvento->objNotificacion->operacion=$this->mensaje;
		$objEvento->objNotificacion->enviarNotificacion();
		unset($objEvento);
	}
}
?>