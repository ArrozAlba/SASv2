<?php
require_once ("../class_folder/sigesp_conexion_dao.php");
class problemaDao extends ADOdb_Active_Record
{
    var $_table = 'spe_problemas';

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
        $Rs = $db->Execute("select max(codprob)  as cod from {$this->_table}");
        //var_dump($Rs->fields['cod']);
        if ($Rs->fields['cod'] == '')
        {
            return "0001";
        }
        else
        {
            $dato = $Rs->fields['cod'];
            return $dato;
        }
    }

    public function LeerTodos()
    {
        global $db;
      //  $Rs = $this->Find("codprob<>''");
      	$Rs=$db->Execute("select * from spe_problemas");
        return $Rs;

    }

    public function LeerPorCadena($cr, $cad)
    {
        global $db;
       // $Rs = $this->Find("{$cr} like  '%{$cad}%' ");
       $Rs = $db->Execute("select * from spe_problemas where {$cr} like '%{$cad}%'");
        return $Rs;

    }


}

?>