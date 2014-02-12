<?php
require_once("../class_folder/sigesp_conexiona_dao.php");
class registroHoras extends ADOdb_Active_Record
{
	var $_table='sch_registro';
	public function Modificar()
	{
		global $db;
	//	$db->debug=1;
		$db->StartTrans();
		$this->Replace();
		$db->CompleteTrans();
		return "1";	
	}
	
	public function Incluir()
	{
		global $db;
	//	$db->debug=1;
		$db->StartTrans();
		$this->save();
		if($db->CompleteTrans())
		{
			return "1";
		}	
		else
		{
			return $db->ErrorMsg();
		}
	}
	
	public function Eliminar()
	{
		global $db;
		$db->StartTrans();
		$this->delete();
		$db->CompleteTrans();
		return "1";
	}
	
	
	public function LeerTodos()
	{
		global $db;
		$Rs = $this->Find("codcli<>''");
		return $Rs;
		
	}
	
	public function LeerRegistros()
	{
	global $db;
	
	$sql="select sch_registro.cedcon,sch_registro.fecreg,sch_registro.hf,sch_registro.hi,cantidad,sch_registro.codcli,codproy,sch_registro.codser,sch_registro.codmod,nota,solicitante,problema,solucion,tp_usu,tp_sis,tp_nrq,tp_imp,aprobado,sch_consultor.nomcon,sif_clientes.nomcli,sch_servicios.denser,sch_modulos.denmod from sch_registro inner join sch_consultor on sch_registro.cedcon=sch_consultor.cedcon inner join sif_clientes on sif_clientes.codcli=sch_registro.codcli inner join sch_servicios on sch_servicios.codser=sch_registro.codser inner join sch_modulos on sch_modulos.codmod=sch_registro.codmod where sch_registro.cedcon='{$this->cedcon}' and sch_registro.fecreg='{$this->fecreg}'";
	//echo $sql;
	//die();
	$Rs = $db->Execute($sql);
	return $Rs;
	}
	
	public function LeerNumHorasDia()
	{
		global $db;
		//$db->debug=1;
		$sql="select sum(cantidad) as suma from sch_registro where sch_registro.cedcon='{$this->cedcon}' and sch_registro.fecreg='{$this->fecreg}'";
		$Rs = $db->Execute($sql);
		$Cantidad = $Rs->fields[0];
		return $Cantidad;
	}

	
	public function LeerRegistrosClientes()
	{
	global $db;
	$sql="select sch_registro.cedcon,sch_registro.fecreg,sch_registro.hf,sch_registro.hi,cantidad,sch_registro.codcli,codproy,sch_registro.codser,sch_registro.codmod,nota,solicitante,problema,solucion,tp_usu,tp_sis,tp_nrq,tp_imp,aprobado,sch_consultor.nomcon,sif_clientes.nomcli,sch_servicios.denser,sch_modulos.denmod from sch_registro inner join sch_consultor on sch_registro.cedcon=sch_consultor.cedcon inner join sif_clientes on sif_clientes.codcli=sch_registro.codcli inner join sch_servicios on sch_servicios.codser=sch_registro.codser inner join sch_modulos on sch_modulos.codmod=sch_registro.codmod where sch_registro.cedcon='{$this->cedcon}' and sch_registro.fecreg='{$this->fecreg}' and sch_registro.codcli='{$this->codcli}'";
	$Rs = $db->Execute($sql);
	return $Rs;
	}
	


public function LeerServicios($consultor)
{
	global $db;
	$Rs = $db->Execute("SELECT codser,s.denser+' CLASIFICACIÓN-> '+ts.dentipser  as tipom 
 FROM sch_categoriaconsultor cc,sch_categoria ct,sch_cs cs,sch_tiposervicio ts,sch_servicios s
 WHERE cc.codcatcon=ct.codcatcon AND ct.visible=1 AND cs.codcatcon=ct.codcatcon AND cc.seleccion=1 AND 
   cs.codtipser=ts.codtipser AND ts.visible=1 AND ts.codtipser=s.codtipser AND s.visible=1 AND cc.cedcon='{$consultor}'
 GROUP BY s.codser,s.denser,ts.dentipser
 ORDER BY s.codser");
	return $Rs;
}



public function LeerPorCadenaSer($cr,$cad)
{
	global $db;
	$Rs = $db->Execute("SELECT codser,s.denser+' CLASIFICACIÓN-> '+ts.dentipser  as tipom 
 FROM sch_categoriaconsultor cc,sch_categoria ct,sch_cs cs,sch_tiposervicio ts,sch_servicios s
 WHERE cc.codcatcon=ct.codcatcon AND ct.visible=1 AND cs.codcatcon=ct.codcatcon AND cc.seleccion=1 AND 
   cs.codtipser=ts.codtipser AND ts.visible=1 AND ts.codtipser=s.codtipser AND s.visible=1 AND cc.cedcon='0000000029' and {$cr} like  '%{$cad}%' GROUP BY s.codser,s.denser,ts.dentipser ORDER BY s.codser");
	return $Rs;
}


public function LeerModulos()
{
	global $db;
	$Rs = $db->Execute("SELECT codmod,denmod FROM sch_modulos");
	return $Rs;
}

public function LeerPorCadenaMod($cr,$cad)
{
	global $db;
	$Rs = $db->Execute("SELECT codmod,denmod FROM sch_modulos where {$cr} like  '%{$cad}%'");
	return $Rs;
}


}

?>