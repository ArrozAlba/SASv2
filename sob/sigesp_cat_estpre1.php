<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	require_once("class_folder/class_funciones_sob.php");
	$io_fun_sob=new class_funciones_sob();
	$ls_tipo=$io_fun_sob->uf_obtenertipo();
	unset($io_fun_sob);
	$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];		
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	switch($ls_modalidad)
	{
		case "1": // Modalidad por Proyecto
			$ls_titulo="Catalogo de Estructura Presupuestaria ".$ls_nomestpro1;
			break;
			
		case "2": // Modalidad por Presupuesto
			$ls_titulo="Catalogo de Estructura Programática ".$ls_nomestpro1;
			break;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php print $ls_titulo; ?> </title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sob.js"></script>
</head>

<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="codestpro1">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td width="500" height="20" colspan="2" class="titulo-ventana"><?php print $ls_titulo; ?>  </td>
    	</tr>
  </table>
	 <br>
	 <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">Codigo</div></td>
        <td width="431"><div align="left">
          <input name="txtcodestpro1" type="text" id="txtcodestpro1" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre</div></td>
        <td><div align="left">
          <input name="txtdenestpro1" type="text" id="txtdenestpro1" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
	<p>
		<div id="resultados" align="center"></div>	
	</p>
	<br>
</div>
</form>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codestpro1,denestpro1,estcla)
{
	opener.document.formulario.txtcodestpro1.value=codestpro1;
	opener.document.formulario.txtdenestpro1.value=denestpro1;
	opener.document.formulario.estcla.value=estcla;
	opener.document.formulario.txtcodestpro2.value="";
	opener.document.formulario.txtdenestpro2.value="";
	opener.document.formulario.txtcodestpro3.value="";
	opener.document.formulario.txtdenestpro3.value="";
	opener.document.formulario.txtcodestpro4.value="";
	opener.document.formulario.txtdenestpro4.value="";
	opener.document.formulario.txtcodestpro5.value="";
	opener.document.formulario.txtdenestpro5.value="";
	close();
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	tipo=f.tipo.value;
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_sob_c_catalogo_ajax.php",true);
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
	ajax.send("catalogo=ESTRUCTURA1&codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&tipo="+tipo+"&orden="+orden+"&campoorden="+campoorden);
}
</script>
</html>