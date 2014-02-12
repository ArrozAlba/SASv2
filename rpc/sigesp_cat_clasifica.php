<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo Clasificación de Proveedor</title>
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

$io_conect=new sigesp_include();
$conn=$io_conect->uf_conectar();
$io_dclasxpro=new class_datastore();
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

$ls_sql=" SELECT CP.codclas as codigo, C.denclas as denominacion , ".
        "        CP.status as estatus , CP.nivstatus as nivel      ".
        " FROM rpc_clasificacion C, rpc_clasifxprov CP  ".
		" WHERE C.codemp=CP.codemp        AND C.codclas=CP.codclas AND ".
        "       C.codemp='".$ls_codemp."' AND cod_pro= '".$ls_codprov."'";

$rs_clasxpro=$io_sql->select($ls_sql);
$data=$rs_clasxpro;
?>
<form name="form1" method="post" action="">
  <input name="txtprov" type="hidden" id="txtprov" value= "<?php print $ls_codprov?>">
</form>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Par&aacute;metros de Calificaci&oacute;n Asociados a ese Proveedor</td>
    </tr>
  </table>
<br>

<?php
if($row=$io_sql->fetch_row($rs_clasxpro))
{
     $data=$io_sql->obtener_datos($rs_clasxpro);
	 $arrcols=array_keys($data);
	 $totcol=count($arrcols);
	 $io_dsclasxpro->data=$data;
	 $totrow=$io_dsclasxpro->getRowCount("codigo");
     print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	 print "<tr class=titulo-celda>";
	 print "<td>Código</td>";
	 print "<td>Denominación</td>";
	 print "<td>Estatus</td>"; 	 
	 print "<td>Nivel del Estatus</td>";
	 print "</tr>";  
	 for($z=1;$z<=$totrow;$z++)
     {
		print "<tr class=celdas-blancas>";
		$codigo=$data["codigo"][$z];
		$denominacion=$data["denominacion"][$z];
		$estatus=$data["estatus"][$z];
		if ($estatus==0)
		   {
			 $estcla="Activa";
		   }
		 else
		 if ($estatus==1)  
		   {
			 $estcla="No Activa";
		   }  
		
		$nivel=$data["nivel"][$z];
		if ($nivel==0)
		   {
			 $nivelclas="Ninguno";
		   }
		else
		if ($nivel==1)
		   {
			 $nivelclas="Bueno";
		   }
		else
		if ($nivel==2)
		   {
			 $nivelclas="Regular";
		   }
		else
		if ($nivel==3)
		   {
			 $nivelclas="Malo";
		   }
        print "<td><a href=\"javascript: aceptar('$codigo','$denominacion','$estatus','$nivel');\">".$codigo."</a></td>";
		print "<td>".$denominacion."</td>";
		print "<td>".$estcla."</td>";
		print "<td>".$nivelclas."</td>";
		print "</tr>";			
    }
$io_sql->free_result($rs_clasxpro);
}	
else
    { ?>
	  <script language="javascript">
	  alert("No se han creado Clasificaciones Por Proveedor !!!");
	  close();
	  </script>
	<?php
	}
?>

</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,estatus,nivel)
  {
    opener.document.form1.txtcodigo.value=codigo;
    opener.document.form1.txtdenominacion.value=denominacion;
	if (estatus==0)
	   {
	     opener.document.form1.cmbestatus[0].selected=true;
	   }
	else
	   {
	     opener.document.form1.cmbestatus[1].selected=true;	   
	   }
	if (nivel==0)
	   {
	     opener.document.form1.cmbnivestatus[0].selected=true;
	   }
	else
	if (nivel==1)
	   {
	     opener.document.form1.cmbnivestatus[1].selected=true;	   
	   }       
	if (nivel==2)
	   {
	     opener.document.form1.cmbnivestatus[2].selected=true;
	   }
	else
	if (nivel==3)
	   {
	     opener.document.form1.cmbnivestatus[3].selected=true;	   
	   }
	close();
  }
</script>
</html>