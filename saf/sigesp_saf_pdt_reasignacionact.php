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
   		global $ls_codact,$ls_denact,$ld_fectraact,$ls_coduninew,$ls_denuninew,$ls_codresnew,$ls_nomresnew;
   		global $ls_seract,$ls_codres,$ls_nomres,$ls_coduniadm,$ls_denuniadm,$ls_ideact,$li_monact,$ls_sccuenta;
		
		$ls_codact="";
		$ls_denact="";
		$ld_fectraact="";
		$ls_coduninew="";
		$ls_denuninew="";
		$ls_codresnew="";
		$ls_nomresnew="";
		$ls_seract="";
		$ls_ideact="";
		$ls_codres="";
		$ls_nomres="";
		$ls_coduniadm="";
		$ls_denuniadm="";
		$li_monact="";
		$ls_sccuenta="";
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalle de la Reasignaci&oacute;n</title>
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
require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

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
			uf_limpiarvariables();
			$li_totrow=    $_POST["totalfilas"];
			$ls_codact=    $_POST["txtcodact"];
			$ls_denact=    $_POST["txtdenact"];
			$ls_ideact=    $_POST["txtideact"];
			$ld_fectraact = $_POST["txtfectraact"];
			
			$ls_coduniadm = $_POST["txtcoduniadm"];
			$ls_denuniadm = $_POST["txtdenuniadm"];
			$ls_codres    = $_POST["txtcodresp"];
			$ls_nomres    = $_POST["txtnomres"];

			$ls_coduninew= $_POST["txtcoduni"];
			$ls_denuninew= $_POST["txtdenuni"];
			$ls_codresnew= $_POST["txtcodresnew"];
			$ls_nomresnew= $_POST["txtnomresnew"];
			$ls_seract=	   $_POST["txtseract"];
			
			$lb_valido=$io_saf->uf_saf_select_activoreasignacion($ls_codemp,$ls_codact,$ls_seract,$ls_ideact,$ls_codres,
																 $ls_nomres,$ls_coduniadm,$ls_denuniadm,$li_monact,$ls_sccuenta);
		break;
		case "VERIFICARUNIDAD":
			$li_totrow=    $_POST["totalfilas"];
			$ls_codact=    $_POST["txtcodact"];
			$ls_denact=    $_POST["txtdenact"];
			$ls_ideact=    $_POST["txtideact"];
			$ld_fectraact= $_POST["txtfectraact"];
			$ls_coduniadm= $_POST["txtcoduniadm"];
			$ls_denuniadm= $_POST["txtdenuniadm"];
			$ls_codres=    $_POST["txtcodresp"];
			$ls_nomres=    $_POST["txtnomres"];
			$ls_coduninew= $_POST["txtcoduni"];
			$ls_denuninew= $_POST["txtdenuni"];
			$ls_codresnew= $_POST["txtcodresnew"];
			$ls_nomresnew= $_POST["txtnomresnew"];
			$li_monact=    $_POST["txtmonact"];
			$ls_sccuenta=  $_POST["txtctacon"];
			$ls_seract=    $_POST["txtseract"];
			$li_monact= str_replace(".","",$li_monact);
			$li_monact= str_replace(",",".",$li_monact);
			if($ls_sccuenta!="")
			{
				$lb_valido=$io_saf->uf_saf_select_cuentaunidad($ls_codemp,$ls_coduninew,$ls_sccuenta);
				if(!$lb_valido)
				{
					$io_msg->message("La unidad no posee asociada la cuenta contable del activo bajo la misma estructura presupuestaria");
					$ls_coduninew="";
					$ls_denuninew="";
				}
			}
		break;
	}
?>
<table width="506" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="502" colspan="2" class="titulo-celda">Detalle de la Reasignaci&oacute;n</td>
  </tr>
</table>
<form name="form1" method="post" action="" id="sigesp_saf_pdt_reasignacionact.php">
  <table width="502" border="0" align="center" class="formato-blanco">
    <tr>
      <td width="110"><div align="right">Activo</div></td>
      <td colspan="3"><input name="txtcodact" type="text" id="txtcodact" style="text-align:center " value="<?php print $ls_codact ?>" readonly>
        <a href="javascript: ue_cataactivo();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtdenact" type="text" class="sin-borde" id="txtdenact" value="<?php print $ls_denact ?>" size="40" readonly></td>
    </tr>
    <tr>
      <td><div align="right">Serial</div></td>
      <td colspan="3"><input name="txtseract" type="text" id="txtseract"  style="text-align:center " value="<?php print $ls_seract ?>" readonly></td>
    </tr>
    <tr>
      <td><div align="right">Identificador</div></td>
      <td colspan="3"><input name="txtideact" type="text" id="txtideact" value="<?php print $ls_ideact ?>" style="text-align:center "></td>
    </tr>
    <tr>
      <td><div align="right">Fecha Traslado </div></td>
      <td colspan="3"><input name="txtfectraact" type="text" id="txtfectraact" style="text-align:center " value="<?php print $ld_fectraact ?>" datepicker="true"></td>
    </tr>
    <tr>
      <td><div align="right">Unidad Actual </div></td>
      <td colspan="3"><input name="txtcoduniadm" type="text" id="txtcoduniadm" style="text-align:center " value="<?php print $ls_coduniadm ?>" readonly> 
      <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm ?>" size="40" readonly>     
      <div align="right"></div></td>
    </tr>
    <tr>
      <td><div align="right">Responsable Actual</div></td>
      <td colspan="3"><input name="txtcodresp" type="text" id="txtcodresp" style="text-align:center " value="<?php print $ls_codres ?>" readonly>
      <input name="txtcodres" type="hidden" id="txtcodres">
      <input name="txtnomres" type="text" class="sin-borde" id="txtnomres" value="<?php print $ls_nomres ?>" size="40" readonly></td>
    </tr>
    <tr>
      <td><div align="right">Unidad Nueva </div></td>
      <td colspan="3"><input name="txtcoduni" type="text" id="txtcoduni" style="text-align:center " value="<?php print $ls_coduninew ?>" readonly>
        <a href="javascript: ue_cataunidad();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtdenuni" type="text" class="sin-borde" id="txtdenuni" value="<?php print $ls_denuninew ?>" size="40" readonly></td>
    </tr>
    <tr>
      <td><div align="right">Responsable Nuevo</div></td>
      <td colspan="3"><input name="txtcodresnew" type="text" id="txtcodresnew" style="text-align:center " value="<?php print $ls_codresnew ?>" readonly>
        <a href="javascript: ue_catapersonal();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtnomresnew" type="text" class="sin-borde" id="txtnomresnew" value="<?php print $ls_nomresnew ?>" size="30" readonly></td>
    </tr>
    <tr>
      <td><div align="right">Monto Activo </div></td>
      <td colspan="3"><input name="txtmonact" type="text" id="txtmonact" value="<?php print number_format($li_monact,2,",","."); ?>" readonly style="text-align:right "></td>
    </tr>
    <tr>
      <td><div align="right">Cuenta Contable </div></td>
      <td colspan="3"><input name="txtctacon" type="text" id="txtctacon" value="<?php print $ls_sccuenta ?>" style="text-align:center ">
        <a href="javascript: ue_catacuentacontable();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
        <input name="txtdenctacon" type="text" class="sin-borde" id="txtdenctacon" size="40" readonly>
      </td>
    </tr>
    <tr>
      <td height="26"><div align="right">Observaciones</div></td>
      <td colspan="3" rowspan="2"><textarea name="txtobstra" cols="60" rows="3" id="txtobstra"></textarea></td>
    </tr>
    <tr>
      <td height="33"><div align="right"></div></td>
    </tr>
    <tr>
      <td><div align="right"></div></td>
      <td width="193" align="center"><input name="operacion" type="hidden" id="operacion">
      <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>"></td>
      <td width="105"><a href="javascript: ue_agregar();"><img src="../shared/imagebank/tools15/aprobado.gif" width="15" height="15" class="sin-borde">Agregar Detalle</a> </td>
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

function ue_cataunidad()
{
	f=document.form1;
	ls_seract=f.txtseract.value;
	if(ls_seract!="")
	{
		window.open("sigesp_saf_cat_unidadfisica.php?destino=activo","_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("Debe seleccionar el Activo a Reasignar");
	}
}

function ue_catapersonal()
{
	window.open("sigesp_cat_personalnuevo.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_agregar()
{
	f=document.form1;
	li_totrows=   f.totalfilas.value;
	ls_codact=    f.txtcodact.value;
	ls_denact=    f.txtdenact.value;
	ls_seract=    f.txtseract.value;
	ls_ideact=    f.txtideact.value;
	ld_fectraact= f.txtfectraact.value;
	ls_coduniadm= f.txtcoduniadm.value;
	ls_codres=    f.txtcodresp.value;
	ls_coduninew= f.txtcoduni.value;
	ls_codresnew= f.txtcodresnew.value;
	ls_obstra=    f.txtobstra.value;
	li_monact=    f.txtmonact.value;
	ls_sccuenta=  f.txtctacon.value;
	totrowscg=opener.f.totalfilasscg.value;

	if((ls_codact=="")||(ls_seract=="")||(ld_fectraact=="")||(ls_coduniadm=="")||(ls_codres=="")||(ls_coduninew=="")||(ls_codresnew==""))
	{
		alert("Debe completar los datos");
	}
	else
	{
		obj=eval("opener.document.form1.txtfectraact"+li_totrows+"");
		obj.value=ld_fectraact;
		obj=eval("opener.document.form1.txtcodact"+li_totrows+"");
		obj.value=ls_codact;
		obj=eval("opener.document.form1.txtdenact"+li_totrows+"");
		obj.value=ls_denact;
		obj=eval("opener.document.form1.txtidact"+li_totrows+"");
		obj.value=ls_ideact;
		obj=eval("opener.document.form1.txtobstraact"+li_totrows+"");
		obj.value=ls_obstra;
		obj=eval("opener.document.form1.txtcoduniadm"+li_totrows+"");
		obj.value=ls_coduniadm;
		obj=eval("opener.document.form1.txtcodres"+li_totrows+"");
		obj.value=ls_codres;
		obj=eval("opener.document.form1.txtcoduniadmnew"+li_totrows+"");
		obj.value=ls_coduninew;
		obj=eval("opener.document.form1.txtcodresnew"+li_totrows+"");
		obj.value=ls_codresnew;
/*		obj2=eval("opener.document.form1.txtmonto"+li_totrows+"");
		obj2.value=li_monact;
*/		obj1=eval("opener.document.form1.txtcontable"+totrowscg+"");
		obj1.value=ls_sccuenta;
/*		obj=eval("opener.document.form1.txtcontable"+totrowscg+"");
		obj.value=ls_sccuenta;*/
		obj1=eval("opener.document.form1.txtcuentaact"+totrowscg+"");
		obj1.value=ls_codact;
		obj1=eval("opener.document.form1.txtcuentaide"+totrowscg+"");
		obj1.value=ls_ideact;
		obj1=eval("opener.document.form1.txtmontocont"+totrowscg+"");
		obj1.value=li_monact;
		obj1=eval("opener.document.form1.txtdebhab"+totrowscg+"");
		obj1.value="H";
		obj1=eval("opener.document.form1.txtdocscg"+totrowscg+"");
		obj1.value=ls_ideact;
		opener.document.form1.operacion.value="AGREGARDETALLE";
		opener.document.form1.submit();
		close();
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
