<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_tipo=$io_fun_cxp->uf_obtenertipo();
	$ls_nomestpro1=$_SESSION["la_empresa"]["nomestpro1"];		
	$ls_nomestpro2=$_SESSION["la_empresa"]["nomestpro2"];		
	$ls_nomestpro3=$_SESSION["la_empresa"]["nomestpro3"];		
	$ls_nomestpro4=$_SESSION["la_empresa"]["nomestpro4"];		
	$ls_nomestpro5=$_SESSION["la_empresa"]["nomestpro5"];		
	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	switch($ls_modalidad)
	{
		case "1": // Modalidad por Proyecto
			$ls_titulo="Catalogo de Estructura Presupuestaria ".$ls_nomestpro4;
			break;
			
		case "2": // Modalidad por Presupuesto
			$ls_titulo="Catalogo de Estructura Programática ".$ls_nomestpro4;
			break;
	}
	$ls_codestprog1=$io_fun_cxp->uf_obtenervalor_get_post("1","codestpro1","txtcodestpro1","");
	$ls_denestprog1=$io_fun_cxp->uf_obtenervalor_get_post("1","denestpro1","txtdenestpro1","");
	$ls_codestprog2=$io_fun_cxp->uf_obtenervalor_get_post("1","codestpro2","txtcodestpro2","");
	$ls_denestprog2=$io_fun_cxp->uf_obtenervalor_get_post("1","denestpro2","txtdenestpro2","");
	$ls_codestprog3=$io_fun_cxp->uf_obtenervalor_get_post("1","codestpro3","txtcodestpro3","");
	$ls_denestprog3=$io_fun_cxp->uf_obtenervalor_get_post("1","denestpro3","txtdenestpro3","");
	$ls_estcla=$io_fun_cxp->uf_obtenervalor_get_post("1","estcla","estcla","");
	unset($io_fun_cxp);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php print $ls_titulo;?></title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
</head>

<body>
<form name="formulario" method="post" action="">
  <p align="left">
    <input name="campoorden" type="hidden" id="campoorden" value="codestpro4">
    <input name="orden" type="hidden" id="orden" value="ASC">
    <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
</p>
  	 <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    	<tr>
     	 	<td height="20" colspan="2" class="titulo-ventana"><?php print $ls_titulo;?></td>
    	</tr>
	 </table>
	 <br>
	 <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="118" height="22"><div align="right"><?php print $ls_nomestpro1; ?></div></td>
        <td width="380"><div align="left"><?php print $ls_denestprog1; ?>
          <input name="txtcodestpro1" type="hidden" id="txtcodestpro1" value="<?php print $ls_codestprog1; ?>" readonly>        
          <input name="txtdenestpro1" type="hidden" id="txtdenestpro1" value="<?php print $ls_denestprog1; ?>" readonly>
		  <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla; ?>" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $ls_nomestpro2; ?></div></td>
        <td><div align="left"><?php print $ls_denestprog2; ?>
          <input name="txtcodestpro2" type="hidden" id="txtcodestpro2" value="<?php print  $ls_codestprog2; ?>" readonly>
          <input name="txtdenestpro2" type="hidden" id="txtdenestpro2" value="<?php print $ls_denestprog2; ?>" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $ls_nomestpro3; ?></div></td>
        <td><div align="left"><?php print $ls_denestprog3; ?>
          <input name="txtcodestpro3" type="hidden" id="txtcodestpro3" value="<?php print  $ls_codestprog3; ?>" readonly>
          <input name="txtdenestpro3" type="hidden" id="txtdenestpro3" value="<?php print $ls_denestprog3; ?>" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Codigo</div></td>
        <td><input name="txtcodestpro4" type="text" id="txtcodestpro4"  size="22" maxlength="3" onKeyPress="javascript: ue_mostrar(this,event);"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominacion</div></td>
        <td><div align="left">
          <input name="txtdenestpro4" type="text" id="txtdenestpro4"  size="72" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);">
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
	<br>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(codestpro4,denestpro4)
{
	opener.document.formulario.txtcodestpro4.value=codestpro4;
	opener.document.formulario.txtdenestpro4.value=denestpro4;
	opener.document.formulario.txtcodestpro5.value="";
	opener.document.formulario.txtdenestpro5.value="";
	close();
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	estcla=f.estcla.value;
	codestpro1=f.txtcodestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	codestpro3=f.txtcodestpro3.value;
	codestpro4=f.txtcodestpro4.value;
	denestpro4=f.txtdenestpro4.value;
	tipo=f.tipo.value;
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
	ajax.send("catalogo=ESTRUCTURA4&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+
			  "&codestpro4="+codestpro4+"&denestpro4="+denestpro4+"&estcla="+estcla+"&tipo="+tipo+"&orden="+orden+"&campoorden="+campoorden);
}
</script>
</html>