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
	$io_funciones_soc=new class_funciones_soc();
	$ls_tipsol=$io_funciones_soc->uf_obtenervalor_get("tipsol",""); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Servicios</title>
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
<!--<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
--><body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="codser">
<input name="orden" type="hidden" id="orden" value="ASC">
<br>
    <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td height="22" colspan="2" class="titulo-celda">Cat&aacute;logo de Servicios </td>
      </tr>
      <tr>
        <td height="15"><span class="titulo-celda">
          <input name="tipsol" type="hidden" id="tipsol" value="<?php print $ls_tipsol; ?>">
        </span></td>
        <td height="15">&nbsp;</td>
      </tr>
      <tr>
        <td width="82" height="22" style="text-align:right">C&oacute;digo</td>
        <td width="412" height="22" style="text-align:left"><input name="txtcodser" type="text" id="txtcodser" onKeyPress="javascript: ue_mostrar(this,event);" style="text-align:center" maxlength="10"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Denominaci&oacute;n</td>
        <td height="22"><input name="txtdenser" type="text" id="nombre" onKeyPress="javascript: ue_mostrar(this,event);" size="65"></td>
      </tr>
	  <tr>
        <td colspan="2" style="text-align:right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a><a href="javascript: ue_close();"> <img src="../shared/imagebank/eliminar.gif" width="15" height="15" class="sin-borde">Cerrar</a></td>
	  </tr>
	</table> 
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
function ue_search()
{
	f   = document.formulario;
	fop = opener.document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	ls_tipo=fop.tipo.value;
	if((ls_tipo=="REPDES")||(ls_tipo=="REPHAS"))
	{
		codestpro1="";
		codestpro2="";
		codestpro3="";
		codestpro4="";
		codestpro5="";
		ls_estcla = "";
	}
	else
	{
		codestpro1 = fop.txtcodestpro1.value;
		codestpro2 = fop.txtcodestpro2.value;
		codestpro3 = fop.txtcodestpro3.value;
		codestpro4 = fop.txtcodestpro4.value;
		codestpro5 = fop.txtcodestpro5.value;
		ls_estcla  = fop.hidestcla.value;
	}
	codser     = f.txtcodser.value;
	denser     = f.txtdenser.value;
	orden      = f.orden.value;
	campoorden = f.campoorden.value;
	ls_codunieje = opener.document.formulario.txtcodunieje.value;
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
	ajax.send("catalogo=SERVICIOS&codser="+codser+"&denser="+denser+"&codestpro1="+codestpro1+"&codestpro2="+codestpro2+
			  "&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&codestpro5="+codestpro5+"&orden="+orden+
			  "&campoorden="+campoorden+"&tipo="+ls_tipo+"&codunieje="+ls_codunieje+"&hidestcla="+ls_estcla);
}

function ue_aceptar(as_codser,as_denser,ad_mondiscta)
{
	//---------------------------------------------------------------------------------
	// Verificamos que el Servicio no esté en el formulario
	//---------------------------------------------------------------------------------
	valido = true;
	li_valdispre = "<?php print $_SESSION["la_empresa"]["estparsindis"] ?>";//Validar disponibilidad Presupuestaria.
    if (li_valdispre==1 && ad_mondiscta<=0)
	   {
	     alert("La Partida Presupuestaria asociada al Item, No tiene Disponibilidad Presupuestaria !!!");
		 valido = false;
	   }
	   
	total = ue_calcular_total_fila_opener("txtcodser");
	opener.document.formulario.totrowservicios.value=total;
	rowservicios=opener.document.formulario.totrowservicios.value;
	for(j=1;(j<=rowservicios)&&(valido);j++)
	{
		codsergrid=eval("opener.document.formulario.txtcodser"+j+".value");
		if(codsergrid==as_codser)
		{
			alert("El Servicio ya está en la solicitud !!!");
			valido=false;
			
		}
	}
	//---------------------------------------------------------------------------------
	// Cargar los Servicios del opener y el seleccionado
	//---------------------------------------------------------------------------------
	parametros="";
	
	for(j=1;(j<rowservicios)&&(valido);j++)
	{
		codser       = eval("opener.document.formulario.txtcodser"+j+".value");
		denser       = eval("opener.document.formulario.txtdenser"+j+".value");
		canser       = eval("opener.document.formulario.txtcanser"+j+".value");
		ls_numsep    = eval("opener.document.formulario.hidnumsep"+j+".value");
	    ls_codestpro = eval("opener.document.formulario.hidcodestpro"+j+".value");
		ls_estcla    = eval("opener.document.formulario.estcla"+j+".value");
		parametros   = parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+"&txtcanser"+j+"="+canser+"&hidnumsep"+j+"="+ls_numsep+"&hidcodunieje"+j+"="+ls_codunieje+"&hidcodestpro"+j+"="+ls_codestpro+"&estcla"+j+"="+ls_estcla;
	}
	totalservicios = eval(rowservicios+"+1");
    ls_codestpro1  = opener.document.formulario.txtcodestpro1.value;
	ls_codestpro2  = opener.document.formulario.txtcodestpro2.value;
	ls_codestpro3  = opener.document.formulario.txtcodestpro3.value;
	ls_codestpro4  = opener.document.formulario.txtcodestpro4.value;
	ls_codestpro5  = opener.document.formulario.txtcodestpro5.value;
	ls_codestpro   = ls_codestpro1+ls_codestpro2+ls_codestpro3+ls_codestpro4+ls_codestpro5;
	ls_estcla      = opener.document.formulario.hidestcla.value;
	parametros     = parametros+"&txtcodser"+rowservicios+"="+as_codser+"&txtdenser"+rowservicios+"="+as_denser+"&txtcanser"+rowservicios+"=0,00"+"&totalservicios="+totalservicios+"&hidcodunieje"+rowservicios+"="+ls_codunieje+"&hidcodestpro"+rowservicios+"="+ls_codestpro+"&estcla"+rowservicios+"="+ls_estcla;
	
	total=ue_calcular_total_fila_opener("txtcodpro");
	opener.document.formulario.totrowproveedores.value=total;
	rowproveedores = opener.document.formulario.totrowproveedores.value;
	for(j=1;(j<=rowproveedores)&&(valido);j++)
	{ 
		codpro     = eval("opener.document.formulario.txtcodpro"+j+".value");
		nompro     = eval("opener.document.formulario.txtnompro"+j+".value");
		dirpro     = eval("opener.document.formulario.txtdirpro"+j+".value");
		telpro     = eval("opener.document.formulario.txttelpro"+j+".value");
		parametros = parametros+"&txtcodpro"+j+"="+codpro+"&txtnompro"+j+"="+nompro+"&txtdirpro"+j+"="+dirpro+"&txttelpro"+j+"="+telpro;
	}
	parametros = parametros+"&totalproveedores="+rowproveedores;

	//--------------------------------------------------------------------------
	// Incorporamos los detalles existentes de las SEP en el formulario
	//--------------------------------------------------------------------------
	li_totrowsep = ue_calcular_total_fila_opener("txtnumsep");
	opener.document.formulario.totrowsep.value = li_totrowsep;
	for(j=1;(j<=li_totrowsep)&&(valido);j++)
	{ 
		ls_numsep = eval("opener.document.formulario.txtnumsep"+j+".value");
		ls_densep = eval("opener.document.formulario.txtdensep"+j+".value");
		ld_monsep = eval("opener.document.formulario.txtmonsep"+j+".value");
		ls_unieje = eval("opener.document.formulario.txtunieje"+j+".value");
		ls_denuni = eval("opener.document.formulario.txtdenuni"+j+".value");
		
		parametros = parametros+"&txtnumsep"+j+"="+ls_numsep+"&txtdensep"+j+"="+ls_densep+"&txtmonsep"+j+"="+ld_monsep+"&txtunieje"+j+"="+ls_unieje+"&txtdenuni"+j+"="+ls_denuni;
	}
	parametros = parametros+"&totalsep="+li_totrowsep+"&tipo=SC";

	
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
		ajax.send("proceso=AGREGARSERVICIOS"+parametros);
		opener.document.formulario.totrowservicios.value=totalservicios;
	}
}

function ue_aceptar_servicio_orden_compra(as_codser,as_denser,ai_precio,as_spg_cuenta,ai_existecuenta,ad_mondiscta)
{
	//---------------------------------------------------------------------------------
	// Verificamos que el Servicio no esté en el formulario
	//---------------------------------------------------------------------------------
	valido = true;
	li_valdispre = "<?php print $_SESSION["la_empresa"]["estparsindis"] ?>";//Validar disponibilidad Presupuestaria.
    if (li_valdispre==1 && ad_mondiscta<=0)
	   {
	     alert("La Partida Presupuestaria asociada al Item, No tiene Disponibilidad Presupuestaria !!!");
		 valido = false;
	   }

	total=ue_calcular_total_fila_opener("txtcodser");
	opener.document.formulario.totrowservicios.value=total;
	ls_coduniadm  = opener.document.formulario.txtcodunieje.value;
	ls_denuniadm  = opener.document.formulario.txtdenunieje.value;
	ls_codestpro1 = opener.document.formulario.txtcodestpro1.value;
	ls_codestpro2 = opener.document.formulario.txtcodestpro2.value;
	ls_codestpro3 = opener.document.formulario.txtcodestpro3.value;
	ls_codestpro4 = opener.document.formulario.txtcodestpro4.value;
	ls_codestpro5 = opener.document.formulario.txtcodestpro5.value;
	ls_estclauni  = opener.document.formulario.hidestcla.value;
	ls_codestpre  = ls_codestpro1+ls_codestpro2+ls_codestpro3+ls_codestpro4+ls_codestpro5; 
	rowservicios  = opener.document.formulario.totrowservicios.value;
	ls_tipsol  = opener.document.formulario.txttipsol.value;
	for(j=1;(j<=rowservicios)&&(valido);j++)
	{
		codsergrid=eval("opener.document.formulario.txtcodser"+j+".value");
		if(codsergrid==as_codser)
		{
			alert("El Servicio ya está en la Orden de Compra !!!");
			valido=false;
		}
	}
	//---------------------------------------------------------------------------------
	// Cargar los Servicios del opener y el seleccionado
	//---------------------------------------------------------------------------------
	parametros="";
	for(j=1;(j<rowservicios)&&(valido);j++)
	{
		codser		 = eval("opener.document.formulario.txtcodser"+j+".value");
		denser		 = eval("opener.document.formulario.txtdenser"+j+".value");
		canser		 = eval("opener.document.formulario.txtcanser"+j+".value");
		preser		 = eval("opener.document.formulario.txtpreser"+j+".value");
		subtotser	 = eval("opener.document.formulario.txtsubtotser"+j+".value");
		carser		 = eval("opener.document.formulario.txtcarser"+j+".value");
		totser       = eval("opener.document.formulario.txttotser"+j+".value");
		spgcuenta	 = eval("opener.document.formulario.txtspgcuenta"+j+".value");
		ls_codestpro = eval("opener.document.formulario.hidcodestpro"+j+".value");
		ls_codunieje = eval("opener.document.formulario.txtcoduniadmsep"+j+".value");
		ls_denunieje = eval("opener.document.formulario.txtdenuniadmsep"+j+".value");
		ls_estcla    = eval("opener.document.formulario.estcla"+j+".value");
		ls_numsep    = eval("opener.document.formulario.txtnumsolord"+j+".value"); 
		//ls_hidspgcuentas= eval("opener.document.formulario.txtspgcuenta"+j+".value");

		parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
				   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
				   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
				   "&hidcodestpro"+j+"="+ls_codestpro+"&estcla"+j+"="+ls_estcla+""+
				   "&txtdenuniadmsep"+j+"="+ls_denunieje+"&txtnumsolord"+j+"="+ls_numsep+""+
				   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+spgcuenta+"&txtcoduniadmsep"+j+"="+ls_codunieje;
	}
	totalservicios=eval(rowservicios+"+1");
	parametros=parametros+"&txtcodser"+rowservicios+"="+as_codser+"&txtdenser"+rowservicios+"="+as_denser+""+
			   "&txtcanser"+rowservicios+"=0,00"+"&txtpreser"+rowservicios+"="+ai_precio+"&txtsubtotser"+rowservicios+"=0,00"+
			   "&txtcarser"+rowservicios+"=0,00&txttotser"+rowservicios+"=0,00"+"&txtspgcuenta"+rowservicios+"="+as_spg_cuenta+
			   "&totalservicios="+totalservicios+"&hidcodestpro"+rowservicios+"="+ls_codestpre+
			   "&txtdenuniadmsep"+rowservicios+"="+ls_denuniadm+"&txtnumsolord"+rowservicios+"=---------------"+
			   "&estcla"+rowservicios+"="+ls_estclauni;
	//---------------------------------------------------------------------------------
	// Cargar los Cargos del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de los cargos y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcodservic");
	opener.document.formulario.totrowcargos.value=total;
	rowcargos=opener.document.formulario.totrowcargos.value;
	for(j=1;(j<=rowcargos)&&(valido);j++)
	{
		codservic=eval("opener.document.formulario.txtcodservic"+j+".value");
		codcar=eval("opener.document.formulario.txtcodcar"+j+".value");
		dencar=eval("opener.document.formulario.txtdencar"+j+".value");
		bascar=eval("opener.document.formulario.txtbascar"+j+".value");
		moncar=eval("opener.document.formulario.txtmoncar"+j+".value");
		subcargo=eval("opener.document.formulario.txtsubcargo"+j+".value");
		cuentacargo  = eval("opener.document.formulario.cuentacargo"+j+".value");
		formulacargo = eval("opener.document.formulario.formulacargo"+j+".value");
		ls_numsep    = eval("opener.document.formulario.hidnumsepcar"+j+".value");
		codprogcargo=eval("opener.document.formulario.codprogcargo"+j+".value");
		estclacargo=eval("opener.document.formulario.estclacargo"+j+".value");
		
		parametros=parametros+"&txtcodservic"+j+"="+codservic+"&txtcodcar"+j+"="+codcar+
				   "&txtdencar"+j+"="+dencar+"&txtbascar"+j+"="+bascar+
				   "&txtmoncar"+j+"="+moncar+"&txtsubcargo"+j+"="+subcargo+
				   "&cuentacargo"+j+"="+cuentacargo+"&formulacargo"+j+"="+formulacargo+"&hidnumsepcar"+j+"="+ls_numsep
				   +"&codprogcargo"+j+"="+codprogcargo+"&estclacargo"+j+"="+estclacargo;
	}
	totalcargos=eval(rowcargos);
	parametros=parametros+"&txtcodservic="+as_codser+"&totalcargos="+totalcargos+"&estclacargo="+ls_estclauni;
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de las cuentas y asignarlo al total row
/*	total=ue_calcular_total_fila_opener("txtcuentagas");
	opener.document.formulario.totrowcuentas.value=total;
	rowcuentas=opener.document.formulario.totrowcuentas.value;
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{
		codpro=eval("opener.document.formulario.txtcodprogas"+j+".value"); 
		cuenta=eval("opener.document.formulario.txtcuentagas"+j+".value");
		moncue=eval("opener.document.formulario.txtmoncuegas"+j+".value");
		estcla=eval("opener.document.formulario.estclapre"+j+".value");
		parametros=parametros+"&txtcodprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+"&txtmoncuegas"+j+"="+moncue+
		           "&estclapre"+j+"="+estcla;
	}
	programatica="";
	ls_estcla="";
	if(ai_existecuenta!=0)
	{
		programatica = ls_codestpro1+ls_codestpro2+ls_codestpro3+ls_codestpro4+ls_codestpro5;
		ls_estcla    = ls_estclauni;
	}
	totalcuentas=eval(rowcuentas);
	parametros=parametros+"&txtcodprogas"+rowcuentas+"="+programatica+"&txtcuentagas"+rowcuentas+"="+as_spg_cuenta+
			   "&totalcuentas="+totalcuentas+"&estclapre"+rowcuentas+"="+ls_estcla; 
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del Cargo del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de las cuentas y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcuentacar");
	opener.document.formulario.totrowcuentascargo.value=total;
	rowcuentas=opener.document.formulario.totrowcuentascargo.value; 
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{  
		cargo=eval("opener.document.formulario.txtcodcargo"+j+".value");
		codpro=eval("opener.document.formulario.txtcodprocar"+j+".value"); 
		cuenta=eval("opener.document.formulario.txtcuentacar"+j+".value");
		moncue=eval("opener.document.formulario.txtmoncuecar"+j+".value");
		ls_estcla=eval("opener.document.formulario.estclacar"+j+".value");
		parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
				   "&txtmoncuecar"+j+"="+moncue+"&estclacar"+j+"="+ls_estcla;
	}
	totalcuentascargo=eval(rowcuentas);
	parametros=parametros+"&totalcuentascargo="+totalcuentascargo;
	//---------------------------------------------------------------------------------
	// Cargar los totales
	//---------------------------------------------------------------------------------
	subtotal=eval("opener.document.formulario.txtsubtotal.value");
	cargos=eval("opener.document.formulario.txtcargos.value");
	total=eval("opener.document.formulario.txttotal.value");
	tipconpro=opener.document.formulario.tipconpro.value;
	parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total+
	           "&codprounidad="+codestpro1+codestpro2+codestpro3+codestpro4+codestpro5+
			   "&tipo=OC"+"&tipconpro="+tipconpro+"&estcla="+ls_estclauni+"&tipsol="+ls_tipsol;*/
	tipconpro=opener.document.formulario.tipconpro.value;
	parametros=parametros+"&tipconpro="+tipconpro+"&codprounidad="+ls_codestpro1+ls_codestpro2+ls_codestpro3+ls_codestpro4+ls_codestpro5+"&estcla="+ls_estclauni+"&tipsol="+ls_tipsol;
	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("bienesservicios");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_soc_c_registro_orden_compra_ajax.php",true);
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
		ajax.send("proceso=AGREGARSERVICIOS"+parametros);
		opener.document.formulario.totrowservicios.value=totalservicios;
	}
}

function ue_aceptar_reportedesde(as_codser)
{
	opener.document.formulario.txtcodserdes.value=as_codser;
	close();
}

function ue_aceptar_reportehasta(as_codser)
{
	opener.document.formulario.txtcodserhas.value=as_codser;
	close();
}

function ue_close()
{
	close();
}
</script>
</html>