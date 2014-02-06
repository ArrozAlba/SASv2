<?php
/****************************************************************************
* @Modelo para las funciones de estructura presupuestaria de nivel 5.
* @fecha de creación: 03/10/2008.
* @autor: Ing.Gusmary Balza
****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
****************************************************************************/
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registroeventos.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/modelo/sss/sigesp_dao_sss_registrofallas.php');

class EstPro5 extends ADOdb_Active_Record
{
	var $_table = 'spg_ep5';
	public $valido = true;
	public $mensaje;
	public $cadena;
	public $criterio;
	public $seguridad = true;
	public $codsis;
	public $nomfisico;
	public $estatus;
	
/***********************************************************************************
* @Función para insertar una estructura presupuestaria.
* @parametros: 
* @retorno:
* @fecha de creación: 03/10/2008.
* @autor: Ing.Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
************************************************************************************/			
	function incluir()
	{
		global $conexionbd;
		$this->mensaje = 'Incluyo la Estructura Presupuestaria '.$this->codestpro1.$this->codestpro2.$this->codestpro3.$this->codestpro4.$this->codestpro5.$this->estcla;
		$conexionbd->StartTrans();
		try 
		{ 
			$this->save();	
		}	
		catch (exception $e) 
	   	{
			$this->valido  = false;				
			$this->mensaje = 'Error al Incluir la Estructura Presupuestaria '.$this->codestpro1.$this->codestpro2.$this->codestpro3.$this->codestpro4.$this->codestpro5.$this->estcla.' '.$conexionbd->ErrorMsg();
		} 
		$conexionbd->CompleteTrans();
		$this->incluirSeguridad('INSERTAR',$this->valido);
	}
	
	
	function leerGeneral()
	{
		global $conexionbd;
		$conexionbdorigen = conectarBD($_SESSION['sigesp_servidor'], $_SESSION['sigesp_usuario'], $_SESSION['sigesp_clave'],
												 $_SESSION['sigesp_basedatos'], $_SESSION['sigesp_gestor']);
		try
		{	
			$consulta = " SELECT DISTINCT codestpro1,codestpro2,codestpro3,codestpro4,codestpro5, ".
						"	denestpro5,1 as valido ".
						" FROM {$this->_table} ".
						" INNER JOIN sss_permisos_internos ".
						"	ON {$this->_table}.codestpro1=substr(sss_permisos_internos.codintper,1,25) ".
						" 	AND {$this->_table}.codestpro2=substr(sss_permisos_internos.codintper,26,25) ".
						"	AND {$this->_table}.codestpro3=substr(sss_permisos_internos.codintper,51,25)".
						"	AND {$this->_table}.codestpro4=substr(sss_permisos_internos.codintper,76,25)".
						"	AND {$this->_table}.codestpro5=substr(sss_permisos_internos.codintper,101,25)".
						"	AND {$this->_table}.estcla=substr(sss_permisos_internos.codintper,126,1) ".
						" WHERE {$this->_table}.codemp='$this->codemp' AND codusu='$this->codusu' ";
			$cadena=" ";
            $total = count($this->criterio);
            for ($contador = 0; $contador < $total; $contador++)
			{
            	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 			               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
            }
            $consulta.= $cadena;
            $consulta.= " ORDER BY codestpro1,codestpro2,codestpro3,codestpro4,codestpro5";
		 	$result = $conexionbdorigen->Execute($consulta);
		 	return $result;
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar la Estructura Presupuestaria '.$consulta.' '.$conexionbd->ErrorMsg();
			$this->incluirSeguridad('CONSULTAR',$this->valido);
	   	} 
	}	
		
	
	
/***********************************************************************************
* @Función que Busca uno o todas las estructuras presupuestarias de nivel 5
* @parametros: 
* @retorno:
* @fecha de creación: 10/10/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
************************************************************************************/		
	public function leer() 
 	{		
		global $conexionbd;
		//$conexionbd->debug =1;
		$conexionbdorigen = conectarBD($_SESSION['sigesp_servidor'], $_SESSION['sigesp_usuario'], $_SESSION['sigesp_clave'],
												 $_SESSION['sigesp_basedatos'], $_SESSION['sigesp_gestor']);
		try
		{
			$codcompleto = $conexionbd->Concat("{$this->_table}.codestpro1","{$this->_table}.codestpro2","{$this->_table}.codestpro3",
												"{$this->_table}.codestpro4","{$this->_table}.codestpro5","{$this->_table}.estcla");
			
			$modalidad = $_SESSION["la_empresa"]["estmodest"];			
			switch ($modalidad)
			{
				case 1:
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
										
					$codest = $conexionbd->Concat("substr({$this->_table}.codestpro1,$longest1,$longaux1)",
								"substr({$this->_table}.codestpro2,$longest2,$longaux2)","substr({$this->_table}.codestpro3,$longest3,$longaux3)",
								"substr({$this->_table}.codestpro4,$longest4,$longaux4)","substr({$this->_table}.codestpro5,$longest5,$longaux5)");
					
					$nombre = $conexionbd->Concat("spg_ep1.denestpro1","'-'","spg_ep2.denestpro2","'-'","spg_ep3.denestpro3");
					
					$consulta = " SELECT {$this->_table}.codemp,substr({$this->_table}.codestpro1,$longest1,$longaux1) as codestpro1, 	".
								" 	substr({$this->_table}.codestpro2,$longest2,$longaux2) as codestpro2, 								".
								" 	substr({$this->_table}.codestpro3,$longest3,$longaux3) as codestpro3, 								".
								" 	{$this->_table}.estcla,denestpro5, {$codcompleto} as codcompleto,									".
								"	{$codest} as codest, {$nombre} as nombre,1 as valido 												".
								" FROM {$this->_table} 																					".
								" INNER JOIN spg_ep1 ON spg_ep1.codemp={$this->_table}.codemp 
									AND spg_ep1.codestpro1={$this->_table}.codestpro1 													".
								" INNER JOIN spg_ep2 ON spg_ep2.codemp={$this->_table}.codemp 
									AND spg_ep2.codestpro1={$this->_table}.codestpro1 													".
								"	AND spg_ep2.codestpro2={$this->_table}.codestpro2 													".
								" INNER JOIN spg_ep3 ON spg_ep3.codemp={$this->_table}.codemp 
									AND spg_ep3.codestpro1={$this->_table}.codestpro1 													".
								"	AND spg_ep3.codestpro2={$this->_table}.codestpro2 
									AND spg_ep3.codestpro3={$this->_table}.codestpro3 													".	
								" WHERE denestpro5<>'Ninguna'																			";	
					
					$agrupar = " GROUP BY spg_ep5.codemp,spg_ep5.codestpro1,spg_ep5.codestpro2,spg_ep5.codestpro3,spg_ep5.codestpro4,
								spg_ep5.codestpro5,spg_ep5.estcla,denestpro5,spg_ep1.denestpro1,spg_ep2.denestpro2,spg_ep3.denestpro3";
				break;
				
				case 2:	
					$longaux1   = $_SESSION["la_empresa"]["loncodestpro1"];
					$longaux2   = $_SESSION["la_empresa"]["loncodestpro2"];
					$longaux3   = $_SESSION["la_empresa"]["loncodestpro3"];
					$longaux4   = $_SESSION["la_empresa"]["loncodestpro4"];
					$longaux5   = $_SESSION["la_empresa"]["loncodestpro5"];
					
					$longest1 = 25-$longaux1;
					$longest2 = 25-$longaux2;
					$longest3 = 25-$longaux3;	
					$longest4 = 25-$longaux4;	
					$longest5 = 25-$longaux5;	
								
					/*$ls_codest1=substr($as_codpro,0,25);
					$ls_codest2=substr($as_codpro,25,25);
					$ls_codest3=substr($as_codpro,50,25);
					$ls_codest4=substr($as_codpro,75,25);
					$ls_codest5=substr($as_codpro,100,25);
					$ls_codest1=substr($ls_codest1,(25-$longaux1),$longaux1);
					$ls_codest2=substr($ls_codest2,(25-$longaux2),$longaux2);
					$ls_codest3=substr($ls_codest3,(25-$longaux3),$longaux3);
					$ls_codest4=substr($ls_codest4,(25-$longaux4),$longaux4);
					$ls_codest5=substr($ls_codest5,(25-$longaux5),$longaux5);	*/	
				
					$codest = $conexionbd->Concat("substr(codestpro1,$longest1,$longaux1)",
								"substr(codestpro2,$longest2,$longaux2)","substr(codestpro3,$longest3,$longaux3)",
								"substr(codestpro4,$longest4,$longaux4)","substr(codestpro5,$longest5,$longaux5)");
					
					$nombre = $conexionbd->Concat("spg_ep1.denestpro1","'-'","spg_ep2.denestpro2","'-'",
								"spg_ep3.denestpro3","'-'","spg_ep4.denestpro4","'-'","spg_ep5.denestpro5");
					
					$consulta = " SELECT {$this->_table}.codemp,substr({$this->_table}.codestpro1,$longest1,$longaux1) as codestpro1, 	".
								" 	substr({$this->_table}.codestpro2,$longest2,$longaux2) as codestpro2, 								".
								" 	substr({$this->_table}.codestpro3,$longest3,$longaux3) as codestpro3, 								".
								" 	substr({$this->_table}.codestpro4,$longest4,$longaux4) as codestpro4, 								".
								" 	substr({$this->_table}.codestpro5,$longest5,$longaux5) as codestpro5, 								".
								" 	{$this->_table}.estcla,denestpro5, {$codcompleto} as codcompleto,									".
								"	{$codest} as codest, {$nombre} as nombre,1 as valido 												".
								" FROM {$this->_table} ".
								" INNER JOIN spg_ep1 ON spg_ep1.codemp={$this->_table}.codemp 
									AND spg_ep1.codestpro1={$this->_table}.codestpro1 													".
								" INNER JOIN spg_ep2 ON spg_ep2.codemp={$this->_table}.codemp 
									AND spg_ep2.codestpro1={$this->_table}.codestpro1 													".
								"	AND spg_ep2.codestpro2={$this->_table}.codestpro2 													".
								" INNER JOIN spg_ep3 ON spg_ep3.codemp={$this->_table}.codemp 
									AND spg_ep3.codestpro1={$this->_table} .codestpro1 													".
								"	AND spg_ep3.codestpro2={$this->_table}.codestpro2 
									AND spg_ep3.codestpro3={$this->_table} .codestpro3 													".	
								" INNER JOIN spg_ep4 ON spg_ep4.codemp={$this->_table}.codemp 
									AND spg_ep4.codestpro1={$this->_table}.codestpro1 													".
								"	AND spg_ep4.codestpro2={$this->_table}.codestpro2 
									AND spg_ep4.codestpro3={$this->_table}.codestpro3 													".
								"	AND spg_ep4.codestpro4={$this->_table}.codestpro4 													".	
								" WHERE denestpro5<>'Ninguna'																			";		

					$agrupar = " GROUP BY spg_ep5.codemp,spg_ep5.codestpro1,spg_ep5.codestpro2,spg_ep5.codestpro3,spg_ep5.codestpro4,spg_ep5.codestpro5,spg_ep5.estcla,denestpro5,spg_ep1.denestpro1,spg_ep2.denestpro2,spg_ep3.denestpro3,spg_ep4.denestpro4";
				break;
			}	 
				
			if (($this->criterio=='')&&(($this->cadena!='')))
			{
				$cadena .= " AND codemp='{$this->codemp}' AND {$codest} ='{$this->cadena}'";
			}
			elseif ($this->criterio!='')
			{
				$cadena .= " AND {$this->criterio} like '%{$this->cadena}%'";
		  	}
			/*$cadena=" ";
            $total = count($this->criterio);
            for ($contador = 0; $contador < $total; $contador++)
			{
            	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 			               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
            }*/
            $consulta.= $cadena;
		  	$consulta.= $agrupar;
		  	$consulta.= " ORDER BY spg_ep5.codestpro1,spg_ep5.codestpro2,spg_ep5.codestpro3,spg_ep5.codestpro4,spg_ep5.codestpro5,spg_ep5.estcla ASC";
		  	//echo $consulta;
		  	//die();
		 	$result = $conexionbdorigen->Execute($consulta);
		 	return $result;
						
		}			
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar la Estructura Presupuestaria '.$consulta.' '.$conexionbd->ErrorMsg();
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
		$objEvento->codsis = $this->codsis;
		$objEvento->nomfisico = $this->nomfisico;
		$objEvento->evento = $evento;
		$objEvento->desevetra = $this->mensaje;
		$objEvento->incluir();
		unset($objEvento);
	}
}	
?>