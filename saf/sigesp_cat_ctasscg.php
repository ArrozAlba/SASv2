<?php
session_start();
require_once("class_funciones_activos.php");
$io_fac= new class_funciones_activos();

if(array_key_exists("coddestino",$_POST))
{
	$ls_coddestino=$_POST["coddestino"];
	$ls_dendestino=$_POST["dendestino"];
}
else
{
	$ls_coddestino=$io_fac->uf_obtenervalor_get("coddestino","txtctacon");
	$ls_dendestino=$io_fac->uf_obtenervalor_get("dendestino","txtdenctacon");
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
        <td height="22" colspan="3" align="right"><div align="center">Cat&aacute;logo de Cuentas Contables</div></td>
      </tr>
      <tr>
        <td align="right">&nbsp;</td>
        <td height="13">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="93" height="22" align="right">Cuenta</td>
        <td width="267" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
        <td width="138" height="22"><input name="dendestino" type="hidden" id="dendestino" value="<?php print $ls_dendestino ?>">
          <input name="coddestino" type="hidden" id="coddestino" value="<?php print $ls_coddestino ?>"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22" colspan="2"><div align="left">
          <input name="nombre" type="text" id="nombre" size="70" maxlength="500">
<label></label>
<br>
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<p><br>
      <?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");

$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_sql     = new class_sql($ls_conect);
$io_msg		= new class_mensajes();
$ls_codemp  = $_SESSION["la_empresa"]["codemp"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_scgcta    = "%".$_POST["codigo"]."%";
	 $ls_denctascg = "%".$_POST["nombre"]."%";
   }
else
   {
	 $ls_operacion="";
   }

print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Cuenta Contable</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql = " SELECT sc_cuenta, denominacion, status
			      FROM scg_cuentas
			     WHERE codemp = '".$ls_codemp."' 
				   AND sc_cuenta like '".$ls_scgcta."' 
				   AND denominacion like '".$ls_denctascg."'
			     ORDER BY sc_cuenta";
	 
	 $rs_data = $io_sql->select($ls_sql);
	 if ($rs_data===false)
	    {
	      $io_msg->message("Error en Consulta, Contacte al Administrador del Sistema !!!");
	    }
 	 else
	   {
	     $li_numrows = $io_sql->num_rows($rs_data);
		 if ($li_numrows>0)
		    {
			  while ($row=$io_sql->fetch_row($rs_data))
			        {
					  $ls_scgcta 	= trim($row["sc_cuenta"]);
					  $ls_denctascg = $row["denominacion"];
					  $ls_estctascg = $row["status"];
					  if ($ls_estctascg=="S")
					     {
						   echo "<tr class=celdas-blancas>";
						   echo "<td>".$ls_scgcta."</td>";
					     }
					  else
						 {
						   echo "<tr class=celdas-azules>";
						   echo "<td><a href=\"javascript: aceptar('$ls_scgcta','$ls_denctascg','$ls_estctascg','$ls_coddestino','$ls_dendestino');\">".$ls_scgcta."</a></td>";
						}
					  echo "<td  align=left title='$ls_denctascg'>".$ls_denctascg."</td>";
					  echo "</tr>";
					}
			}
	     else
		    {
			  $io_msg->message("No se han creado Cuentas Contables !!!");
			}
	   }
   }
print "</table>";
?>
	</p>
  </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

  function aceptar(cuenta,denominacion,status,ls_coddestino,ls_dendestino)
  {
	f=document.form1;
	if(ls_coddestino!="txtctacon")
	{
		obj=eval("opener.document.form1."+ls_coddestino+"");
		obj.value=cuenta;
		obj=eval("opener.document.form1."+ls_dendestino+"");
		obj.value=denominacion;
	}
	else
	{
	opener.document.form1.txtctacon.value=cuenta;
	opener.document.form1.txtdenctacon.value=denominacion;
	}
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