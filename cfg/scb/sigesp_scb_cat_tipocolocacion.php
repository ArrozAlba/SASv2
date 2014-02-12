<?php
session_start();
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Tipos de Colocaciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="21" colspan="2"><input name="operacion" type="hidden" id="operacion">
        Cat&aacute;logo de Tipos de Colocaciones</td>
       </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="100" height="21"><div align="right">C&oacute;digo</div></td>
        <td width="398" height="21"><div align="left">
          <input name="codigo" type="text" id="codigo" style="text-align:center" maxlength="3">        
        </div></td>
      </tr>
      <tr>
        <td height="21"><div align="right">Nombre</div></td>
        <td height="21"><div align="left">
          <input name="denominacion" type="text" id="denominacion">
        </div></td>
      </tr>
      <tr>
        <td height="21">&nbsp;</td>
        <td height="21"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	 <div align="center"><br>
         <?php
require_once("../../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$ds=new class_datastore();
$ls_codemp=$arr["codemp"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["codigo"]."%";
	$ls_denominacion="%".$_POST["denominacion"]."%";
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codigo="%%";
	$ls_denominacion="%%";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td style=text-align:center>Código </td>";
print "<td style=text-align:center>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT * FROM scb_tipocolocacion WHERE  codtipcol like '".$ls_codigo."' AND nomtipcol like '".$ls_denominacion."' ";
	$rs_cta=$SQL->select($ls_sql);
	$data=$rs_cta;
	if($row=$SQL->fetch_row($rs_cta))
	{
		$data=$SQL->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("codtipcol");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$codigo=$data["codtipcol"][$z];
			$denominacion=$data["nomtipcol"][$z];
			print "<td style=text-align:center><a href=\"javascript: aceptar('$codigo','$denominacion');\">".$codigo."</a></td>";
			print "<td style=text-align:left>".$denominacion."</td>";
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No se han definido tipos de colocación");
	}
}
print "</table>";
?>
       </div>
     </div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
  function aceptar(codigo,deno)
  {
    opener.document.form1.txtcodigotipcol.value=codigo;
    opener.document.form1.txtdenotipcol.value=deno;
	opener.document.form1.status.value='C';
	close();
  }
  function ue_search()
  {
 	 f=document.form1;
	 f.operacion.value="BUSCAR";
	 f.action="sigesp_scb_cat_tipocolocacion.php";
	 f.submit();
  }
</script>
</html>
