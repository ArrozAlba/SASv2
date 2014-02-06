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
	$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];		
	$ls_nomestpro2=$_SESSION["la_empresa"]["nomestpro2"];		
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	switch($ls_modalidad)
	{
		case "1": // Modalidad por Proyecto
			$ls_titulo="Catalogo de Estructura Presupuestaria ".$ls_nomestpro2;
			break;
			
		case "2": // Modalidad por Presupuesto
			$ls_titulo="Catalogo de Estructura Programática ".$ls_nomestpro2;
			break;
	}
	$ls_codestprog1=$io_fun_sob->uf_obtenervalor_get_post("1","codestpro1","txtcodestpro1","");
	$ls_denestprog1=$io_fun_sob->uf_obtenervalor_get_post("1","denestpro1","txtdenestpro1","");
	$ls_estcla=$io_fun_sob->uf_obtenervalor_get_post("1","estcla","estcla","");
	unset($io_fun_sob);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php print $ls_titulo; ?></title>
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
<input name="campoorden" type="hidden" id="campoorden" value="codestpro2">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
   <tr>
     <td colspan="2" class="titulo-ventana"><?php print $ls_titulo; ?></td>
   </tr>
  </table>
	 <br>
	 <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="137" height="22"><div align="right"><?php print $ls_nomestpro1;?></div></td>
        <td width="461"><div align="left"><?php print $ls_denestprog1; ?>
          <input name="txtcodestpro1" type="hidden" id="txtcodestpro1" value="<?php print $ls_codestprog1; ?>" readonly>        
          <input name="txtdenestpro1" type="hidden" id="txtdenestpro1" value="<?php print $ls_denestprog1; ?>" readonly>
          <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla; ?>" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Codigo</div></td>
        <td><div align="left">
          <input name="txtcodestpro2" type="text" id="txtcodestpro2" size="22" onKeyPress="javascript: ue_mostrar(this,event);">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdenestpro2" type="text" id="txtdenestpro2" size="72" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
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
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codestpro2,denestpro2)
{
	opener.document.formulario.txtcodestpro2.value=codestpro2;
	opener.document.formulario.txtdenestpro2.value=denestpro2;
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
	estcla=f.estcla.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
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
	ajax.send("catalogo=ESTRUCTURA2&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&estcla="+estcla+
	          "&tipo="+tipo+"&orden="+orden+"&campoorden="+campoorden);
}
</script>
</html>