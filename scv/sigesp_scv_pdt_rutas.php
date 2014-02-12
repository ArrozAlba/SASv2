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
   		global $ls_codpai,$ls_despai,$ls_codest,$ls_desest,$ls_codciu,$ls_desciu;
		
		$ls_codpai="058";
		$ls_despai="VENEZUELA";
		$ls_codest="";
		$ls_desest="";
		$ls_codciu="";
		$ls_desciu="";
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalle de Rutas</title>
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
	uf_limpiarvariables();
	$li_totrows=$io_fun_viaticos->uf_obtenervalor_get("totrow","");
?>
<table width="506" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="502" colspan="2" class="titulo-celda">Detalle de Rutas</td>
  </tr>
</table>
<form name="form1" method="post" action="">
  <table width="502" border="0" align="center" class="formato-blanco">
    <tr>
      <td><div align="right">Pa&iacute;s Destino </div></td>
      <td colspan="3" align="center"><div align="left">
        <input name="txtcodpai" type="text" id="txtcodpai" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codpai ?>" size="8" maxlength="3" readonly style="text-align:center ">
        <a href="javascript: ue_buscarpais();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
        <input name="txtdespai" type="text" class="sin-borde" id="txtdespai"  value="<?php print $ls_despai ?>" size="45" readonly>
      </div></td>
    </tr>
    <tr>
      <td><div align="right">Estado Destino </div></td>
      <td colspan="3" align="center"><div align="left">
        <input name="txtcodest" type="text" id="txtcodest" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codest ?>" size="8" maxlength="3" readonly style="text-align:center ">
        <a href="javascript: ue_buscarestado();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
        <input name="txtdesest" type="text" class="sin-borde" id="txtdesest"  value="<?php print $ls_desest ?>" size="40" readonly>
      </div></td>
    </tr>
    <tr>
      <td><div align="right">Ciudad Destino </div></td>
      <td colspan="3" align="center"><div align="left">
        <input name="txtcodciu" type="text" id="txtcodciu" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codciu ?>" size="8" maxlength="3" readonly style="text-align:center ">
        <a href="javascript: ue_buscarciudad();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
        <input name="txtdesciu" type="text" class="sin-borde" id="txtdesciu"  value="<?php print $ls_desciu ?>" size="40" readonly>
      </div></td>
    </tr>
    <tr>
      <td width="110"><div align="right"></div></td>
      <td width="186" align="center"><input name="hidestino" type="hidden" id="hidestino">
      <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
      <input name="hidestatus" type="hidden" id="hidestatus"></td>
      <td width="112"><a href="javascript: ue_agregar();"><img src="../shared/imagebank/tools15/aprobado.gif" width="15" height="15" class="sin-borde">Agregar Detalle</a> </td>
      <td width="74"><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/tools15/eliminar.gif" width="15" height="15" class="sin-borde">Cancelar</a></td>
    </tr>
  </table>
</form>
</body>
<script language="javascript">
function ue_buscarpais()
{
	f=document.form1;
	window.open("sigesp_scv_cat_pais.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	f.txtcodest.value="";
	f.txtdesest.value="";
	f.txtcodciu.value="";
	f.txtdesciu.value="";
}

function ue_buscarestado()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	if(codpai!="")
	{
		window.open("sigesp_scv_cat_estados.php?codpai="+codpai+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais.");
	}
	f.txtcodciu.value="";
	f.txtdesciu.value="";

}

function ue_buscarciudad()
{
	f=document.form1;
	ls_codpai=ue_validarvacio(f.txtcodpai.value);
	ls_codest=ue_validarvacio(f.txtcodest.value);
	if((codpai!="")||(codest!=""))
	{
		window.open("sigesp_scv_cat_ciudades.php?hidpais="+ls_codpai+"&hidestado="+ls_codest+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais y un estado.");
	}
}

function ue_agregar()
{
	f=document.form1;
	lb_valido=true;
	li_totrows=f.totalfilas.value;
	ls_codpai=f.txtcodpai.value;
	ls_despai=f.txtdespai.value;
	ls_codest=f.txtcodest.value;
	ls_desest=f.txtdesest.value;
	ls_codciu=f.txtcodciu.value;
	ls_desciu=f.txtdesciu.value;
	for(li_i=1; li_i<=li_totrows;li_i++)
	{
		ls_codpaigrid=eval("opener.document.form1.txtcodpaides"+li_i+".value");
		ls_codestgrid=eval("opener.document.form1.txtcodestdes"+li_i+".value");
		ls_codciugrid=eval("opener.document.form1.txtcodciudes"+li_i+".value");
		if((ls_codpaigrid==ls_codpai)&&(ls_codestgrid==ls_codest)&&(ls_codciugrid==ls_codciu))
		{
			lb_valido=false;
			break;
		}
	}
	if(lb_valido)
	{
		if((ls_codpai=="")||(ls_codest=="")||(ls_codciu==""))
		{
			alert("Debe completar los datos");
		}
		else
		{
			obj=eval("opener.document.form1.txtcodpaides"+li_totrows+"");
			obj.value=ls_codpai;
			obj=eval("opener.document.form1.txtdespaides"+li_totrows+"");
			obj.value=ls_despai;
			obj=eval("opener.document.form1.txtcodestdes"+li_totrows+"");
			obj.value=ls_codest;
			obj=eval("opener.document.form1.txtdesestdes"+li_totrows+"");
			obj.value=ls_desest;
			obj=eval("opener.document.form1.txtcodciudes"+li_totrows+"");
			obj.value=ls_codciu;
			obj=eval("opener.document.form1.txtdesciudes"+li_totrows+"");
			obj.value=ls_desciu;
			ls_desrut=opener.document.form1.txtdesrut.value;
			opener.document.form1.txtdesrut.value=ls_desrut+" - "+ls_desciu;
			opener.document.form1.operacion.value="AGREGARDETALLE";
			opener.document.form1.submit();
			close();
		}
	}
	else
	{
		alert("El destino ya esta en el movimiento");
	}
}
function ue_cancelar()
{
	close();
}

</script> 
<script language="javascript" src="js/funciones.js"></script>
</html>
