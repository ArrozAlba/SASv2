<?php
session_start();
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Bancos SIGECOF</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
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
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="contorno" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2" class="titulo-celda">Cat&aacute;logo de Bancos SIGECOF </td>
       </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr class="formato-blanco">
        <td width="90" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="408" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" size="6" maxlength="3">        
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion" size="70" maxlength="254">
        </div></td>
      </tr>
      <tr class="formato-blanco">
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
<?php
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
$io_include = new sigesp_include();
$ls_conect  = $io_include->uf_conectar();
$io_msg     = new class_mensajes();
$io_sql		= new class_sql($ls_conect);
$ls_codemp  = $arr["codemp"];

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion = $_POST["operacion"];
	$ls_codbansig = "%".$_POST["codigo"]."%";
	$ls_denbansig = "%".$_POST["denominacion"]."%";
}
else
{
	$ls_operacion = "BUSCAR";
	$ls_codbansig = "%%";
	$ls_denbansig = "%%";
}
echo "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
echo "<tr class=titulo-celda>";
echo "<td width=80  height=22 style=text-align:center>Código</td>";
echo "<td width=420 height=22 style=text-align:center>Denominación</td>";
echo "</tr>";
if ($ls_operacion=="BUSCAR")
   {
     $ls_sql = " SELECT codbansig,denbansig
	               FROM sigesp_banco_sigecof
			      WHERE codbansig like '".$ls_codbansig."'
				    AND denbansig like '".$ls_denbansig."'
					AND codbansig<>'---'";
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
					   echo "<tr class=celdas-blancas>";
					   $ls_codbansig = $row["codbansig"];
					   $ls_denbansig = $row["denbansig"];
					   echo "<td width=80  style=text-align:center><a href=\"javascript: aceptar('$ls_codbansig','$ls_denbansig');\">".$ls_codbansig."</a></td>";
					   echo "<td width=420 style=text-align:left title='".ltrim($ls_denbansig)."'>".$ls_denbansig."</td>";
					   echo "</tr>";
					 }
             }
          else
		     {
			   $io_msg->message("No se han definido Bancos !!!");
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
  function aceptar(codigo,deno)
  {
    opener.document.form1.txtcodbancof.value=codigo;
    opener.document.form1.txtnombancof.value=deno;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_rpc_cat_bancos_sigecof.php";
  f.submit();
  }
</script>
</html>