<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	if(array_key_exists("catalogo",$_GET))
	{
		$ls_opener=$_GET["catalogo"];
	}
	else
	{
		$ls_opener="";
	}
	//-----------------------------------------------------------------------------------------------------------------------------------
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Analisis Cotizaciones</title>
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
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>

</head>
<script type="text/javascript"  src="js/funcion_soc.js" language="javascript"></script>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="numanacot">
<input name="orden" type="hidden" id="orden" value="ASC">
<input type="hidden" name="numsol" id="numsol">
<input type="hidden" name="origen" id="origen" value="<?php print $ls_opener?>">
<br>
    <table width="630" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="4" class="titulo-celda">Cat&aacute;logo de An&aacute;lisis de Cotizaciones</td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">N&deg; Solicitud Cotizaci&oacute;n</div></td>
        <td width="189" height="22"><input type="text" name="txtnumsol"  id='txtnumsol' onBlur="javascript:rellenar_cad(this,15)"  style="text-align:center "></td>
        <td width="130" height="22"><div align="right">Fecha Inicio </div></td>
        <td width="143" height="22"><input name="txtfecini" type="text" id="txtfecini" size="11" maxlength="10" datepicker="true"  value="<?PHP print "01/".date("m/Y")?>" onKeyPress="javascript:currencyDate(this)"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">N&deg; An&aacute;lisis de Cotizaci&oacute;n</div></td>
        <td height="22"><input name="txtnumanacot" type="text" id="txtnumanacot" onBlur="javascript:rellenar_cad(this,15)" style="text-align:center"></td>
        <td height="22"><div align="right">Fecha Fin </div></td>
        <td height="22"><input name="txtfecfin" type="text" id="txtfecfin" size="11" maxlength="10" datepicker="true" value="<?PHP print date("d/m/Y")?>" onKeyPress="javascript:currencyDate(this)"></td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td height="15" colspan="2">&nbsp;</td>
        <td height="15">&nbsp;</td>
      </tr>
	  <tr>
        <td height="22" colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a><a href="javascript: ue_close();"> <img src="../shared/imagebank/eliminar.gif" width="15" height="15" class="sin-borde">Cerrar</a></div></td>
	  </tr>
  </table> 
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script type="text/javascript"  src="js/funcion_soc.js" language="javascript"></script>
<script language="JavaScript">
function ue_aceptar(numanacot,fecanacot,obsana,numsolcot,tipsolcot,estana)
{
	ls_opener=document.formulario.origen.value;
	if(ls_opener=="")
	{
		parametros="&numanacot="+numanacot+"&fecanacot="+fecanacot+"&obsana="+obsana+"&numsolcot="+numsolcot+"&txttipsolcot1="+tipsolcot;
		opener.document.formulario.txtnumero.value=numanacot;
		opener.document.formulario.txtfecha.value=fecanacot;
		opener.document.formulario.txtobservacion.value=obsana;
		opener.document.formulario.catalogo.value="T";
		opener.document.formulario.txtestatus.value=estana;
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("cotizaciones");
		// Instancia del Objeto AJAX
			ajax=objetoAjax();
			// Pagina donde están los métodos para buscar y pintar los resultados
			ajax.open("POST","class_folder/sigesp_soc_c_analisis_cotizacion_ajax.php",true);
			ajax.onreadystatechange=function()
			{
				if(ajax.readyState==1)
				{
					//divgrid.innerHTML = "";//<-- aqui iria la precarga en AJAX 
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
			ajax.send("proceso=CARGARCOTIZACIONES"+parametros);
		
		
	
			// Div donde se van a cargar los resultados	
			divgrid2 = opener.document.getElementById("items");
			// Instancia del Objeto AJAX
			ajax2=objetoAjax();
			// Pagina donde están los métodos para buscar y pintar los resultados
			ajax2.open("POST","class_folder/sigesp_soc_c_analisis_cotizacion_ajax.php",true);
			ajax2.onreadystatechange=function()
			{
				if(ajax2.readyState==1)
				{
					//divgrid.innerHTML = "";//<-- aqui iria la precarga en AJAX 
				}
				else
				{
					if(ajax2.readyState==4)
					{
						if(ajax2.status==200)
						{//mostramos los datos dentro del contenedor
							divgrid2.innerHTML = ajax2.responseText
						}
						else
						{
							if(ajax2.status==404)
							{
								divgrid2.innerHTML = "La página no existe";
							}
							else
							{//mostramos el posible error     
								divgrid2.innerHTML = "Error:".ajax2.status;
							}
						}
						
					}
				}
			}
			ajax2.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			// Enviar todos los campos a la pagina para que haga el procesamiento
			ajax2.send("proceso=CARGARITEMS"+parametros);
			//opener.document.formulario.totrowbienes.value=totalbienes;	
		}
		else
		{
			ls_txt=document.formulario.origen.value;
			eval("opener.document.formulario."+ls_txt+".value=numanacot");
		}
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX	
	numsol=f.txtnumsol.value;
	numanacot=f.txtnumanacot.value;
	fecini=f.txtfecini.value;
	fecfin=f.txtfecfin.value;
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
	ajax.send("catalogo=ANALISIS&orden="+orden+"&campoorden="+campoorden+"&numsol="+numsol+"&numanacot="+numanacot+"&fecini="+fecini+"&fecfin="+fecfin);
	//&codart="+codart+"&denart="+denart+"&codtipart="+codtipart+"&codestpro1="+codestpro1+ "&codestpro2="+codestpro2+"&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&codestpro5="+codestpro5+
}

function ue_close()
{
	close();
}

function rellenar_cad(cadena,longitud)
{
  if(cadena.value!="")
  {
	  var mystring=new String(cadena.value);
	  cadena_ceros="";
	  lencad=mystring.length;
	  total=longitud-lencad;
	  for (i=1;i<=total;i++)
	  {
		cadena_ceros=cadena_ceros+"0";
	  }
	  cadena.value=cadena_ceros+cadena.value;
	 }
}
function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
   }
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>