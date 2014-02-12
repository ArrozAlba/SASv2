<?php
session_start();
$arr=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Bancos</title>
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
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="496" colspan="2" class="titulo-celda">Cat&aacute;logo de Bancos </td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="codigo" type="text" id="codigo">        
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="denominacion" type="text" id="denominacion">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
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
print "<td>Código </td>";
print "<td>Denominación</td>";
print "</tr>";
if($ls_operacion=="BUSCAR")
{
	$ls_sql=" SELECT * ".
	        " FROM scb_banco  ".
			" WHERE codemp='".$ls_codemp."' AND codban like '".$ls_codigo."' ORDER BY codban";


			$rs_banco=$SQL->select($ls_sql);
			$data=$rs_banco;
			if(($rs_banco===false))
			{
				$io_msg->message("No hay registros");
			}
			else
			{
			
				if($row=$SQL->fetch_row($rs_banco))
				{
						$data=$SQL->obtener_datos($rs_banco);
						$arrcols=array_keys($data);
						$totcol=count($arrcols);
						$ds->data=$data;
						$totrow=$ds->getRowCount("codban");
						
					for($z=1;$z<=$totrow;$z++)
					{
						print "<tr class=celdas-blancas>";
						$codigo=$data["codban"][$z];
						$nombre=$data["nomban"][$z];
						$direccion=$data["dirban"][$z];
						$gerente=$data["gerban"][$z];
						$telefono=$data["telban"][$z];
						$email=$data["conban"][$z];
						$movil=$data["movcon"][$z];
						$esttesnac=$data["esttesnac"][$z];
						$codsudeban=$data["codsudeban"][$z];
						print "<td><a href=\"javascript: aceptar('$codigo','$nombre','$direccion','$gerente','$telefono','$movil','$email','$esttesnac','$codsudeban');\">".$codigo."</a></td>";
						print "<td>".$nombre."</td>";
						print "</tr>";			
					}
				}
				else
				{
					$io_msg->message("No se han definido bancos");
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
  function aceptar(codigo,deno,direccion,gerente,telefono,celular,email,esttesnac,codsudeban)
  {
    opener.document.form1.txtcodigo.value=codigo;
    opener.document.form1.txtnombre.value=deno;
	opener.document.form1.txtdireccion.value=direccion;
	opener.document.form1.txtgerente.value=gerente;
	opener.document.form1.txttelefono.value=telefono;
	opener.document.form1.txtcelular.value=celular;
	opener.document.form1.txtemail.value=email;
	opener.document.form1.txtcodsude.value=codsudeban;
	opener.document.form1.status.value='C';
	opener.document.form1.txtcodigo.readOnly=true;
	if(esttesnac==1)
	{
		opener.document.form1.chktesoreria.checked=true;
	}
	close();
  }
  function ue_search()
  {
  f=document.form1;
  f.operacion.value="BUSCAR";
  f.action="sigesp_scb_cat_bancos.php";
  f.submit();
  }
</script>
</html>
