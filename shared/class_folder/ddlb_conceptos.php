<?php
class ddlb_conceptos
{
	var $SQL;
	function ddlb_conceptos($con)
	{
		$this->SQL=new class_sql($con);
	}
	
	function uf_cargar_conceptos($as_CodOpe,$as_seleccionado)
	{
		$ls_sql="SELECT codconmov,denconmov,codope
			     FROM scb_concepto
				 WHERE codope = '".$as_CodOpe."' OR codope='--' ORDER BY codconmov";
	
		$rs_conceptos=$this->SQL->select($ls_sql);
		
		if($rs_conceptos==false)
		{
			print "Error".$this->SQL->message;
			print "<select name=ddlb_conceptos style=width:200px>";
		    print "<option value=--->Ninguno</option>";
			print "</select>";
		}
		else
		{
			print "<select name=ddlb_conceptos style=width:200px>";
			while($row=$this->SQL->fetch_row($rs_conceptos))
			{
				 $as_operacion=$row["codconmov"];

				 if($as_seleccionado==$as_operacion)
				 {
					 print "<option value=".$row["codconmov"]." selected>".$row["denconmov"]."</option>";
				 }
				 else
				 {
					 print "<option value=".$row["codconmov"].">".$row["denconmov"]."</option>";
				 }
					
			}
			print "</select>";
			$this->SQL->free_result($rs_conceptos);
		}
	}
}
?>
