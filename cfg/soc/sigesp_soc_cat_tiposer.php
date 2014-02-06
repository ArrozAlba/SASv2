<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Tipos de Servicios</title>
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
   <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Tipos de Servicios </td>
  </tr>
</table>
<div align="center">
<form name="form1" method="post" action="">
<?php
	require_once("../../shared/class_folder/sigesp_include.php");
	require_once("../../shared/class_folder/class_datastore.php");
	require_once("../../shared/class_folder/class_sql.php");
	
	$io_in=new sigesp_include();
	$con=$io_in->uf_conectar();
	$io_ds=new class_datastore();
	$io_sql=new class_sql($con);
	$arr=$_SESSION["la_empresa"];
	$ls_sql=" SELECT codtipser, dentipser, codmil, ".
			"        (SELECT denmil ".
			"         FROM sigesp_catalogo_milco  ".
			"         WHERE soc_tiposervicio.codmil=sigesp_catalogo_milco.codmil) as denmil ".
			" FROM soc_tiposervicio ".
			" ORDER BY codtipser ASC";
	$rs=$io_sql->select($ls_sql);
	$data=$rs;
	if ($row=$io_sql->fetch_row($rs))
	{
		 $data=$io_sql->obtener_datos($rs);
		 $arrcols=array_keys($data);
		 $totcol=count($arrcols);
		 $io_ds->data=$data;
		 $totrow=$io_ds->getRowCount("codtipser");
		 print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
		 print "<tr class=titulo-celda>";
		 print "<td>Código </td>";
		 print "<td>Denominación</td>";
		 print "</tr>";
		 for($z=1;$z<=$totrow;$z++)
			{
			  print "<tr class=celdas-blancas>";
			  $codigo=$data["codtipser"][$z];
			  $denominacion=$data["dentipser"][$z];
			  $ls_codmil=$data["codmil"][$z];
			  $ls_denmil=$data["denmil"][$z];
			  print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion','$ls_codmil','$ls_denmil');\">".$codigo."</a></td>";
			  print "<td align=left>".$denominacion."</td>";
			  print "</tr>";			
		   }
		  $io_sql->free_result($rs);
		  print "</table>";
	}
	else
	{
		?>
		 <script language="javascript">
		 alert("No se han creado Tipos de Servicios !!!");
		 close();
		 </script> 
		<?php
	}
?>
  </form>
  <br>
</div>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion,codmil,denmil)
  {
     fop                       = opener.document.form1;
	 ls_maestro                = fop.hidmaestro.value;
	 if (ls_maestro=='Y')
	    {
	      fop.hidestatus.value      = "GRABADO";	
		  fop.txtcodigo.value       = codigo;
          fop.txtcodigo.readOnly    = true;
	      fop.txtdenominacion.value = denominacion;
	      fop.txtcodmil.value       = codmil;
	      fop.txtdenmil.value       = denmil;
  	    } 
	 else
	    {
		 fop.txtcodtipser.value    = codigo;
         fop.txtcodtipser.readOnly = true;
	     fop.txtdentipser.value    = denominacion;
	     fop.txtcodmil.value       = codmil;
	     fop.txtdenmil.value       = denmil;
		}
	 close();
  }
</script>
</html>