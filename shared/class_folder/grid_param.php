<link href="css/tablas.css" rel="stylesheet" type="text/css">
<style type="text/css">
.innerb {height:10em; overflow:auto; width:635px; background-color:#FFFFFF;}
</style>
<?php
require_once("class_mensajes.php");
class grid_param
{
	var $numrows;
	var $titles;
	var $align;
	var $size;
	var $maxlength;
	var $validaciones;
	var $widthtable;
	var $titletable;
	function grid_param()
    {			
    }
	
	function makegrid($rows,$titulos,$object,$widthtable,$titletable,$name)
	{
		$this->numrows=$rows;
		$this->titles=$titulos;
		$this->widthtable=$widthtable;
		$this->titletable=$titletable;
		$totcols=count($this->titles);
		print "<table width=".$this->widthtable." border=0 cellspacing=1 cellpadding=1 id=".$name." class=fondo-tabla>";
		print "<tr class=titulo-celda >";
		print "<td colspan=".$totcols.">".$this->titletable."</td>";
		print "</tr>";
		print "<tr class=titulo-celdanew>";

		for($i=1;$i<=$totcols;$i++)
		{
			print "<td align=center>".$this->titles[$i]."</td>";
		}
		print "</tr>";
		
		for($z=1;$z<=$this->numrows;$z++)
		{			
			print "<tr class=celdas-blancas>";
			for($y=1;$y<=$totcols;$y++)
			{
				$txt="txt".$this->titles[$y].$z;
				$alineacion=$this->align[$y];
				$tamaño=$this->size[$y];
				$max=$this->maxlength[$y];
				$valida=$this->validaciones[$y];
				print "<td class=celdas-blancas >".$object[$z][$y]."</td>";
			}	
		print "</tr>";
	  }
	  print "</table>";
	  print "<br>";
	}
	
	function make_gridScroll($rows,$titulos,$object,$widthtable,$titletable,$name,$alto_tabla)
	{
		print "<style type=text/css>";
              print ".innerb {height:10em; overflow:auto; width:".$widthtable."px; background-color:#FFFFFF;}";
		print "</style>";
		$this->numrows=$rows;
		$this->titles=$titulos;
		$this->widthtable=$widthtable;
		$this->titletable=$titletable;
		$totcols=count($this->titles);
		print "<table width=".$this->widthtable." id=".$name." class=fondo-tabla align=center>";
			print "<thead > ";
				print "<tr class=titulo-celda >";
					print "<td colspan=".$totcols.">".$this->titletable."</td>";
				print "</tr>";
				
			print "</thead>";
			print "<tbody class=fondo-tabla> ";
				print "<tr class=fondo-tabla><td colspan=".$totcols." class=fondo-tabla>";
					print "<div class='innerb' style='height:".$alto_tabla."px;' >";
						print "<table class=fondo-tabla border=0 cellspacing=1 cellpadding=0>";
						print "<tr class=titulo-celdanew>";	
							for($i=1;$i<=$totcols;$i++)
							{
								print "<td align=center >".$this->titles[$i]."</td>";
							}
						print "</tr>";
						for($z=1;$z<=$this->numrows;$z++)
						{			
							print "<tr id=celda".$z." name=celda".$z." class=celdas-blancas>";
							for($y=1;$y<=$totcols;$y++)
							{
								$txt="txt".$this->titles[$y].$z;
								$alineacion=$this->align[$y];
								$tamaño=$this->size[$y];
								$max=$this->maxlength[$y];
								$valida=$this->validaciones[$y];
								print "<td class=celdas-blancas  >".$object[$z][$y]."</td>";
							}	
							print "</tr>";
					   }
					   print "</table>";
				  print "</div>";
			  print "</td>"	;
			  print "</tr>";
		  print "</tbody> ";
	  print "</table>";
	  print "<br>";
	}
	
}
?>

<link href="css/general.css" rel="stylesheet" type="text/css">
