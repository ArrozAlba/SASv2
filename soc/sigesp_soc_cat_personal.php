<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_folder/class_funciones_soc.php");
	$io_fun_soc=new class_funciones_soc();
	$ls_tipo=$io_fun_soc->uf_obtenertipo();
	unset($io_fun_soc);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Personal</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<body>
<?php
if (array_key_exists("operacion",$_POST))
   {
     $ls_origen = $_POST["origen"];
   }
else
   {
     $ls_origen = $_GET["origen"];
   }
?>
<form name="formulario" method="post" action="">
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-celda">
        <td height="22" colspan="2"><input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
          <input name="orden" type="hidden" id="orden" value="ASC">
          Cat&aacute;logo de Personal 
        <input name="campoorden" type="hidden" id="campoorden" value="cedper">
        <input name="origen" type="hidden" id="origen" value="<?php print $ls_origen ?>"></td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
      </tr>
      <tr>
        <td width="67" height="22"><div align="right">C&eacute;dula</div></td>
        <td width="431" height="22"><div align="left">
          <input name="txtcedper" type="text" id="txtcedper" size="20" maxlength="10" onKeyPress="javascript: ue_mostrar(this,event);" style="text-align:center">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td height="22"><div align="left">
          <input name="txtnomper" type="text" id="txtnomper" size="75" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);" style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Apellido</div></td>
        <td height="22"><div align="left">
            <input name="txtapeper" type="text" id="txtapeper" size="75" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);" style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>
</body>
<script language="JavaScript">
f   = document.formulario;
fop = opener.document.formulario;

function ue_aceptar(ls_cedper,ls_nomper,ls_apeper,ls_codcarper)
{
	ls_origen 			   = f.origen.value;
	fop.txtcedper.value    = ls_cedper;
    fop.txtnompersol.value = ls_apeper+" ,"+ls_nomper;
    if (ls_origen=='SC')
	   {
	     fop.txtcodcarper.value = ls_codcarper;   
	   }
    close();
}

function ue_search()
{
	// Cargamos las variables para pasarlas al AJAX
	cedper     = f.txtcedper.value;
	nomper     = f.txtnomper.value;
	apeper     = f.txtapeper.value;
	tipo       = f.tipo.value;
	orden      = f.orden.value;
	campoorden = f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_soc_c_catalogo_ajax.php",true);
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
	ajax.send("catalogo=PERSONAL&cedper="+cedper+"&nomper="+nomper+"&apeper="+apeper+"&tipo="+tipo+"&orden="+orden+"&campoorden="+campoorden);
}
</script>
</html>