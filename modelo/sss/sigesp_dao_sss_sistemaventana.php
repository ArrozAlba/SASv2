<?php
/***********************************************************************************
* @Modelo para la definición de Sistema Ventana. 
* de datos.
* @fecha de creación: 30/09/2008.
* @autor: Ing.Gusmary Balza
* **************************
* @fecha modificacion  10/10/2008
* @autor  Ing. Yesenia Moreno de Lang
* @descripcion  Se agrego la seguridad y manejo de errores
***********************************************************************************/

require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION["sigesp_sitioweb"].'/base/librerias/php/general/sigesp_lib_conexion.php');

class SistemaVentana extends ADOdb_Active_Record
{
	var $_table = 'sss_sistemas_ventanas';
	public $valido = true;
	public $mensaje;
	//public $codusuario;
	public $campo;
	
/***********************************************************************************
* @Función para insertar las ventanas por sistema
* @parametros: 
* @retorno:
* @fecha de creación: 02/10/2008.
* @autor: Ing.Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/			
	function incluir()
	{
		global $conexionbd;
		
		$this->save();	
		if($conexionbd->HasFailedTrans())
		{
			$this->valido  = false;	
			$this->mensaje=$conexionbd->ErrorMsg();
		}
	}
	
	
/***********************************************************************************
* @Función que busca el código del sistema ventana
* @parametros: 
* @retorno: 
* @fecha de creación: 09/10/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function obtenerCodigoMenu()
	{
		global $conexionbd;
		$consulta = "SELECT codmenu ".
					"  FROM $this->_table ".
					" WHERE $this->_table.codsis = '$this->codsis' ".
					"	AND $this->_table.nomfisico ='$this->nomfisico' ";
		$result = $conexionbd->Execute($consulta); 
		if($result === false)
		{
			$this->valido  = false;
		}
		else
		{
			if(!$result->EOF)
			{   
				$codmenu=$result->fields["codmenu"];
			}
			$result->Close();
		}
		return $codmenu;
	}
	
	
/***********************************************************************************
* @Función que busca especificacmente si una opción de menu es válida ó no 
* @parametros: 
* @retorno: 
* @fecha de creación: 12/11/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function verificarCampoMenu()
	{
		global $conexionbd;
		
		$campo=0;
		$consulta = "SELECT codmenu ".
					"  FROM $this->_table ".
					" WHERE $this->_table.codsis = '$this->codsis' ".
					"	AND $this->_table.codmenu = '$this->codmenu' ".
					"   AND $this->_table.$this->campo = '1'";
		$result = $conexionbd->Execute($consulta); 
		if($result === false)
		{
			$this->valido  = false;
		}
		else
		{
			if(!$result->EOF)
			{   
				$campo=1;
			}
			$result->Close();
		}
		return $campo;
	}
	
	
	
/***********************************************************************************
* @Función que busca las opciones de menu según el usuario
* @parametros: 
* @retorno: 
* @fecha de creación: 28/08/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function obtenerMenuUsuario()
	{
		global $conexionbd;
		$consulta = "SELECT $this->_table.codsis, titven as nomlogico, nomven as nomfisico, codpadre, nivel, hijo, marco, orden, 1 as valido ".
					"  FROM $this->_table ".
					" WHERE $this->_table.hijo = 1 ".
					"	AND $this->_table.codmenu IN (SELECT $this->_table.codpadre 
					  									FROM $this->_table 
					 								   INNER JOIN sss_derechos_usuarios 
					    								  ON sss_derechos_usuarios.codusu = '$this->codusu' 
					  									 AND sss_derechos_usuarios.enabled = '1'  									 
					   								 AND $this->_table.codsis = sss_derechos_usuarios.codsis 
					   								 AND $this->_table.nomven = sss_derechos_usuarios.nomven 
					 								   WHERE $this->_table.hijo = 0	   								 
					   								 AND $this->_table.codsis = '$this->codsis') ".
					"   AND $this->_table.codsis = '$this->codsis' ".
					" UNION ".
					" SELECT $this->_table.codsis,titven as nomlogico, nomven as nomfisico,  codpadre, nivel, hijo, marco, orden, 1 as valido ".
					"  FROM $this->_table ".
					" INNER JOIN sss_derechos_usuarios ".
					"    ON sss_derechos_usuarios.codusu = '$this->codusu' ".
					"   AND sss_derechos_usuarios.enabled = '1' ". 
					"   AND $this->_table.codsis = sss_derechos_usuarios.codsis ".
					"   AND $this->_table.nomven = sss_derechos_usuarios.nomven ".
					" WHERE $this->_table.hijo = 0 ".
					"   AND $this->_table.codsis = '$this->codsis' ".
					" ORDER BY nivel, orden";
					
		$result = $conexionbd->Execute($consulta); 
		return $result;
	}
	
	
/***********************************************************************************
* @Función que busca las funcionalidades del menu
* @parametros: 
* @retorno: 
* @fecha de creación: 07/11/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function obtenerOpcionesMenu()
	{
		global $conexionbd;
		$consulta = "SELECT $this->_table.codmenu, $this->_table.codsis, nomlogico, nomfisico, codpadre, nivel, hijo, marco, orden, ".
					" 		$this->_table.visible,$this->_table.enabled,$this->_table.leer,$this->_table.incluir,$this->_table.cambiar,$this->_table.eliminar,$this->_table.imprimir,$this->_table.administrativo,".
					" 		$this->_table.anular,$this->_table.ejecutar,$this->_table.ayuda,$this->_table.cancelar,$this->_table.enviarcorreo, $this->_table.descargar, 1 as valido ".
					"  FROM $this->_table ".				
					" WHERE $this->_table.codsis='$this->codsis'"; 								   
		$cadena=" ";
        $total = count($this->criterio);
        for ($contador = 0; $contador < $total; $contador++)
		{
          	$cadena.= $this->criterio[$contador]['operador']." ".$this->criterio[$contador]['criterio']." ".
 		               $this->criterio[$contador]['condicion']." ".$this->criterio[$contador]['valor']." ";
        }
        $consulta.= $cadena;
        $consulta.= "ORDER BY nivel, orden";
		$result = $conexionbd->Execute($consulta); 
		return $result;
	}
		
	
/***********************************************************************************
* @Función que busca las opciones de la Barra de Herramientas según el usuario
* @parametros: 
* @retorno: 
* @fecha de creación: 29/08/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function obtenerBarraHerramientaUsuario()
	{
		global $conexionbd;
		$consulta = "SELECT sss_derechos_usuarios.visible, sss_derechos_usuarios.leer, sss_derechos_usuarios.incluir, sss_derechos_usuarios.cambiar, sss_derechos_usuarios.eliminar, sss_derechos_usuarios.imprimir, ".
					"		sss_derechos_usuarios.anular, sss_derechos_usuarios.ejecutar, sss_derechos_usuarios.administrativo, sss_derechos_usuarios.ayuda, sss_derechos_usuarios.cancelar, sss_derechos_usuarios.descargar ".
					"  FROM $this->_table ".
					" INNER JOIN sss_derechos_usuarios ".
					"    ON sss_derechos_usuarios.codusu = '$this->codusu' ".
					"   AND sss_derechos_usuarios.visible = '1' ". 
					"   AND $this->_table.codsis = sss_derechos_usuarios.codsis ".
					"   AND $this->_table.codmenu = sss_derechos_usuarios.codmenu ".
					" WHERE $this->_table.hijo = 0 ".
					"   AND $this->_table.codsis = '$this->codsis' ".
					"   AND $this->_table.nomfisico = '$this->nomfisico' ".
					" ORDER BY nivel, orden";
		$result = $conexionbd->Execute($consulta); 
		return $result;
	}
	

/****************************************************************************
* @Función que Verifica que el usuario tenga acceso a la funcionalidad y 
* a la acción que proceso
* @parametros: 
* @retorno: Verdadero ó false según la permisología
* @fecha de creación: 03/09/2008
* @autor: Ing. Yesenia Moreno de Lang
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*******************************************************************************/		
	public function verificarUsuario()
	{
		global $conexionbd;
		$usuariovalido = false;
		$consulta = "SELECT $this->_table.codmenu ".
					"  FROM $this->_table ".
					" INNER JOIN sss_derechos_usuarios ".
					"    ON sss_derechos_usuarios.codusu = '$this->codusu' ".
					"   AND sss_derechos_usuarios.visible = '1' ". 
					"   AND sss_derechos_usuarios.$this->campo = '1' ". 
					"   AND $this->_table.codsis = sss_derechos_usuarios.codsis ".
					"   AND $this->_table.codmenu = sss_derechos_usuarios.codmenu ".
					" WHERE $this->_table.hijo = 0 ".
					"   AND $this->_table.codsis = '$this->codsis' ".
					"   AND $this->_table.nomfisico = '$this->nomfisico' ";
		$result = $conexionbd->Execute($consulta); 
		if(!$result->EOF)
		{   
			$usuariovalido=true;
		}
		$result->Close();
		return $usuariovalido;
	}
	
	
/****************************************************************************
* @Función que obtiene las opciones de menu de un usuario y funcionalidad
* @parametros: 
* @retorno: Verdadero ó false según la permisología
* @fecha de creación: 03/09/2008
* @autor: Ing. Gusmary Balza
*****************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
*******************************************************************************/		
	function obtenerMenu()
	{
		global $conexionbd;
		try
		{
			$consulta = " SELECT $this->_table.codmenu, $this->_table.codsis, nomlogico, $this->_table.visible,$this->_table.enabled,".
						" 		 $this->_table.leer,$this->_table.incluir,$this->_table.cambiar,$this->_table.eliminar,".
						" 		 $this->_table.imprimir,$this->_table.administrativo,$this->_table.anular, ".
						" 		 $this->_table.ejecutar,$this->_table.ayuda,$this->_table.cancelar,$this->_table.enviarcorreo, ".
						"        $this->_table.descargar, 1 as valido".
						"   FROM $this->_table ".
						"  WHERE $this->_table.hijo = 0 ".
					/*	"    AND $this->_table.codmenu IN (SELECT $this->_table.codmenu ".
						"  								  	 FROM $this->_table ".
						" 								    INNER JOIN sss_derechos_usuarios ".
						"    							   	   ON sss_derechos_usuarios.codusu = '$this->codusu' ".
						"  									  AND sss_derechos_usuarios.enabled = 1 ".  									 
						"  								 	  AND $this->_table.codsis = sss_derechos_usuarios.codsis ". 
						"  								 	  AND $this->_table.codmenu = sss_derechos_usuarios.codmenu ". 
						" 								    WHERE $this->_table.hijo = 0 ".	   								 
						"  								      AND $this->_table.codsis = '$this->codsis') ".	*/			
						"    AND $this->_table.codsis = '$this->codsis' ".
						"    AND $this->_table.codmenu = $this->codmenu ";
			 
			$result = $conexionbd->Execute($consulta);
			return $result;
		}
		catch (exception $e) 
		{ 
			$this->valido  = false;	
			$this->mensaje='Error al consultar el menú de la funcionalidad '.$consulta.' '.$conexionbd->ErrorMsg();
			//$this->incluirSeguridad('CONSULTAR',$this->valido);
	   	} 	
	}
}	
?>