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
<title>Cat&aacute;logo de Recepciones de Documento</title>
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
<body onBlur="javascript:close();">
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="numsol">
<input name="orden" type="hidden" id="orden" value="ASC">
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="500" height="20" colspan="2" class="titulo-ventana">Catalogo de Recepciones de Documento </td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>
		<div id="resultados" align="center"></div>	
	</p>
  <p><br>    
    </p>
</form>  
</body>
<script language="JavaScript">
var ajax=false;
function aceptar(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_dentipdoc,ls_tipproben,ls_codpro,ls_cedbene,li_estcon,li_estpre)
{
	f=opener.document.formulario;
	f.txtnumrecdoc.value=ls_numrecdoc;
	f.txttipdoc.value=ls_codtipdoc;
	f.txtdentipdoc.value=ls_dentipdoc;
	f.txtestcontipdoc.value=li_estcon;	
	f.txtestpretipdoc.value=li_estpre;
	if(ls_tipproben=='P')
	{
		ls_codproben=ls_codpro;
	}
	else
	{
		ls_codproben=ls_cedbene;
	}
	uf_cargar_dtrecepcion(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_tipproben,ls_codproben);

}
function uf_cargar_dtrecepcion(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_tipproben,ls_codproben)
{
	f=opener.document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	divgrid = opener.document.getElementById('detallesrecepcion');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
					uf_cargar_dtnota();
					//close();
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
	ajax.send("funcion=DTRECEPCION&codemp="+ls_codemp+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben);
	
}

function uf_cargar_dtnota()
{
	f=opener.document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	divgrid = opener.document.getElementById('detallesnota');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
					//close();
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
	ajax.send("funcion=DTNOTA");
	
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	ls_numord="<?php print $_GET["numord"]?>";
	ls_tipo="<?php print $_GET["tipproben"]?>";
	ls_codproben="<?php print $_GET["codproben"]?>";
	ls_nomproben="";
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
			divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
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
	ajax.send("catalogo=RECEPCIONESNCND&numord="+ls_numord+"&tipo="+ls_tipo+"&codproben="+ls_codproben+
			  "&orden="+orden+"&campoorden="+campoorden);
}

function ue_close()
{
	close();
}
ue_search();
</script>
</html>