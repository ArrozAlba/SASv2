<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Usuarios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<br>
<?php
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/grid_param.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql		= new class_sql($ls_conect);
$io_msg		= new class_mensajes();
$io_grid	= new grid_param();
$la_emp     = $_SESSION["la_empresa"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
     $totrow       = $_POST["total_usu"];
   }
else
   {
	 $totrow	   = "";
	 $ls_operacion = "";	
   }
?>
<form name="form1" method="post" action="">
<p align="center">
<?php
$title[1]="Código"; $title[2]="Nombre"; $title[3]="Apellido"; 
$grid2="grid";	
if($ls_operacion=="")
{
    $ls_codemp=$la_emp["codemp"];
    $ls_sql="SELECT codusu,nomusu,apeusu FROM sss_usuarios".
			" ORDER BY codusu ASC";  
    $rs_data=$io_sql->select($ls_sql);	
	if($rs_data===false)
	{
		$io_msg->message("");
	}
	else
	{
		$li_numrows = $io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
		    {$z=0;
			  while($row=$io_sql->fetch_row($rs_data))
			       {
				     $z++;
				     $ls_codusu = trim($row["codusu"]);
					 $ls_nomusu = $row["nomusu"];
					 $ls_apeusu = $row["apeusu"];
					 $object[$z][1]="<a href=\"javascript: uf_aceptar('$ls_codusu','$ls_nomusu','$ls_apeusu');\">".$ls_codusu."</a>";
					 $object[$z][2]="<input type=text name=txtnomusu".$z." value='".$ls_nomusu."' id=txtnomusu".$z." class=sin-borde readonly style=text-align:left   size=30 maxlength=100>";
					 $object[$z][3]="<input type=text name=txtapeusu".$z." value='".$ls_apeusu."' id=txtapeusu".$z." class=sin-borde readonly style=text-align:left  size=30 maxlength=100>";
				   }
			}
		else
		{
		  $io_msg->message("No se han definido usuarios !!!");
		  $object[1][1]="<input name=chk1 type=checkbox id=chk1 value=1>";
		  $object[1][2]="<input type=text name=txtcodusu value='' id=txtcodusu class=sin-borde readonly style=text-align:center size=15 maxlength=10>";		
		  $object[1][3]="<input type=text name=txtnomusu value='' id=txtnomusu class=sin-borde readonly style=text-align:left   size=25 maxlength=100>";
          $object[1][4]="<input type=text name=txtapeusu value='' id=txtapeusu class=sin-borde readonly style=text-align:left  size=10 maxlength=100>";
		  $li_numrows=1;
		}
		$io_grid->makegrid($li_numrows,$title,$object,520,'Catálogo de Usuarios',$grid2);		
  }
}
?>
 <input name="total_usu" type="hidden" id="total_usu" value="<?php print $totrow;?>">
  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function uf_aceptar(ls_usucod,ls_nomusu,ls_apeusu)
{
  f   = document.form1;
  fop = opener.document.form1;
  li_totfilusu = fop.totrows.value;
  lb_existe = false;
  for (li_y=1;li_y<=li_totfilusu;li_y++)
	  {
	    ls_codusu = eval("fop.txtcodusu"+li_y+".value");
	    if (ls_codusu==ls_usucod)
		   {
		     alert("El Código de Usuario ya fue incluido !!!");
		     lb_existe = true;
			 break;
		   }
	  }
   if (!lb_existe)
	  {
	    eval("fop.txtcodusu"+li_totfilusu+".value='"+ls_usucod+"'");
		eval("fop.txtnomusu"+li_totfilusu+".value='"+ls_nomusu+"'");
		eval("fop.txtapeusu"+li_totfilusu+".value='"+ls_apeusu+"'");
		fop.totrows.value=parseInt(li_totfilusu)+1;
		fop.operacion.value="PINTAR";
		fop.action="sigesp_scb_d_chequera.php";
        fop.submit();
	  }	  
}
</script>
</html>