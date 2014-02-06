<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_tipo=$io_fun_cxp->uf_obtenertipo();
	$ls_numrecdoc=$io_fun_cxp->uf_obtenervalor_get("numrecdoc","");
	$li_subtotal=$io_fun_cxp->uf_obtenervalor_get("subtotal","0,00");
	$ls_procede=$io_fun_cxp->uf_obtenervalor_get("procede","0,00");
	unset($io_fun_cxp);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Otros Cr&eacute;ditos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<body onLoad="javascript: ue_search();">
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="codcar">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<input name="numrecdoc" type="hidden" id="numrecdoc" value="<?php print $ls_numrecdoc; ?>">
<input name="subtotal" type="hidden" id="subtotal" value="<?php print $li_subtotal; ?>">
<input name="procede" type="hidden" id="procede" value="<?php print $ls_procede; ?>">
<input name="totrow" type="hidden" id="totrow">
<input name="ajustar" type="hidden" id="ajustar">
  <table width="640" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td height="20" colspan="2" class="titulo-ventana">Otros Cr&eacute;ditos </td>
    </tr>
  </table>
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
function ue_cerrar()
{
	close();
}

function ue_calcular(fila)
{
	f=document.formulario;
	marcado=eval("f.chkcargos"+fila+".checked");
	if(marcado==true)
	{
		baseimponible=eval("f.txtbaseimp"+fila+".value");
		baseimponible=ue_formato_calculo(baseimponible);
		subtotal=f.subtotal.value;
		subtotal=ue_formato_calculo(subtotal);
		if(parseFloat(baseimponible)<=parseFloat(subtotal))
		{
			formula=eval("f.formula"+fila+".value");
			while(formula.indexOf("$LD_MONTO")!=-1)
			{ 
				formula=formula.replace("$LD_MONTO",baseimponible);
			} 	
			while(formula.indexOf("ROUND")!=-1)
			{ 
				formula=formula.replace("ROUND","redondear");
			} 
			formula=formula.replace("IIF","ue_iif");

			cargo=eval(formula);
			cargo=redondear(cargo,2);
			cargo=uf_convertir(cargo);
			eval("f.txtmonimp"+fila+".value='"+cargo+"'"); 
		}
		else
		{
			alert("La Base Imponible no puede ser mayor que el monto de la Recepción de Documentos");
			eval("f.txtbaseimp"+fila+".value='"+uf_convertir(subtotal)+"'"); 
			eval("f.chkcargos"+fila+".checked=false;");
		}
	}
	else
	{
		eval("f.txtmonimp"+fila+".value='0,00'"); 
	}
}

function ue_ajustar()
{
	f=document.formulario;
	totrow=ue_calcular_total_fila_local("txtcodcar");
	f.totrow.value=totrow;
	ls_ajuste="";
	for(fila=1;(fila<=totrow);fila++)
	{
		marcado=eval("f.chkcargos"+fila+".checked");
		if(marcado==true)
		{
			codcar=eval("f.txtcodcar"+fila+".value");
			montoajuste=eval("f.txtmonaju"+fila+".value");
			montoajuste=ue_formato_calculo(montoajuste);
			if((parseFloat(montoajuste)>=-0.99)&&(parseFloat(montoajuste)<=0.99))
			{
				monto=eval("f.txtmonimp"+fila+".value");
				monto=ue_formato_calculo(monto);
				monto=eval(monto+"+"+montoajuste);
				monto=redondear(monto,2);
				monto=uf_convertir(monto);
				eval("f.txtmonimp"+fila+".value='"+monto+"'"); 
				ls_ajuste=ls_ajuste+" Ajusto el monto del cargo "+codcar+" en "+montoajuste+". ";
				eval("f.txtmonaju"+fila+".value='0,00'");
			}
			else
			{
				alert("el monto del ajuste del cargo "+codcar+" debe ser mayor que -1,00 y menor que 1,00 ");
				eval("f.txtmonaju"+fila+".value='0,00'"); 
			}
		}
		else
		{
			eval("f.txtmonimp"+fila+".value='0,00'"); 
		}
	}
	f.ajustar.value=f.ajustar.value+ls_ajuste;
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	orden      = f.orden.value;
	campoorden = f.campoorden.value;
	tipo       = f.tipo.value;
	if (tipo=='CMPRET')
	   {
	     numrecdoc="";
		 subtotal="";
	   }
	else 
	   {
 	     numrecdoc=opener.document.formulario.txtnumrecdoc.value;
	     subtotal=ue_formato_calculo(opener.document.formulario.txtsubtotal.value);
	   }
	procede=f.procede.value;
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
	ajax.send("catalogo=OTROSCREDITOS&tipo="+tipo+"&orden="+orden+"&campoorden="+campoorden+"&compromiso="+numrecdoc+
			  "&baseimponible="+subtotal+"&procededoc="+procede+"&parcial=0");
}

function ue_aceptar()
{
	f=document.formulario;
	valido=true;
	totrow=ue_calcular_total_fila_local("txtcodcar");
	f.totrow.value=totrow;
	ls_ajuste=f.ajustar.value;
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas del opener y el seleccionado
	//---------------------------------------------------------------------------------
	parametros="";
	opener.document.formulario.cerrarasiento.value="0";
	totrowspg=ue_calcular_total_fila_opener("txtspgnrocomp");
	opener.document.formulario.totrowspg.value=totrowspg;
	totrowscg=ue_calcular_total_fila_opener("txtscgnrocomp");
	opener.document.formulario.totrowscg.value=totrowscg;
	procede=opener.document.formulario.procede.value;
	generarcontable=opener.document.formulario.generarcontable.value;
	ls_codfuefin=opener.document.formulario.txtcodfuefin.value;
	//---------------------------------------------------------------------------------
	// recorremos grid de las cuentas presupuestarias
	//---------------------------------------------------------------------------------
	li_i=0;
	for(j=1;(j<totrowspg)&&(valido);j++)
	{
		cargo=eval("opener.document.formulario.txtcargo"+j+".value");
		if(cargo=="0")
		{
			li_i=li_i+1;
			spgnrocomp=eval("opener.document.formulario.txtspgnrocomp"+j+".value");
			programatica=eval("opener.document.formulario.txtprogramatica"+j+".value");
			estcla=eval("opener.document.formulario.txtestcla"+j+".value");
			spgcuenta=eval("opener.document.formulario.txtspgcuenta"+j+".value");
			spgmonto=eval("opener.document.formulario.txtspgmonto"+j+".value");
			codpro=eval("opener.document.formulario.txtcodpro"+j+".value");
			original=eval("opener.document.formulario.txtoriginal"+j+".value");
			spgsccuenta=eval("opener.document.formulario.txtspgsccuenta"+j+".value");
			procededoc=eval("opener.document.formulario.txtspgprocededoc"+j+".value");
			codfuefin=eval("opener.document.formulario.txtcodfuefin"+j+".value");
			parametros=parametros+"&txtspgnrocomp"+li_i+"="+spgnrocomp+"&txtprogramatica"+li_i+"="+programatica+"&txtestcla"+j+"="+estcla+""+
					   "&txtspgcuenta"+li_i+"="+spgcuenta+"&txtspgmonto"+li_i+"="+spgmonto+""+
					   "&txtcodpro"+li_i+"="+codpro+"&txtcargo"+li_i+"="+cargo+""+"&txtoriginal"+li_i+"="+original+
					   "&txtspgsccuenta"+li_i+"="+spgsccuenta+"&txtspgprocededoc"+li_i+"="+procededoc+
					   "&txtcodfuefin"+j+"="+codfuefin;
		}
	}
	totalcuentasspg=eval(li_i+"+1");
	parametros=parametros+"&totrowspg="+totalcuentasspg+"";
	//---------------------------------------------------------------------------------
	// recorremos grid de las cuentas contables
	//---------------------------------------------------------------------------------
	li_i=0;
	for(j=1;(j<totrowscg)&&(valido);j++)
	{
		scgnrocomp=eval("opener.document.formulario.txtscgnrocomp"+j+".value");
		scgcuenta=eval("opener.document.formulario.txtscgcuenta"+j+".value");
		mondeb=eval("opener.document.formulario.txtmondeb"+j+".value");
		monhab=eval("opener.document.formulario.txtmonhab"+j+".value");
		debhab=eval("opener.document.formulario.txtdebhab"+j+".value");
		estatus=eval("opener.document.formulario.txtestatus"+j+".value");
		scgprocededoc=eval("opener.document.formulario.txtscgprocededoc"+j+".value");
		if(estatus=="M")
		{
			li_i=li_i+1;
			parametros=parametros+"&txtscgnrocomp"+li_i+"="+scgnrocomp+"&txtscgcuenta"+li_i+"="+scgcuenta+""+
								  "&txtmondeb"+li_i+"="+mondeb+"&txtmonhab"+li_i+"="+monhab+"&txtdebhab"+li_i+"="+debhab+
								  "&txtestatus"+li_i+"="+estatus+"&txtscgprocededoc"+li_i+"="+scgprocededoc;
		}
	}
	totrowscg=li_i+1;
	parametros=parametros+"&totrowscg="+totrowscg+"";
	//---------------------------------------------------------------------------------
	// recorremos el arreglo de los cargos para cargar las cuentas
	//---------------------------------------------------------------------------------
	li_i=0;
	li_cargos=0;
	for(j=1;(j<=totrow);j++)
	{
		marcado=eval("f.chkcargos"+j+".checked");
		monto=ue_formato_calculo(eval("f.txtmonimp"+j+".value"));
		if((marcado==true)&&(parseFloat(monto)>0))
		{
			li_i=li_i+1;
			codcar=eval("f.txtcodcar"+j+".value");
			baseimp=eval("f.txtbaseimp"+j+".value");
			monimp=eval("f.txtmonimp"+j+".value");
			codestpro=eval("f.codestpro"+j+".value");
			estcla=eval("f.estcla"+j+".value");
			spgcuenta=eval("f.spgcuenta"+j+".value");
			sccuenta=eval("f.sccuenta"+j+".value");
			formula=eval("f.formula"+j+".value");
			porcar=eval("f.porcar"+j+".value");
			procededoc=eval("f.procededoc"+j+".value");
			li_cargos=eval(li_cargos+"+"+ue_formato_calculo(monimp));
			parametros=parametros+"&txtcodcar"+li_i+"="+codcar+"&txtbaseimp"+li_i+"="+baseimp+""+
					   "&txtmonimp"+li_i+"="+monimp+"&codestpro"+li_i+"="+codestpro+"&spgcuenta"+li_i+"="+spgcuenta+"&estcla"+li_i+"="+estcla+""+
					   "&sccuenta"+li_i+"="+sccuenta+"&formula"+li_i+"="+formula+"&porcar"+li_i+"="+porcar+""+
					   "&procededoc"+li_i+"="+procededoc+"&codfuefin"+li_i+"="+ls_codfuefin;
		}
	}
	parametros=parametros+"&totrowcargos="+li_i+"";
	//---------------------------------------------------------------------------------
	// Cargar los totales
	//---------------------------------------------------------------------------------
	causadoparcial=opener.document.formulario.causadoparcial.value;
	presupuestario=opener.document.formulario.estatuspresupuesto.value;
	contable=opener.document.formulario.estatuscontable.value;
	sccuentaprov=opener.document.formulario.codigocuenta.value;
	li_subtotal=ue_formato_calculo(opener.document.formulario.txtsubtotal.value);
	li_deducciones=ue_formato_calculo(opener.document.formulario.txtdeducciones.value);
	li_total=eval(li_subtotal+"+"+li_cargos);
	li_totalgeneral=eval(li_total+"-"+li_deducciones);
	ls_numrecdoc=f.numrecdoc.value;
	ls_proceso="";
	if(presupuestario=="1")
	{
		ls_proceso="CAUSA";
		if(causadoparcial=="1")
		{
			ls_proceso="CAUSAPARCIAL";
		}
	}
	if(presupuestario=="2")
	{
		ls_proceso="COMPROMETECAUSA";
	}
	parametros=parametros+"&estcontable="+contable+"&estpresupuestario="+presupuestario+"&sccuentaprov="+sccuentaprov;
	parametros=parametros+"&subtotal="+li_subtotal+"&cargos="+li_cargos+"&total="+li_total+"&numrecdoc="+ls_numrecdoc;
	parametros=parametros+"&deducciones="+li_deducciones+"&totgeneral="+li_totalgeneral+"&cargarcargos=1&ajuste="+ls_ajuste;
	parametros=parametros+"&generarcontable="+generarcontable;
	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("cuentas");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_cxp_c_recepcion_ajax.php",true);
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
		ajax.send("proceso="+ls_proceso+""+parametros);
		opener.document.formulario.totrowspg.value=totalcuentasspg;
	}
}

function uf_aceptar_creditos(li_totrows)
{
  f         = document.formulario;
  fop       = opener.document.formulario;
  li_filsel = 0;
  li_filope = fop.hidfilsel.value;
  lb_valido = false;
  for (i=1;i<=li_totrows;i++)
      {
	    if (eval("f.radiocargos"+i+".checked==true"))
		   {
		     lb_valido=true;
			 li_filsel=i;
			 break;
		   }
	  }
  if (lb_valido)
     {
       ld_porcar = eval("f.porcar"+li_filsel+".value");
	   ls_forcar = eval("f.formula"+li_filsel+".value");
	   ld_basimp = eval("fop.txtbasimp"+li_filope+".value");
	   ld_basimp = ue_formato_calculo(ld_basimp);
	   ld_totimp = eval(ls_forcar.replace('$LD_MONTO',ld_basimp));
	   ld_totimp = redondear(ld_totimp,3);
	   ld_totimp = uf_convertir(ld_totimp);
	   eval("fop.txtporimp"+li_filope+".value='"+ld_porcar+"'");
	   eval("fop.txttotimp"+li_filope+".value='"+ld_totimp+"'");
	   close();
	 }
}
</script>
</html>