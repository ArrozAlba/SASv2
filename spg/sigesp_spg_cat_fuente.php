<?php
   session_start();
   if(!array_key_exists("la_logusr",$_SESSION))
   {
		print "<script language=JavaScript>";
		print "opener.document.form1.submit();";
		print "close();";
		print "</script>";		
   }
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
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_datastore.php");
	require_once("../shared/class_folder/class_sql.php");

	$io_in=new sigesp_include();
	$con=$io_in->uf_conectar();
	$io_ds=new class_datastore();
	$io_sql=new class_sql($con);
	$arr=$_SESSION["la_empresa"];
	$ls_codemp=$arr["codemp"];
	if(array_key_exists("tipo",$_GET))
	{
	$ls_tipo=$_GET["tipo"];
	}
	else
	{
	$ls_tipo="";
	}
	
	
	$ls_sql=" SELECT *                               ".
			" FROM    sigesp_fuentefinanciamiento    ".		
			" WHERE   codemp = '".$ls_codemp."'      ".
			//"         codfuefin <> '--'              ".		
			" ORDER  BY codfuefin ASC                ";
			
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
			if ($ls_tipo=="")
			{
				 print "<td align=center><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
			}
			if($ls_tipo=="DESDE")  
			{
			   print "<td align=center><a href=\"javascript: aceptar_desde('$codigo','$denominacion');\">".$codigo."</a></td>";
			}
			if($ls_tipo=="HASTA")  
			{
			   print "<td align=center><a href=\"javascript: aceptar_hasta('$codigo','$denominacion');\">".$codigo."</a></td>";
			}
			if($ls_tipo=="REPORTE_DESDE")  
			{
			   print "<td align=center><a href=\"javascript: aceptar_reporte_desde('$codigo','$denominacion');\">".$codigo."</a></td>";
			}
			if($ls_tipo=="REPORTE_HASTA")  
			{
			   print "<td align=center><a href=\"javascript: aceptar_reporte_hasta('$codigo','$denominacion');\">".$codigo."</a></td>";
			}
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
	  		alert("No se han creado Fuentes de Financiamientos para esta Busqueda !!!");
		    close();
	     </script>
        <?php
  	}
?>
</div>
</body>
<script language="JavaScript">


  function aceptar(codigo,denominacion)
  {
     opener.document.form1.txtfuente.value=codigo;
     opener.document.form1.txtdesfuente.value=denominacion; 	 	
	 close();
  }
  function aceptar_desde(codigo,denominacion)
  {
     opener.document.form1.txtcodfuefindes.value=codigo;
     opener.document.form1.txtdenfuefindes.value=denominacion; 	 	
	 close();
  }
  
  function aceptar_hasta(codigo,denominacion)
  {
     opener.document.form1.txtcodfuefinhas.value=codigo;
     opener.document.form1.txtdenfuefinhas.value=denominacion; 	 	
	 close();
  }

  function aceptar_reporte_desde(codigo,denominacion)
  {
     opener.document.form1.txtcodfuefindes.value=codigo;
	 close();
  }

  function aceptar_reporte_hasta(codigo,denominacion)
  {
     opener.document.form1.txtcodfuefinhas.value=codigo;
	 close();
  }
</script>
</html>