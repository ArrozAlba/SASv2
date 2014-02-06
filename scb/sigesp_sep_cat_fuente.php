<?Php
   session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Fuente de Financiamiento</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda"> Cat&aacute;logo de Fuente de Financiamiento</td>
  </tr>
</table>
<div align="center"><br>
  <?Php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$io_in=new sigesp_include();
$con=$io_in->uf_conectar();
$io_ds=new class_datastore();
$io_sql=new class_sql($con);
$arr=$_SESSION["la_empresa"];

$ls_sql=" SELECT  * ".
        " FROM    sigesp_fuentefinanciamiento ".	
		" WHERE   codfuefin <> '--' ".		
		" ORDER  BY codfuefin ASC   ";
		
$rs=$io_sql->select($ls_sql);
$data=$rs;
if ($row=$io_sql->fetch_row($rs))
   {
	 $data=$io_sql->obtener_datos($rs);
     $arrcols=array_keys($data);
     $totcol=count($arrcols);
     $io_ds->data=$data;
     $totrow=$io_ds->getRowCount("codfuefin");
	 
	 print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
	 print "<tr class=titulo-celda>";
	 print "<td>Código </td>";
	 print "<td>Denominación</td>";
	 print "</tr>";
	 for($z=1;$z<=$totrow;$z++)
	 {
		$ls_estatus="";
		print "<tr class=celdas-blancas>";
		$codigo      =$data["codfuefin"][$z];
		$denominacion=$data["denfuefin"][$z];
		  
		print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
		print "<td align=left>".$denominacion."</td>";
		print "</tr>";			
	 }
	 $io_sql->free_result($rs);
	 $io_sql->close();
	 print "</table>";
   }
else
  {
  ?>
  	<script language="javascript">
		alert("No se han definido Fuentes de Financiamiento.");
	</script>	 
  <?php
  }
?>
</div>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,afepre,esttip)
  {
     opener.document.form1.txtftefinanciamiento.value=codigo;
     opener.document.form1.txtdenftefinanciamiento.value=denominacion; 	 	
	 close();
  }
</script>
</html>
