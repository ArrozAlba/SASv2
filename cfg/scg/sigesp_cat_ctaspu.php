<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas del Plan &Uacute;nico</title>
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
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">&nbsp;</p>
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Cuentas del Plan &Uacute;nico </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="87" height="22" style="text-align:right">Cuenta</td>
        <td width="411" height="22"><input name="codigo" type="text" id="codigo" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22"><input name="nombre" type="text" id="nombre" size="70"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
    <p>
<?php
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_sigesp_int.php");
require_once("../../shared/class_folder/class_sigesp_int_scg.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_conect);
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_scgcta    = $_POST["codigo"];
	 $ls_denscgcta = $_POST["nombre"];
   }
else
   {
	 $ls_operacion = "";
	 $ls_scgcta    = "";
	 $ls_denscgcta = "";
   }
   
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=100>Cuenta</td>";
echo "<td style=text-align:center width=400>Denominaci&oacute;n</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $ls_sql = "SELECT trim(sc_cuenta) as ctascg,rtrim(denominacion) as denctascg 
	              FROM sigesp_plan_unico 
		         WHERE sc_cuenta like '".$ls_scgcta."%' 
			       AND UPPER(denominacion) like '%".strtoupper($ls_denscgcta)."%' 
		         ORDER BY ctascg ASC";
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
			          $ls_ctascg    = $rs_data->fields["ctascg"];
			          $ls_denctascg = $rs_data->fields["denctascg"];
					  echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_ctascg','$ls_denctascg');\">".$ls_ctascg."</a></td>";
				      echo "<td style=text-align:left   width=400 title='".$ls_denctascg."'>".$ls_denctascg."</td>";
				      echo "</tr>";
                      $rs_data->MoveNext();
					}
			  }
		   else
		      {
			    $io_msg->message("No se han definido Cuentas !!!");
			  }
		 }  		 
   }
echo "</table>";
?>
    </p>
  </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(as_ctascg,as_denctascg)
{
  opener.document.form1.txtcuenta.value       = as_ctascg;
  opener.document.form1.txtdenominacion.value = as_denctascg;
  close();
}

function ue_search()
{
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_ctaspu.php";
  f.submit();
}
</script>
</html>