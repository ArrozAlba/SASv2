<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Documentos</title>
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
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Documentos</td>
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
$ls_sql=" SELECT codtipdoc,dentipdoc,estpre,estcon ".
        " FROM cxp_documento ".
		" ORDER BY codtipdoc ASC";
$rs_doc=$io_sql->select($ls_sql);
$data=$rs_doc;
if($row=$io_sql->fetch_row($rs_doc))
  {//1
	$data=$io_sql->obtener_datos($rs_doc);
    $arrcols=array_keys($data);
    $totcol=count($arrcols);
    $io_ds->data=$data;
    $totrow=$io_ds->getRowCount("codtipdoc");
	print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
	print "<tr class=titulo-celda>";
	print "<td>Código </td>";
	print "<td>Denominación</td>";
	print "</tr>";
    for($z=1;$z<=$totrow;$z++)
       {//2
		 print "<tr class=celdas-blancas>";
		 $codigo=$data["codtipdoc"][$z];
		 $denominacion=$data["dentipdoc"][$z];
		 $ls_presu=$data["estpre"][$z];
		 $ls_conta=$data["estcon"][$z];
		if ($ls_conta==1)
		   {//3
		   $contable="D";
		   }//3
	    if ($ls_conta==2)
		   {//4
		     $contable="C";
		   }//4
	    if ($ls_conta==3)
	 	   {//5
			 $contable="N";
		   }//5
		if ($ls_conta==4)
		   {//6
			 $contable="S";
		   }//6
	    if ($ls_presu==1)
		   {//7
		     $presupuest="C";
		   }//7
	    if ($ls_presu==2)
		   {//8
		     $presupuest="P";
		   }//8
	    if ($ls_presu==3)
		   {//9
			$presupuest="N";
		   }//9
	   if ($ls_presu==4)
		  {//10
			 $presupuest="S";
		  }//10
		print "<td style=text-align:center><a href=\"javascript: aceptar('$codigo','$denominacion','$presupuest','$contable');\">".$codigo."</a></td>";
		print "<td style=text-align:left>".$denominacion."</td>";
		print "</tr>";			
	}//2
    print "</table>";
    $io_sql->free_result($rs_doc);
}//1
else
{//11
?>
<script language="javascript">
alert("No se han creados Tipos de Documentos !!!");
close();
</script>
<?php
}//11
?>
</div>
</body>
<script language="JavaScript">
  function aceptar(c,d,p,t)
  {
    opener.document.form1.txtcodigo.value=c;
    opener.document.form1.txtcodigo.readOnly=true;
	opener.document.form1.txtdenominacion.value=d;
    opener.document.form1.cmbpresupuesto.value=p;
    opener.document.form1.cbmcontable.value=t;
	close();
  }
</script>
</html>