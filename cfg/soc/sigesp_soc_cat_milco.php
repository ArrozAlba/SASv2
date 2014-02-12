<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo Milco</title>
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
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
  </p>
  <br>
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td height="22" colspan="2" class="titulo-celda"><div align="center">Cat&aacute;logo Milco </div></td>
      </tr>
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="txtcodmil" type="text" id="txtnombre2" value="<?php $ls_codmil; ?>" size="15" maxlength="15">
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><div align="left"><input name="txtdenmil" type="text" id="txtdenmil" value="<?php $ls_denmil; ?>" size="50">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0">Buscar</a></div></td>
      </tr>
    </table>
  <br>
<?php
require_once("../../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
$ds=new class_datastore();
$io_sql=new class_sql($con);
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codmil="%".$_POST["txtcodmil"]."%";
	$ls_denmil="%".$_POST["txtdenmil"]."%";
}
else
{
	$ls_operacion="";
}
print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td width='80' align='center'>Código</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql=" SELECT * ".
	        " FROM  sigesp_catalogo_milco ".
			" WHERE codmil LIKE '".$ls_codmil."' AND ".
			"       denmil LIKE '".$ls_denmil."'";
	$rs_cta=$io_sql->select($ls_sql);
	$li_numrows=$io_sql->num_rows($rs_cta);
	if($li_numrows>0)
	{
		while($row=$io_sql->fetch_row($rs_cta))
		{
			print "<tr class=celdas-blancas>";
			$ls_codmil=$row["codmil"];
			$ls_denmil=$row["denmil"];
			print "<td align='center'><a href=\"javascript: aceptar('$ls_codmil','$ls_denmil');\">".$ls_codmil."</a></td>";
			print "<td>".$ls_denmil."</td>";
			print "</tr>";			
		}
	}
	else
	{
		$io_msg->message("No hay registros para esta busqueda");
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
  function aceptar(catalogo,denominacion)
  {

	opener.document.form1.txtcodmil.value=catalogo;
	opener.document.form1.txtdenmil.value=denominacion;
	opener.document.form1.txtcodmil.readonly=true;
	opener.document.form1.txtdenmil.readonly=true;
	close();
  }
  function ue_search()
  {
		f=document.form1;
		ls_codigo=f.txtcodmil.value;
		ls_denominacion=f.txtdenmil.value;
		f.operacion.value="BUSCAR";
		f.action="sigesp_soc_cat_milco.php";
		f.submit();
  }
</script>
</html>
