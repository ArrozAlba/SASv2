<?php
class ddlb_operaciones_spi
{
	var $SQL;
	function ddlb_operaciones_spi($con)
	{
		$this->SQL=new class_sql($con);
	}
	
	function uf_cargar_ddlb_spi($ai_reservado,$as_seleccionado,$as_mov_operacion)
	{
		$ls_sql="SELECT operacion,denominacion,previsto,aumento,disminucion,devengado,cobrado,cobrado_ant,reservado
			     FROM spi_operaciones
				 WHERE reservado = ".$ai_reservado." ORDER BY operacion";
	//print $ls_sql;
		$rs_operaciones=$this->SQL->select($ls_sql);
		
		if($rs_operaciones==false)
		{
		print "Error".$this->SQL->message;
		}
		else
		{
			print "<select name=ddlb_spi style=width:120px>";
			//$x=0;
			while($row=$this->SQL->fetch_row($rs_operaciones))
			{
			  	//$x++;
				 //print $x;
				 $as_operacion=trim($row["operacion"]);
				 //print $as_operacion;
				 if(($as_mov_operacion=='DP')||($as_mov_operacion=='NC'))
				 {
					 if($as_operacion=='COB')
					 {
						 if($as_seleccionado==1)
						 {
							 print "<option value=1 selected>".$row["denominacion"]."</option>";
						 }
						 else
						 {
							 print "<option value=1>".$row["denominacion"]."</option>";
						 }
					}
					elseif($as_operacion=='DC')
					{				
						 if($as_seleccionado==0)
						 {
							 print "<option value=0 selected>".$row["denominacion"]."</option>";
						 }
						 else
						 {
							 print "<option value=0>".$row["denominacion"]."</option>";
						 }
					
					}		
					
				}
			}
			print "</select>";
			$this->SQL->free_result($rs_operaciones);
		}
	}
}
?>
