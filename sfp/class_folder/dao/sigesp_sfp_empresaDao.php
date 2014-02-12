<?php
require_once("../class_folder/sigesp_conexion_dao.php");
class Empresa extends ADOdb_Active_Record
{
	var $_table='sigesp_empresa';
	public function Modificar()
	{
		global $db;
		$db->StartTrans();
		$this->Replace();
		$db->CompleteTrans();
		return "1";	
	}
	public function Incluir()
	{
		global $db;
		$db->StartTrans();
		$this->save();
		$db->CompleteTrans();
		return "1";

	}
	public function Eliminar()
	{
		global $db;
		$db->StartTrans();
		$this->delete();
		$db->CompleteTrans();
		return "1";

	}
	
	public function BuscarCodigo()
	{
		global $db;
		$Rs = $db->Execute("select max(cod_fuenfin)  as cod from {$this->_table}"); 
		var_dump($Rs->fields['cod']); 
		if($Rs->fields['cod']=='')
		{
			return "0001"; 
		}
		else
		{	
			$dato = $Rs->fields['cod'];
			return $dato;
		}
	}
	
	public function LeerDatos()
	{
		global $db;
		$Rs = $db->Execute("select * from sigesp_empresa");
		return $Rs;
	}
	
	public function LeerDatosCabRep()
	{
		global $db;
		$anopre=date("Y")+1;
		$Rs = $db->Execute("select codemp,nombre,nomorgads,{$anopre} as periodo from sigesp_empresa");
		return $Rs;
	}
	
	public function LeerDatosGenerales()
	{
		global $db;
		$sql="select sector,baselegal,forma_juri,ano_inicio,
			  act_principal,mision,vision,nom_presi,tel_presi,nom_dirplan,
			  tel_dirplan,email_dirplan,nom_diradmin,tel_diradmin,email_diradmin,nom_dirrh,tel_dirrh,email_dirrh,
			  nom_respre,tel_respre,email_respre,comp_patrimonio,nombre,direccion,telemp,faxemp,rifemp,estemp,ciuemp,zonpos,email,website 
			  from sigesp_empresa";
		//$sql="select mision,vision from sigesp_empresa";
		$Rs = $db->Execute($sql);
		return $Rs;
	}

	public function LeerFormatoCuentas()
	{
		global $db;
		$sql="select formspi, formplan, formpre from sigesp_empresa";
		$Rs = $db->Execute($sql);
		return $Rs;
	}
}
?>