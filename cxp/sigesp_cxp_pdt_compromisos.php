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
<title>Compromisos</title>
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
      <td height="20" colspan="2" class="titulo-ventana">Compromisos</td>
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
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	numdoc=opener.document.formulario.txtnumrecdoc.value;
	codtipdoc=opener.document.formulario.cmbcodtipdoc.value;
	codigo=opener.document.formulario.txtcodigo.value;
	tipodes=opener.document.formulario.cmbtipdes.value;
	fechareg=opener.document.formulario.txtfecregdoc.value;
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
	ajax.send("catalogo=COMPROMISOS&numdoc="+numdoc+"&codtipdoc="+codtipdoc+"&codigo="+codigo+"&tipodes="+tipodes+"&fechareg="+fechareg);
}

function ue_aceptar(comprobante,procede,fecha,total,monto_cargo,li_i)
{
	f=document.formulario;
	valido=true;
	parametros="";
	
	opener.document.formulario.cerrarasiento.value="0";
	opener.document.formulario.txtdencondoc.value=opener.document.formulario.txtdencondoc.value+" - "+eval("f.txtconcepto"+li_i+".value");
	totrowspg=ue_calcular_total_fila_opener("txtspgnrocomp");
	opener.document.formulario.totrowspg.value=totrowspg;
	totrowscg=ue_calcular_total_fila_opener("txtscgnrocomp");
	opener.document.formulario.totrowscg.value=totrowscg;
	generarcontable=opener.document.formulario.generarcontable.value;
	//---------------------------------------------------------------------------------
	// Verificamos que el compromiso no exista
	//---------------------------------------------------------------------------------
	for(j=1;(j<totrowspg)&&(valido);j++)
	{
		spgnrocomp=eval("opener.document.formulario.txtspgnrocomp"+j+".value");
		procededoc=eval("opener.document.formulario.txtspgprocededoc"+j+".value");
		if((comprobante==spgnrocomp)&&(procede==procededoc))
		{
			alert("El comprobante ya está en la recepción");
			valido=false;
		}
	}
	//---------------------------------------------------------------------------------
	// recorremos grid de las cuentas presupuestarias
	//---------------------------------------------------------------------------------
	for(j=1;(j<totrowspg)&&(valido);j++)
	{
		spgnrocomp=eval("opener.document.formulario.txtspgnrocomp"+j+".value");
		programatica=eval("opener.document.formulario.txtprogramatica"+j+".value");
		spgcuenta=eval("opener.document.formulario.txtspgcuenta"+j+".value");
		spgmonto=eval("opener.document.formulario.txtspgmonto"+j+".value");
		codpro=eval("opener.document.formulario.txtcodpro"+j+".value");
		cargo=eval("opener.document.formulario.txtcargo"+j+".value");
		original=eval("opener.document.formulario.txtoriginal"+j+".value");
		spgsccuenta=eval("opener.document.formulario.txtspgsccuenta"+j+".value");
		procededoc=eval("opener.document.formulario.txtspgprocededoc"+j+".value");
		codfuefin=eval("opener.document.formulario.txtcodfuefin"+j+".value");
		tipbieordcom=eval("opener.document.formulario.txttipbieordcom"+j+".value");
		estcla=eval("opener.document.formulario.txtestcla"+j+".value");
		estint=eval("opener.document.formulario.txtestint"+j+".value");
		cuentaint=eval("opener.document.formulario.txtcuentaint"+j+".value");
		parametros=parametros+"&txtspgnrocomp"+j+"="+spgnrocomp+"&txtprogramatica"+j+"="+programatica+""+
				   "&txtspgcuenta"+j+"="+spgcuenta+"&txtspgmonto"+j+"="+spgmonto+""+
				   "&txtcodpro"+j+"="+codpro+"&txtcargo"+j+"="+cargo+""+"&txtoriginal"+j+"="+original+
				   "&txtspgsccuenta"+j+"="+spgsccuenta+"&txtspgprocededoc"+j+"="+procededoc+
				   "&txtcodfuefin"+j+"="+codfuefin+"&txttipbieordcom"+j+"="+tipbieordcom+
				   "&txtestint"+j+"="+estint+"&txtestcla"+j+"="+estcla+"&txtcuentaint"+j+"="+cuentaint;
	}
	parametros=parametros+"&totrowspg="+totrowspg+"";
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
	// Cargar los totales
	//---------------------------------------------------------------------------------
	ls_numrecdoc=opener.document.formulario.txtnumrecdoc.value;
	presupuestario=opener.document.formulario.estatuspresupuesto.value;
	contable=opener.document.formulario.estatuscontable.value;
	sccuentaprov=opener.document.formulario.codigocuenta.value;
	li_subtotal=ue_formato_calculo(opener.document.formulario.txtsubtotal.value);
	li_cargos=ue_formato_calculo(opener.document.formulario.txtcargos.value);
	li_deducciones=ue_formato_calculo(opener.document.formulario.txtdeducciones.value);
	li_total=eval(li_subtotal+"+"+li_cargos);
	li_totalgeneral=eval(li_total+"-"+li_deducciones);
	parametros=parametros+"&comprobante="+comprobante+"&procede="+procede+"&disponible="+total+"&montocargo="+monto_cargo;
	parametros=parametros+"&estcontable="+contable+"&estpresupuestario="+presupuestario+"&sccuentaprov="+sccuentaprov;
	parametros=parametros+"&subtotal="+li_subtotal+"&cargos="+li_cargos+"&total="+li_total+"&numrecdoc="+ls_numrecdoc;
	parametros=parametros+"&deducciones="+li_deducciones+"&totgeneral="+li_totalgeneral+"&cargarcomprobantes=1";
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
		ajax.send("proceso=CAUSA"+parametros);
	}
}
</script>
</html>