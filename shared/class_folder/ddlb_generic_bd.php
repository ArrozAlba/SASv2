<?php
class ddlb_generic_bd
{
	var $SQL;
	function ddlb_generic_bd($con)
	{
		$this->SQL=new class_sql($con);
	}
	
	function uf_cargar_conceptos($as_selec,$as_coddev,$as_tabla,$as_codemp,$as_codigo,$as_clave,$as_nomcmb,$ai_width,$as_seleccionado)
	{
		if (!empty($as_codigo))
		{
		  $ls_cad_sql=" AND ".$as_codigo."=".$as_clave." ";
		}
		else
		{
		  $ls_cad_sql="";
		}
		$ls_sql=" SELECT ".$as_selec." ".
			    " FROM ".$as_tabla." ".
				" WHERE codemp = '".$as_codemp."' ".$ls_cad_sql."  ".
				" ORDER BY ".$as_coddev." ";
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			print "Error".$this->SQL->message;
			print "<select name=".$as_nomcmb." style=width:".$ai_width."px>";
		    print "<option value=s1>Seleccione</option>";
			print "</select>";
		}
		else
		{
			print "<select name=".$as_nomcmb." style=width:".$ai_width."px>";
			print "<option value=s1>Seleccione</option>";
			while($row=$this->SQL->fetch_row($rs_data))
			{
				 $ls_operacion=$row["$as_coddev"];
				 if($as_seleccionado==$as_operacion)
				 {
					 print "<option value=".$row["$as_coddev"]." selected>".$row["$as_coddev"]."</option>";
				 }
				 else
				 {
					 print "<option value=".$row["$as_coddev"].">".$row["$as_coddev"]."</option>";
				 }
					
			}
			print "</select>";
			$this->SQL->free_result($rs_data);
		}
	}
	
	function uf_cargar_ddlb($ls_columns,$ls_codigo,$ls_display,$ls_table,$ls_where,$ls_name,$ai_width,$ls_operacion)
	{
		$ls_sql=" SELECT ".$ls_columns." ".
			    " FROM ".$ls_table." ".
				$ls_where."  ".
				" ORDER BY ".$ls_codigo." ";
		$rs_data=$this->SQL->select($ls_sql);
		if($rs_data===false)
		{
			print "<select name=".$ls_name." style=width:".$ai_width."px>";
		    print "<option value=s1>Seleccione</option>";
			print "</select>";
		}
		else
		{
			print "<select name=".$ls_name." style=width:".$ai_width."px>";
			while($row=$this->SQL->fetch_row($rs_data))
			{
				 $ls_val=$row["$ls_codigo"];
				 if($ls_val==$ls_operacion)
				 {
					 print "<option value=".$row["$ls_codigo"]." selected>".$row["$ls_display"]."</option>";
				 }
				 else
				 {
					 print "<option value=".$row["$ls_codigo"].">".$row["$ls_display"]."</option>";
				 }
					
			}
			print "</select>";
			$this->SQL->free_result($rs_data);
		}
	
	
	}
}
?>
