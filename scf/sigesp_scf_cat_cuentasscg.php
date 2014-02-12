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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Contables</title>
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
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="sc_cuenta">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<table width="580" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Cuentas Contables</td>
    </tr>
  </table>
  <br>
    <table width="580" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="149" height="22"><div align="right">Cuenta Contable </div></td>
        <td width="425" height="22"><div align="left">
          <input name="txtscgcuenta" type="text" id="txtscgcuenta" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><input name="txtdencue" type="text" id="txtdencue" onKeyPress="javascript: ue_mostrar(this,event);">      </td>
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
function ue_aceptar(scg_cuenta,denominacion)
{
	opener.document.formulario.txtscgcuenta.value=scg_cuenta;
	opener.document.formulario.txtdenominacion.value=denominacion;
	close();
}

function ue_aceptarrepdes(scg_cuenta)
{
	opener.document.formulario.txtcuentadesde.value=scg_cuenta;
	opener.document.formulario.txtcuentahasta.value="";
	close();
}

function ue_aceptarrephas(scg_cuenta)
{
	if(opener.document.formulario.txtcuentadesde.value<=scg_cuenta)
	{
		opener.document.formulario.txtcuentahasta.value=scg_cuenta;
		close();
	}
	else
	{
		alert("Rango de Cuentas Inválido.");
	}
}

function ue_search()
{
	f=document.formulario;
	scgcuenta=f.txtscgcuenta.value;
	dencue=f.txtdencue.value;
	orden=f.orden.value;
	tipo=f.tipo.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_scf_c_catalogo_ajax.php",true);
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
	ajax.send("catalogo=CUENTASSCG&scgcuenta="+scgcuenta+"&dencue="+dencue+"&orden="+orden+"&campoorden="+campoorden+"&tipo="+tipo);
}

function ue_close()
{
	close();
}
</script>
</html>