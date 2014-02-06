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
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="cod_pro">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<br>
<table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="2" class="titulo-celda" style="text-align:center">Cat&aacute;logo de Proveedores</td>
      </tr>
      <tr>
        <td height="15" style="text-align:right">&nbsp;</td>
        <td height="15">&nbsp;</td>
      </tr>
      <tr>
        <td width="64" height="22" style="text-align:right">C&oacute;digo</td>
        <td height="22"><div align="left">
          <input name="txtcodpro" type="text" id="txtcodpro" style="text-align:center" onKeyPress="javascript: ue_mostrar(this,event);" size="20" maxlength="10">        
        </div>          
          <div align="right"></div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Nombre</td>
        <td height="22" style="text-align:left"><input name="txtnompro" type="text" id="txtnompro" onKeyPress="javascript: ue_mostrar(this,event);" size="70" maxlength="254"></td>
      <tr>
        <td height="22" style="text-align:right">Direcci&oacute;n</td>
        <td height="22" style="text-align:left"><input name="txtdirpro" type="text" id="txtdirpro" onKeyPress="javascript: ue_mostrar(this,event);" size="70" maxlength="254"></td>
      <tr>
        <td height="22" style="text-align:right">RIF</td>
        <td height="22" style="text-align:left"><input name="txtrifpro" type="text" id="txtrifpro" onKeyPress="javascript: ue_mostrar(this,event);" size="20" maxlength="15"></td>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22" style="text-align:right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0">Buscar</a></td>
  </table> 
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
fop = opener.document.formulario;
f   = document.formulario;
function ue_aceptar_proveedor_solicitud_cotizacion(as_codpro,as_nompro,as_dirpro,as_telpro)
{
	//---------------------------------------------------------------------------------
	// Verificamos que el Proveedor no esté en el formulario
	//---------------------------------------------------------------------------------
	valido = true;
	total  = ue_calcular_total_fila_opener("txtcodpro");
	fop.totrowproveedores.value=total;
	rowproveedores = fop.totrowproveedores.value;
	ls_tipsolcot   = fop.cmbtipsolcot.value;

	for(j=1;(j<=rowproveedores)&&(valido);j++)
	{
		codprogrid=eval("fop.txtcodpro"+j+".value");
		if(codprogrid==as_codpro)
		{
			alert("El Proveedor ya está incluido en la solicitud !!!");
			valido=false;
			
		}
	}
	//---------------------------------------------------------------------------------
	// Cargar los Proveedores del opener y el seleccionado
	//---------------------------------------------------------------------------------
	parametros="";
	
	for(j=1;(j<rowproveedores)&&(valido);j++)
	{
		codpro     = eval("fop.txtcodpro"+j+".value");
		nompro     = eval("fop.txtnompro"+j+".value");
		dirpro     = eval("fop.txtdirpro"+j+".value");
		telpro     = eval("fop.txttelpro"+j+".value");
		parametros = parametros+"&txtcodpro"+j+"="+codpro+"&txtnompro"+j+"="+nompro+"&txtdirpro"+j+"="+dirpro+"&txttelpro"+j+"="+telpro;
	}
	totalproveedores=eval(rowproveedores+"+1");
	parametros=parametros+"&txtcodpro"+rowproveedores+"="+as_codpro+"&txtnompro"+rowproveedores+"="+as_nompro+"&txtdirpro"+rowproveedores+"="+as_dirpro+"&txttelpro"+rowproveedores+"="+as_telpro+"&totalproveedores="+totalproveedores;
	
	//--------------------------------------------------------------------------------
	// Incorporamos los detalles existentes de los Bienes/Materiales en el formulario
	//--------------------------------------------------------------------------------
	total                  = ue_calcular_total_fila_opener("txtcodart");
	fop.totrowbienes.value = total;
	rowbienes              = fop.totrowbienes.value;
	if (rowbienes>1)
	   {
		 for (j=1;(j<=rowbienes)&&(valido);j++)
			 { 
		       ls_codart    = eval("fop.txtcodart"+j+".value");
		       ls_denart    = eval("fop.txtdenart"+j+".value");
		       ls_canart    = eval("fop.txtcanart"+j+".value");
			   ls_numsep    = eval("fop.hidnumsep"+j+".value"); 
			   ls_codunieje = eval("fop.hidcodunieje"+j+".value"); 
			   ls_codestpro = eval("fop.hidcodestpro"+j+".value"); 
			   ls_estcla    = eval("fop.estcla"+j+".value"); 
			   parametros   = parametros+"&txtcodart"+j+"="+ls_codart+"&txtdenart"+j+"="+ls_denart+"&txtcanart"+j+"="+ls_canart+"&hidnumsep"+j+"="+ls_numsep+"&hidcodestpro"+j+"="+ls_codestpro+"&estcla"+j+"="+ls_estcla+"&hidcodunieje"+j+"="+ls_codunieje;
			 }
		 totalbienes = eval(rowbienes);
		 parametros  = parametros+"&totalbienes="+totalbienes;		   
	   }
	
	//--------------------------------------------------------------------------------
	// Incorporamos los detalles existentes de los servicios en el formulario
	//--------------------------------------------------------------------------------
	   total                     = ue_calcular_total_fila_opener("txtcodser");
	   fop.totrowservicios.value = total;
	   rowservicios              = fop.totrowservicios.value;
	   if (rowservicios>1)
	      {
	        for (j=1;(j<=rowservicios)&&(valido);j++)
	            {
		          codser       = eval("fop.txtcodser"+j+".value");
		          denser       = eval("fop.txtdenser"+j+".value");
		          canser       = eval("fop.txtcanser"+j+".value");
				  ls_numsep    = eval("fop.hidnumsep"+j+".value");
				  ls_codunieje = eval("fop.hidcodunieje"+j+".value"); 
				  ls_codestpro = eval("fop.hidcodestpro"+j+".value"); 
			      ls_estcla    = eval("fop.estcla"+j+".value"); 
		          parametros = parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+"&txtcanser"+j+"="+canser+"&hidnumsep"+j+"="+ls_numsep+"&hidcodestpro"+j+"="+ls_codestpro+"&estcla"+j+"="+ls_estcla+"&hidcodunieje"+j+"="+ls_codunieje;
	            }
	        totalservicios   = eval(rowservicios);
	        parametros       = parametros+"&totalservicios="+totalservicios;
		  } 	

	//--------------------------------------------------------------------------
	// Incorporamos los detalles existentes de las SEP en el formulario
	//--------------------------------------------------------------------------
	li_totrowsep = ue_calcular_total_fila_opener("txtnumsep");
	fop.totrowsep.value = li_totrowsep;
	for(j=1;(j<=li_totrowsep)&&(valido);j++)
	{ 
		ls_numsep  = eval("opener.document.formulario.txtnumsep"+j+".value");
		ls_densep  = eval("opener.document.formulario.txtdensep"+j+".value");
		ld_monsep  = eval("opener.document.formulario.txtmonsep"+j+".value");
		ls_unieje  = eval("opener.document.formulario.txtunieje"+j+".value");
		ls_denuni  = eval("opener.document.formulario.txtdenuni"+j+".value");
     	parametros = parametros+"&txtnumsep"+j+"="+ls_numsep+"&txtdensep"+j+"="+ls_densep+"&txtmonsep"+j+"="+ld_monsep+"&txtunieje"+j+"="+ls_unieje+"&txtdenuni"+j+"="+ls_denuni;
	}
	parametros = parametros+"&totalsep="+li_totrowsep;
	
	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("bienesservicios");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_soc_c_solicitud_cotizacion_ajax.php",true);
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1)
			{
				divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";
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
		ajax.send("tipo="+ls_tipsolcot+"&proceso=AGREGARPROVEEDORES"+parametros);
		fop.totrowproveedores.value=totalproveedores;
    }
}

function ue_aceptar(ls_codpro,ls_nompro,ls_tipconpro,ls_rifpro)
{
	fop.txtcodprov.value = ls_codpro;
	fop.txtnomprov.value = ls_nompro;
	fop.tipconpro.value = ls_tipconpro;
	fop.txtrifpro.value=ls_rifpro;
	close();
}

function ue_aceptar_proveedor_registro_cotizacion(ls_codpro,ls_nompro,ls_tipconpro)
{
	fop.txtcodprov.value   = ls_codpro;
	fop.txtnomprov.value   = ls_nompro;
	fop.hidtipconpro.value = ls_tipconpro;
	close();
}

function ue_search()
{
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
	ajax.send("catalogo=PROVEEDOR&codpro="+codpro+"&nompro="+nompro+"&dirpro="+dirpro+"&tipo="+tipo+"&orden="+orden+
			  "&campoorden="+campoorden+"&rifpro="+rifpro);
}

function aceptar_reportedesde(ls_codpro)
{
	fop.txtcodprodes.value = ls_codpro;
	close();
}

function aceptar_reportehasta(ls_codpro) 
{
	fop.txtcodprohas.value = ls_codpro;
	close();
}
</script>
</html>