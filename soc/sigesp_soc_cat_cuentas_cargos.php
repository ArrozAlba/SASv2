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
<title>Cat&aacute;logo de Cuentas Presupuestarias para Otros Cr&eacute;ditos</title>
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
<body onLoad="javascript: ue_search();">
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="codpro">
<input name="orden" type="hidden" id="orden" value="ASC">
<br>
    <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
	  <tr>
	    <td height="22" colspan="2" class="titulo-celda">Cat&aacute;logo de Cuentas Presupuestarias para Otros Cr&eacute;ditos </td>
      </tr>
	  <tr>
	    <td height="15">&nbsp;</td>
	    <td height="15">&nbsp;</td>
      </tr>
	  <tr>
	    <td width="93" height="22"><div align="right">Otro Cr&eacute;dito </div></td>
        <td width="401" height="22"><input name="txtcodcar" type="text" id="txtcodcar" style="text-align:center" size="10" maxlength="5" onBlur="javascript: ue_rellenarcampo(this,5);">
        <a href="javascript: ue_catalogo_cargos();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0" /></a>
        <label><input name="txtdencar" type="text" class="sin-borde" id="txtdencar" size="50" readonly style="text-align:left">
        </label>        </td>
	  </tr>
	  <tr>
	    <td height="15">&nbsp;</td>
	    <td height="15">&nbsp;</td>
      </tr>
	</table> 
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
function ue_catalogo_cargos()
{
	window.open("sigesp_soc_cat_cargos.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,resizable=yes,location=no,left=50,top=50,dependent=yes");
}

function ue_aceptar(as_programatica,as_cuenta,as_denominacion,as_codestpro,as_estcla,ad_mondiscta)
{
	//---------------------------------------------------------------------------------
	// Verificamos que la cuenta presupuestaria no esté en el formulario
	//---------------------------------------------------------------------------------
	valido = true;
	li_valdispre = "<?php print $_SESSION["la_empresa"]["estparsindis"] ?>";//Validar disponibilidad Presupuestaria.
    if (li_valdispre==1 && ad_mondiscta<=0)
	   {
	     alert("La Partida Presupuestaria asociada al Item, No tiene Disponibilidad Presupuestaria !!!");
		 valido = false;
	   }
	   
	// Obtenemos el total de filas de los servicios
	total=ue_calcular_total_fila_opener("txtcodser");
	opener.document.formulario.totrowservicios.value=total;
	// Obtenemos el total de filas de los bienes
	total=ue_calcular_total_fila_opener("txtcodart");
	opener.document.formulario.totrowbienes.value=total;
	// Obtenemos el total de filas de los cargos
	total=ue_calcular_total_fila_opener("txtcodservic");
	opener.document.formulario.totrowcargos.value=total;
	//obtener el numero de filas real de las Cuentas y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcuentacar");
	opener.document.formulario.totrowcuentascargo.value=total;
	rowcuentas=opener.document.formulario.totrowcuentascargo.value;
	codcargo=document.formulario.txtcodcar.value;
	if(valido)
	{
		//---------------------------------------------------------------------------------
		// Verificar la existencia del cargo
		//---------------------------------------------------------------------------------
		if(codcargo=="")
		{
			alert("Favor indique el cargo al que sera asignada la cuenta");
			valido=false;
		}
		else
		{
			valido=false;
			rowcargos=opener.document.formulario.totrowcargos.value;
			for(j=1;j<=rowcargos;j++)
			{
				cargo=eval("opener.document.formulario.txtcodcar"+j+".value");
				if(cargo==codcargo)
				{
					valido=true;
					j=rowcargos+2;
				}
			}
			if(valido==false)
			{
				alert("El Cargo no Existe en la Orden de Compra.");
			}
		}
	}
	for(j=1;(j<=rowcuentas)&&(valido);j++)
	{
		cuentagrid   = eval("opener.document.formulario.txtcuentacar"+j+".value");
		cargo		 = eval("opener.document.formulario.txtcodcargo"+j+".value");
		programatica = eval("opener.document.formulario.txtcodprocar"+j+".value");
		ls_estcla    = eval("opener.document.formulario.estclacar"+j+".value");
		if((cuentagrid==as_cuenta)&&(codcargo==cargo)&&(as_codestpro==programatica))
		{
			alert("La cuenta presupuestaria ya está en la solicitud, se va a reemplazar La Estructura.");
			eval("opener.document.formulario.txtprogramaticacar"+j+".value='"+as_programatica+"'");
			eval("opener.document.formulario.txtcodprocar"+j+".value='"+as_codestpro+"'");
			eval("opener.document.formulario.estclacar"+j+".value='"+as_estcla+"'");
			valido=false;			
		}
	}
	tipo=opener.document.formulario.tipord.value;
	parametros="";
	proceso="";
	if(tipo=="B")
	{
		proceso="AGREGARBIENES";
		//---------------------------------------------------------------------------------
		// Cargar los Bienes del opener y el seleccionado
		//---------------------------------------------------------------------------------
		rowbienes=opener.document.formulario.totrowbienes.value;
		for(j=1;(j<rowbienes)&&(valido);j++)
		{
			codart		 = eval("opener.document.formulario.txtcodart"+j+".value");
			denart		 = eval("opener.document.formulario.txtdenart"+j+".value");
			canart       = eval("opener.document.formulario.txtcanart"+j+".value");
			unidad		 = eval("opener.document.formulario.cmbunidad"+j+".value");
			preart		 = eval("opener.document.formulario.txtpreart"+j+".value");
			subtotart	 = eval("opener.document.formulario.txtsubtotart"+j+".value");
			carart		 = eval("opener.document.formulario.txtcarart"+j+".value");
			totart		 = eval("opener.document.formulario.txttotart"+j+".value");
			spgcuenta	 = eval("opener.document.formulario.txtspgcuenta"+j+".value");
			unidadfisica = eval("opener.document.formulario.txtunidad"+j+".value");
			ls_codunieje = eval("opener.document.formulario.txtcoduniadmsep"+j+".value");
			ls_denunieje = eval("opener.document.formulario.txtdenuniadmsep"+j+".value");
			ls_codestpro = eval("opener.document.formulario.hidcodestpro"+j+".value");
			ls_estcla    = eval("opener.document.formulario.estcla"+j+".value");
			ls_numsep 	 = eval("opener.document.formulario.txtnumsolord"+j+".value");
			
			parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
					   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
					   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
					   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
					   "&txtdenuniadmsep"+j+"="+ls_denunieje+"&txtnumsolord"+j+"="+ls_numsep+""+
					   "&txtspgcuenta"+j+"="+spgcuenta+"&txtunidad"+j+"="+unidadfisica+"&txtcoduniadmsep"+j+"="+ls_codunieje+""+
					   "&hidcodestpro"+j+"="+ls_codestpro+"&estcla"+j+"="+ls_estcla;
		}
		parametros=parametros+"&totalbienes="+rowbienes+"";
	}
	if(tipo=="S")
	{
		proceso="AGREGARSERVICIOS";
		//---------------------------------------------------------------------------------
		// Cargar los Servicios del opener y el seleccionado
		//---------------------------------------------------------------------------------
		rowservicios=opener.document.formulario.totrowservicios.value;
		for(j=1;(j<rowservicios)&&(valido);j++)
		{
			codser		 = eval("opener.document.formulario.txtcodser"+j+".value");
			denser		 = eval("opener.document.formulario.txtdenser"+j+".value");
			canser		 = eval("opener.document.formulario.txtcanser"+j+".value");
			preser		 = eval("opener.document.formulario.txtpreser"+j+".value");
			subtotser    = eval("opener.document.formulario.txtsubtotser"+j+".value");
			carser       = eval("opener.document.formulario.txtcarser"+j+".value");
			totser       = eval("opener.document.formulario.txttotser"+j+".value");
			spgcuenta    = eval("opener.document.formulario.txtspgcuenta"+j+".value");
			ls_codunieje = eval("opener.document.formulario.txtcoduniadmsep"+j+".value");
			ls_denunieje = eval("opener.document.formulario.txtdenuniadmsep"+j+".value");
			ls_codestpro = eval("opener.document.formulario.hidcodestpro"+j+".value");
			ls_estcla    = eval("opener.document.formulario.estcla"+j+".value");
			ls_numsep 	 = eval("opener.document.formulario.txtnumsolord"+j+".value");
		
			parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
					   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
					   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
					   "&txtdenuniadmsep"+j+"="+ls_denunieje+"&txtnumsolord"+j+"="+ls_numsep+""+
					   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+spgcuenta+"&txtcoduniadmsep"+j+"="+ls_codunieje+""+
					   "&hidcodestpro"+j+"="+ls_codestpro+"&estcla"+j+"="+ls_estcla
		}
		parametros=parametros+"&totalservicios="+rowservicios+"";
	}
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
		parametros=parametros+"&txtcodservic"+j+"="+codservic+"&txtcodcar"+j+"="+codcar+
				   "&txtdencar"+j+"="+dencar+"&txtbascar"+j+"="+bascar+
				   "&txtmoncar"+j+"="+moncar+"&txtsubcargo"+j+"="+subcargo+
				   "&cuentacargo"+j+"="+cuentacargo+"&formulacargo"+j+"="+formulacargo+"&hidnumsepcar"+j+"="+ls_numsep;
	}
	parametros=parametros+"&totalcargos="+rowcargos;
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias Cargo del opener y el seleccionado
	//---------------------------------------------------------------------------------
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{
		cargo	  = eval("opener.document.formulario.txtcodcargo"+j+".value");
		codpro    = eval("opener.document.formulario.txtcodprocar"+j+".value");
		cuenta	  = eval("opener.document.formulario.txtcuentacar"+j+".value");
		moncue	  = eval("opener.document.formulario.txtmoncuecar"+j+".value");
		ls_estcla = eval("opener.document.formulario.estclacar"+j+".value");
		parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
				              "&txtmoncuecar"+j+"="+moncue+"&estclacar"+j+"="+ls_estcla;
	}
	totalcuentas=eval(rowcuentas+"+"+1);
	opener.document.formulario.totrowcuentascargo.value=totalcuentas;	
	parametros=parametros+"&txtcodcargo"+rowcuentas+"="+codcargo+"&txtcodprocar"+rowcuentas+"="+as_codestpro+"&txtcuentacar"+rowcuentas+"="+as_cuenta+
			   "&totalcuentascargo="+totalcuentas+"&estclacar"+rowcuentas+"="+as_estcla;
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de las cuentas y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcuentagas");
	opener.document.formulario.totrowcuentas.value=total;
	rowcuentas=opener.document.formulario.totrowcuentas.value;
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{  
		ls_codestpro = eval("opener.document.formulario.txtcodprogas"+j+".value");
		cuenta       = eval("opener.document.formulario.txtcuentagas"+j+".value");
		moncue       = eval("opener.document.formulario.txtmoncuegas"+j+".value");
		ls_estcla    = eval("opener.document.formulario.estclapre"+j+".value");
		parametros   = parametros+"&txtcodprogas"+j+"="+ls_codestpro+"&txtcuentagas"+j+"="+cuenta+
				                  "&txtmoncuegas"+j+"="+moncue+"&estclapre"+j+"="+ls_estcla;
	}
	totalcuentas=eval(rowcuentas);
	parametros=parametros+"&totalcuentas="+totalcuentas;
	//---------------------------------------------------------------------------------
	// Cargar los totales
	//---------------------------------------------------------------------------------
	subtotal=eval("opener.document.formulario.txtsubtotal.value");
	cargos=eval("opener.document.formulario.txtcargos.value");
	total=eval("opener.document.formulario.txttotal.value");
	parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total;
	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("bienesservicios");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_soc_c_registro_orden_compra_ajax.php",true);
		ajax.onreadystatechange=function()
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
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("proceso="+proceso+"&cargarcargos=0"+parametros);
	}
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	tipo=opener.document.formulario.tipord.value;
	codestpro1=opener.document.formulario.txtcodestpro1.value;
	codestpro2=opener.document.formulario.txtcodestpro2.value;
	codestpro3=opener.document.formulario.txtcodestpro3.value;
	codestpro4=opener.document.formulario.txtcodestpro4.value;
	codestpro5=opener.document.formulario.txtcodestpro5.value;
	ls_estcla = opener.document.formulario.hidestcla.value; 
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_soc_c_catalogo_ajax.php",true);
	ajax.onreadystatechange=function()
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
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("catalogo=CUENTAS-CARGOS&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+
			  "&codestpro4="+codestpro4+"&codestpro5="+codestpro5+"&orden="+orden+"&campoorden="+campoorden+"&tipo="+tipo+"&hidestcla="+ls_estcla);
}

function ue_close()
{
	close();
}
</script>
</html>