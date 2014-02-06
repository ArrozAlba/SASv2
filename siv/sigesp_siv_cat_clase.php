<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Clase</title>
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
    <input name="txtempresa" type="hidden" id="txtempresa">
    <input name="hidstatus" type="hidden" id="hidstatus">
    <input name="txtnombrevie" type="hidden" id="txtnombrevie">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Clases </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="txtcodigo" type="text" id="txtnombre2">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left">          <input name="txtdenominacion" type="text" id="txtdenominacion">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
    <?php
require_once("../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);
$ls_codemp = $_SESSION["la_empresa"]["codemp"];
require_once("../shared/class_folder/class_funciones.php");
$io_funciones=new class_funciones();
require_once("class_funciones_inventario.php");
$io_fun_inv=new class_funciones_inventario;
$ls_codsegmento=$io_fun_inv->uf_obtenervalor_get("codseg","");
if($ls_codsegmento!="")
{
   $ls_criterio= " AND siv_clase.codseg='".$ls_codsegmento."' AND "; 
}else
{
  $ls_criterio= " AND";
}		

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codigo="%".$_POST["txtcodigo"]."%";
	$ls_denominacion="%".$_POST["txtdenominacion"]."%";	
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codigo="%%";
	$ls_denominacion="%%";	
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código Segmento</td>";
print "<td>Código Familia</td>";
print "<td>Código Clase</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT siv_clase.*,siv_familia.codseg,siv_segmento.codseg ".
            "  FROM siv_clase,siv_familia,siv_segmento ".
			" WHERE siv_clase.codemp='".$ls_codemp."'".
			"   AND siv_familia.codemp=siv_segmento.codemp ".
			"   AND siv_clase.codemp=siv_familia.codemp ".
			"   AND siv_familia.codseg=siv_segmento.codseg ".
			"   AND siv_familia.codseg=siv_clase.codseg ".
			"   AND siv_familia.codfami=siv_clase.codfami ".$ls_criterio.
			"   siv_clase.codclase like '".$ls_codigo."'".
			"   AND siv_clase.desclase like '".$ls_denominacion."'".
			" ORDER BY siv_clase.codclase";
    $rs_data=$io_sql->select($ls_sql);
    if($rs_data===false)
		{
        	$io_msg->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				print "<tr class=celdas-blancas>";
				$ls_codseg=$row["codseg"];
				$ls_codfami=$row["codfami"];
				$ls_codclase=$row["codclase"];
				$ls_denominacion=$row["desclase"];
				print "<td><a href=\"javascript: aceptar('$ls_codseg','$ls_denominacion','$ls_codfami','$ls_codclase');\">".$ls_codseg."</a></td>";
				print "<td>".$ls_codfami."</td>";
				print "<td>".$ls_codclase."</td>";
				print "<td>".$ls_denominacion."</td>";
				print "</tr>";			
			}
			$io_sql->free_result($rs_data);
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
	function aceptar(ls_codseg,ls_denominacion,ls_codfami,ls_codclase)
	{
		opener.document.form1.txtcodseg.value=ls_codseg;
		opener.document.form1.txtcodseg.readOnly=true;
		opener.document.form1.txtcodfam.value=ls_codfami;
		opener.document.form1.txtcodfam.readOnly=true;
		opener.document.form1.txtcodclase.value=ls_codclase;
		opener.document.form1.txtcodclase.readOnly=true;
		opener.document.form1.txtdesclase.value=ls_denominacion;
		opener.document.form1.existe.value="TRUE";
		opener.document.form1.operacion.value=" ";
		close();
	}
	function ue_search()
	{
		f=document.form1;
		f.operacion.value="BUSCAR";
		f.action="sigesp_siv_cat_clase.php?codseg=<?PHP print $ls_codsegmento;?>";
		f.submit();
	}
</script>
</html>

