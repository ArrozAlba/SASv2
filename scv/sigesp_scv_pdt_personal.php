<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";		
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalle de Personal </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
	require_once("class_folder/class_funciones_viaticos.php");
	$io_fun_viaticos=new class_funciones_viaticos();
	$li_totrows=$io_fun_viaticos->uf_obtenervalor_get("totrow","");
?>
<table width="550" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="550" class="titulo-celda">Detalle de Personal </td>
  </tr>
</table>
<form name="form1" method="post" action="">
  <table width="555" border="0" align="center" class="formato-blanco">
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22" colspan="3" align="center"><div align="left">
        <input name="rdbpersonal" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_personal();">
      Personal 
      <input name="rdbpersonal" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_beneficiarios();">
      For&aacute;neo</div></td>
    </tr>
    <tr>
      <td height="22" align="right">C&oacute;digo</td>
      <td height="22" colspan="3" align="left">
        <input name="txtcodben" type="text" id="txtcodben" size="16" style="text-align:center"  readonly>
      </td>
    </tr>
    <tr>
      <td height="22" align="right">Nombre</td>
      <td height="22" colspan="3" align="left">
        <input name="txtnomben" type="text" id="txtnomben" size="60" readonly>
     </td>
    </tr>
    <tr>
      <td height="22" align="right">C&eacute;dula</td>
      <td height="22" colspan="3" align="left">
        <input name="txtcedben" type="text" id="txtcedben" size="16"  style="text-align:center" readonly>
      </td>
    </tr>
    <tr>
      <td height="22" align="right">Cargo</td>
      <td height="22" colspan="3" align="left">
        <input name="txtcarper" type="text" id="txtcarper" style="text-align:left" size="60"  readonly>
      </td>
    </tr>
    <tr>
      <td height="22" align="right">Categor&iacute;a</td>
      <td height="22" colspan="3" align="left">
        <input name="txtcodclavia" type="text" id="txtcodclavia" size="5" readonly>
      </td>
    </tr>
    <tr>
      <td width="115" height="21"></td>
      <td width="254" align="center"><div align="left">
        <input name="hidestino" type="hidden" id="hidestino">
        <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
        <input name="hidestatus" type="hidden" id="hidestatus">
        <input name="txtcodnom" type="hidden" id="txtcodnom">
      </div></td>
      <td width="97"><a href="javascript: ue_agregar();"><img src="../shared/imagebank/tools15/aprobado.gif" width="15" height="15" class="sin-borde">Agregar Detalle</a> </td>
      <td width="69"><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/tools15/eliminar.gif" width="15" height="15" class="sin-borde">Cancelar</a></td>
    </tr>
  </table>
</form>
</body>
<script language="javascript">
function ue_beneficiarios()
{
	f=document.form1;
	f.txtcodben.value="";
	f.txtnomben.value="";
	f.txtcedben.value="";
	f.txtcarper.value="";
	f.txtcodclavia.value="";
	window.open("sigesp_scv_cat_beneficiarios.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_personal()
{
	f=document.form1;
	f.txtcodben.value="";
	f.txtnomben.value="";
	f.txtcedben.value="";
	f.txtcarper.value="";
	f.txtcodclavia.value="";
	window.open("sigesp_scv_cat_personal.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_agregar()
{
	lb_valido=true;
	lb_personal=true;	
	f=document.form1;
	li_totrows=f.totalfilas.value;
	ls_codper= f.txtcodben.value;
	ls_nomper= f.txtnomben.value;
	ls_cedper= f.txtcedben.value;
	ls_cargo=  f.txtcarper.value;
	ls_codclavia= f.txtcodclavia.value;
	ls_codnom= f.txtcodnom.value;
	
	for(li_i=1; li_i<li_totrows;li_i++)
	{
		ls_codpergrid=eval("opener.document.form1.txtcodper"+li_i+".value");
		ls_cargogrid=eval("opener.document.form1.txtcodcar"+li_i+".value");
		if(ls_codpergrid==ls_codper)
		{
			lb_valido=false;
			break;
		}
		if(ls_cargo=="")
		{
			if((ls_cargogrid!="")&&(li_totrows>1))
			{
				alert("Debe incluir solo personal del organismo");
				lb_personal=false;	
				break;
			}
		}
		else
		{
			if((ls_cargogrid=="")&&(li_totrows>1))
			{
				alert("No se debe incluir personal del organismo");
				lb_personal=false;	
				break;
			}
		}
		
	}
	if(lb_valido)
	{
		if(lb_personal)
		{
			opener.document.form1.operacion.value="AGREGARPERSONAL"
			obj=eval("opener.document.form1.txtcodper"+li_totrows+"");
			obj.value=ls_codper;
			obj=eval("opener.document.form1.txtnomper"+li_totrows+"");
			obj.value=ls_nomper;
			obj=eval("opener.document.form1.txtcedper"+li_totrows+"");
			obj.value=ls_cedper;
			obj=eval("opener.document.form1.txtcodcar"+li_totrows+"");
			obj.value=ls_cargo;
			obj=eval("opener.document.form1.txtcodclavia"+li_totrows+"");
			obj.value=ls_codclavia;
			obj=eval("opener.document.form1.txtcodnom"+li_totrows+"");
			obj.value=ls_codnom;
			opener.document.form1.submit();
			close();
		}
	}
	else
	{
		alert("La persona ya esta en el movimiento");
	}	
}
function ue_cancelar()
{
	close();
}

</script> 
<script language="javascript" src="../cfg/scv/js/funciones.js"></script>
</html>
