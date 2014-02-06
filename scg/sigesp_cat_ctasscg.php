<?php
session_start();
ini_set('max_execution_time ','0');

require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$int_scg=new class_sigesp_int_scg();
$io_msg=new class_mensajes();
$ds=new class_datastore();
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo=$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["nombre"]."%";
	$ls_obj=$_POST["obj"];
	
}
else
{
	$ls_operacion="";
	$ls_obj=$_GET["obj"];
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
      <tr>
        <td height="22" colspan="3" align="right" class="titulo-celda">Cat&aacute;logo de Cuentas Contables</td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="103" height="24" align="right">Cuenta</td>
        <td width="257"><div align="left">
          <input name="codigo" type="text" id="codigo" size="30" maxlength="25" style="text-align:center">        
        </div></td>
        <td width="138">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="65" maxlength="254">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a>
            <input name="obj" type="hidden" id="obj" value="<? print $ls_obj; ?>">
        </div></td>
      </tr>
    </table>
	<br>
    <?php

print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td style=text-align:center width=120>Cuenta</td>";
print "<td style=text-align:center width=380>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_cadena =" SELECT sc_cuenta, denominacion, status
	                FROM scg_cuentas
		   		   WHERE codemp = '".$as_codemp."'
				     AND sc_cuenta like '".$ls_codigo."' 
					 AND denominacion like '".$ls_denominacion."'
		   		   ORDER BY sc_cuenta";
		$rs_data=$SQL->select($ls_cadena);
		if($rs_data===false)
		{
        	$io_msg->message("ERROR->".$io_funciones->uf_convertirmsg($SQL->message)); 
		}
		else
		{
			$lb_existe=false;
			while($row=$SQL->fetch_row($rs_data))
			{
				$lb_existe    = true;
				$ls_scgcta    = trim($row["sc_cuenta"]);
				$ls_denctascg = $row["denominacion"];
				$ls_estctascg = $row["status"];
				if ($ls_estctascg=="S")
				   {
					 echo "<tr class=celdas-blancas>";
					 echo "<td style=text-align:center width=120>".$ls_scgcta."</td>";
				   }
				else
				   {
					 echo "<tr class=celdas-azules>";
					 echo "<td style=text-align:center width=120><a href=\"javascript: aceptar('$ls_scgcta','$ls_denctascg','$ls_estctascg');\">".$ls_scgcta."</a></td>";
				   }
				echo "<td style=text-align:left width=380 title='$ls_denctascg'>".$ls_denctascg."</td>";
				echo "</tr>";			
			}
			if($lb_existe==false)
			{
				$io_msg->message("No se han creado Cuentas Contables !!!");
			}
		}
}
print "</table>";
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(cuenta,d,status)
  {
     opener.document.form1.<?php print $ls_obj ?>.value=cuenta;
     opener.document.form1.<?php print $ls_obj ?>.readOnly=true;
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