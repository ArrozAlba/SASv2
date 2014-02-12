<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
//-----------------------------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cotizaciones</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
</head>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="s.numcot">
<input name="orden" type="hidden" id="orden" value="ASC">
<input type="hidden" name="numsol" id="numsol">
<br>
    <table width="630" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="4">Cat&aacute;logo de Cotizaciones</td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
        <td height="15">&nbsp;</td>
      </tr>
      <tr>
        <td width="128" height="22" style="text-align:right">N&deg; Solicitud Cotizaci&oacute;n</td>
        <td width="189" height="22"><input type="text" name="txtnumsol" onBlur="javascript:rellenar_cad(this,15)"  style="text-align:center " maxlength="15"></td>
        <td width="130" height="22" style="text-align:right">Fecha Inicio</td>
        <td width="143" height="22"><input name="txtfecini" type="text" id="txtfecini" size="11" maxlength="10" datepicker="true"  value="<?PHP print "01/".date("m/Y")?>" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">N&deg; Cotizaci&oacute;n</td>
        <td height="22"><input name="txtnumcot" type="text" id="txtnumcot" onBlur="javascript:rellenar_cad(this,15)"  style="text-align:center "></td>
        <td height="22" style="text-align:right">Fecha Fin</td>
        <td height="22"><input name="txtfecfin" type="text" id="txtfecfin" size="11" maxlength="10" datepicker="true" value="<?PHP print date("d/m/Y")?>" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Proveedor</td>
        <td height="22" colspan="2"><input name="txtcodpro" type="text" id="txtcodpro"></td>
        <td height="22">&nbsp;</td>
      </tr>
	  <tr>
        <td height="22" colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0">Buscar</a><a href="javascript: ue_close();"> <img src="../shared/imagebank/eliminar.gif" width="15" height="15" class="sin-borde">Cerrar</a></div></td>
	  </tr>
  </table> 
	<p>
  <div id="resultados" align="center"></div>
	</p>
</form>      
</body>
<script language="JavaScript">
function ue_aceptar(as_numsolcot,as_numcot,as_nompro,as_codpro,ad_feccot,ai_poriva,ai_montotcot,as_tipsolcot)
{
	//---------------------------------------------------------------------------------
	// Verificamos que la cotizacion no esté en el formulario
	//---------------------------------------------------------------------------------
	valido=true;
	ls_sqlaux = "";
	total=ue_calcular_total_fila_opener("txtnumcot");
	//Validando que seleccione solo cotizaciones de la misma solicitud
	if((opener.document.formulario.txtnumsol1.value!="") && (as_numsolcot!=opener.document.formulario.txtnumsol1.value))
	{
		alert("Por favor seleccione cotizaciones que pertenezcan a la misma solicitud !!!");
		valido=false;			
	}
	for(j=1;(j<=total)&&(valido);j++)
	{
		numcotgrid=eval("opener.document.formulario.txtnumcot"+j+".value");
		if(numcotgrid==as_numcot)
		{
			alert("La cotización ya fue seleccionada !!!");
			valido=false;
		}
	}
	//---------------------------------------------------------------------------------
	// Cargar las cotizaciones del opener y el seleccionado
	//---------------------------------------------------------------------------------
	parametros="";
	for (j=1;(j<total)&&(valido);j++)
	    {
		  ls_numcot = eval("opener.document.formulario.txtnumcot"+j+".value");
		  if ((j==1) && (ls_numcot!=""))
		     {
			   ls_sqlaux = ls_sqlaux+" numcot = "+ls_numcot+";";
			 }
		  else
		     {
			   if ((j>1) && (ls_numcot!=""))
			      {
				    ls_sqlaux = ls_sqlaux+" OR numcot = "+ls_numcot+";";
				  }
			 }
		  ls_nompro	   = eval("opener.document.formulario.txtnompro"+j+".value");
		  ls_codpro	   = eval("opener.document.formulario.txtcodpro"+j+".value");
		  ls_fecha	   = eval("opener.document.formulario.txtfecha"+j+".value");
		  ld_monto	   = eval("opener.document.formulario.txtmonto"+j+".value");
		  ld_iva	   = eval("opener.document.formulario.txtiva"+j+".value");		
		  ls_tipsolcot = eval("opener.document.formulario.txttipsolcot"+j+".value");
		  ls_numsol	   = eval("opener.document.formulario.txtnumsol"+j+".value");	
		  parametros   = parametros+"&txtnumsol"+j+"="+ls_numsol+"&txtnumcot"+j+"="+ls_numcot+"&txtnompro"+j+"="+ls_nompro+
		  							"&txtcodpro"+j+"="+ls_codpro+"&txtfecha"+j+"="+ls_fecha+"&txtmonto"+j+"="+ld_monto+
									"&txtiva"+j+"="+ld_iva+"&txttipsolcot"+j+"="+ls_tipsolcot;
	}
	totalcotizaciones=total+1;
	parametros=parametros+"&txtnumsol"+total+"="+as_numsolcot+"&txtnumcot"+total+"="+as_numcot+"&txtnompro"+total+"="+as_nompro+
				          "&txtcodpro"+total+"="+as_codpro+"&txtfecha"+total+"="+ad_feccot+"&txtmonto"+total+"="+ai_montotcot+""+
			   			  "&txtiva"+total+"="+ai_poriva+""+"&txttipsolcot"+total+"="+as_tipsolcot+"&totalcotizaciones="+totalcotizaciones+"";
	
	
	
	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("cotizaciones");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_soc_c_analisis_cotizacion_ajax.php",true);
		ajax.onreadystatechange=function(){
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
		ajax.send("proceso=AGREGARCOTIZACIONES"+parametros);
	}
	li_totrows = ue_calcular_total_fila_opener("txtcoditem");
	for (i=1;i<=li_totrows;i++)
	    {
		  ls_coditem     = eval("opener.document.formulario.txtcoditem"+i+".value");
		  ls_nomitem     = eval("opener.document.formulario.txtnomitem"+i+".value");
		  ls_nompro      = eval("opener.document.formulario.txtnomproitem"+i+".value");
		  ls_codpro      = eval("opener.document.formulario.txtcodproselec"+i+".value");
		  ld_cantidad    = eval("opener.document.formulario.txtcanselec"+i+".value");
		  ld_precio      = eval("opener.document.formulario.txtpreuniselec"+i+".value");
		  ld_moniva      = eval("opener.document.formulario.txtivaselec"+i+".value");
		  ld_monto       = eval("opener.document.formulario.txtmonselec"+i+".value");
		  ls_observacion = eval("opener.document.formulario.txtobservacion"+i+".value");
		  ls_numcot      = eval("opener.document.formulario.txtnumcotsele"+i+".value");
		  parametros     = parametros+"&txtcoditem"+i+"="+ls_coditem+"&txtnomitem"+i+"="+ls_nomitem+"&txtnomproitem"+i+"="+ls_nompro+
		                              "&txtcodproselec"+i+"="+ls_codpro+"&txtcanselec"+i+"="+ld_cantidad+"&txtpreuniselec"+i+"="+ld_precio+
									  "&txtivaselec"+i+"="+ld_moniva+"&txtmonselec"+i+"="+ld_monto+"&txtobservacion"+i+"="+ls_observacion+
									  "&txtnumcotsele"+i+"="+ls_numcot;
		}

	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados	
		divgrid2 = opener.document.getElementById("items");
		// Instancia del Objeto AJAX
		ajax2=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax2.open("POST","class_folder/sigesp_soc_c_analisis_cotizacion_ajax.php",true);
		ajax2.onreadystatechange=function(){
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
		// Enviar todos los campos a la pagina para que haga el procesamiento*/
		ajax2.send("proceso=AGREGARITEMS"+parametros+"&hidtotrows="+li_totrows+"&hidnumcot="+as_numcot+"&hidcodpro="+as_codpro+"&hidsqlaux="+ls_sqlaux);
	}
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX	
	numsol=f.txtnumsol.value;
	numcot=f.txtnumcot.value;
	codpro=f.txtcodpro.value;
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
	ajax.send("catalogo=COTIZACION_ANALISIS&orden="+orden+"&campoorden="+campoorden+"&numsol="+numsol+"&numcot="+numcot+"&codpro="+codpro+"&fecini="+fecini+"&fecfin="+fecfin);
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
	if (li_long==2)
	   {
	     ls_date   = ls_date+"/";
		 ls_string = ls_date.substr(0,2);
		 li_string = parseInt(ls_string,10);
		 if ((li_string>=1)&&(li_string<=31))
			{
			  date.value=ls_date;
			}
		 else
			{
			  date.value="";
			}
	   }
	if (li_long==5)
	   {
	     ls_date   = ls_date+"/";
		 ls_string = ls_date.substr(3,2);
		 li_string = parseInt(ls_string,10);
		 if ((li_string>=1)&&(li_string<=12))
			{
			  date.value=ls_date;
			}
			else
			{
			  date.value=ls_date.substr(0,3);
			}
	   }
	if (li_long==10)
	   {
	     ls_string = ls_date.substr(6,4);
		 li_string = parseInt(ls_string,10);
		 if ((li_string>=1900)&&(li_string<=2090))
			{
			  date.value=ls_date;
			}
		 else
			{
			  date.value=ls_date.substr(0,6);
			}
	   }
   }
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);	
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>