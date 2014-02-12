<?php
session_start();
require_once("../../shared/class_folder/sigesp_include.php");
$in=new sigesp_include();
$con=$in->uf_conectar();
$dat=$_SESSION["la_empresa"];
require_once("../../shared/class_folder/class_funciones.php");
$fun=new class_funciones();
require_once("../../shared/class_folder/class_datastore.php");
$ds=new class_datastore();
require_once("../../shared/class_folder/class_sql.php");
$SQL=new class_sql($con);
$arr=$_SESSION["la_empresa"];
$as_codemp=$arr["codemp"];


if(array_key_exists("operacion",$_POST))
{
  $ls_operacion=$_POST["operacion"];
 }
else
{
  $ls_operacion="NUEVO";
}


if(array_key_exists("codigo",$_POST))
{
  $ls_codigo=$_POST["codigo"];
 }
else
{
  $ls_codigo="";
}

if(array_key_exists("nombre",$_POST))
{
  $ls_denominacion=$_POST["nombre"];
 }
else
{
  $ls_denominacion="";
}

if(array_key_exists("txtcuentascg",$_POST))
{
  $ls_codscg=$_POST["txtcuentascg"];
 }
else
{
  $ls_codscg="";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo de Cuentas Presupuestarias</title>
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
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="22" colspan="2" class="titulo-celda"><div align="center">Cat&aacute;logo de Cuentas Presupuestaria</div></td>
    </tr>
  </table>
  <br>
  <div align="center">
    <table width="490" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="135" height="22" align="right"><div align="right">Codigo&nbsp;</div></td>
        <td width="122" height="22">          <input name="codigo" type="text" id="codigo" value="<?php print $ls_codigo?>" size="22" maxlength="20">        
        </td>
        <td width="341" height="22" colspan="4"><div align="left"></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n&nbsp;</div></td>
        <td height="22" colspan="5"><div align="left">
          <input name="nombre" type="text" id="nombre" value="<?php print $ls_denominacion?>" size="72">
<label></label>
<br>
          </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cuenta Contable&nbsp;</div></td>
        <td height="22" colspan="5"><div align="left">
          <input name="txtcuentascg" type="text" id="txtcuentascg" value="<?php print $ls_codscg;?>" size="22" maxlength="20">
</div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
        <td height="22" colspan="4"><div align="right"><a href="javascript: ue_search();">        
          <input name="operacion" type="hidden" class="formato-blanco" id="operacion"  value="<?php print $ls_operacion?>">
        <img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar </a></div></td>
      </tr>
    </table>
	<br>
    <?php
print "<table width=490 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Presupuestaria</td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	//*,(asignado-(comprometido+precomprometido)+aumento-disminucion) as disponible **  CAMBIADO 02/02/2007 POR AGUAS DE MERIDA **
	$ls_cadena =" SELECT max(spg_cuenta) as spg_cuenta,max(denominacion) as denominacion ,max(status) as status ".
	            " FROM   spg_cuentas ".
		   		" WHERE  codemp = '".$as_codemp."' AND spg_cuenta like '%".$ls_codigo."%' AND ".
				"        denominacion like '%".$ls_denominacion."%'  AND  sc_cuenta like '%".$ls_codscg."%' AND status='C' ".
				" GROUP BY  spg_cuenta ORDER BY spg_cuenta, denominacion,status  ";
	$rs_cta=$SQL->select($ls_cadena);
	if($rs_cta===false)
	{
		$msg->message($fun->uf_convertirmsg($SQL->message));
		
	}
	else
	{
		while($row=$SQL->fetch_row($rs_cta))
		{
			$cuenta=trim($row["spg_cuenta"]);
			$denominacion=trim($row["denominacion"]);
			$status=trim($row["status"]);
			if($status=="S")
			{
				print "<tr class=celdas-blancas>";
				print "<td>".$cuenta."</td>";
				print "<td  align=left>".$denominacion."</td>";				
			}
			else
			{
				print "<tr class=celdas-azules>";
				print "<td><a href=\"javascript: aceptar('$cuenta','$denominacion');\">".$cuenta."</a></td>";
				print "<td  align=left>".$denominacion."</td>";
			}
			print "</tr>";			
		 }
		$SQL->free_result($rs_cta);
		$SQL->close();
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

  function aceptar(cuenta,denominacion)
  {
    opener.document.form1.txtcuenta.value=cuenta;	
    opener.document.form1.txtcuenta.readOnly=true;	
	opener.document.form1.txtdencuenta.value=denominacion;	
	close();
  }

  function ue_search()
  {
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_sep_cat_ctasspg.php";
	  f.submit();
  }
	function uf_cambio_estprog1()
	{
		f=document.form1;
		f.action="sigesp_sep_cat_ctasspg.php";
		f.operacion.value="est1";
		f.submit();
	}
	function uf_cambio_estprog2()
	{
		f=document.form1;
		f.action="sigesp_sep_cat_ctasspg.php";
		f.operacion.value="est2";
		f.submit();
	}
</script>
</html>