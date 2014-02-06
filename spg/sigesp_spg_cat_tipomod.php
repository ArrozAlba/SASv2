<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Tipo de Modificaciones Presupuestarias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
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
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");


$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_dsfuente=new class_datastore();
$io_sql=new class_sql($conn);

$arr=$_SESSION["la_empresa"];
$ls_sql="SELECT codtipmodpre, dentipmodpre, pretipmodpre, contipmodpre ".
        "  FROM spg_tipomodificacion".
		" WHERE codtipmodpre <> '----' ".
		" ORDER BY codtipmodpre ASC ";
$rs_fuente=$io_sql->select($ls_sql);
$data=$rs_fuente;
?>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Tipo de Modificaciones Presupuestarias </td>
  </tr>
</table>
  <br>
<form name="form1" method="post" action="">
  <div align="center">
    <?php
if ($row=$io_sql->fetch_row($rs_fuente))
   {
     $data=$io_sql->obtener_datos($rs_fuente);
     $io_dsfuente->data=$data;
     $totrow=$io_dsfuente->getRowCount("codtipmodpre");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 print "<tr class=titulo-celda>";
	 print "<td>Código</td>";
	 print "<td  style=text-align:left>Denominación</td>";
	 print "<td  style=text-align:left>Prefijo</td>";
 	 print "</tr>";
	 for ($z=1;$z<=$totrow;$z++)
	{
			print "<tr class=celdas-blancas>";
			$ls_codtipmodpre= $data["codtipmodpre"][$z];
			$ls_dentipmodpre= $data["dentipmodpre"][$z];
			$ls_pretipmodpre= $data["pretipmodpre"][$z];
			$ls_contipmodpre= $data["contipmodpre"][$z];
			print "<td><a href=\"javascript: aceptar('$ls_codtipmodpre','$ls_dentipmodpre','$ls_contipmodpre','$ls_pretipmodpre');\">".$ls_codtipmodpre."</a></td>";
			print "<td  style=text-align:left>".$ls_dentipmodpre."</td>";
			print "<td  style=text-align:left>".$ls_pretipmodpre."</td>";
			print "</tr>";			
	}
	print "</table>";
	}
else
    {
      ?>
	  <script language="javascript" >
	  alert("No existen registros");
	  close();
	  </script>
     <?php  
	}		 
$io_sql->free_result($rs_fuente);
$io_sql->close();
?>
  </div>
</form>
</body>
<script language="JavaScript">
  function aceptar(ls_codtipmodpre,ls_dentipmodpre,ls_contipmodpre,ls_pretipmodpre)
  {
   	opener.document.form1.txtcomprobante.value=ls_pretipmodpre+ls_contipmodpre;
	//opener.document.form1.txtcomprobante.readOnly=true;
	opener.document.form1.codtipomod.value=ls_codtipmodpre;	
	close();
  }
</script>
</html>