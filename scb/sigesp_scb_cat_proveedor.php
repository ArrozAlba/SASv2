<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_funciones_banco.php");
	$io_fun_scb=new class_funciones_banco();
	$ls_tipo=$io_fun_scb->uf_obtenertipo();
	unset($io_fun_scb);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catálogo de Proveedores</title>
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
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
</head>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="cod_pro">
<input name="orden"      type="hidden" id="orden" 	   value="ASC">
<input name="tipo" 		 type="hidden" id="tipo" 	   value="<?php print $ls_tipo; ?>">
<br>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="2">Cat&aacute;logo de Proveedores </td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
      </tr>
      <tr>
        <td width="64" height="22"><div align="right">C&oacute;digo</div></td>
        <td height="22"><div align="left">
          <input name="txtcodpro" type="text" id="txtcodpro" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div>          
          <div align="right"></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="txtnompro" type="text" id="txtnompro" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      <tr>
        <td height="22"><div align="right">Direcci&oacute;n</div></td>
        <td height="22"><div align="left">
          <input name="txtdirpro" type="text" id="txtdirpro" onKeyPress="javascript: ue_mostrar(this,event);">      
        </div></td>
      <tr>
        <td height="22"><div align="right">R.I.F.</div></td>
        <td height="22"><input name="txtrifpro" type="text" id="txtrifpro" onKeyPress="javascript: ue_mostrar(this,event);"></td>
    <tr>
      <td height="22">&nbsp;</td>
      <td><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0"></a><a href="javascript: ue_search();">Buscar</a> </div></td>
  </table> 
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
function aceptar(codpro,nompro,rifpro,sccuenta,tipconpro)
{
	if(sccuenta!="")
	{
		opener.document.formulario.txtcodigo.value=codpro;
		opener.document.formulario.txtnombre.value=nompro;
		opener.document.formulario.txtrifpro.value=rifpro;
		opener.document.formulario.codigocuenta.value=sccuenta;
		opener.document.formulario.txtproveedor.value=sccuenta;
		opener.document.formulario.tipocontribuyente.value=tipconpro;
	}
	else
	{
		alert("Debe verificar la configuración de la recepción de documentos y las cuentas contables asociadas");
	}
	close();
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	codpro=f.txtcodpro.value;
	nompro=f.txtnompro.value;
	dirpro=f.txtdirpro.value;
	rifpro=f.txtrifpro.value;
	tipo=f.tipo.value;
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_scb_c_catalogo_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
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
	ajax.send("catalogo=PROVEEDOR&codpro="+codpro+"&nompro="+nompro+"&dirpro="+dirpro+"&rifpro="+rifpro+"&tipo="+tipo+"&orden="+orden+
			  "&campoorden="+campoorden);
}

function aceptar_modcmpretencion(as_codpro,as_nompro,as_rifpro,as_dirpro)
{
  opener.document.formulario.txtcodigo.value    = as_codpro;
  opener.document.formulario.txtnomsujret.value = as_nompro;
  opener.document.formulario.txtrif.value 		= as_rifpro;
  opener.document.formulario.txtdirsujret.value = as_dirpro;
  close();
}
</script>
</html>