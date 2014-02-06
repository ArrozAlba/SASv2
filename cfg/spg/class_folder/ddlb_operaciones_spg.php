<?php
class ddlb_operaciones_spg
{
	var $SQL;
	function ddlb_operaciones_spg($con)
	{
		require_once("../../shared/class_folder/class_sql.php");
		$this->SQL=new class_sql($con);
	}
	
	function uf_cargar_ddlb_spg($ai_reservado,$as_seleccionado,$as_mov_operacion)
	{
		$ls_sql="SELECT Operacion,denominacion,asignar,aumento,disminución,precomprometer,comprometer,causar,pagar,reservado
			     FROM SPG_Operaciones
				 WHERE reservado = ".$ai_reservado." ORDER BY Operacion";
	
		$rs_operaciones=$this->SQL->select($ls_sql);
		
		if($rs_operaciones==false)
		{
		}
		else
		{
			print "<select name=ddlb_spg>";
			while($row=$this->SQL->fetch_row($rs_operaciones))
			{
				 $as_operacion=$row["Operacion"];
				 if($as_seleccionado==$as_operacion)
				 {
				 	 print "<option value=".$row["Operacion"]." selected>".$row["denominacion"]."</option>";
				 }
				 else
				 {
				 	 print "<option value=".$row["Operacion"].">".$row["denominacion"]."</option>";
				 }
			}
			print "</select>";
			$this->SQL->free_result($rs_operacion);
		}
	}
}
?>
