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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sep.js"></script>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="codser">
<input name="orden" type="hidden" id="orden" value="ASC">
<table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="500" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Servicios </td>
    </tr>
  </table>
  <br>
    <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="82" height="22"><div align="right">C&oacute;digo</div></td>
        <td width="412" height="22"><div align="left">
          <input name="txtcodser" type="text" id="txtcodser" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Denominaci&oacute;n</div></td>
        <td height="22"><input name="txtdenser" type="text" id="nombre" onKeyPress="javascript: ue_mostrar(this,event);">      </td>
      </tr>
	  <tr>
        <td colspan="2"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a><a href="javascript: ue_close();"> <img src="../shared/imagebank/eliminar.gif" width="15" height="15" class="sin-borde">Cerrar</a></div></td>
	  </tr>
	</table> 
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
//////////////////-------------------------------------------------------------------------------------------------------------
        function lTrim(sStr)
		{
			 while (sStr.charAt(0) == " ")
		     sStr = sStr.substr(1, sStr.length - 1);
			 return sStr;
		}	 
		
		function rTrim(sStr)
		{
			 while (sStr.charAt(sStr.length - 1) == " ")
		     sStr = sStr.substr(0, sStr.length - 1);
			 return sStr;
		}
		function allTrim(sStr){
		  return rTrim(lTrim(sStr));
		}		
///--------------------------------------------------------------------------------------------------------------------------
function ue_aceptar(as_codser,as_denser,ai_precio,as_spg_cuenta,ai_existecuenta,as_estcla)
{
	//---------------------------------------------------------------------------------
	// Verificamos que el Servicio no esté en el formulario
	//---------------------------------------------------------------------------------
	valido=true;
	total=ue_calcular_total_fila_opener("txtcodser");
	opener.document.formulario.totrowservicios.value=total;
	rowservicios=opener.document.formulario.totrowservicios.value;
	codestpro1= opener.document.formulario.txtcodestpro1.value;
	codestpro2= opener.document.formulario.txtcodestpro2.value;
	codestpro3= opener.document.formulario.txtcodestpro3.value;
	codestpro4= opener.document.formulario.txtcodestpro4.value;
	codestpro5= opener.document.formulario.txtcodestpro5.value;	   
	ls_estcla=	opener.document.formulario.txtestcla.value;
	programatica="";
	if(ai_existecuenta!=0)
	{
		programatica=codestpro1+codestpro2+codestpro3+codestpro4+codestpro5;
	}
	for(j=1;(j<=rowservicios)&&(valido);j++)
	{
		codsergrid=eval("opener.document.formulario.txtcodser"+j+".value");
		if(allTrim(codsergrid)==allTrim(as_codser))
		{
			alert("El Servicio ya está en la solicitud");
			valido=false;
			
		}
	}
	//---------------------------------------------------------------------------------
	// Cargar los Servicios del opener y el seleccionado
	//---------------------------------------------------------------------------------
	parametros="";
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
		ls_codgas	 = eval("opener.document.formulario.txtcodgas"+j+".value");
		ls_codspg	 = eval("opener.document.formulario.txtcodspg"+j+".value");
		ls_estatus   = eval("opener.document.formulario.txtstatus"+j+".value");

		parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
				   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
				   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
				   "&txttotser"+j+"="+totser+""+"&txtcodgas"+j+"="+ls_codgas+"&txtcodspg"+j+"="+ls_codspg+""+
								  "&txtspgcuenta"+j+"="+spgcuenta+"&txtstatus"+j+"="+ls_estatus;
	}
	totalservicios=eval(rowservicios+"+1");
	parametros=parametros+"&txtcodser"+rowservicios+"="+as_codser+"&txtdenser"+rowservicios+"="+as_denser+""+
			   "&txtcanser"+rowservicios+"=0,00"+"&txtpreser"+rowservicios+"="+ai_precio+"&txtsubtotser"+rowservicios+"=0,00"+
			   "&txtcarser"+rowservicios+"=0,00&txttotser"+rowservicios+"=0,00"+"&txtspgcuenta"+rowservicios+"="+as_spg_cuenta+
			   "&txtcodgas"+rowservicios+"="+programatica+"&txtcodspg"+rowservicios+"="+as_spg_cuenta+
			   "&totalservicios="+totalservicios+"&txtstatus"+rowservicios+"="+ls_estcla+"";
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
		codgascre  = eval("opener.document.formulario.txtcodgascre"+j+".value");
		codspgcre  = eval("opener.document.formulario.txtcodspgcre"+j+".value");
		statuscre  = eval("opener.document.formulario.txtstatuscre"+j+".value");
		parametros=parametros+"&txtcodservic"+j+"="+codservic+"&txtcodcar"+j+"="+codcar+
				   "&txtdencar"+j+"="+dencar+"&txtbascar"+j+"="+bascar+
				   "&txtmoncar"+j+"="+moncar+"&txtsubcargo"+j+"="+subcargo+
				   "&cuentacargo"+j+"="+cuentacargo+"&formulacargo"+j+"="+formulacargo+
					"&txtcodgascre"+j+"="+codgascre+"&txtcodspgcre"+j+"="+codspgcre+"&txtstatuscre"+j+"="+statuscre;
	}
	totalcargos=eval(rowcargos);
	parametros=parametros+"&txtcodservic="+as_codser+"&totalcargos="+totalcargos;
/*	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del opener y el seleccionado
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de las cuentas y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcuentagas");
	opener.document.formulario.totrowcuentas.value=total;
	rowcuentas=opener.document.formulario.totrowcuentas.value;
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{
		codpro=eval("opener.document.formulario.txtcodprogas"+j+".value");
		cuenta=eval("opener.document.formulario.txtcuentagas"+j+".value");
		estclagas=eval("opener.document.formulario.txtestclagas"+j+".value");
		moncue=eval("opener.document.formulario.txtmoncuegas"+j+".value");
		parametros=parametros+"&txtcodprogas"+j+"="+codpro+"&txtcuentagas"+j+"="+cuenta+
				   "&txtmoncuegas"+j+"="+moncue+"&txtestclagas"+j+"="+estclagas;
	}
	totalcuentas=eval(rowcuentas);
	parametros=parametros+"&txtcodprogas"+rowcuentas+"="+programatica+"&txtcuentagas"+rowcuentas+"="+as_spg_cuenta+
			   "&totalcuentas="+totalcuentas+"&txtestclagas"+rowcuentas+"="+ls_estcla;
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
		ls_estclacar = eval("opener.document.formulario.txtestclacar"+j+".value");
		parametros=parametros+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+"&txtcuentacar"+j+"="+cuenta+
				   "&txtmoncuecar"+j+"="+moncue+"&txtestclacar"+j+"="+ls_estclacar;
	}
	totalcuentascargo=eval(rowcuentas);
	parametros=parametros+"&totalcuentascargo="+totalcuentascargo;
	//---------------------------------------------------------------------------------
	// Cargar los totales
	//---------------------------------------------------------------------------------
	subtotal=eval("opener.document.formulario.txtsubtotal.value");
	cargos=eval("opener.document.formulario.txtcargos.value");
	total=eval("opener.document.formulario.txttotal.value");
	parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total+"&codprounidad="+codestpro1+codestpro2+codestpro3+codestpro4+codestpro5+"&estcla="+ls_estcla;*/
	parametros=parametros+"&codprounidad="+codestpro1+codestpro2+codestpro3+codestpro4+codestpro5+"&estcla="+ls_estcla;
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

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
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
	     codestpro4 = codestpro5 = "0000000000000000000000000";
	   }
	estcla    =opener.document.formulario.txtestcla.value;
	
	codser=f.txtcodser.value;
	denser=f.txtdenser.value;
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
	ajax.send("catalogo=SERVICIOS&codser="+codser+"&denser="+denser+"&codestpro1="+codestpro1+"&codestpro2="+codestpro2+
			  "&codestpro3="+codestpro3+"&codestpro4="+codestpro4+"&codestpro5="+codestpro5+"&orden="+orden+"&campoorden="+campoorden+"&estcla="+estcla);
}

function ue_mensaje()
{
   alert("Este servicio no tiene disponibilidad presupuestaria");
}

function ue_close()
{
	close();
}
</script>
</html>