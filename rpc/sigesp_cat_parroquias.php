<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Parroquias</title>
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Parroquias</td>
    </tr>
</table>
  <br>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");

$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_dsparro=new class_datastore();
$io_sql=new class_sql($conn);
if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion=$_POST["operacion"];
	 $ls_codpais=$_POST["hidpais"];
   }
else
   {
     $ls_operacion="";
 	 $ls_codpais=$_GET["hidpais"];
   }
if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion=$_POST["operacion"];
	 $ls_codest=$_POST["hidestado"];
   }
else
   {
     $ls_operacion="";
 	 $ls_codest=$_GET["hidestado"];
   }
  if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion=$_POST["operacion"];
	 $ls_codmun=$_POST["hidmunicipio"];
   }
else
   {
     $ls_operacion="";
 	 $ls_codmun=$_GET["hidmunicipio"];
   }

$ls_sql=" SELECT codpar,denpar ".
        " FROM sigesp_parroquia ".
        " WHERE codpai= '".$ls_codpais."' AND codest= '".$ls_codest."' AND ".
        "       codmun= '".$ls_codmun."' ".
        " ORDER BY codpar ASC";

$rs_parro=$io_sql->select($ls_sql);
$data=$rs_parro;
if($row=$io_sql->fetch_row($rs_parro))
{
     $data=$io_sql->obtener_datos($rs_parro);
	 $arrcols=array_keys($data);
	 $totcol=count($arrcols);
	 $io_dsparro->data=$data;
	 $totrow=$io_dsparro->getRowCount("codpar");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
	 print "<tr class=titulo-celda>";
     print "<td>Código</td>";
     print "<td>Denominación</td>";
	 print "</tr>";
	 for($z=1;$z<=$totrow;$z++)
	 {
		print "<tr class=celdas-blancas>";
		$codigo=$data["codpar"][$z];
		$denominacion=$data["denpar"][$z];
		print "<td><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
		print "<td>".$denominacion."</td>";
		print "</tr>";			
	 }
     print "</table>";
     $io_sql->free_result($rs_parro);
}
else
{ ?>
  <script language="javascript">
  alert("No se han creado Parroquias para este Municipio !!!");
  close();
  </script>
<?php
}
?>
<form name="form1" method="post" action="">
  <input name="hidpais" type="hidden" id="hidpais">
</form>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion)
  {
    opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtcodigo.readOnly=true;
    opener.document.form1.txtdenominacion.value=denominacion;
	close();
  }
</script>
</html>