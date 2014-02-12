<?php
/***********************************************************************************
* @Clase para Manejar el menu del sistema
* @fecha de creación: 07/08/2008
* @autor: Ing. Gusmary Balza
* **************************
* @fecha modificacion 
* @autor   
* @descripcion 
***********************************************************************************/

require_once($_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['sigesp_sitioweb'].'/base/librerias/php/general/sigesp_lib_conexion.php');

class Menu extends ADOdb_Active_Record
{
	var $_table='msgmenum';
	public $codusuario;
	public $campo;
	
/***********************************************************************************
* @Función que incluye las opciones de menu 
* @parametros: 
* @retorno: 
* @fecha de creación: 28/08/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function incluir()
	{
		global $conexionbd;
		$conexionbd->StartTrans();
		$this->save();
		if ($conexionbd->CompleteTrans())
		{
			$this->valido = true;
		}
		else
		{
			$this->valido = false;
		}
	}	

/***********************************************************************************
* @Función que busca las opciones de menu
* @parametros: 
* @retorno: 
* @fecha de creación: 28/08/2008
* @autor: Ing. Gusmary Balza
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function obtenerMenu()
	{
		global $conexionbd;
		$consulta = "SELECT codmenu, codsistema, nomlogico, nomfisico, codpadre, nivel, hijo, marco ".
					"  FROM $this->_table ".
					" WHERE $this->_table.codempresa = '$this->codempresa' ". 
					"   AND $this->_table.codsistema = '$this->codsistema' ".
					" ORDER BY nivel, orden";
		$result = $conexionbd->Execute($consulta); 
		return $result;
	
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
		$consulta = "SELECT $this->_table.codmenu, $this->_table.codsistema, nomlogico, nomfisico, codpadre, nivel, hijo, marco, orden ".
					"  FROM $this->_table ".
					" WHERE $this->_table.hijo = 1 ".
					"	AND $this->_table.codmenu IN (SELECT $this->_table.codpadre ".
					"  									FROM $this->_table ".
					" 								   INNER JOIN msgperfild ".
					"    								  ON msgperfild.codusuario = '$this->codusuario' ".
					"  									 AND msgperfild.visible = '1' ". 
					"  									 AND $this->_table.codempresa = msgperfild.codempresa ".
					"   								 AND $this->_table.codsistema = msgperfild.codsistema ".
					"   								 AND $this->_table.codmenu = msgperfild.codmenu ".
					" 								   WHERE $this->_table.hijo = 0 ".
					"   								 AND $this->_table.codempresa = '$this->codempresa' ". 
					"   								 AND $this->_table.codsistema = '$this->codsistema') ".
					"   AND $this->_table.codempresa = '$this->codempresa' ". 
					"   AND $this->_table.codsistema = '$this->codsistema' ".
					" UNION ".
					"SELECT $this->_table.codmenu, $this->_table.codsistema, nomlogico, nomfisico, codpadre, nivel, hijo, marco, orden ".
					"  FROM $this->_table ".
					" INNER JOIN msgperfild ".
					"    ON msgperfild.codusuario = '$this->codusuario' ".
					"   AND msgperfild.visible = '1' ". 
					"   AND $this->_table.codempresa = msgperfild.codempresa ".
					"   AND $this->_table.codsistema = msgperfild.codsistema ".
					"   AND $this->_table.codmenu = msgperfild.codmenu ".
					" WHERE $this->_table.hijo = 0 ".
					"   AND $this->_table.codempresa = '$this->codempresa' ". 
					"   AND $this->_table.codsistema = '$this->codsistema' ".
					" ORDER BY nivel, orden";
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
		$consulta = "SELECT msgperfild.visible, msgperfild.leer, msgperfild.incluir, msgperfild.actualizar, msgperfild.eliminar, msgperfild.imprimir, ".
					"		msgperfild.anular, msgperfild.ejecutar, msgperfild.administrativo, msgperfild.ayuda, msgperfild.cancelar ".
					"  FROM $this->_table ".
					" INNER JOIN msgperfild ".
					"    ON msgperfild.codusuario = '$this->codusuario' ".
					"   AND msgperfild.visible = '1' ". 
					"   AND $this->_table.codempresa = msgperfild.codempresa ".
					"   AND $this->_table.codsistema = msgperfild.codsistema ".
					"   AND $this->_table.codmenu = msgperfild.codmenu ".
					" WHERE $this->_table.hijo = 0 ".
					"   AND $this->_table.codempresa = '$this->codempresa' ". 
					"   AND $this->_table.codsistema = '$this->codsistema' ".
					"   AND $this->_table.nomfisico = '$this->nomfisico' ".
					" ORDER BY nivel, orden";
		$result = $conexionbd->Execute($consulta); 
		return $result;
	}


/***********************************************************************************
* @Función que Verifica que el usuario tenga acceso a la funcionalidad y a la acción que proceso
* @parametros: 
* @retorno: Verdadero ó false según la permisología
* @fecha de creación: 03/09/2008
* @autor: Ing. Yesenia Moreno de Lang
************************************************************************************
* @fecha modificación:
* @descripción:
* @autor:
***********************************************************************************/		
	public function verificarUsuario()
	{
		global $conexionbd;
		$usuariovalido=false;
		$consulta = "SELECT $this->_table.codmenu ".
					"  FROM $this->_table ".
					" INNER JOIN msgperfild ".
					"    ON msgperfild.codusuario = '$this->codusuario' ".
					"   AND msgperfild.visible = '1' ". 
					"   AND msgperfild.$this->campo = '1' ". 
					"   AND $this->_table.codempresa = msgperfild.codempresa ".
					"   AND $this->_table.codsistema = msgperfild.codsistema ".
					"   AND $this->_table.codmenu = msgperfild.codmenu ".
					" WHERE $this->_table.hijo = 0 ".
					"   AND $this->_table.codempresa = '$this->codempresa' ". 
					"   AND $this->_table.codsistema = '$this->codsistema' ".
					"   AND $this->_table.nomfisico = '$this->nomfisico' ";
		$result = $conexionbd->Execute($consulta); 
		if(!$result->EOF)
		{   
			$usuariovalido=true;
		}
		$result->Close();
		return $usuariovalido;
	}
	
}

?>