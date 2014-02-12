<?php
session_start();
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Conceptos de Movimiento</title>
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
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22" colspan="2" class="titulo-celda">Cat&aacute;logo de Conceptos de Movimiento </td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="98" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="400" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo" style="text-align:center" maxlength="3">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	<br>
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
require_once("../class_folder/class_funciones_cfg.php");
$fun=new class_funciones_cfg();
$ls_destino=$fun->uf_obtenervalor_get("destino","");
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
print "<td style=text-align:center>Operación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT * FROM scb_concepto WHERE  codconmov like '".$ls_codigo."' AND denconmov like '".$ls_denominacion."' AND codconmov<>'---'";
	$rs_cta=$SQL->select($ls_sql);
	$data=$rs_cta;
	if($row=$SQL->fetch_row($rs_cta))
	{
		$data=$SQL->obtener_datos($rs_cta);
		$arrcols=array_keys($data);
		$totcol=count($arrcols);
		$ds->data=$data;
		$totrow=$ds->getRowCount("codconmov");
	
		for($z=1;$z<=$totrow;$z++)
		{
			print "<tr class=celdas-blancas>";
			$codigo=$data["codconmov"][$z];
			$denominacion=$data["denconmov"][$z];
			$codope=$data["codope"][$z];
			switch ($ls_destino)
			{
			  case "":
				print "<td style=text-align:center><a href=\"javascript: aceptar('$codigo','$denominacion','$codope');\">".$codigo."</a></td>";
				print "<td style=text-align:left>".$denominacion."</td>";
				print "<td style=text-align:center>".$codope."</td>";
				print "</tr>";
			  break;	
			  
              case "destino":
				print "<td style=text-align:center><a href=\"javascript: aceptar2('$codigo','$denominacion');\">".$codigo."</a></td>";
				print "<td style=text-align:left>".$denominacion."</td>";
				print "<td style=text-align:center>".$codope."</td>";
				print "</tr>";
			  break;

			}		
		}
	}
	else
	{
		$io_msg->message("No se han definido conceptos");
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
  function aceptar(codigo,deno,codope)
  {
    opener.document.form1.txtcodigo.value=codigo;
    opener.document.form1.txtdenominacion.value=deno;
	opener.document.form1.cmboperacion.value=codope;
	opener.document.form1.status.value="C";
	opener.document.form1.operacion.value="BUSCARDETALLE";
	opener.document.form1.txtcodigo.readOnly=true;
  	opener.document.form1.submit();
	close();
  }
  
  function aceptar2(codigo,denominacion)
  {
    opener.document.form1.txtcodigo.value=codigo;
    opener.document.form1.txtdenominacion2.value=denominacion;
	opener.document.form1.status.value="C";
	opener.document.form1.txtcodigo.readOnly=true;
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_scb_cat_conceptos.php";
  f.submit();
  }
</script>
</html>
