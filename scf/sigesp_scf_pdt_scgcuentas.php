<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_folder/class_funciones_scf.php");
	$io_fun_scf=new class_funciones_scf("../");
	$ls_tipo=$io_fun_scf->uf_obtenertipo();
	unset($io_fun_scf);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalles Contables</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scf.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<body onLoad="javascript: ue_cargar();">
<form name="formulario" method="post" action="">
  <table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td height="20" colspan="2" class="titulo-ventana">Detalles Contables </td>
    </tr>
  </table>
<br>
<table width="650" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="134" height="22">&nbsp;</td>
        <td height="22" colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">N&uacute;mero de Documento </div></td>
        <td height="22" colspan="3"><div align="left">
          <input name="txtdocumento" type="text" id="txtdocumento" value="" size="20" maxlength="15"  onKeyUp="javascript: ue_validarcomillas(this);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td height="22" colspan="3">
          <div align="left">
            <input name="txtdescripcion" type="text" id="txtdescripcion" size="70" maxlength="500" onKeyUp="javascript: ue_validarcomillas(this);">
            </div>
        </td>
      <tr>
        <td height="22"><div align="right">Cuenta</div></td>
        <td height="22" colspan="3"><div align="left">
          <input name="txtscgcuenta" type="text" id="txtscgcuenta" style="text-align:center" size="27" maxlength="25" readonly>
          <a href="javascript:ue_cuentasscg();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas de Gastos"></a> 
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" size="55">
        </div></td>
      <tr>
        <td height="22"><div align="right">Operaci&oacute;n:</div></td>
        <td width="85" height="22"><label>
          <select name="cmboperacion" id="cmboperacion">
            <option value="D" selected>Debe</option>
            <option value="H">Haber</option>
            </select>
        </label></td>
        <td width="66"><div align="right">Monto</div></td>
        <td width="355"><div align="left">
          <input name="txtmonto" type="text" id="txtmonto" style="text-align:right" size="22" onKeyPress="return(ue_formatonumero(this,'.',',',event));">
        <a href="javascript: ue_aceptar();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar Detalle Presupuestario" width="15" height="15" border="0"></a> <a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools15/eliminar.gif" width="15" height="15" border="0" alt="Cancelar Registro de Detalle Presupuestario"></a></div></td>
    <tr>
      <td height="22">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
  </table> 
	<p>
    </p>
</form>      
</body>
<script language="JavaScript">
function ue_cerrar()
{
	close();
}

function ue_cargar()
{
	f=document.formulario;
	f.txtdocumento.value=opener.document.formulario.txtcomprobante.value;
	f.txtdescripcion.value=opener.document.formulario.txtdescripcion.value;
}

function ue_cuentasscg()
{
	f=document.formulario;
	pagina="sigesp_scf_cat_cuentasscg.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=610,height=400,resizable=yes,location=no");
	f.txtscgcuenta.value="";
	f.txtdenominacion.value="";
	f.txtmonto.value="0,00";
}

function ue_aceptar()
{
	f=document.formulario;
	monto=ue_formato_calculo(f.txtmonto.value);
	if(parseFloat(monto)>0)
	{
		// Datos del formulario
		monto=f.txtmonto.value;
		ls_cuenta=f.txtscgcuenta.value;
		ls_descripcion=f.txtdescripcion.value;
		ls_documento=f.txtdocumento.value;
		ls_operacion=f.cmboperacion.value;
		//Datos del opener
		ls_procede=opener.document.formulario.txtprocede.value;
		totrowscg=ue_calcular_total_fila_opener("txtcuenta");
		opener.document.formulario.totrow.value=totrowscg;
		// Inicializaciones
		parametros="";
		//---------------------------------------------------------------------------------
		// Cargar las cuentas contables
		//---------------------------------------------------------------------------------
		for(j=1;(j<totrowscg);j++)
		{
			cuenta=eval("opener.document.formulario.txtcuenta"+j+".value");
			descripcion=eval("opener.document.formulario.txtdescripcion"+j+".value");
			procede=eval("opener.document.formulario.txtprocede"+j+".value");
			documento=eval("opener.document.formulario.txtdocumento"+j+".value");
			mondeb=eval("opener.document.formulario.txtmondeb"+j+".value");
			monhab=eval("opener.document.formulario.txtmonhab"+j+".value");
			debhab=eval("opener.document.formulario.txtdebhab"+j+".value");
			parametros=parametros+"&txtcuenta"+j+"="+cuenta+"&txtdescripcion"+j+"="+descripcion+"&txtprocede"+j+"="+procede+
								  "&txtdocumento"+j+"="+documento+"&txtmondeb"+j+"="+mondeb+"&txtmonhab"+j+"="+monhab+
								  "&txtdebhab"+j+"="+debhab;
		}
		if(ls_operacion=="D")
		{
			li_mondeb=monto;
			li_monhab=0;
		}
		else
		{
			li_mondeb=0;
			li_monhab=monto;
		}
		j=totrowscg;
		totrowscg=totrowscg+1;
		parametros=parametros+"&txtcuenta"+j+"="+ls_cuenta+"&txtdescripcion"+j+"="+ls_descripcion+"&txtprocede"+j+"="+ls_procede+
							  "&txtdocumento"+j+"="+ls_documento+"&txtmondeb"+j+"="+li_mondeb+"&txtmonhab"+j+"="+li_monhab+
							  "&txtdebhab"+j+"="+ls_operacion;
		parametros=parametros+"&totrowscg="+totrowscg;
		if(parametros!="")
		{
			// Div donde se van a cargar los resultados
			divgrid = opener.document.getElementById("cuentas");
			// Instancia del Objeto AJAX
			ajax=objetoAjax();
			// Pagina donde están los métodos para buscar y pintar los resultados
			ajax.open("POST","class_folder/sigesp_scf_c_comprobante_ajax.php",true);
			ajax.onreadystatechange=function(){
				if(ajax.readyState==1)
				{
					//divgrid.innerHTML = "";//<-- aqui iria la precarga en AJAX 
				}
				else
				{
					if(ajax.readyState==4)
					{
						if(ajax.status==200)
						{//mostramos los datos dentro del contenedor
							divgrid.innerHTML = ajax.responseText
						}
						else
						{
							if(ajax.status==404)
							{
								divgrid.innerHTML = "La página no existe";
							}
							else
							{//mostramos el posible error     
								divgrid.innerHTML = "Error:".ajax.status;
							}
						}
						
					}
				}
			}	
			ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			// Enviar todos los campos a la pagina para que haga el procesamiento
			ajax.send("proceso=PROCESAR"+parametros);
			opener.document.formulario.totrow.value=totrowscg;
		}
	}
	else
	{
		alert("El monto debe ser mayor a cero");
	}
}
</script>
</html>