<?php
session_start();
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Agencias</title>
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
	$ls_codban=$_POST["txtcodban"];
	$ls_denban=$_POST["txtdenban"];
}
else
{
	$ls_operacion="BUSCAR";
	$ls_codban="";
	$ls_denban="";
	$ls_codigo="%%";
	$ls_denominacion="%%";

}
?>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2">Cat&aacute;logo de Agencias</td>
       </tr>
      <tr>
        <td height="13">&nbsp;</td>
        <td height="13">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431" height="22"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="denominacion" type="text" id="denominacion">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Banco</div></td>
        <td height="22"><div align="left">
          <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
          <a href="javascript:cat_bancos();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas de Gastos"></a>
          <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="65" class="sin-borde" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
    </table>
	 <div align="center"><br>
         <?php

print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
print "<tr class=titulo-celda>";
print "<td>Código </td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql="SELECT a.codage as codage,a.codban as codban,a.nomage as nomage,b.nomban as nomban 
			 FROM scb_agencias a,scb_banco b 
			 WHERE a.codemp='".$ls_codemp."' AND a.codage like '".$ls_codigo."' AND a.codban like '%".$ls_codban."%' AND a.codban=b.codban AND a.codemp=b.codemp";


			$rs_cta=$SQL->select($ls_sql);
			$data=$rs_cta;
			if($row=$SQL->fetch_row($rs_cta))
			{
					$data=$SQL->obtener_datos($rs_cta);
					$arrcols=array_keys($data);
					$totcol=count($arrcols);
					$ds->data=$data;
					$totrow=$ds->getRowCount("codban");
					
				for($z=1;$z<=$totrow;$z++)
				{
					print "<tr class=celdas-blancas>";
					$codban=$data["codban"][$z];
					$nomban=$data["nomban"][$z];
					$codigo=$data["codage"][$z];
					$nombre=$data["nomage"][$z];
					print "<td><a href=\"javascript: aceptar('$codigo','$nombre','$codban','$nomban');\">".$codigo."</a></td>";
					print "<td>".$nombre."</td>";
					print "</tr>";			
				}
			}
			else
			{
				$io_msg->message("No se han definido agencias");
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
  function aceptar(codigo,nombre,codban,nomban)
  {
    opener.document.form1.txtcodigo.value=codigo;
    opener.document.form1.txtdenominacion.value=nombre;
	opener.document.form1.txtcodban.value=codban;
	opener.document.form1.txtdenban.value=nomban;
	opener.document.form1.txtcodigo.readOnly=true;
	opener.document.form1.status.value="C";
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_scb_cat_agencias.php";
  f.submit();
  }
  function cat_bancos()
  {
	  f=document.form1;
	  pagina="sigesp_cat_bancos.php";
	  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
  }
</script>
</html>