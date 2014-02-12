<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalle de Garantias de Pago</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin-top: 30px;
}
-->
</style></head>

<body>


<form name="form1" method="post" action="">
<table width="597" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="13" colspan="3" class="titulo-celdanew">Detalle de Garant&iacute;as</td>
    </tr>
    <tr>
      <td width="78" height="66"><div align="right">Descripci&oacute;n</div></td>
      <td width="500" align="left">
          <div align="left">
              <input name="txtdescripcion" type="text" id="txtdescripcion" style="margin-right:inherit " value="" size="100" maxlength="254">
          </div></td>
      <td width="17" align="left">&nbsp;</td>
    </tr>
    <tr>
      <td><div align="right"></div></td>
      <td colspan="2"><a href="javascript:uf_aceptar();"><img src="../shared/imagebank/aprobado.gif" alt="Aceptar" width="15" height="15" border="0"></a><a href="javascript:uf_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar" width="15" height="15" border="0"></a></td>
    </tr>
  </table>
  
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">

function uf_aceptar()
{
	f=document.form1;
	ls_descripcion=f.txtdescripcion.value;
	if(ls_descripcion=="")
	{
		alert("La Descripción está vacía!!!");
		f.txtdescripcion.focus();
	}
	else
	{
		
		opener.ue_cargargarantias(ls_descripcion);
		f.txtdescripcion.value="";
		close();
	}
	
}

function uf_cancelar()
{
	f=document.form1;
	f.txtdescripcion.value="";
	f.txtdescripcion.focus();
}


 

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
