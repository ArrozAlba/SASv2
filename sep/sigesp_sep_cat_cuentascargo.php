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
<title>Cat&aacute;logo de Cuentas Presupuestarias</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sep.js"></script>
<body onLoad="javascript: ue_search();">
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="codpro">
<input name="orden" type="hidden" id="orden" value="ASC">
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="500" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Cuentas Presupuestarias para los cargos </td>
    </tr>
  </table>
  <br>
    <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
	  <tr>
	    <td width="148" height="22"><div align="right">Cargo</div></td>
        <td width="346" height="22"><input name="txtcodcar" type="text" id="txtcodcar" size="10" maxlength="5" onBlur="javascript: ue_rellenarcampo(this,5);"></td>
	  </tr>
	</table> 
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">

function ue_aceptar(as_programatica,as_cuenta,as_denominacion,as_codespro,as_estcla)
{
	//---------------------------------------------------------------------------------
	// Verificamos que la cuenta presupuestaria no esté en el formulario
	//---------------------------------------------------------------------------------
	valido=true;
	// Obtenemos el total de filas de los Conceptos
	total=ue_calcular_total_fila_opener("txtcodcon");
	opener.document.formulario.totrowconceptos.value=total;
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
				alert("El Cargo no Existe en la Solicitud.");
			}
		}
	}
	for(j=1;(j<=rowcuentas)&&(valido);j++)
	{
		cuentagrid=eval("opener.document.formulario.txtcuentacar"+j+".value");
		cargo=eval("opener.document.formulario.txtcodcargo"+j+".value");
		programatica=eval("opener.document.formulario.txtcodprocar"+j+".value");
		estclagrid=eval("opener.document.formulario.txtestclacar"+j+".value");
		if((cuentagrid==as_cuenta)&&(codcargo==cargo)&&(as_codespro==programatica)&&(as_estcla==estclagrid))
		{
			alert("La cuenta presupuestaria ya está en la solicitud, se va reemplazar La Estructura.");
			eval("opener.document.formulario.txtprogramaticacar"+j+".value='"+as_programatica+"'");
			eval("opener.document.formulario.txtcodprocar"+j+".value='"+as_codespro+"'");
			eval("opener.document.formulario.txtestclacar"+j+".value='"+as_estcla+"'");
			valido=false;			
		}
	}
	tiposolicitud=opener.document.formulario.cmbcodtipsol.value;
	tipo=tiposolicitud.substr(3,1);// Para saber si es de bienes, servicios ó conceptos
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
			codart=eval("opener.document.formulario.txtcodart"+j+".value");
			denart=eval("opener.document.formulario.txtdenart"+j+".value");
			canart=eval("opener.document.formulario.txtcanart"+j+".value");
			unidad=eval("opener.document.formulario.cmbunidad"+j+".value");
			preart=eval("opener.document.formulario.txtpreart"+j+".value");
			subtotart=eval("opener.document.formulario.txtsubtotart"+j+".value");
			carart=eval("opener.document.formulario.txtcarart"+j+".value");
			totart=eval("opener.document.formulario.txttotart"+j+".value");
			spgcuenta=eval("opener.document.formulario.txtspgcuenta"+j+".value");
			unidadfisica=eval("opener.document.formulario.txtunidad"+j+".value");
			parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
					   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
					   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
					   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
					   "&txtspgcuenta"+j+"="+spgcuenta+"&txtunidad"+j+"="+unidadfisica+"";
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
			codser=eval("opener.document.formulario.txtcodser"+j+".value");
			denser=eval("opener.document.formulario.txtdenser"+j+".value");
			canser=eval("opener.document.formulario.txtcanser"+j+".value");
			preser=eval("opener.document.formulario.txtpreser"+j+".value");
			subtotser=eval("opener.document.formulario.txtsubtotser"+j+".value");
			carser=eval("opener.document.formulario.txtcarser"+j+".value");
			totser=eval("opener.document.formulario.txttotser"+j+".value");
			spgcuenta=eval("opener.document.formulario.txtspgcuenta"+j+".value");
	
			parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
					   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
					   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
					   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+spgcuenta;
		}
		parametros=parametros+"&totalservicios="+rowservicios+"";
	}
	if(tipo=="O")
	{
		proceso="AGREGARCONCEPTOS";
		//---------------------------------------------------------------------------------
		// Cargar los Conceptos del opener y el seleccionado
		//---------------------------------------------------------------------------------
		rowconceptos=opener.document.formulario.totrowconceptos.value;
		for(j=1;(j<rowconceptos)&&(valido);j++)
		{
			codcon=eval("opener.document.formulario.txtcodcon"+j+".value");
			dencon=eval("opener.document.formulario.txtdencon"+j+".value");
			cancon=eval("opener.document.formulario.txtcancon"+j+".value");
			precon=eval("opener.document.formulario.txtprecon"+j+".value");
			subtotcon=eval("opener.document.formulario.txtsubtotcon"+j+".value");
			carcon=eval("opener.document.formulario.txtcarcon"+j+".value");
			totcon=eval("opener.document.formulario.txttotcon"+j+".value");
			spgcuenta=eval("opener.document.formulario.txtspgcuenta"+j+".value");
	
			parametros=parametros+"&txtcodcon"+j+"="+codcon+"&txtdencon"+j+"="+dencon+""+
					   "&txtcancon"+j+"="+cancon+"&txtprecon"+j+"="+precon+""+
					   "&txtsubtotcon"+j+"="+subtotcon+"&txtcarcon"+j+"="+carcon+""+
					   "&txttotcon"+j+"="+totcon+"&txtspgcuenta"+j+"="+spgcuenta;
		}
		parametros=parametros+"&totalconceptos="+rowconceptos+"";
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
		cuentacargo=eval("opener.document.formulario.cuentacargo"+j+".value");
		formulacargo=eval("opener.document.formulario.formulacargo"+j+".value");
		parametros=parametros+"&txtcodservic"+j+"="+codservic+"&txtcodcar"+j+"="+codcar+
				   "&txtdencar"+j+"="+dencar+"&txtbascar"+j+"="+bascar+
				   "&txtmoncar"+j+"="+moncar+"&txtsubcargo"+j+"="+subcargo+
				   "&cuentacargo"+j+"="+cuentacargo+"&formulacargo"+j+"="+formulacargo;
	}
	parametros=parametros+"&totalcargos="+rowcargos;
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias Cargo del opener y el seleccionado
	//---------------------------------------------------------------------------------
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{
		cargo=eval("opener.document.formulario.txtcodcargo"+j+".value");
		codpro=eval("opener.document.formulario.txtcodprocar"+j+".value");
		estclacar=eval("opener.document.formulario.txtestclacar"+j+".value");
		cuenta=eval("opener.document.formulario.txtcuentacar"+j+".value");
		moncue=eval("opener.document.formulario.txtmoncuecar"+j+".value");
		parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
				   "&txtmoncuecar"+j+"="+moncue+"&txtestclacar"+j+"="+estclacar;
	}
	totalcuentas=eval(rowcuentas+"+"+1);
	opener.document.formulario.totrowcuentascargo.value=totalcuentas;	
	parametros=parametros+"&txtcodcargo"+rowcuentas+"="+codcargo+"&txtcodprocar"+rowcuentas+"="+as_codespro+"&txtcuentacar"+rowcuentas+"="+as_cuenta+
			   "&totalcuentascargo="+totalcuentas+"&txtestclacar"+rowcuentas+"="+as_estcla;
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de las cuentas y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcuentagas");
	opener.document.formulario.totrowcuentas.value=total;
	rowcuentas=opener.document.formulario.totrowcuentas.value;
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{  
		codpro=eval("opener.document.formulario.txtcodprogas"+j+".value");
		estclagas=eval("opener.document.formulario.txtestclagas"+j+".value");
		cuenta=eval("opener.document.formulario.txtcuentagas"+j+".value");
		moncue=eval("opener.document.formulario.txtmoncuegas"+j+".value");
		parametros=parametros+"&txtcodprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+
				   "&txtmoncuegas"+j+"="+moncue+"&txtestclagas"+j+"="+estclagas;
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
		ajax.open("POST","class_folder/sigesp_sep_c_solicitud_ajax.php",true);
		ajax.onreadystatechange=function(){
			if(ajax.readyState==1)
			{
				//divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
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
		ajax.send("proceso="+proceso+"&cargarcargos=0"+parametros);
	}
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	tiposolicitud=opener.document.formulario.cmbcodtipsol.value;
	tipo=tiposolicitud.substr(3,1);// Para saber si es de bienes, servicios ó conceptos	
	codestpro1=opener.document.formulario.txtcodestpro1.value;
	codestpro2=opener.document.formulario.txtcodestpro2.value;
	codestpro3=opener.document.formulario.txtcodestpro3.value;
	li_estmodest = "<?php print $_SESSION["la_empresa"]["estmodest"] ?>";
	if (li_estmodest=='2')//Presupuesto por Programas.
	   {
	     codestpro4=opener.document.formulario.txtcodestpro4.value;
		 codestpro5=opener.document.formulario.txtcodestpro5.value;	   
	   }
	else
	   {
	     codestpro4 = codestpro5 = "";
	   }
	estcla=	opener.document.formulario.txtestcla.value;		
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_sep_c_catalogo_ajax.php",true);
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
	ajax.send("catalogo=CUENTASCARGOS&codestpro1="+codestpro1+"&codestpro2="+codestpro2+"&codestpro3="+codestpro3+
			  "&codestpro4="+codestpro4+"&codestpro5="+codestpro5+"&estcla="+estcla+"&orden="+orden+"&campoorden="+campoorden+"&tipo="+tipo);
}

function ue_close()
{
	close();
}
</script>
</html>