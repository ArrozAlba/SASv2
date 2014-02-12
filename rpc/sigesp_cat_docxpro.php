<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Socios por Proveedor</title>
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
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_funciones.php");

$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_dsdocxpro=new class_datastore();
$io_funciones=new class_funciones();
$io_sql=new class_sql($conn);
$arr=$_SESSION["la_empresa"];
$ls_codemp=$arr["codemp"];
if(array_key_exists("operacion",$_POST))
	{
	  $ls_operacion=$_POST["operacion"];
	  $ls_codprov=$_POST["txtprov"];
	}
else
	{
	  $ls_operacion="";
 	  $ls_codprov=$_GET["txtprov"];
	}
$ls_sql=" SELECT DP.coddoc as codigo,             D.dendoc as denominacion, ".
        "        DP.fecrecdoc as fecharecepcion, DP.fecvendoc, ".
        "        DP.estdoc as estatusdocumento, DP.estorig as estatusoriginal ".
		" FROM rpc_documentos D, rpc_docxprov DP ".		
		" WHERE D.codemp=DP.codemp        AND D.coddoc=DP.coddoc  AND".
        "       D.codemp='".$ls_codemp."' AND cod_pro='".$ls_codprov."' ";
$rs_docxpro=$io_sql->select($ls_sql);
$data=$rs_docxpro;

?>
<form name="form1" method="post" action="">
  <input name="txtprov" type="hidden" id="txtprov" value= "<?php print $ls_codprov?>">
</form>
<table width="780" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="779" colspan="2" class="titulo-celda">Recaudos Asociados a ese Proveedor</td>
    </tr>
  </table>
  <br>

<?php
if ($row=$io_sql->fetch_row($rs_docxpro))
   {
     $data=$io_sql->obtener_datos($rs_docxpro);
	 $arrcols=array_keys($data);
     $totcol=count($arrcols);
     $io_dsdocxpro->data=$data;
     $totrow=$io_dsdocxpro->getRowCount("codigo");
     print "<table width=780 border=0 cellpadding=1  cellspacing=1 class=fondo-tabla align=center>";
     print "<tr class=titulo-celda>";
	 print "<td width=55>Código</td>";
     print "<td width=180>Denominación</td>";
	 print "<td width=125>Fecha de Recepción</td>";
	 print "<td width=179>Fecha de Vencimiento</td>";
	 print "<td width=169>Estatus del Documento</td>";
	 print "<td width=126>Estatus de Original</td>";
	 print "</tr>";
     for($z=1;$z<=$totrow;$z++)
	 {
		print "<tr class=celdas-blancas>";
		$codigo=$data["codigo"][$z];
		$denominacion=$data["denominacion"][$z];
		$ls_fecharecepcion=$io_funciones->uf_formatovalidofecha($data["fecharecepcion"][$z]);
		$ls_fecrec=$io_funciones->uf_convertirfecmostrar($ls_fecharecepcion);
		$ls_fechavencimiento=$io_funciones->uf_formatovalidofecha($data["fecvendoc"][$z]);
		$ls_fecven=$io_funciones->uf_convertirfecmostrar($ls_fechavencimiento);
		
		$estdocumento=$data["estatusdocumento"][$z];
		if ($estdocumento==0)
		   {
			 $estdoc="No Entregado";
		   }
		else
		if ($estdocumento==1)
		   {
			 $estdoc="Entregado";
		   }
		else
		if ($estdocumento==2)
		   {
			 $estdoc="En Tramite";
		   }
		else
		if ($estdocumento==3)
		   {
			 $estdoc="No Aplica al Proveedor";
		   }
			
		$estoriginal=$data["estatusoriginal"][$z];
		if ($estoriginal==0)
		   {
			 $estori="Copia del Documento";
		   }
		else
		if ($estoriginal==1)
		   {
			 $estori="Original";
		   }
		print "<td><a href=\"javascript: aceptar('$codigo','$denominacion','$ls_fecrec','$ls_fecven','$estdocumento','$estoriginal');\">".$codigo."</a></td>";
		print "<td>".$denominacion."</td>";
		print "<td>".$ls_fecrec."</td>";
		print "<td>".$ls_fecven."</td>";
		print "<td>".$estdoc."</td>";
		print "<td>".$estori."</td>";
		print "</tr>";			
	}//End del for
   print "</table>"; 
$io_sql->free_result($rs_docxpro);
}//End del If
else
  { ?>
    <script language="javascript">
    alert("No se han creado Documentos Para este Proveedor !!!");
    close();
	</script>
   <?php
  }

?>
</table>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,FecRec,FecVen,EstatusDoc,EstatusOri)
  {
    opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtcodigo.readOnly=true;
    opener.document.form1.txtdenominacion.value=denominacion;
	opener.document.form1.txtfecrec.value=FecRec;
    opener.document.form1.txtfecven.value=FecVen;
	if (EstatusDoc==0)
	   {
	     opener.document.form1.cmbestdoc[0].selected=true;
	   }
	else
	if (EstatusDoc==1)
	   {
	     opener.document.form1.cmbestdoc[1].selected=true;
	   }
	else
	if (EstatusDoc==2)
	   {
	     opener.document.form1.cmbestdoc[2].selected=true;
	   }
	else
	if (EstatusDoc==3)
	   {
	     opener.document.form1.cmbestdoc[3].selected=true;
	   }
	   
	   
	if (EstatusOri==0)
	   {
	     opener.document.form1.cmbestori[0].selected=true;
	   }
	else
	if (EstatusOri==1)
	   {
	     opener.document.form1.cmbestori[1].selected=true;
	   }
close();
  }
</script>
</html>