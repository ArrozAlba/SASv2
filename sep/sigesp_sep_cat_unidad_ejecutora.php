<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_folder/class_funciones_sep.php");
	$io_fun_sep=new class_funciones_sep();
	$ls_tipo=$io_fun_sep->uf_obtenertipo();
	unset($io_fun_sep);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Unidad Ejecutora</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sep.js"></script>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="coduniadm">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo 
        de Unidad Ejecutora</td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="431"><div align="left">
          <input name="txtcoduniadm" type="text" id="txtcoduniadm" size="30" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdenuniadm" type="text" id="txtdenuniadm" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<p>
		<div id="resultados" align="center"></div>	
	</p>
</form>
</body>
<script language="JavaScript">
function aceptar(coduniadm,denuniadm)
{
	opener.document.formulario.txtcoduniadm.value=coduniadm;
    opener.document.formulario.txtdenuniadm.value=denuniadm;

	close();
}

function aceptar_aprobacion(coduniadm,denuniadm)
{
	opener.document.formulario.txtcoduniadm.value=coduniadm;
    opener.document.formulario.txtdenuniadm.value=denuniadm;
	close();
}

function aceptar_unidad(coduniadm,denuniadm)
{
	opener.document.formulario.txtcoduniadm.value=coduniadm;
    opener.document.formulario.txtdenuniadm.value=denuniadm;
	close();
}

function aceptar_reportedesde(coduniadm)
{
	opener.document.formulario.txtcodunides.value=coduniadm;
	close();
}

function aceptar_reportehasta(coduniadm)
{
	opener.document.formulario.txtcodunihas.value=coduniadm;
	close();
}

function aceptar_catalogo_sep(coduniadm,denuniadm)
{
	opener.document.formulario.txtcoduniadm.value=coduniadm;
    opener.document.formulario.txtdenuniadm.value=denuniadm;
	close();
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	coduniadm=f.txtcoduniadm.value;
	denuniadm=f.txtdenuniadm.value;
	tipo=f.tipo.value;
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_sep_c_catalogo_ajax.php",true);
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
	ajax.send("catalogo=UNIDADEJECUTORA&coduniadm="+coduniadm+"&denuniadm="+denuniadm+"&tipo="+tipo+"&orden="+orden+
			  "&campoorden="+campoorden);
}

function uf_catalogo_estructuras(ls_codunieje)
{
  window.open('sigesp_cat_estructura_presupuestaria.php?hidcodunieje='+ls_codunieje,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=450,left=50,top=50,location=no,resizable=yes,dependent=yes");
}
</script>
</html>