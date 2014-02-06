<?php
class ddlb_generic
{
	var $ia_value;
	var $ia_text;	
	var $is_selected;
	var $is_name;	
	var $is_accion; 
	var $ii_width;
	function ddlb_generic($aa_value,$aa_text,$as_selected,$as_name,$as_accion,$ai_width)
	{
		$this->ia_value=$aa_value;
		$this->ia_text=$aa_text;
		$this->is_selected=$as_selected;
		$this->is_name=$as_name;				
		$this->is_accion=$as_accion;				
		$this->ii_width=$ai_width;				
	}
	
	function uf_cargar_combo()
	{
		$li_total=count($this->ia_value);
		print "<select name=".$this->is_name."  id=".$this->is_name." style=width:".$this->ii_width."px ".$this->is_accion.">";
		print "<option value=--->---Seleccione---</option>";
		for($li_i=0;$li_i<$li_total;$li_i++)
		{
			$ls_valor=$this->ia_value[$li_i];
			$ls_text=$this->ia_text[$li_i];

			 if($this->is_selected==$ls_valor)
			 {
				 print "<option value=".$ls_valor." selected>".$ls_text."</option>";
			 }
			 else
			 {
				 print "<option value=".$ls_valor." >".$ls_text."</option>";
			 }
		}
		print "</select>";
	}
}
?>
