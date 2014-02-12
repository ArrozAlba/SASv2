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
	$ls_sccuentaprov=$io_fun_cxp->uf_obtenervalor_get("sccuenta","");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Amortizacion</title>
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
  <table width="600" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td height="20" colspan="2" class="titulo-ventana">Amortizacion</td>
    </tr>
  </table>
	<p>
  <div id="resultados" align="center"></div>	
	</p>
	<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
          <input name="sccuentaprov" type="hidden" id="sccuentaprov" value="<?php print $ls_sccuentaprov; ?>">
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><div align="right"><a href="javascript: ue_aceptar();"><img src="../shared/imagebank/aprobado.gif" alt="Aceptar" width="15" height="15" class="sin-borde">Aceptar</a>  <a href="javascript: ue_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="cancelar" width="15" height="15" class="sin-borde">Cancelar</a></div> </td>
      </tr>
    </table>
</form>      
</body>
<script language="JavaScript">
function ue_cancelar()
{
	close();
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	codigo=opener.document.formulario.txtcodigo.value;
	tipodes=opener.document.formulario.cmbtipdes.value;
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
	ajax.send("catalogo=AMORTIZACION&codigo="+codigo+"&tipodes="+tipodes);
}

function ue_validarmonto()
{
	f=document.formulario;
	valido=true;
	totrowamort= f.txttotrow.value;
	cero="0,00";
	for(j=1;(j<=totrowamort)&&(valido);j++)
	{
		monto=eval("f.txtmonto"+j+".value");
		saldo=eval("f.txtsaldo"+j+".value");
		monto=ue_formato_calculo(monto);
		saldo=ue_formato_calculo(saldo);
		if(parseFloat(monto)>parseFloat(saldo))
		{
			alert("El monto ingresado debe ser menor al saldo correspondiente");
			eval("f.txtmonto"+j+".value="+cero+"");
			valido=false;
		}
	}
}
function ue_aceptar()
{
	f=document.formulario;
	valido=true;
	parametros="";
	sccuentaprov=f.sccuentaprov.value;
	opener.document.formulario.cerrarasiento.value="0";
	totrowspg=ue_calcular_total_fila_opener("txtspgnrocomp");
	totrowscg=ue_calcular_total_fila_opener("txtscgnrocomp");
	totrowamort= f.txttotrow.value;
	//---------------------------------------------------------------------------------
	// Verificamos que el compromiso no exista
	//---------------------------------------------------------------------------------
	totcompromisos=0;
	totmontogrid=0;
	for(j=1;(j<totrowspg)&&(valido);j++)
	{
		cargo=eval("opener.document.formulario.txtcargo"+j+".value");
		montogrid=eval("opener.document.formulario.txtspgmonto"+j+".value");
		if(cargo!="C")
		{
			totcompromisos=totcompromisos+1;
			totmontogrid=totmontogrid+1;
		}
	}
	totamortizados=0;
	totmontoamortizados=0;
	for(j=1;(j<=totrowamort)&&(valido);j++)
	{
		monto=eval("f.txtmonto"+j+".value");
		saldo=eval("f.txtsaldo"+j+".value");
		monto=ue_formato_calculo(monto);
		saldo=ue_formato_calculo(saldo);
		if(monto>0)
		{
			totamortizados=totamortizados+1;
			totmontoamortizados=totmontoamortizados+1;
		}
	}
	if(totcompromisos<totamortizados)
	{
		alert("La cantidad de amortizaciones no debe superar los compromisos que intenta causar");
		valido=false;
	}
	else
	{
		if(totamortizados<1)
		{
			alert("Debe indicar un monto a amortizar");
			valido=false;
		}
		if(parseFloat(totmontoamortizados)>parseFloat(totmontogrid))
		{
			alert("El monto a amortizar no debe ser mayor a los compromisos causados");
			valido=false;
		}
		if(valido)
		{
			// Datos del formulario
			opener.document.formulario.cerrarasiento.value="0";
			//Datos del opener
			ls_numrecdoc=opener.document.formulario.txtnumrecdoc.value;
			causadoparcial=opener.document.formulario.causadoparcial.value;
			presupuestario=opener.document.formulario.estatuspresupuesto.value;
			contable=opener.document.formulario.estatuscontable.value;
			procede=opener.document.formulario.procede.value;
			generarcontable=opener.document.formulario.generarcontable.value;
			totrowspg=ue_calcular_total_fila_opener("txtspgnrocomp");
			opener.document.formulario.totrowspg.value=totrowspg;
			totrowscg=ue_calcular_total_fila_opener("txtscgnrocomp");
			opener.document.formulario.totrowscg.value=totrowscg;
			// Inicializaciones
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
			valido=true;
			li_subtotal=0;
			li_total=0;
			li_totalgeneral=0;
			parametros="";
			//---------------------------------------------------------------------------------
			// Cargar las cuentas presupuestarias
			//---------------------------------------------------------------------------------
			for(j=1;(j<totrowspg)&&(valido);j++)
			{
				spgnrocomp=eval("opener.document.formulario.txtspgnrocomp"+j+".value");
				programatica=eval("opener.document.formulario.txtprogramatica"+j+".value");
				estcla=eval("opener.document.formulario.txtestcla"+j+".value");
				spgcuenta=eval("opener.document.formulario.txtspgcuenta"+j+".value");
				spgmonto=eval("opener.document.formulario.txtspgmonto"+j+".value");
				codpro=eval("opener.document.formulario.txtcodpro"+j+".value");
				cargo=eval("opener.document.formulario.txtcargo"+j+".value");
				original=eval("opener.document.formulario.txtoriginal"+j+".value");
				procededoc=eval("opener.document.formulario.txtspgprocededoc"+j+".value");
				spgsccuenta=eval("opener.document.formulario.txtspgsccuenta"+j+".value");
				cargo=eval("opener.document.formulario.txtcargo"+j+".value");
				codfuefin=eval("opener.document.formulario.txtcodfuefin"+j+".value");
				if(cargo=="0")
				{
					li_subtotal=eval(li_subtotal+"+"+ue_formato_calculo(spgmonto));
				}
				parametros=parametros+"&txtspgnrocomp"+j+"="+spgnrocomp+"&txtprogramatica"+j+"="+programatica+""+"&txtestcla"+j+"="+estcla+""+
						   "&txtspgcuenta"+j+"="+spgcuenta+"&txtspgmonto"+j+"="+spgmonto+""+
						   "&txtcodpro"+j+"="+codpro+"&txtcargo"+j+"="+cargo+""+"&txtoriginal"+j+"="+original+
						   "&txtspgsccuenta"+j+"="+spgsccuenta+"&txtspgprocededoc"+j+"="+procededoc+
						   "&txtcodfuefin"+j+"="+codfuefin;
			}
			parametros=parametros+"&totrowspg="+totrowspg+"";
			//---------------------------------------------------------------------------------
			// Cargar las cuentas contables
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
					if(debhab=="D")
					{
						li_subtotal=eval(li_subtotal+"+"+ue_formato_calculo(mondeb));
					}
					else
					{
						/*if(ls_proceso!="CONTABLE")
						{*/
							li_subtotal=eval(li_subtotal+"-"+ue_formato_calculo(monhab))
						//}
					}
					li_i=li_i+1;
					parametros=parametros+"&txtscgnrocomp"+li_i+"="+scgnrocomp+"&txtscgcuenta"+li_i+"="+scgcuenta+""+
										  "&txtmondeb"+li_i+"="+mondeb+"&txtmonhab"+li_i+"="+monhab+"&txtdebhab"+li_i+"="+debhab+
										  "&txtestatus"+li_i+"="+estatus+"&txtscgprocededoc"+li_i+"="+scgprocededoc;
				}
			}
			li_totamr=0;
			//---------------------------------------------------------------------------------
			// Cargar las cuentas de amortizacion
			//---------------------------------------------------------------------------------
			for(j=1;(j<=totrowamort)&&(valido);j++)
			{
				monto=eval("f.txtmonto"+j+".value");
				montoaux=ue_formato_calculo(monto);
				if(montoaux>0)
				{
					sc_cuenta=eval("f.txtcuenta"+j+".value");
					codtipdoc=eval("f.txtcodtipdoc"+j+".value");
					numrecdoc=eval("f.txtnumrecdoc"+j+".value");
					codamo=eval("f.txtcodamo"+j+".value");
					ls_operacion="H";
					li_i=li_i+1;
					li_totamr=li_totamr+1;
					mondeb=0;
					monhab=monto;
					li_subtotal=eval(li_subtotal+"-"+ue_formato_calculo(monto));
					
					parametros=parametros+"&txtscgnrocomp"+li_i+"="+ls_numrecdoc+"&txtscgcuenta"+li_i+"="+sc_cuenta+""+
										  "&txtmondeb"+li_i+"="+mondeb+"&txtmonhab"+li_i+"="+monhab+"&txtdebhab"+li_i+"="+ls_operacion+
										  "&txtestatus"+li_i+"=M&txtscgprocededoc"+li_i+"=CXPRCD&txtnumrecdoc"+li_totamr+"="+numrecdoc+
										  "&txtcodtipdoc"+li_totamr+"="+codtipdoc+"&txtcodamo"+li_totamr+"="+codamo;
				}
							
			}
			totrowscg=li_i+1;
			parametros=parametros+"&totrowscg="+totrowscg+"";
			parametros=parametros+"&totrowamort="+li_totamr+"";
			//---------------------------------------------------------------------------------
			// Cargar los totales
			//---------------------------------------------------------------------------------
			li_cargos=ue_formato_calculo(opener.document.formulario.txtcargos.value);
			li_deducciones=ue_formato_calculo(opener.document.formulario.txtdeducciones.value);
			li_total=eval(li_subtotal+"+"+li_cargos);
			li_totalgeneral=eval(li_total+"-"+li_deducciones);
			parametros=parametros+"&numrecdoc="+ls_numrecdoc;
			parametros=parametros+"&estcontable="+contable+"&estpresupuestario="+presupuestario+"&sccuentaprov="+sccuentaprov;
			parametros=parametros+"&generarcontable="+generarcontable+"&subtotal="+li_subtotal+"&cargos="+li_cargos;
			parametros=parametros+"&total="+li_total+"&deducciones="+li_deducciones+"&totgeneral="+li_totalgeneral;
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
				opener.document.formulario.totrowscg.value=totrowscg;
			}
		}
	}
}
</script>
</html>