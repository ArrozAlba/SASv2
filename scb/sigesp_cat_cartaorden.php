<?php
//session_id('8675309');
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Formatos de Carta Orden</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Formatos de Carta Orden </td>
    	</tr>
	 </table>
	 <br>
	 <br>
    <?php
include("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();

$ls_codemp=$arr["codemp"];

print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "<td>En Uso</td>";
print "</tr>";
	$ls_sql="SELECT * FROM scb_cartaorden WHERE codemp='".$ls_codemp."'";
	$rs_data=$SQL->select($ls_sql);
	if($rs_data===false)
	{
		$io_mensajes->message("ERROR->al seleccionar una carta orden"); 
	}
	else
	{
		$z=0;
		while($row=$SQL->fetch_row($rs_data))
		{
			$z++;
			print "<tr class=celdas-blancas>";					
			$ls_codigo=$row["codigo"];
			$ls_nombre=$row["nombre"];
			$ls_encabezado=$row["encabezado"];
			$ls_cuerpo=$row["cuerpo"];
			$ls_pie=$row["pie"];
			$ls_archrtf=$row["archrtf"];
			//print "<input type='hidden' name=encabezado".$z." id=encabezado".$z." value='$ls_encabezado'>";
			//print "<input type='hidden' name=cuerpo".$z." id=cuerpo".$z." value='$ls_cuerpo'>";
			//print "<input type='hidden' name=pie".$z." id=pie".$z." value='$ls_pie'>";					
			$ls_status=$row["status"];			
			print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_nombre','$ls_status','$z','$ls_archrtf');\">".$ls_codigo."</a></td>";
			print "<td>".$ls_nombre."</td><input type='hidden' name=encabezado".$z." id=encabezado".$z." value='$ls_encabezado'>
			<input type='hidden' name=cuerpo".$z." id=cuerpo".$z." value='$ls_cuerpo'><input type='hidden' name=pie".$z." id=pie".$z." value='$ls_pie'>";
			if($ls_status=="0")
				print "<td>No</td>";
			else
				print "<td>Si</td>";
			print "</tr>";			
		}
	}

print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(ls_codigo,ls_nombre,ls_status,z,archrtf)
  {
    f=opener.document.form1;
	f.txtcodigo.value=ls_codigo;
    f.txtnombre.value=ls_nombre;
    f.txtnomrtf.value=archrtf;
	f.txtencabezado.value=eval("document.form1.encabezado"+z+".value;");
    f.txtcuerpo.value=eval("document.form1.cuerpo"+z+".value;");
	f.txtpie.value=eval("document.form1.pie"+z+".value;");
	//f.hidstatus.value=ls_status;
	f.hidstatus.value='C';
	close();
  }
   function aceptar_aut(codigo,deno)
  {
    opener.document.form1.codbanaut.value=codigo;
    opener.document.form1.nombanaut.value=deno;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_bancos.php";
  f.submit();
  }
</script>
</html>
