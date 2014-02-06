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
<title>Cat&aacute;logo de Beneficiarios</title>
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
<input name="campoorden" type="hidden" id="campoorden" value="ced_bene">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<br>
    <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="2" class="titulo-celda" style="text-align:center">Cat&aacute;logo de Beneficiarios</td>
      </tr>
      <tr>
        <td height="15" style="text-align:right">&nbsp;</td>
        <td height="15">&nbsp;</td>
      </tr>
      <tr>
        <td width="68" height="22" style="text-align:right">C&eacute;dula</td>
        <td height="22"><input name="txtcedbene" type="text" id="txtcedbene" onKeyPress="javascript: ue_mostrar(this,event);" size="15" maxlength="10" style="text-align:center"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Nombre</td>
        <td height="22"><input name="txtnombene" type="text" id="nombre" onKeyPress="javascript: ue_mostrar(this,event);" size="60">      </td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Apellido</td>
        <td height="22"><input name="txtapebene" type="text" id="txtapebene" onKeyPress="javascript: ue_mostrar(this,event);" size="60">      </td>
      </tr>
	  <tr>
        <td colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
	  </tr>
</table> 
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
function aceptar(cedula,nombre, rifben)
{
	opener.document.formulario.txtcodigo.value=cedula;
	opener.document.formulario.txtnombre.value=nombre;
	opener.document.formulario.txproben.value=rifben;
	close();
}
function aceptar_reportedesde(cedula)
{
	opener.document.formulario.txtcoddes.value=cedula;
	close();
}

function aceptar_reportehasta(cedula)
{
	opener.document.formulario.txtcodhas.value=cedula;
	close();
}


function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	cedbene=f.txtcedbene.value;
	nombene=f.txtnombene.value;
	apebene=f.txtapebene.value;
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
	ajax.send("catalogo=BENEFICIARIO&cedbene="+cedbene+"&nombene="+nombene+"&apebene="+apebene+"&tipo="+tipo+
			  "&orden="+orden+"&campoorden="+campoorden);
}
</script>
</html>