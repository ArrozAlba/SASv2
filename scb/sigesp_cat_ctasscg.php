<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "opener.document.form1.submit();";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Contables</title>
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
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="3" style="text-align:center">Cat&aacute;logo de Cuentas Contables</td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td width="272" height="13">&nbsp;</td>
        <td width="138" height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="88" height="22" align="right">Cuenta</td>
        <td height="22" colspan="2"><div align="left">
          <input name="codigo" type="text" id="codigo" size="35" maxlength="25" style="text-align:center">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22" colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="70" maxlength="254">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_msg     = new class_mensajes(); 

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_scgcta    = $_POST["codigo"]."%";
	 $ls_denctascg = "%".$_POST["nombre"]."%";
   }
else
   {
	 $ls_operacion = "";
   }
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td style=text-align:center width=100>Cuenta</td>";
echo "<td style=text-align:center width=400>Denominación</td>";
echo "</tr>";

if ($ls_operacion=="BUSCAR") 
   {
	 $ls_sql =  " SELECT TRIM(sc_cuenta) as sc_cuenta, denominacion, status
	                FROM scg_cuentas
		           WHERE codemp = '".$_SESSION["la_empresa"]["codemp"]."' 
				     AND sc_cuenta like '".$ls_scgcta."'
				     AND denominacion like '".$ls_denctascg."'
				   ORDER BY sc_cuenta ASC";

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
					  $ls_scgcue = $rs_data->fields["sc_cuenta"];
					  $ls_dencue = $rs_data->fields["denominacion"];
					  $ls_estcue = trim($rs_data->fields["status"]);
					  if ($ls_estcue=="S")
						 {
						   echo "<tr class=celdas-blancas>";
						   echo "<td style=text-align:center width=100>".$ls_scgcue."</td>";
						 }
					  else
						 {
						   echo "<tr class=celdas-azules>";
						   echo "<td style=text-align:center width=100><a href=\"javascript: aceptar('$ls_scgcue','$ls_dencue');\">".$ls_scgcue."</a></td>";
						 }
					  echo "<td style=text-align:left width=400 title='".$ls_dencue."'>".$ls_dencue."</td>";
					  echo "</tr>";	
					  $rs_data->MoveNext();		
					}
			 }
		  else
		     {
			   $io_msg->message("No se han creado Cuentas Contables !!!");
			 }
		}	 
   }
echo "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(as_scgcta,as_denctascg)
{
  opener.document.form1.txtcuenta.value		  = as_scgcta;
  opener.document.form1.txtdenominacion.value = as_denctascg;
  close();
}

function ue_search()
{
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_ctasscg.php";
  f.submit();
}
</script>
</html>