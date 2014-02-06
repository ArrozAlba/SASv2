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
      <td width="496" colspan="2" class="titulo-celdanew">Cat&aacute;logo de Procedencias</td>
    </tr>
</table>
  <br>
<?php
require_once("class_folder/sigesp_cfg_c_procedencias.php");
$io_procedencia=new sigesp_cfg_c_procedencias();
require_once("../shared/class_folder/sigesp_include.php");
$io_conect=new sigesp_include();
$con=$io_conect->uf_conectar();
require_once("../shared/class_folder/class_datastore.php");
$io_dsprocedencia=new class_datastore();
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
print "<td>Sistema</td>";
print "<td>Operación</td>";
print "<td>Descripción</td>";
print "</tr>";

if ($ls_operacion=="BUSCAR")
   {
		$ls_codigo="%".$_POST["txtcodigo"]."%";
		$ls_sql= " SELECT * ".
		         " FROM sigesp_procedencias ".
				 " WHERE procede like '".$ls_codigo."' ";
		$rs_procedencia=$io_sql->select($ls_sql);
		$data=$rs_procedencia;
    	if ($row=$io_sql->fetch_row($rs_procedencia))
		   {
			 $data=$io_sql->obtener_datos($rs_procedencia);
			 $arrcols=array_keys($data);
			 $totcol=count($arrcols);
			 $io_dsprocedencia->data=$data;
			 $totrow=$io_dsprocedencia->getRowCount("procede");
			 for ($z=1;$z<=$totrow;$z++)
				 {
			  	   print "<tr class=celdas-blancas>";
				   $ls_codigo=$data["procede"][$z];
				   $ls_codsis=$data["codsis"][$z];
				   $ls_operacion=$data["opeproc"][$z];
				   $ls_descripcion=$data["desproc"][$z];
				   print "<td><a href=\"javascript: aceptar('$ls_codigo','$ls_codsis','$ls_operacion','$ls_descripcion');\">".$ls_codigo."</a></td>";
				   print "<td  align=center>".$data["codsis"][$z]."</td>";
				   print "<td  align=center>".$data["opeproc"][$z]."</td>";
				   print "<td  align=center>".$data["desproc"][$z]."</td>";
				   print "</tr>";			
			     }
		   print "</table>";
		   $io_sql->free_result($rs_procedencia);
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
  function aceptar(codigo,sistema,operacion,descripcion)
  {
    opener.document.form1.txtcodigo.value=codigo;
	opener.document.form1.txtcodigosistema.value=sistema;
	opener.document.form1.txtoperacion.value=operacion;
	opener.document.form1.txtdescripcion.value=descripcion;
	opener.document.form1.status.value='C';
	opener.document.form1.txtcodigo.readOnly=true;
	close();
  }
  
  function ue_search()
  {
    f=document.form1;
    f.operacion.value="BUSCAR";
    f.action="sigesp_cfg_cat_procedencia.php";
    f.submit();
  }
</script>
</html>