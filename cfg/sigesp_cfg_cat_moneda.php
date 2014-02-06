<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Procedencias</title>
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
      <td width="496" colspan="2" class="titulo-celdanew">Cat&aacute;logo de Monedas</td>
    </tr>
</table>
  <br>
<?php
require_once("../shared/class_folder/sigesp_include.php");
$io_conect=new sigesp_include();
$con=$io_conect->uf_conectar();
require_once("../shared/class_folder/class_datastore.php");
$io_dsmoneda=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
$la_emp=$_SESSION["la_empresa"];
	if (array_key_exists("operacion",$_POST))
	   {
		 $ls_operacion=$_POST["operacion"];
		 $ls_codigo="%".$_POST["txtcodigo"]."%";
	   }
	else
	   {
		 $ls_operacion="";
	   }
?>
<form name="form1" method="post" action="">
<table width="498" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="88" height="15" align="right">&nbsp;</td>
        <td width="149">&nbsp;        </td>
        <td width="159" align="right">&nbsp;</td>
      </tr>
      <tr>
        <td height="18" align="right">C&oacute;digo</td>
        <td><input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center"  maxlength="6">
        <input name="operacion" type="hidden" id="operacion"></td>
        <td align="right"><div align="left"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" onClick="ue_search()">Buscar</a></div></td>
      <tr>
        <td height="18" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right">&nbsp;</td>
  </table> 
</form>      
<div align="center">
<?php
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código</td>";
print "<td>Denominación</td>";
print "<td>Abreviatura</td>";
print "<td>País</td>";

print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
		$ls_codigo="%".$_POST["txtcodigo"]."%";
		
		$ls_sql= " SELECT sigesp_moneda.*, sigesp_pais.despai as despai".
		         " FROM sigesp_moneda, sigesp_pais ".
				 " WHERE sigesp_moneda.codmon like '".$ls_codigo."' ".
				 "   AND sigesp_moneda.codmon <> '---'".
				 "   AND sigesp_pais.codpai=sigesp_moneda.codpai".
				 " ORDER BY sigesp_moneda.codmon";
				 
		$rs_moneda=$io_sql->select($ls_sql);
		$data=$rs_moneda; 		
    	if ($row=$io_sql->fetch_row($rs_moneda))
		   {
			 $data=$io_sql->obtener_datos($rs_moneda);
			 $arrcols=array_keys($data);
			 $totcol=count($arrcols);
			 $io_dsmoneda->data=$data;
			
			 $totrow=$io_dsmoneda->getRowCount("codmon");
			 for ($z=1;$z<=$totrow;$z++)
				 {
			  	   print "<tr class=celdas-blancas>";
				   $ls_codigo=$data["codmon"][$z];				  
				   $ls_desmon=$data["denmon"][$z];
				   $ls_abremon=$data["abrmon"][$z];
				   $ls_estmon= $data["estmonpri"][$z];
				   $ls_codpais=$data["codpai"][$z];
				   $ls_denpais=$data["despai"][$z];
				   print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_desmon','$ls_abremon','$ls_estmon','$ls_codpais','$ls_denpais');\">".$ls_codigo."</a></td>";
				   print "<td  align=center>".$ls_desmon."</td>";
				   print "<td  align=center>".$ls_abremon."</td>";	
				   print "<td  align=center>".$ls_denpais."</td>";			 
				   print "</tr>";			
			     }
		   print "</table>";
		   //$io_sql->free_result($data);
		   }
		else
		 { ?>
		   <script  language="javascript">
		   alert("No se han creado Procedencias !!!");
		   </script>
		 <?php
		 }  
}
?>
</div>
</body>
<script language="JavaScript">
  function aceptar(codigo,desmon,abremon,estmon, codpai, denpai)
  {
    opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtcodigo.readOnly=true;
	opener.document.form1.txtdenmoneda.value=desmon;
	opener.document.form1.txtabrmon.value=abremon;
	opener.document.form1.txtcodpais.value=codpai;
	opener.document.form1.txtdenpais.value=denpai;
	opener.document.form1.estmoneda.value=estmon;
	opener.document.form1.status.value='C';
	
	close();
  }
  
  function ue_search()
  {
    f=document.form1;
    f.operacion.value="BUSCAR";
    f.action="sigesp_cfg_cat_moneda.php";
    f.submit();
  }
</script>
</html>