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
	$ls_procede=$io_fun_cxp->uf_obtenervalor_get("procede","CXPRCD");
	$li_cargos=$io_fun_cxp->uf_obtenervalor_get("cargos","0,00");
	$ls_modageret = $_SESSION["la_empresa"]["modageret"];
	unset($io_fun_cxp);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Deducciones</title>
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
<input name="cargos" type="hidden" id="cargos" value="<?php print $li_cargos; ?>">
<input name="procede" type="hidden" id="procede" value="<?php print $ls_procede; ?>">
<input name="totrow" type="hidden" id="totrow">
  <table width="580" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td height="20" colspan="2" class="titulo-ventana">Deducciones</td>
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

function ue_calcular(fila,disabled)
{
	f=document.formulario;
	if(disabled=='true')
	{
		eval("f.chkdeduccion"+fila+".checked=true");
		alert("Esta Deducción viene configurada con el Proveedor ó Beneficiario no se puede destildar");
	}
	marcado=eval("f.chkdeduccion"+fila+".checked");
	if(marcado==true)
	{
		baseimponible=eval("f.txtmonobjret"+fila+".value");

		deducible=eval("f.txtmonded"+fila+".value");
		baseimponible=ue_formato_calculo(baseimponible);
		deducible=ue_formato_calculo(deducible);
		if(parseFloat(baseimponible)>0)
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
			deduccion=eval(formula);
			if(deduccion>0)
			{
				deduccion=redondear(deduccion,2);
				deduccion=uf_convertir(deduccion);
				eval("f.txtmonret"+fila+".value='"+deduccion+"'"); 
			}
			else
			{
				alert("El monto de la Deducción es menor ó Igual a Cero");
				eval("f.chkdeduccion"+fila+".checked=false;");
			}
		}
	}
	else
	{
		eval("f.txtmonret"+fila+".value='0,00'"); 
	}
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	tipo=f.tipo.value;
	if (tipo=='CMPRETIVA' || tipo=='CMPRETMUN' || tipo=='CMPRETAPO')
	   {
	     numrecdoc="";
		 subtotal="";
		 cargos="";
		 procede="";
		 presupuestario="";
		 contable="";
		 tipdes="";
		 codproben="";
	   }
	else 
	   {
 	     numrecdoc		= opener.document.formulario.txtnumrecdoc.value;
	     subtotal		= ue_formato_calculo(opener.document.formulario.txtsubtotal.value);
		 cargos		    = opener.document.formulario.txtcargos.value;
		 procede		= opener.document.formulario.procede.value;
		 presupuestario = opener.document.formulario.estatuspresupuesto.value;
		 contable		= opener.document.formulario.estatuscontable.value;
		 tipdes 		= opener.document.formulario.cmbtipdes.value;
		 codproben		= opener.document.formulario.txtcodigo.value;
	   }

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
	ajax.send("catalogo=DEDUCCIONES&tipo="+tipo+"&orden="+orden+"&campoorden="+campoorden+"&numrecdoc="+numrecdoc+
			  "&subtotal="+subtotal+"&cargos="+cargos+"&procede="+procede+"&presupuestario="+presupuestario+"&contable="+contable+
			  "&tipdes="+tipdes+"&codproben="+codproben);
}

function ue_aceptar()
{
	f=document.formulario;
	totrow=ue_calcular_total_fila_local("txtcodded");
	f.totrow.value=totrow;
	li_i=0;
	valido=true;
	for(j=1;(j<=totrow);j++)
	{
		marcado=eval("f.chkdeduccion"+j+".checked");
		monto=ue_formato_calculo(eval("f.txtmonret"+j+".value"));
		montoobj=ue_formato_calculo(eval("f.txtmonobjret"+j+".value"));
		if(marcado==true)
		{
			if((parseFloat(monto)<=0)||(parseFloat(montoobj)<=0))
			{
				alert("No se puede procesar las Deducciones hay valores errados");
				valido=false;
			}
		}
	}
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas del opener y el seleccionado
	//---------------------------------------------------------------------------------
	parametros="";
	opener.document.formulario.cerrarasiento.value="0";
	procede=opener.document.formulario.procede.value;
	generarcontable=opener.document.formulario.generarcontable.value;
	totrowspg=ue_calcular_total_fila_opener("txtspgnrocomp");
	opener.document.formulario.totrowspg.value=totrowspg;
	totrowscg=ue_calcular_total_fila_opener("txtscgnrocomp");
	opener.document.formulario.totrowscg.value=totrowscg;
	//---------------------------------------------------------------------------------
	// recorremos grid de las cuentas presupuestarias
	//---------------------------------------------------------------------------------
	for(j=1;(j<totrowspg);j++)
	{
		cargo=eval("opener.document.formulario.txtcargo"+j+".value");
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
		parametros=parametros+"&txtspgnrocomp"+j+"="+spgnrocomp+"&txtprogramatica"+j+"="+programatica+"&txtestcla"+j+"="+estcla+""+
				   "&txtspgcuenta"+j+"="+spgcuenta+"&txtspgmonto"+j+"="+spgmonto+""+
				   "&txtcodpro"+j+"="+codpro+"&txtcargo"+j+"="+cargo+""+"&txtoriginal"+j+"="+original+
				   "&txtspgsccuenta"+j+"="+spgsccuenta+"&txtspgprocededoc"+j+"="+procededoc+
				   "&txtcodfuefin"+j+"="+codfuefin;
	}
	parametros=parametros+"&totrowspg="+totrowspg+"";
	//---------------------------------------------------------------------------------
	// recorremos grid de las cuentas contables
	//---------------------------------------------------------------------------------
	li_i=0;
	for(j=1;(j<totrowscg);j++)
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
	// recorremos el arreglo de las deducciones para cargar las cuentas
	//---------------------------------------------------------------------------------
	li_deducciones=0;
	totrow=ue_calcular_total_fila_local("txtcodded");
	f.totrow.value=totrow;
	li_i=0;
	for(j=1;(j<=totrow);j++)
	{
		marcado=eval("f.chkdeduccion"+j+".checked");
		monto=ue_formato_calculo(eval("f.txtmonret"+j+".value"));
		montoobj=ue_formato_calculo(eval("f.txtmonobjret"+j+".value"));
		if((marcado==true)&&(parseFloat(monto)>0)&&(parseFloat(montoobj)>=parseFloat(monto)))
		{
			li_i=li_i+1;
			codded=eval("f.txtcodded"+j+".value");
			monobjret=eval("f.txtmonobjret"+j+".value");
			monret=eval("f.txtmonret"+j+".value");
			sccuenta=eval("f.sccuenta"+j+".value");
			porded=eval("f.porded"+j+".value");
			iva=eval("f.txtiva"+j+".value");
			islr=eval("f.txtislr"+j+".value");
			li_deducciones=eval(li_deducciones+"+"+ue_formato_calculo(monret));
			parametros=parametros+"&txtcodded"+li_i+"="+codded+"&txtmonobjret"+li_i+"="+monobjret+""+
					   "&txtmonret"+li_i+"="+monret+"&sccuenta"+li_i+"="+sccuenta+"&porded"+li_i+"="+porded+"&iva"+li_i+"="+iva+
					   "&procededoc"+li_i+"="+procede+"&islr"+li_i+"="+islr;
		}
	}
	parametros=parametros+"&totrowdeducciones="+li_i+"";
	//---------------------------------------------------------------------------------
	// Cargar los totales
	//---------------------------------------------------------------------------------
	causadoparcial=opener.document.formulario.causadoparcial.value;
	presupuestario=opener.document.formulario.estatuspresupuesto.value;
	contable=opener.document.formulario.estatuscontable.value;
	sccuentaprov=opener.document.formulario.codigocuenta.value;
	ls_numrecdoc=f.numrecdoc.value;
	ls_documento=ls_numrecdoc;
	li_subtotal=ue_formato_calculo(opener.document.formulario.txtsubtotal.value);
	li_cargos=ue_formato_calculo(opener.document.formulario.txtcargos.value);
	li_total=eval(li_subtotal+"+"+li_cargos);
	li_totalgeneral=eval(li_total+"-"+li_deducciones);
	parametros=parametros+"&estcontable="+contable+"&estpresupuestario="+presupuestario+"&sccuentaprov="+sccuentaprov;
	parametros=parametros+"&subtotal="+li_subtotal+"&cargos="+li_cargos+"&total="+li_total+"&numrecdoc="+ls_numrecdoc;
	parametros=parametros+"&deducciones="+li_deducciones+"&totgeneral="+li_totalgeneral+"&cargardeducciones=1";
	parametros=parametros+"&documento="+ls_documento+"&generarcontable="+generarcontable;
	ls_proceso="";
	if(presupuestario=="1") // Causa
	{
		ls_proceso="CAUSA";
		if(causadoparcial=="1")
		{
			ls_proceso="CAUSAPARCIAL";
		}
	}
	if(presupuestario=="2") // Compromete y Causa
	{
		ls_proceso="COMPROMETECAUSA";
	}
	if((ls_proceso=="")&&(contable=="1"))// Contable
	{
		ls_proceso="CONTABLE";
	}
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
		opener.document.formulario.totrowspg.value=totrowspg;
	}
}

function uf_aceptar_deducciones(li_totrows)
{
  f         = document.formulario;
  fop       = opener.document.formulario;
  li_filsel = 0;
  li_filope = fop.hidfilsel.value;
  ls_tipcmp = f.tipo.value;
  lb_valido = false;
  for (i=1;i<=li_totrows;i++)
      {
	    if (eval("f.radiodeduccion"+i+".checked==true"))
		   {
		     lb_valido=true;
			 li_filsel=i;
			 break;
		   }
	  }
  if (lb_valido)
     {
       ld_porded = eval("f.porded"+li_filsel+".value");
	   ls_forded = eval("f.formula"+li_filsel+".value");
	   if (ls_tipcmp=='CMPRETIVA')
	      {
		    ld_moniva = eval("fop.txttotimp"+li_filope+".value");
	        ld_monto  = ue_formato_calculo(ld_moniva);
		  }
	   else
	      {
		    ld_basimp = eval("fop.txtbasimp"+li_filope+".value");
	        ld_monto  = ue_formato_calculo(ld_basimp);
		  }
	   while(ls_forded.indexOf("$LD_MONTO")!=-1)
			{ 
			  ls_forded=ls_forded.replace("$LD_MONTO",ld_monto);
			}
	   while(ls_forded.indexOf("ROUND")!=-1)
			{ 
			  ls_forded=ls_forded.replace("ROUND","redondear");
			}
	   ls_forded = ls_forded.replace("IIF","ue_iif");
	   ld_totret = eval(ls_forded);
	   ld_totret = redondear(ld_totret,3);
	   ld_totret = uf_convertir(ld_totret);
	   ld_porded  = ue_formato_calculo(ld_porded);
	   eval("fop.txtivaret"+li_filope+".value='"+ld_totret+"'");
	   eval("fop.txtporret"+li_filope+".value='"+ld_porded+"'");
	   close();
	 }
}
</script>
</html>