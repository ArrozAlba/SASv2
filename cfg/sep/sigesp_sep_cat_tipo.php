<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Tipos de SEP</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Tipos de SEP </td>
  </tr>
</table>
<div align="center"><br>
<?php
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");

$io_in=new sigesp_include();
$con=$io_in->uf_conectar();
$io_ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$ls_sql=" SELECT * ".
        " FROM sep_tiposolicitud ".
		" ORDER BY codtipsol ASC ";
$rs=$io_sql->select($ls_sql);
if ($row=$io_sql->fetch_row($rs))
{
     $data=$rs;
	 $data=$io_sql->obtener_datos($rs);
     $arrcols=array_keys($data);
     $totcol=count($arrcols);
     $io_ds->data=$data;
     $totrow=$io_ds->getRowCount("codtipsol");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
	 print "<tr class=titulo-celda>";
	 print "<td>Código </td>";
	 print "<td>Denominación</td>";
	 print "<td>Estatus</td>";
	 print "</tr>";
	 for($z=1;$z<=$totrow;$z++)
	 { 
		$ls_estatus="";
		print "<tr class=celdas-blancas>";
		$codigo       = $data["codtipsol"][$z];
		$denominacion = $data["dentipsol"][$z];
		$afepre       = $data["estope"][$z];
		$esttip       = $data["modsep"][$z];
		$estayu       = $data["estayueco"][$z];
				
		if($afepre=="R")	
		{
		  $ls_estatus="Precompromiso";
		}
		if($afepre=="O")	
		{
		  $ls_estatus="Compromiso";
		}
		if($afepre=="S")	
		{
		  $ls_estatus="Sin Afectacion";
		}
		if($estayu=="A")
		{
		  $ls_estatus="Ayudas Economicas personal";
		}
		
		print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion','$afepre','$esttip','$estayu');\">".$codigo."</a></td>";
		print "<td align=left>".$denominacion."</td>";
		print "<td align=left>".$ls_estatus."</td>";
		print "</tr>";			
	}
	$io_sql->free_result($rs);
	print "</table>";
}
else
{ 
    ?>
	<script language="javascript">
	alert("No se han creado Tipos de Solicitudes !!!");
	close();
	</script>
	<?php
}	
?>
</div>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,afepre,esttip,estayu)
  {
     opener.document.form1.txtcodigo.value=codigo;
     opener.document.form1.txtdenominacion.value=denominacion;
	
	 if(afepre=="R")
	 {
       opener.document.form1.afepre[0].checked=true;
	 }
	 else if(afepre=="O")
	 {
       opener.document.form1.afepre[1].checked=true;
	   opener.document.form1.estayu.disabled=false;
 	 }	 
	 else 
	 {
       opener.document.form1.afepre[2].checked=true;
 	 }	 
	 
	 
	 if(esttip=="B")
	 {
       opener.document.form1.esttip[0].checked=true;
	   opener.document.form1.estayu.disabled=true;
	 }
	 else 
	 {
	 	 if(esttip=="S")
		 {
		   opener.document.form1.esttip[1].checked=true;
		   opener.document.form1.estayu.disabled=true;
		 }
         else
		 {
		    opener.document.form1.esttip[2].checked=true;
		 }
 	 }
	 if(estayu=="A")
	 {
        opener.document.form1.estayu.checked=true;
		//opener.document.form1.estayu.disabled=true;
 	 }
 	 opener.document.form1.hidestatus.value="GRABADO";
	 close();
  }
</script>
</html>