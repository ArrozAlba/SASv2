<?php
session_start();
	if(array_key_exists("codact",$_GET))
	{
		$ls_codact=$_GET["codact"];
		$ls_denact=$_GET["denact"];
		$ls_ideact=$_GET["ideact"];
	}
	else
	{
		$ls_codact=$_POST["txtcodact"];
		$ls_ideact=$_POST["txtideact"];
		$ls_denact=$_POST["txtdenact"];
	}
	
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
   		global $ls_codpar,$ls_denpar,$li_monpar,$li_viduti,$li_cossal,$ls_sccuenta,$ls_denctacon;
		
		$ls_codpar="";
		$ls_denpar="";
		$li_monpar="0,00";
		$li_viduti="0,00";
		$li_cossal="0,00";
		$ls_sccuenta="";
		$ls_denctacon="";
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalle de la Modificaci&oacute;n</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
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
$io_msg= new class_mensajes();

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
		case "":
			$li_monact="";
			$ls_sccuenta="";
/*			$lb_valido=$io_saf->uf_saf_select_activocuenta($ls_codemp,$ls_codact,$ls_ideact,$li_monact,$ls_sccuenta);
			if($ls_sccuenta=="")
			{
				print "<script language=JavaScript>";
				print "close();" ;
				print "</script>";
			}*/
		
		break;

		case "VERIFICAR":
			$li_totrows=$_POST["totalfilas"];
			$ls_codact= $_POST["txtcodact"];
			$ls_denact= $_POST["txtdenact"];
			$ls_ideact= $_POST["txtideact"];
			$ls_codpar= $_POST["txtcodpar"];
			$ls_denpar= $_POST["txtdenpar"];
			$li_monpar= $_POST["txtmonpar"];
			$li_viduti= $_POST["txtviduti"];
			$li_cossal= $_POST["txtcossal"];
			$ls_sccuenta= $_POST["txtctacon"];
			$ls_denctacon= $_POST["txtdenctacon"];
			
			$lb_existe=$io_saf->uf_saf_select_parte($ls_codemp,$ls_codact,$ls_ideact,$ls_codpar);
			if($lb_existe)
			{
				$io_msg->message("La parte que desea incluir ya existe, por favor seleccionela del catálogo");
				$ls_codpar="";
			}
			
		break;
	}

?>
<table width="506" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="502" colspan="2" class="titulo-celda">Detalle de la Modificaci&oacute;n</td>
  </tr>
</table>
<form name="form1" method="post" action="">
  <table width="502" border="0" align="center" class="formato-blanco">
    <tr>
      <td height="22" colspan="4"><input name="txtdenact" type="text" class="sin-borde2" id="txtdenact" value="<?php print $ls_denact ?>" size="50" readonly>
      <input name="txtcodact" type="hidden" id="codact" value="<?php print $ls_codact?>">
      <input name="txtideact" type="hidden" id="txtideact" value="<?php print $ls_ideact?>"></td>
    </tr>
    <tr>
      <td width="110"><div align="right">Parte</div></td>
      <td height="22" colspan="3"><input name="txtcodpar" type="text" id="txtcodpar" style="text-align:center "  onBlur="javascript: ue_rellenarcampo(this,'15'); ue_verificar();" value="<?php print $ls_codpar ?>">
        <a href="javascript: ue_catapartes();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>      </td>
    </tr>
    <tr>
      <td><div align="right">Denominaci&oacute;n</div></td>
      <td height="22" colspan="3"><input name="txtdenpar" type="text" id="txtdenpar" style="text-align:left " value="<?php print $ls_denpar ?>" onKeyUp="javascript: ue_validarcomillas(this)"></td>
    </tr>
    <tr>
      <td><div align="right">Monto</div></td>
      <td height="22" colspan="3"><input name="txtmonpar" type="text" id="txtmonpar" style="text-align:right "  onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php print $li_monpar ?>"></td>
    </tr>
    <tr>
      <td><div align="right">Vida Util </div></td>
      <td height="22" colspan="3"><input name="txtviduti" type="text" id="txtviduti" style="text-align:right " onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php print $li_viduti ?>" maxlength="10">
        A&ntilde;os</td>
    </tr>
    <tr>
      <td><div align="right">Valor de Rescate </div></td>
      <td height="22" colspan="3"><input name="txtcossal" type="text" id="txtcossal" style="text-align:right " onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php print $li_cossal ?>"></td>
    </tr>
    <tr>
      <td><div align="right">Cuenta Contable</div></td>
      <td height="22" colspan="3"><input name="txtctacon" type="text" id="txtctacon" style="text-align:center " onBlur="ue_verificarcuenta();" value="<?php print $ls_sccuenta ?>" readonly>
      <a href="javascript: ue_catasccuenta();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtdenctacon" type="text" class="sin-borde" id="txtdenctacon" value="<?php print $ls_denctacon ?>" size="40" readonly></td>
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
function ue_catapartes()
{
	f=document.form1;
	ls_codact=f.txtcodact.value;
	ls_ideact=f.txtideact.value;
	window.open("sigesp_saf_cat_partes.php?codact="+ ls_codact +"&ideact="+ ls_ideact +"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
}

function  ue_catasccuenta()
{
	window.open("sigesp_cat_ctasscg.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_verificar()
{
	f=document.form1;
	ls_codpar=f.txtcodpar.value;
	if(ls_codpar!="")
	{
		f.operacion.value="VERIFICAR";
		f.action="sigesp_saf_pdt_partecont.php";
		f.submit();
	}
}

function ue_verificarcuenta()
{
	f=document.form1;
	lb_existe=false;
	totrowscg=opener.f.totalfilasscg.value;
	ls_sccuenta=f.txtctacon.value;
	if(totrowscg>1)
	{
		for(li_i=1; li_i<=totrowscg;li_i++)
		{
			ls_cuentagrid=eval("opener.document.form1.txtcontable"+li_i+".value");
			if(ls_cuentagrid==ls_sccuenta)
			{
				lb_existe=true;
				break;
			}
		}
		if(!lb_existe)
		{
			alert("No se puede utilizar otra cuenta para las partes");
		}
	}
	else
	{lb_existe=true;}
	return lb_existe;
}

function ue_agregar()
{
	f=document.form1;
	lb_valido=true;
	lb_existe=ue_verificarcuenta();
	if(lb_existe)
	{
		li_totrows=f.totalfilas.value;
		ls_codpar=f.txtcodpar.value;
		ls_codact=f.txtcodact.value;
		ls_ideact=f.txtideact.value;
		ls_denpar=f.txtdenpar.value;
		li_monpar=f.txtmonpar.value;
		li_viduti=f.txtviduti.value;
		li_cossal=f.txtcossal.value;
		ls_sccuenta=f.txtctacon.value;
		totrowscg=opener.f.totalfilasscg.value;
		for(li_i=1; li_i<=li_totrows;li_i++)
		{
			ls_codpargrid=eval("opener.document.form1.txtcodpar"+li_i+".value");
			if(ls_codpargrid==ls_codpar)
			{
				lb_valido=false;
				break;
			}
		}
		if((ls_codpar=="")||(ls_denpar=="")||(li_monpar=="")||(ls_sccuenta=="")||(li_viduti=="")||(li_cossal==""))
		{
			alert("Debe completar los datos");
			lb_valido=false;
		}
		else
		{
			if(lb_valido)
			{
				li_cossalaux=ue_formato_operaciones(li_cossal);
				li_monparaux=ue_formato_operaciones(li_monpar);
				if(parseFloat(li_cossalaux)<parseFloat(li_monparaux))
				{
					obj=eval("opener.document.form1.txtcodpar"+li_totrows+"");
					obj.value=ls_codpar;
					obj=eval("opener.document.form1.txtdenpar"+li_totrows+"");
					obj.value=ls_denpar;
					obj=eval("opener.document.form1.txtmonpar"+li_totrows+"");
					obj.value=li_monpar;
					obj=eval("opener.document.form1.txtviduti"+li_totrows+"");
					obj.value=li_viduti;
					obj=eval("opener.document.form1.txtcossal"+li_totrows+"");
					obj.value=li_cossal;
					obj=eval("opener.document.form1.txtcontable"+totrowscg+"");
					obj.value=ls_sccuenta;
					obj=eval("opener.document.form1.txtcuentaact"+totrowscg+"");
					obj.value=ls_codact;
					obj=eval("opener.document.form1.txtcuentaide"+totrowscg+"");
					obj.value=ls_ideact;
					obj=eval("opener.document.form1.txtmontocont"+totrowscg+"");
					obj.value=li_monpar;
					obj=eval("opener.document.form1.txtdebhab"+totrowscg+"");
					obj.value="H";
					obj=eval("opener.document.form1.txtdocscg"+totrowscg+"");
					obj.value=ls_codact;
					opener.document.form1.operacion.value="AGREGARDETALLE";
					opener.document.form1.submit();
					close();
				}
				else
				{
					alert("El valor de rescate no debe ser mayor que el costo de la parte");
				}
			}
			else
			{
				alert("La modificación ya esta en el movimiento");
			}
		}
	}
}
function ue_cancelar()
{
	close();
}

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
