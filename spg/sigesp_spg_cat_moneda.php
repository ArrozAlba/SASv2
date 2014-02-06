<?php
session_start();
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql		= new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion=$_POST["operacion"];
	 $ls_codigo="%".$_POST["codigo"]."%";
	 $ls_denominacion="%".$_POST["denominacion"]."%";	
   }
else
   {
	 $ls_operacion="";	
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Tipo de Moneda</title>
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
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
  	 <br>
	 <table width="564" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Tipo de Moneda </td>
       </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="111" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="451" height="22" style="text-align:left"><input name="codigo" type="text" id="codigo"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" style="text-align:left"><input name="denominacion" type="text" id="denominacion" size="60"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	 <p align="center"><br>
<?php
echo "<table width=560 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=100>C&oacute;digo</td>";
echo "<td style=text-align:center width=460>Denominaci&oacute;n</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
	 $ls_sql = "SELECT codmon,denmon FROM sigesp_moneda
				 WHERE codmon like '".$ls_codigo."'
				   AND UPPER(denmon) like '".strtoupper($ls_denominacion)."'
			       AND codmon<>'---'";
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
			           $ls_codmon = $rs_data->fields["codmon"];
			           $ls_denmon = rtrim($rs_data->fields["denmon"]);
					   echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_codmon','$ls_denmon');\">".$ls_codmon."</a></td>";
				       echo "<td style=text-align:left title='".$ls_denmon."' width=460>".$ls_denmon."</td>";
					   echo "</tr>";
                       $rs_data->MoveNext();
					 }
			  }
		   else
		      {
			    $io_msg->message("No se han definido Monedas !!!");  
			  }
		 }  		 
   }
echo "</table>";
?>
	   </div>
  </p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codigo,deno)
  {
    opener.document.form1.txtmoneda.value=codigo;
    opener.document.form1.txtdenmoneda.value=deno;	
	close();
  }
  
function ue_search()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.action="sigesp_spg_cat_moneda.php";
	f.submit();
}  

</script>
</html>
