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
   		global $ls_codact,$ls_denact,$li_monto,$ls_ctacon,$ls_denctacon,$ls_ideact,$ls_numdoc,$la_debhab;
		
		$ls_codact="";
		$ls_denact="";
		$li_monto="0,00";
		$ls_ctacon="";
		$ls_denctacon="";
		$ls_ideact="";
		$ls_numdoc="";
		$la_debhab[0]="";
		$la_debhab[1]="";
   }
  
    function uf_seleccionarcombo($as_valores,$as_seleccionado,&$aa_parametro,$li_total)
   {
	//////////////////////////////////////////////////////////////////////////////
	//	Function:  uf_seleccionarcombo
	//	Access:    public
	//	Arguments:
	//  			  as_valores      // valores que puede tomar el combo
	//  			  as_seleccionado // item seleccionado
	// 				  aa_parametro    // arreglo de seleccionados
	//  			  li_total        // total de elementos en el combo
	//	Description:  Funcion que mantiene la seleccion de un combo despues de hacer un submit
	//              
	//////////////////////////////////////////////////////////////////////////////		
   		$la_valores = split("-",$as_valores);
		for($li_index=0;$li_index<$li_total;++$li_index)
		{
			if($la_valores[$li_index]==$as_seleccionado)
			{
				$aa_parametro[$li_index]=" selected";
			}
		}
   }


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalle de Cuenta</title>
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
			$la_debhab[0]="";
			$la_debhab[1]="";
			$li_totrow=$_POST["totalfilas"];
			$ls_codact= $_POST["txtcodact"];
			$ls_denact= $_POST["txtdenact"];
			$ls_ctacon=$_POST["txtctacon"];
			$ls_denctacon=$_POST["txtdenctacon"];
			$ls_ideact=$_POST["txtideact"];
			$ls_numdoc=$_POST["txtnumdoc"];
			$li_monto=$_POST["txtmonto"];
			$ls_debhab=$_POST["cmbdh"];
			uf_seleccionarcombo("D-H",$ls_debhab,$la_debhab,2);			
			//$lb_valido=$io_saf->uf_saf_select_activocuenta($ls_codemp,$ls_codact,$ls_seract,$li_monact,$ls_sccuenta);
		break;
	}
?>
<table width="529" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="525" colspan="2" class="titulo-celda">Detalle de Cuenta</td>
  </tr>
</table>
<form name="form1" method="post" action="">
  <table width="525" border="0" align="center" class="formato-blanco">
    <tr>
      <td width="110"><div align="right">Cuenta Contable </div></td>
      <td height="22" colspan="3"><input name="txtctacon" type="text" id="txtctacon" style="text-align:center " value="<?php print $ls_ctacon ?>" maxlength="25" readonly>
        <a href="javascript: ue_catasccuenta();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtdenctacon" type="text" class="sin-borde" id="txtdenctacon" value="<?php print $ls_denctacon ?>" size="40" readonly></td>
    </tr>
    <tr>
      <td><div align="right">Activo</div></td>
      <td height="22" colspan="3"><input name="txtcodact" type="text" id="txtcodact" onBlur="javascript: ue_rellenarcampo(this,'15');" value="<?php print $ls_codact ?>" readonly>
      <a href="javascript: ue_cataactivo();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtdenact" type="text" class="sin-borde" id="txtdenact" value="<?php print $ls_denact ?>" size="40"></td>
    </tr>
    <tr>
      <td><div align="right">Identificaci&oacute;n</div></td>
      <td height="22" colspan="3"><input name="txtideact" type="text" id="txtideact" value="<?php print $ls_ideact ?>" readonly style="text-align:center">
      <input name="txtseract" type="hidden" id="txtseract"></td>
    </tr>
    <tr>
      <td><div align="right">Documento</div></td>
      <td height="22" colspan="3"><input name="txtnumdoc" type="text" id="txtnumdoc" style="text-align:center "  onBlur="javascript: ue_rellenarcampo(this,'15');" value="<?php print $ls_numdoc ?>" size="25" maxlength="15"></td>
    </tr>
    <tr>
      <td><div align="right">Debe/Haber</div></td>
      <td height="22" colspan="3"><select name="cmbdh" id="cmbdh">
        <option value="D"<?php print $la_debhab[0]; ?>>Debe</option>
        <option value="H"<?php print $la_debhab[1]; ?>>Haber</option>
      </select></td>
    </tr>
    <tr>
      <td><div align="right">Monto</div></td>
      <td height="22" colspan="3"><input name="txtmonto" type="text" id="txtmonto" style="text-align:right "  onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php print $li_monto ?>"></td>
    </tr>
    <tr>
      <td><div align="right"></div></td>
      <td width="186" align="center"><input name="operacion" type="hidden" id="operacion">
      <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>"></td>
      <td width="112"><a href="javascript: ue_agregar();"><img src="../shared/imagebank/tools15/aprobado.gif" width="15" height="15" class="sin-borde">Agregar Detalle</a> </td>
      <td width="97"><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/tools15/eliminar.gif" width="15" height="15" class="sin-borde">Cancelar</a></td>
    </tr>
  </table>
</form>
</body>
<script language="javascript">
function  ue_catasccuenta()
{
	window.open("sigesp_cat_ctasscg.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
}

function  ue_cataactivo()
{
	window.open("sigesp_saf_cat_codactivo.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_agregar()
{
	f=document.form1;
	lb_valido=true;
	li_totrows=f.totalfilas.value;
	ls_sccuenta=f.txtctacon.value;
	ls_codact=f.txtcodact.value;
	ls_ideact=f.txtideact.value;
	ls_numdoc=f.txtnumdoc.value;
	ls_debehaber=f.cmbdh.value;
	li_monto=f.txtmonto.value;
	totrowscg=opener.f.totalfilasscg.value;
	
	if(lb_valido)
	{
		if((ls_sccuenta=="")||(ls_numdoc=="")||(ls_codact=="")||(li_monto==""))
		{
			alert("Debe completar los datos.");
		}
		else
		{
			lb_existe=false;
			for(li_i=1; li_i<=totrowscg;li_i++)
			{
				ls_codactgrid=eval("opener.document.form1.txtcodact"+li_i+".value");
				ls_ideactgrid=eval("opener.document.form1.txtidact"+li_i+".value");
				if((ls_codactgrid==ls_codact)&&(ls_ideactgrid==ls_ideact))
				{
					lb_existe=true;
					break;
				}
			}
			if(lb_existe)
			{
				obj=eval("opener.document.form1.txtcontable"+totrowscg+"");
				obj.value=ls_sccuenta;
				obj=eval("opener.document.form1.txtcuentaact"+totrowscg+"");
				obj.value=ls_codact;
				obj=eval("opener.document.form1.txtcuentaide"+totrowscg+"");
				obj.value=ls_ideact;
				obj=eval("opener.document.form1.txtdocscg"+totrowscg+"");
				obj.value=ls_numdoc;
				obj=eval("opener.document.form1.txtmontocont"+totrowscg+"");
				obj.value=li_monto;
				obj=eval("opener.document.form1.txtdebhab"+totrowscg+"");
				obj.value=ls_debehaber;
				opener.document.form1.operacion.value="AGREGARDETALLE";
				opener.document.form1.submit();
				close();
			}
			else
			{
				alert ("El activo al que hace referencia no existe en el movimiento");
			}
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
//--------------------------------------------------------
//	Función que rellena un campo con ceros a la izquierda
//--------------------------------------------------------
function ue_rellenarcampo(valor,maxlon)
{
	var total;
	var auxiliar;
	var longitud;
	var index;
	
	total=0;
    auxiliar=valor.value;
	longitud=valor.value.length;
	total=maxlon-longitud;
	if (total < maxlon)
	{
		for (index=0;index<total;index++)
		{
		   auxiliar="0"+auxiliar;      
		}
		valor.value = auxiliar;
	}
}

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script language="javascript" src="js/funciones.js"></script>
</html>
