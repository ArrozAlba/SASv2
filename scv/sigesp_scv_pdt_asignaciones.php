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
<title>Detalle de Asignaciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
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
	$li_numdia= $io_fun_viaticos->uf_obtenervalor_get("numdia","");
?>
<table width="550" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="550" class="titulo-celda">Detalle de Asignaciones</td>
  </tr>
</table>
<form name="form1" method="post" action="">
  <table width="555" border="0" align="center" class="formato-blanco">
    <tr>
      <td height="22" colspan="4"><div align="center">
          <input name="rdprocedencia" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_tarifaviaticos();">
      Tarifa de Viaticos 
      <input name="rdprocedencia" type="radio" class="sin-borde" value="radiobutton"  onClick="javascript: ue_transporte();">
      Transporte 
       <input name="rdprocedencia" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_tarifadistancia();">
      Tarifas de Distancias 
        
      <input name="radiobutton" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_otrastarifas();">
      Otros</div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">C&oacute;digo</div></td>
      <td height="22" colspan="3" align="center"><div align="left">
        <input name="txtcodasi" type="text" id="txtcodasi" size="12" style="text-align:center"  readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Denominaci&oacute;n</div></td>
      <td height="22" colspan="3" align="center"><p align="left">
        <input name="txtdenasi" type="text" id="txtdenasi" size="60" readonly>
      </p></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Procedencia</div></td>
      <td height="22" colspan="3" align="center"><div align="left">
        <input name="txtproasi" type="text" id="txtproasi" size="5"  style="text-align:center" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="35"><div align="right">Cantidad</div></td>
      <td height="35" colspan="3" align="center"><div align="left">
        <input name="txtcanasi" type="text" id="txtcanasi" style="text-align:right" onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="0,00" size="10">
      </div></td>
    </tr>
    <tr>
      <td width="115" height="21"><div align="right"></div></td>
      <td width="254" align="center"><div align="left">
        <input name="hidestino" type="hidden" id="hidestino">
        <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
        <input name="hidestatus" type="hidden" id="hidestatus">
        <input name="hidnumdia" type="hidden" id="hidnumdia" value="<?php print $li_numdia; ?>">
      </div></td>
      <td width="97"><a href="javascript: ue_agregar();"><img src="../shared/imagebank/tools15/aprobado.gif" width="15" height="15" class="sin-borde">Agregar Detalle</a> </td>
      <td width="69"><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/tools15/eliminar.gif" width="15" height="15" class="sin-borde">Cancelar</a></td>
    </tr>
  </table>
</form>
</body>
<script language="javascript">
function ue_tarifaviaticos()
{
	f=document.form1;
	ls_destino="SOLICITUD";
	window.open("sigesp_scv_cat_tarifas.php?destino="+ls_destino+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	li_numdia=f.hidnumdia.value;
	f.txtcanasi.value=li_numdia;
}

function ue_transporte()
{
	f=document.form1;
	ls_destino="SOLICITUD";
	window.open("sigesp_scv_cat_transporte.php?destino="+ls_destino+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	f.txtcanasi.value="0,00";
}

function ue_tarifadistancia()
{
	f=document.form1;
	ls_destino="SOLICITUD";
	window.open("sigesp_scv_cat_tarifasxdistancias.php?destino="+ls_destino+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	f.txtcanasi.value="0,00";
}

function ue_otrastarifas()
{
	f=document.form1;
	ls_destino="SOLICITUD";
	window.open("sigesp_scv_cat_otrasasignaciones.php?destino="+ls_destino+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	f.txtcanasi.value="0,00";
}

function ue_agregar()
{
	f=document.form1;
	lb_valido=true;
	li_totrows=f.totalfilas.value;
	ls_codasi=f.txtcodasi.value;
	ls_denasi=f.txtdenasi.value;
	ls_proasi=f.txtproasi.value;
	li_canasi=f.txtcanasi.value;
	if((ls_codasi!="")&&(li_canasi!="0,00"))
	{
		for(li_i=1; li_i<=li_totrows;li_i++)
		{
			ls_codasigrid=eval("opener.document.form1.txtcodasig"+li_i+".value");
			ls_proasigrid=eval("opener.document.form1.txtproasig"+li_i+".value");
			if((ls_codasigrid==ls_codasi)&&(ls_proasigrid==ls_proasi))
			{
				lb_valido=false;
				break;
			}
		}
		if(lb_valido)
		{
			obj=eval("opener.document.form1.txtproasig"+li_totrows+"");
			obj.value=ls_proasi;
			obj=eval("opener.document.form1.txtcodasig"+li_totrows+"");
			obj.value=ls_codasi;
			obj=eval("opener.document.form1.txtdenasig"+li_totrows+"");
			obj.value=ls_denasi;
			obj=eval("opener.document.form1.txtcantidad"+li_totrows+"");
			obj.value=li_canasi;
			opener.document.form1.operacion.value="AGREGARASIGNACIONES";
			opener.document.form1.submit();
			close();
		}
		else
		{
			alert("La asignación ya esta en el movimiento");
		}
	}
	else
	{
		alert("Faltan campos por llenar");
	}
}
function ue_cancelar()
{
	close();
}

</script> 
<script language="javascript" src="../cfg/scv/js/funciones.js"></script>
</html>
