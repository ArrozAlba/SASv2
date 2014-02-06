<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";		
}
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codact,$ls_denact,$li_monact,$ls_seract,$ls_ideact;
		
		$ls_codact="";
		$ls_denact="";
		$li_monact="";
		$ls_seract="";
		$ls_ideact="";
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalle de Activo </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
require_once("sigesp_saf_c_movimiento.php");
$io_saf=  new sigesp_saf_c_movimiento();

$arre=$_SESSION["la_empresa"];
$ls_codemp=$arre["codemp"];

	if (array_key_exists("totrow",$_GET))
	{
		$li_totrows=$_GET["totrow"];
	}
	else
	{
		$li_totrows="";
	}

	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		uf_limpiarvariables();
	}

	switch ($ls_operacion) 
	{
		case "BUSCARACTIVO":
			$li_totrow=$_POST["totalfilas"];
			$ls_codact= $_POST["txtcodact"];
			$ls_seract= $_POST["txtseract"];
			$ls_ideact= $_POST["txtideact"];
			$ls_denact= $_POST["txtdenact"];
			$li_monact="";
			
			$lb_valido=$io_saf->uf_saf_select_activo($ls_codemp,$ls_codact,$li_monact);
		break;
	}
?>
<table width="506" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="502" colspan="2" class="titulo-celda">Detalle de Activo </td>
  </tr>
</table>
<form name="form1" method="post" action="">
  <table width="502" border="0" align="center" class="formato-blanco">
    <tr>
      <td width="110"><div align="right">Activo</div></td>
      <td height="22" colspan="3"><input name="txtcodact" type="text" id="txtcodact" style="text-align:center " value="<?php print $ls_codact ?>" readonly>
        <a href="javascript: ue_cataactivo();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtdenact" type="text" class="sin-borde" id="txtdenact" value="<?php print $ls_denact ?>" size="40" readonly></td>
    </tr>
    <tr>
      <td><div align="right">Serial</div></td>
      <td height="22" colspan="3"><input name="txtseract" type="text" id="txtseract" style="text-align:center " value="<?php print $ls_seract ?>" readonly></td>
    </tr>
    <tr>
      <td><div align="right">Identificaci&oacute;n</div></td>
      <td height="22" colspan="3"><input name="txtideact" type="text" id="txtideact" style="text-align:center " value="<?php print $ls_ideact ?>"></td>
    </tr>
    <tr>
      <td><div align="right">Monto Activo</div></td>
      <td height="22" colspan="3"><input name="txtmonact" type="text" id="txtmonact" value="<?php print number_format($li_monact,2,",","."); ?>" readonly style="text-align:right "></td>
    </tr>
    <tr>
      <td><div align="right">Observaciones</div></td>
      <td colspan="3" rowspan="2"><textarea name="txtobstra" cols="60" rows="3" id="txtobstra"></textarea></td>
    </tr>
    <tr>
      <td height="33"><div align="right"></div></td>
    </tr>
    <tr>
      <td><div align="right"></div></td>
      <td width="186" align="center"><input name="operacion" type="hidden" id="operacion">
      <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>"></td>
      <td width="112"><a href="javascript: ue_agregar();"><img src="../shared/imagebank/tools15/aprobado.gif" width="15" height="15" class="sin-borde">Agregar Detalle</a> </td>
      <td width="74"><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/tools15/eliminar.gif" width="15" height="15" class="sin-borde">Cancelar</a></td>
    </tr>
  </table>
</form>
</body>
<script language="javascript">
function ue_cataactivo()
{
	ls_estact="RD";
	window.open("sigesp_saf_cat_codactivo.php?estact="+ls_estact+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_agregar()
{
	f=document.form1;
	lb_valido=true;
	li_totrows=f.totalfilas.value;
	ls_codact=f.txtcodact.value;
	ls_denact=f.txtdenact.value;
	ls_ideact=f.txtideact.value;
	li_monact=f.txtmonact.value;
	ls_obstra=f.txtobstra.value;
	for(li_i=1; li_i<=li_totrows;li_i++)
	{
		ls_codactgrid=eval("opener.document.form1.txtcodact"+li_i+".value");
		ls_ideactgrid=eval("opener.document.form1.txtidact"+li_i+".value");
		if((ls_codactgrid==ls_codact)&&(ls_ideactgrid==ls_ideact))
		{
			lb_valido=false;
			break;
		}
	}
	if(lb_valido)
	{
		if((ls_codact=="")||(ls_ideact==""))
		{
			alert("Debe completar los datos");
		}
		else
		{
			obj=eval("opener.document.form1.txtdenact"+li_totrows+"");
			obj.value=ls_denact;
			obj=eval("opener.document.form1.txtcodact"+li_totrows+"");
			obj.value=ls_codact;
			obj=eval("opener.document.form1.txtidact"+li_totrows+"");
			obj.value=ls_ideact;
			obj=eval("opener.document.form1.txtdesmov"+li_totrows+"");
			obj.value=ls_obstra;
			obj=eval("opener.document.form1.txtmonact"+li_totrows+"");
			obj.value=li_monact;
			opener.document.form1.operacion.value="AGREGARDETALLE";
			opener.document.form1.submit();
			close();
		}
	}
	else
	{
		alert("El activo ya esta en el movimiento");
	}
}
function ue_cancelar()
{
	close();
}

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script language="javascript" src="js/funciones.js"></script>
</html>