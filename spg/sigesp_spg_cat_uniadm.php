<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo Unidades Administradoras</title>
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
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");

$io_conect = new sigesp_include();
$ls_conect = $io_conect->uf_conectar();
$io_sql    = new class_sql($ls_conect);
$io_msg    = new class_mensajes();
$ls_codemp = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion       = $_POST["operacion"];
	 $ls_codigo          = $_POST["txtcodigo"];
	 $ls_denominacion    = $_POST["txtdenominacion"];
	 
   }
else
   {
      $ls_operacion       = "";
	  $ls_codigo          = "";
	  $ls_denominacion    = "";
   }
?>
<form name="form1" method="post" action="">
<br>
<table width="411" border="0" align="center" cellpadding="1" cellspacing="1"  class="formato-blanco"><tr>  
   <td height="22" colspan="2" class="titulo-celda"><span style="text-align:center">
     <input name="operacion" type="hidden" id="operacion">
   </span>Cat&aacute;logo Unidades Administradoras </td>   
  </tr>
      <tr>
        <td height="15" align="right">&nbsp;</td>
        <td height="15" colspan="2" style="text-align:left">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" align="right">C&oacute;digo</td>
        <td height="22" colspan="2" style="text-align:left"><input name="txtcodigo" type="text" id="txtcodigo" maxlength="5" style="text-align:center"></td>
	  </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td width="313" height="22" align="left"><input name="txtdenominacion" type="text" id="txtdenominacion" style="text-align:left" size="50" maxlength="30" ></td>
      </tr>
      <tr>
        <td height="22" colspan="6" style="text-align:right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar..." width="20" height="20" border="0">Buscar</a></td>
      </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13" colspan="5">&nbsp;</td>
      </tr>
</table>
<div align="center"><br>
<?php
if ($ls_operacion=="BUSCAR")
   {
	  echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
	  echo "<tr class=titulo-celda>";
	  echo "<td style=text-align:center width=100>C&oacute;digo</td>";
	  echo "<td style=text-align:center width=400>Denominaci&oacute;n</td>";
	  echo "</tr>";
	  
	  $ls_sql = "SELECT coduac,denuac 
	               FROM spg_ministerio_ua
		          WHERE codemp='".$ls_codemp."'
		            AND coduac <>'-----'
					AND coduac like '%".$ls_codigo."%'
					AND UPPER(denuac) like '%".strtoupper($ls_denominacion)."%'
				  ORDER BY coduac ASC";
	  $rs_data = $io_sql->select($ls_sql);
	  if ($rs_data===false)
	     {
		   $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
		 }
      else
	     {
		   $li_totrows = $io_sql->num_rows($rs_data);
		   if ($li_totrows>0)
		      {
			    while(!$rs_data->EOF)
				     {
					   echo "<tr class=celdas-blancas>";
			           $ls_coduac = $rs_data->fields["coduac"];
			           $ls_denuac = rtrim($rs_data->fields["denuac"]);
					   echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_coduac','$ls_denuac');\">".$ls_coduac."</a></td>";
				       echo "<td style=text-align:left title='".$ls_denuac."' width=400>".$ls_denuac."</td>";
					   echo "</tr>";
                       $rs_data->MoveNext();
					 }
			  }
		   else
		      {
			    $io_msg->message("No se han creado Unidades Administradoras !!!");  
			  }
		 }  		 
   }
?>
  </table>
</div>
</form>
</body>
<script language="JavaScript">
  function aceptar(codigo,denominacion)
  {
    fop                         = opener.document.form1;
	fop.txtuniadm.value         = codigo;
    fop.txtuniadm.readOnly      = true;
	fop.txtdenuni.value   = denominacion;	
	close();
  }
  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_spg_cat_uniadm.php";
	  f.submit();
  }
</script>
</html>