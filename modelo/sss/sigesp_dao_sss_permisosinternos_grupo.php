<?php
/***********************************************************************************
* @Modelo para proceso de asignación de los permisos internos a los grupos
* @fecha de creación: 21/10/2008.
* @autor: Ing.Gusmary Balza
* ********************************************
* @fecha modificacion  
* @autor 
* @descripcion  
***********************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');

class PermisosInternosGrupo extends ADOdb_Active_Record
{
	var $_table = 'sss_permisos_internos_grupos';
	public $mensaje;
	public $evento;
	public $valido = true;
	public $existe = true;
	public $seguridad = true;
	public $codsis;
	public $nomfisico;
	public $conexionbd;
	public $codest;

/***********************************************************************************
* @Función que inserta los permisos de un grupo 
* @parametros: 
* @retorno:
* @fecha de creación: 21/10/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación: 
* @descripción: 
* @autor: Ing. 
***********************************************************************************/
	function incluirPermisosInternos()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->mensaje = 'Incluyo el permiso para el grupo '.$this->nomgru.'en el sistema'.$this->codsis;
		$this->verificarPermiso();
		if ($this->existe==false)
		{
			$consulta= " INSERT INTO {$this->_table} ".
					  "		(codemp,nomgru,codsis,codintper,enabled) ".
					  " VALUES ('{$this->codemp}','{$this->nomgru}', ".
					  " 		'{$this->codsis}','$this->codintper',1)	";
			$result = $conexionbd->Execute($consulta);
			if ($conexionbd->HasFailedTrans())
			{
				$this->valido  = false;	
				$this->mensaje='Error al incluir el permiso para el grupo '.$this->nomgru.'en el sistema'.$this->codsis.$conexionbd->ErrorMsg();
			}	
		}
		else
		{
			$consulta = " UPDATE {$this->_table} ".
						"    SET enabled = 1 ".
						"  WHERE codemp = '{$this->codemp}' ".
						"    AND nomgru = '{$this->nomgru}'".
						"    AND codsis = '{$this->codsis}' ". 
						"    AND codintper = '{$this->codintper}'";			
			$result = $conexionbd->Execute($consulta);
		}
		$this->incluirSeguridad('INSERTAR',$this->valido);	
	}
	
	
/***********************************************************************************
* @Función que busca si un grupo tiene permiso para un sistema
* @parametros: 
* @retorno:
* @fecha de creación: 21/10/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación: 
* @descripción: 
* @autor: Ing. 
***********************************************************************************/	
	function verificarPermiso()
	{
		global $conexionbd;
		try 
		{ 
			$consulta=" SELECT codemp,codsis,nomgru,codintper ".
					" FROM {$this->_table} ".
					" WHERE codemp= '{$this->codemp}' ".
					" AND nomgru= '{$this->nomgru}' ".
					" AND codsis='{$this->codsis}' ".
					" AND codintper ='{$this->codintper}'";
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
			$this->mensaje = 'Error al consultar el permiso para el Grupo '.$this->nomgru.' en el Sistema'.$this->codsis.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
		}
	}
	
	
/***********************************************************************************
* @Función que actualiza los permisos asignados a grupos  
* @parametros: 
* @retorno:
* @fecha de creación: 03/11/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación: 
* @descripción: 
* @autor: Ing. 
***********************************************************************************/		
	/*function actualizar()
	{
		global $conexionbd;
		try 
		{ 
			$total=	count($this->usuarioeliminar);
			for ($contador=0; $contador < $total; $contador++)
			{	
				$this->usuarioeliminar[$contador]->eliminar();
			}
			$total=	count($this->admin);
			for ($contador=0; $contador < $total; $contador++)
			{	
				$this->admin[$contador]->incluirPermisosInternos();
			}
		}
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al Modificar el permiso '.$this->codintper.' para el usuario '.$this->codusu.' en el sistema '.$this->codsis.' '.$conexionbd->ErrorMsg();
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('MODIFICAR',$this->valido);	
	}*/
	
	
/***********************************************************************************
* @Función que elimina los permisos asignados a grupos  
* @parametros: 
* @retorno:
* @fecha de creación: 03/11/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación: 
* @descripción: 
* @autor: Ing. 
***********************************************************************************/			
	public function eliminarGeneral()
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		$this->objDerechos = new DerechosGrupo();
		$this->objDerechos->codemp = $this->codemp;
		$this->objDerechos->nomfisico = $this->nomfisico;
			
		$this->objDerechos->criterio[0]['operador'] = /*"AND";*/$this->criterio[0]['operador'];
		$this->objDerechos->criterio[0]['criterio'] = /*"nomgru";*/$this->criterio[0]['criterio'];
		$this->objDerechos->criterio[0]['condicion'] = /*"=";*/$this->criterio[0]['condicion'];
		$this->objDerechos->criterio[0]['valor'] = /*"'".$this->nomgru."'";*/$this->criterio[0]['valor'];
										
		$this->objDerechos->criterio[1]['operador'] = /*"AND";*/$this->criterio[1]['operador'];
		$this->objDerechos->criterio[1]['criterio'] = /*"codsis";*/$this->criterio[1]['criterio'];
		$this->objDerechos->criterio[1]['condicion'] = /*"=";*/$this->criterio[1]['condicion'];
		$this->objDerechos->criterio[1]['valor'] = /*"'".$this->codsis."'";*/$this->criterio[1]['valor'];
		
		$this->objDerechos->criterio[2]['operador'] = /*"AND";*/$this->criterio[2]['operador'];
		$this->objDerechos->criterio[2]['criterio'] = /*"codintper";*/$this->criterio[2]['criterio'];
		$this->objDerechos->criterio[2]['condicion'] = /*"=";*/$this->criterio[2]['condicion'];
		$this->objDerechos->criterio[2]['valor'] = /*"'".$this->codintper."'";*/$this->criterio[2]['valor'];
			
		$this->mensaje='Elimino el permiso '.$this->codintper.' al Grupo '.$this->nomgru;
		$conexionbd->StartTrans();
		try
		{
			$consulta = " UPDATE {$this->_table} SET  enabled=0  ".
						" WHERE codemp='{$this->codemp}'  ";
			$cadena=" ";
            $total = count($this->criterio);
            for ($contador = 0; $contador < $total; $contador++)
			{
            	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 			               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
            }
            $consulta.= $cadena;
            $result = $conexionbd->Execute($consulta);
			$this->objDerechos->eliminarTodosPrueba();
		}
		catch (exception $e) 
		{
			$this->valido  = false;	
			$this->mensaje='Error al eliminar el permiso '.$this->codintper.' al Usuario '.$this->codusu.' '.$conexionbd->ErrorMsg();
		}
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('ELIMINAR',$this->valido);			
	}
		
	
/***********************************************************************************
* @Función que busca los permisos de un grupo
* @parametros: 
* @retorno: 
* @fecha de creación: 03/11/2008.
* @autor: Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/	
	function obtenerPermisos() 
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		if ($this->tabla=='sno_constante')
		{
			$codigo = $conexionbd->Concat(codnom,"'-'",codcons);
			
			$consulta = " SELECT substr(codintper,0,5) as codnom, 						".
						"	substr(codintper,6,10) as {$this->campo},					".
						"	{$this->_table}.codsis,										".
					    "	(SELECT {$this->campo2} FROM {$this->tabla} 				".
					    "		WHERE {$this->tabla}.codemp={$this->_table}.codemp 		".
					    "		AND $codigo={$this->_table}.codintper 					".
					    " 		GROUP BY {$this->campo2}) as {$this->campo2}			".
					    " FROM {$this->_table}											".
					    "  WHERE codemp= '{$this->codemp}'".
					    "    AND nomgru= '{$this->nomgru}'".
					    "    AND codsis= '{$this->sistema}'".
					    "    AND enabled= 1".
					    "    AND codintper IN (SELECT {$this->campo} ".
					    "						 FROM {$this->tabla}".
					    "						WHERE codemp='{$this->codemp}')";
		}
		else
		{
			$consulta = " SELECT codintper as {$this->campo},{$this->_table}.codsis,".
					    "        (SELECT {$this->campo2} FROM {$this->tabla} ".
					    "          WHERE {$this->tabla}.codemp={$this->_table}.codemp ".
					    "            AND {$this->tabla}.{$this->campo}={$this->_table}.codintper ) as {$this->campo2}".
					    "  FROM {$this->_table}".
					    "  WHERE codemp= '{$this->codemp}'".
					    "    AND nomgru= '{$this->nomgru}'".
					    "    AND codsis= '{$this->sistema}'".
					    "    AND enabled= 1".
					    "    AND codintper IN (SELECT {$this->campo} ".
					    "						 FROM {$this->tabla}".
					    "						WHERE codemp='{$this->codemp}')";
			
		}
		$result = $conexionbd->Execute($consulta);
		return $result;				
	}

	
	
/***********************************************************************************
* @Función que busca los permisos de un grupo para una estructura presupuestaria.
* @parametros: 
* @retorno: 
* @fecha de creación: 03/11/2008.
* @autor: Gusmary Balza.
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
************************************************************************************/		
	function obtenerEstPre() 
	{
		global $conexionbd;
		//$conexionbd->debug = 1;
		
		$codcompleto = $conexionbd->Concat(codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,estcla);
		
		$longaux1 = $_SESSION['la_empresa']['loncodestpro1'];
		$longest1 = (25-$longaux1)+1;
		$longaux2 = $_SESSION['la_empresa']['loncodestpro2'];
		$longest2 = (25-$longaux2)+1;
		$longaux3 = $_SESSION['la_empresa']['loncodestpro3'];
		$longest3 = (25-$longaux3)+1;
		$longaux4 = $_SESSION['la_empresa']['loncodestpro4'];
		$longest4 = (25-$longaux4)+1;
		$longaux5 = $_SESSION['la_empresa']['loncodestpro5'];
		$longest5 = (25-$longaux5)+1;		
		
		$codest = $conexionbd->Concat("substr(substr(codintper,1,25),$longest1,$longaux1)","substr(substr(codintper,26,25),$longest2,$longaux2)","substr(substr(codintper,51,25),$longest3,$longaux3)","substr(substr(codintper,76,25),$longest4,$longaux4)","substr(substr(codintper,101,25),$longest5,$longaux5)","substr(codintper,126,1)");
		
		$nombre = $conexionbd->Concat("spg_ep1.denestpro1","'-'","spg_ep2.denestpro2","'-'","spg_ep3.denestpro3","'-'","spg_ep4.denestpro4","'-'","spg_ep5.denestpro5");
		
		$consulta = " SELECT {$codest} as codest, {$nombre} as nombre".
					" FROM {$this->_table} ".
					" INNER JOIN spg_ep5 ".
					"	ON spg_ep5.codemp = {$this->_table}.codemp ".
					"	AND {$codcompleto} = {$this->_table}.codintper ".
					"	AND spg_ep5.codestpro1=substr(codintper,1,25)  ".
					"	AND spg_ep5.codestpro2=substr(codintper,26,25) AND spg_ep5.codestpro3=substr(codintper,51,25) ".
					"	AND spg_ep5.codestpro4=substr(codintper,76,25) AND spg_ep5.codestpro5=substr(codintper,101,25)".
					" INNER JOIN spg_ep1 ON spg_ep1.codemp={$this->_table}.codemp AND spg_ep1.codestpro1=substr(codintper,1,25)  ".
					" INNER JOIN spg_ep2 ON spg_ep2.codemp={$this->_table}.codemp AND spg_ep2.codestpro1=substr(codintper,1,25)  ".
					"	AND spg_ep2.codestpro2=substr(codintper,26,25) ".
					" INNER JOIN spg_ep3 ON spg_ep3.codemp={$this->_table}.codemp AND spg_ep3.codestpro1=substr(codintper,1,25)  ".
					"	AND spg_ep3.codestpro2=substr(codintper,26,25) AND spg_ep3.codestpro3=substr(codintper,51,25) ".
					" INNER JOIN spg_ep4 ON spg_ep4.codemp={$this->_table}.codemp AND spg_ep4.codestpro1=substr(codintper,1,25)  ".
					"	AND spg_ep4.codestpro2=substr(codintper,26,25) AND spg_ep4.codestpro3=substr(codintper,51,25) ".
					"	AND spg_ep4.codestpro4=substr(codintper,76,25)".
					" WHERE {$this->_table}.codemp ='{$this->codemp}' ".
					" AND nomgru='{$this->nomgru}' AND codsis='SPG' ".
					" AND enabled=1 ";	
		
		$result = $conexionbd->Execute($consulta);
		return $result;		
	
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
		$objEvento->codsis = 'SSS';
		$objEvento->nomfisico = $this->nomfisico;
		$objEvento->evento = $evento;
		$objEvento->desevetra = $this->mensaje;
		$objEvento->incluir();
		unset($objEvento);
	}
	
	
}
?>