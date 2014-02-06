<?php
class spgestprogDao extends ADODB_Active_Record
{		
	public function IniciarTran($db)
	{
		$db->debug=true;
		$db->StartTrans();
	}
	
	public function CompletarTran($db)
	{
		if($db->CompleteTrans())
		{
			return "1";
		}	
		else
		{
			return "0";
		}
	}
	
	public function Incluir($db)
	{
		try
		{
			//$db->debug=true;
			if($this->save())
			{
				return "1";	
			}
			else
			{
				return "0";
			}
		}
		catch (Exception $e) 
		{
			//mandar a un archivo de logs con los eventos fallidos fallidos	
    		return "0";
		}
	}	
}
?>