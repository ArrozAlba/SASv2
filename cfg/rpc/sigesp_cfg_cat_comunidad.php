<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Comunidades</title>
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
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Comunidades</td>
    </tr>
</table>
  <br>
<form name="form1" method="post" action="">
  <div align="center">
    <input name="hidpais" type="hidden" id="hidpais">
    <?php
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");

$io_conect  = new sigesp_include();
$conn       = $io_conect->uf_conectar();
$io_data = new class_datastore();
$io_sql     = new class_sql($conn);
if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion = $_POST["operacion"];
	 $ls_codpais   = $_POST["hidpais"];
	 $ls_codest    = $_POST["hidestado"];
	 $ls_codmun    = $_POST["hidmunicipio"];
     $ls_codpar    = $_POST["hidparroquia"];
   }
else
   {
     $ls_operacion = "";
 	 $ls_codpais   = $_GET["hidpais"];
     $ls_codest    = $_GET["hidestado"];
     $ls_codmun    = $_GET["hidmunicipio"];
     $ls_codpar    = $_GET["hidparroquia"];
   }
$ls_sql=" SELECT codcom,nomcom FROM sigesp_comunidad                                                                             ".
	    "  WHERE codpai= '".$ls_codpais."' AND codest= '".$ls_codest."' AND codmun= '".$ls_codmun."' AND codpar= '".$ls_codpar."'".
		"  ORDER BY codcom ASC                                                                                                   ";
$rs_data = $io_sql->select($ls_sql);
$data    = $rs_data;
if ($row=$io_sql->fetch_row($rs_data))
   {
     $data          = $io_sql->obtener_datos($rs_data);
	 $arrcols       = array_keys($data);
	 $totcol        = count($arrcols);
	 $io_data->data = $data;
	 $totrow        = $io_data->getRowCount("codcom");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
	 print "<tr class=titulo-celda>";
     print "<td style=text-align:center>Código</td>"; 
     print "<td style=text-align:center>Denominación</td>";
	 print "</tr>";
	 for ($z=1;$z<=$totrow;$z++)
	     {
		   print "<tr class=celdas-blancas>";
		   $ls_codcom = $data["codcom"][$z];
		   $ls_nomcom = $data["nomcom"][$z];
	  	   print "<td  style=text-align:center><a href=\"javascript: aceptar('$ls_codcom','$ls_nomcom');\">".$ls_codcom."</a></td>";
		   print "<td style=text-align:left>".$ls_nomcom."</td>";
		   print "</tr>";			
	      }
     print "</table>";
     $io_sql->free_result($rs_data); 
   }
else
   { ?>
     <script  language="javascript">
     alert("No se han creado Comunidades en esta Ubicación Geográfica !!!");
     close();
     </script>
     <?php
   }
?>
</div>
</form>
</body>
<script language="JavaScript">
  function aceptar(ls_codcom,ls_nomcom)
  {
    opener.document.form1.txtcodigo.value       = ls_codcom;
    opener.document.form1.txtcodigo.readOnly    = true;
	opener.document.form1.txtdenominacion.value = ls_nomcom;
	opener.document.form1.hidestatus.value      = "GRABADO";
	close();
  }
</script>
</html>