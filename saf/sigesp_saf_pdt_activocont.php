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
   		global $ls_codact,$ls_denact,$li_monact,$ls_seract,$ls_ideact,$ls_sccuenta;
		
		$ls_codact="";
		$ls_denact="";
		$li_monact="";
		$ls_seract="";
		$ls_ideact="";
		$ls_sccuenta="";
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
			$ls_ideact= $_POST["txtideact"];
			$ls_seract= $_POST["txtseract"];
			$ls_denact= $_POST["txtdenact"];
			$ls_sccuenta="";
			$li_monact="";
			
			$lb_valido=$io_saf->uf_saf_select_activocuenta($ls_codemp,$ls_codact,$ls_ideact,$li_monact,$ls_sccuenta);
		break;
	}
?>
<form name="form1" method="post" action="">
  <table width="502" border="0" align="center" class="formato-blanco">
    <tr>
      <td height="22" colspan="4" class="titulo-ventana">Detalle de Activo </td>
    </tr>
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
      <td><div align="right">Identidicaci&oacute;n</div></td>
      <td height="22" colspan="3"><input name="txtideact" type="text" id="txtideact" style="text-align:center " value="<?php print $ls_ideact ?>" readonly></td>
    </tr>
    <tr>
      <td><div align="right">Monto Activo</div></td>
      <td height="22" colspan="3"><input name="txtmonact" type="text" id="txtmonact" value="<?php print number_format($li_monact,2,",","."); ?>" readonly style="text-align:right "></td>
    </tr>
    <tr>
      <td><div align="right">Cuenta Contable</div></td>
      <td height="22" colspan="3"><input name="txtctacon" type="text" id="txtctacon" value="<?php print $ls_sccuenta ?>" style="text-align:center " readonly>
      <a href="javascript: ue_catacuentacontable();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtdenctacon" type="text" class="sin-borde" id="txtdenctacon" size="40" readonly>
      </td>
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
	ls_estact="IM";
	window.open("sigesp_saf_cat_codactivo.php?estact="+ls_estact+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_catacuentacontable()
{
	window.open("sigesp_cat_ctasscg.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=yes");
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
	ls_sccuenta=f.txtctacon.value;
	totrowscg=opener.f.totalfilasscg.value;
	
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
		if((ls_codact=="")||(ls_ideact=="")||(li_monact=="")||(ls_sccuenta==""))
		{
			alert("Debe completar los datos");
		}
		else
		{
			obj=eval("opener.document.form1.txtcodact"+li_totrows+"");
			obj.value=ls_codact;
			obj=eval("opener.document.form1.txtdenact"+li_totrows+"");
			obj.value=ls_denact;
			obj=eval("opener.document.form1.txtidact"+li_totrows+"");
			obj.value=ls_ideact;
			obj=eval("opener.document.form1.txtdesmov"+li_totrows+"");
			obj.value=ls_obstra;
			obj=eval("opener.document.form1.txtmonact"+li_totrows+"");
			obj.value=li_monact;
			obj=eval("opener.document.form1.txtcontable"+totrowscg+"");
			obj.value=ls_sccuenta;
			obj=eval("opener.document.form1.txtcuentaact"+totrowscg+"");
			obj.value=ls_codact;
			obj=eval("opener.document.form1.txtcuentaide"+totrowscg+"");
			obj.value=ls_ideact;
			obj=eval("opener.document.form1.txtmontocont"+totrowscg+"");
			obj.value=li_monact;
			obj=eval("opener.document.form1.txtdebhab"+totrowscg+"");
			obj.value="H";
			obj=eval("opener.document.form1.txtdocscg"+totrowscg+"");
			obj.value=ls_ideact;
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
