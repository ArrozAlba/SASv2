<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class intMetaDao extends ADOdb_Active_Record
{
	var $_table='spe_relacion_estvar';
	
	public function Eliminar()
	{
		global $db;
		$db->StartTrans();
		$this->delete();
		if($db->CompleteTrans())
		{
			return "1";		
		}
		else
		{
			return "0";
		}
	}
	
	public function Incluir()
	{
		try
		{
			global $db;
			$db->StartTrans();
			$this->save();
			$db->CompleteTrans();
			return "1";
		}
		catch (Exception $e) 
		{

    		return "0";
		}
	}
	
	public function BuscarMetas($integracion)
	{
		global $db;
		$sql = "select spe_relacion_estvars.cod_var,enero_masc,febrero_masc,marzo_masc,abril_masc,mayo_masc,junio_masc,julio_masc,agosto_masc,septiembre_masc,
octubre_masc,noviembre_masc,diciembre_masc,enero_fem,febrero_fem,marzo_fem,abril_fem,mayo_fem,junio_fem,julio_fem,agosto_fem,septiembre_fem,
octubre_fem,noviembre_fem,diciembre_fem,  
sig_variables.denominacion,sig_variables.cod_uni,sig_unidademedidas.denominacion as unidad 
from spe_relacion_estvars
inner join sig_variables on spe_relacion_estvars.cod_var=sig_variables.cod_var
inner join sig_unidademedidas on sig_variables.cod_uni=sig_unidademedidas.cod_uni
where {$this->_table}.codinte='{$integracion}' and {$this->_table}.codemp='{$this->codemp}'";
		$Rs = $db->Execute($sql); 
		return $Rs;
	}

}
?>