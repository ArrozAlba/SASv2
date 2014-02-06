<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Presupuestarias</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="codpro">
<input name="orden" type="hidden" id="orden" value="ASC">
<table width="620" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="540" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Cuentas Presupuestarias </td>
    </tr>
  </table>
  <br>
    <table width="620" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="148" height="22"><div align="right">Cuenta Presupuestaria </div></td>
        <td width="346" height="22"><div align="left">
          <input name="txtspgcuenta" type="text" id="txtspgcuenta" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><input name="txtdencue" type="text" id="nombre" onKeyPress="javascript: ue_mostrar(this,event);">      </td>
      </tr>
	  <tr>
        <td colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a><a href="javascript: ue_close();"> <img src="../shared/imagebank/eliminar.gif" alt="Cerrar" width="15" height="15" class="sin-borde">Cerrar</a></div></td>
	  </tr>
  </table> 
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
function ue_aceptar(spg_cuenta,denominacion,sccuenta,disponible)
{
	opener.document.formulario.txtspgcuenta.value=spg_cuenta;
	opener.document.formulario.txtdenominacion.value=denominacion;
	opener.document.formulario.sccuenta.value=sccuenta;
	opener.document.formulario.disponibilidad.value=disponible;
	close();
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	estcla=opener.document.formulario.estcla.value;
	codestpro1=opener.document.formulario.txtcodestpro1.value;
	codestpro2=opener.document.formulario.txtcodestpro2.value;
	codestpro3=opener.document.formulario.txtcodestpro3.value;
	codestpro4=opener.document.formulario.txtcodestpro4.value;
	if(codestpro4=="")
	{
		codestpro4="00";
	}
	codestpro5=opener.document.formulario.txtcodestpro5.value;
	if(codestpro5=="")
	{
		codestpro5="00";
	}
	spgcuenta=f.txtspgcuenta.value;
	dencue=f.txtdencue.value;
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_cxp_c_catalogo_ajax.php",true);
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
	ajax.send("catalogo=CUENTASSPG&spgcuenta="+spgcuenta+"&dencue="+dencue+"&codestpro1="+codestpro1+
			  "&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&codestpro5="+codestpro5+"&estcla="+estcla+
			  "&orden="+orden+"&campoorden="+campoorden);
}

function ue_close()
{
	close();
}
</script>
</html>