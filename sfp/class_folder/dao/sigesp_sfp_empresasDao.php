<?php
require_once ("../class_folder/sigesp_conexion_dao.php");
class empresas extends ADOdb_Active_Record
{
    var $_table = 'sfp_empresa';
    public function Modificar()
    {
        global $db;
        $db->StartTrans();
        $this->Replace();
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
        global $db;
        $db->StartTrans();
        $this->save();
        if($db->CompleteTrans())
        {
        	return "1";
        }
        else
        {
        	return "0";
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

    public function BuscarCodigo()
    {
        global $db;
        $Rs = $db->Execute("select max(codemp)  as cod from {$this->_table}");
        //var_dump($Rs->fields['cod']);
        if ($Rs->fields['cod'] == '')
        {
            return "0000";
        }
        else
        {
            $dato = $Rs->fields['cod'];
            return $dato;
        }
    }

    public function obtenerEncReporte()
    {
    	global $db;
    	$sql="select sigesp_empresa.nomorgads, sfp_empresa.nombre  
    		  from sfp_empresa left outer
    		  join sigesp_empresa on 
    		  sfp_empresa.emprin=sigesp_empresa.codemp where sfp_empresa.codemp='{$this->codemp}'";
    	$rs = $db->Execute($sql);
    	return $rs;
    	
    }
    
    
    
    public function LeerTodos()
    {
        global $db;
       // $db->debug=true;
      //  $Rs = $this->Find("codprob<>''");
      	$Rs=$db->Execute("select * from sfp_empresa");
        return $Rs;
    }
    
    public function LeerUno()
    {
        global $db;
       // $db->debug=true;
      //  $Rs = $this->Find("codprob<>''");
      	$Rs=$db->Execute("select * from sfp_empresa where codemp='{$this->codemp}'");
        return $Rs;
    }
    

    public function LeerPorCadena($cr, $cad)
    {
        global $db;
       // $Rs = $this->Find("{$cr} like  '%{$cad}%' ");
       $Rs = $db->Execute("select * from sfp_empresa where {$cr} like '%{$cad}%'");
        return $Rs;

    }


}

?>