<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";		
}
	if(array_key_exists("codact",$_GET))
	{
		$ls_codact=$_GET["codact"];
		$ls_ideact=$_GET["ideact"];
		$ls_ctacon=$_GET["scgcta"];
		
	}

   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_denact,$li_monto,$ls_denctacon,$ls_numdoc,$la_debhab;
		
		$li_monto="0,00";
		$ls_ctacon="";
		$ls_denctacon="";
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
      <td width="145" height="22"><div align="right">Cuenta Contable </div></td>
      <td height="23" colspan="3"><input name="txtctacon" type="text" id="txtctacon" style="text-align:center " value="<?php print $ls_ctacon ?>" maxlength="25">
        <a href="javascript: ue_catasccuenta();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtdenctacon" type="text" class="sin-borde" id="txtdenctacon" value="<?php print $ls_denctacon ?>" size="40" readonly></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Documento</div></td>
      <td height="22" colspan="3"><input name="txtnumdoc" type="text" id="txtnumdoc" style="text-align:center "  onBlur="javascript: ue_rellenarcampo(this,'15');" value="<?php print $ls_numdoc ?>" size="25" maxlength="15"></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Debe/Haber</div></td>
      <td height="22" colspan="3"><select name="cmbdh" id="cmbdh">
        <option value="D"<?php print $la_debhab[0]; ?>>Debe</option>
        <option value="H"<?php print $la_debhab[1]; ?>>Haber</option>
      </select></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Monto</div></td>
      <td height="22" colspan="3"><input name="txtmonto" type="text" id="txtmonto" style="text-align:right "  onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php print $li_monto ?>"></td>
    </tr>
    <tr>
      <td height="22"><div align="right">
        <input name="hidcodact" type="hidden" id="hidcodact" value="<?php print $ls_codact ?>">
        <input name="hidideact" type="hidden" id="hidideact" value="<?php print $ls_ideact ?>">
      </div></td>
      <td width="181" align="center"><input name="operacion" type="hidden" id="operacion">      <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>"></td>
      <td width="103"><a href="javascript: ue_agregar();"><img src="../shared/imagebank/tools15/aprobado.gif" width="15" height="15" class="sin-borde">Agregar Detalle</a> </td>
      <td width="76"><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/tools15/eliminar.gif" width="15" height="15" class="sin-borde">Cancelar</a></td>
    </tr>
  </table>
</form>
</body>
<script language="javascript">
function  ue_catasccuenta()
{
	window.open("sigesp_cat_ctasscg.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_agregar()
{
	f=document.form1;
	lb_valido=true;
	ls_codact=f.hidcodact.value;
	ls_ideact=f.hidideact.value;
	li_totrows=f.totalfilas.value;
	ls_sccuenta=f.txtctacon.value;
	ls_numdoc=f.txtnumdoc.value;
	ls_debehaber=f.cmbdh.value;
	li_monto=f.txtmonto.value;
	totrowscg=opener.f.totalfilasscg.value;
	
	if((ls_sccuenta=="")||(ls_numdoc=="")||(li_monto==""))
	{
		alert("Debe completar los datos.");
	}
	else
	{
		lb_existe=false;
		if(ls_debehaber=="H")
		{
			if(ls_numdoc==ls_codact)
			{
				lb_existe=true;
			}
		}
		else
		{
			li_j=0;
			for(li_i=1; li_i<=totrowscg;li_i++)
			{
				ls_numdocgrid=eval("opener.document.form1.txtdocscg"+li_i+".value");
				ls_debhahgrid=eval("opener.document.form1.txtdebhab"+li_i+".value");
				ls_contablegrid=eval("opener.document.form1.txtcontable"+li_i+".value");
				if(ls_debhahgrid=="D")
				{
					li_j=li_j + 1;
					if((ls_numdocgrid==ls_numdoc)&&(ls_contablegrid==ls_sccuenta))
					{
						lb_existe=true;
						break;
					}
				}
				if(li_j==0)
				{
					lb_existe=true;
				}
			}	
		}
		if (lb_existe)
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
			alert("La cuenta contable y el numero de documento deben existir en el movimiento contable");
		}
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
