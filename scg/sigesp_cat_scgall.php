<?php
session_start();
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
$io_include = new sigesp_include();
$io_msg     = new class_mensajes();
$ls_conect  = $io_include->uf_conectar();
$int_scg    = new class_sigesp_int_scg();
$io_sql		= new class_sql($ls_conect);
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

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
  <p align="center">&nbsp;</p>
  <br>
  <div align="center">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="3" style="text-align:center"><input name="operacion" type="hidden" id="operacion">Cat&aacute;logo de Cuentas Contables</td>
      </tr>
      <tr>
        <td height="13" align="right">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="102" height="22" align="right">Cuenta</td>
        <td width="258" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="30" maxlength="25" style="text-align:center">        
        </div></td>
        <td width="138" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" colspan="2"><div align="left">
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
if ($ls_operacion=="BUSCAR")
   {
     $ls_sql = "SELECT trim(sc_cuenta) as sc_cuenta, denominacion, status
	              FROM scg_cuentas
		         WHERE codemp = '".$ls_codemp."' 
				   AND sc_cuenta like '".$ls_codigo."' 
				   AND UPPER(denominacion) like '".strtoupper($ls_denominacion)."'
				 ORDER BY sc_cuenta";
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
					  $ls_scgcta    = trim($rs_data->fields["sc_cuenta"]);
					  $ls_denctascg = $rs_data->fields["denominacion"];
					  $ls_estctascg = $rs_data->fields["status"];
					  if ($ls_estctascg=="S")
						 {
						   echo "<tr class=celdas-blancas>";
						   echo "<td style=text-align:center width=120>".$ls_scgcta."</td>";
						 }
					  else
						 {
						   echo "<tr class=celdas-azules>";
						   echo "<td style=text-align:center width=120><a href=\"javascript: aceptar('$ls_scgcta');\">".$ls_scgcta."</a></td>";
						 }
					  echo "<td style=text-align:left width=380 title='$ls_denctascg'>".$ls_denctascg."</td>";
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
function aceptar(as_scgcta)
{
  opener.document.form1.<? print $ls_obj ?>.value	 = as_scgcta;
  opener.document.form1.<? print $ls_obj ?>.readOnly = true;
  close();
}

function ue_search()
{
  f = document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_cat_scgall.php";
  f.submit();
}
</script>
</html>