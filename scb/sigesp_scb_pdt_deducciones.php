<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_funciones_banco.php");
	$io_fun_scb	  = new class_funciones_banco();
	$ls_tipo	  = $io_fun_scb->uf_obtenertipo();
	$ls_numrecdoc = $io_fun_scb->uf_obtenervalor_get("numrecdoc","");
	$li_subtotal  = $io_fun_scb->uf_obtenervalor_get("subtotal","0,00");
	$ls_procede   = $io_fun_scb->uf_obtenervalor_get("procede","CXPRCD");
	$li_cargos    = $io_fun_scb->uf_obtenervalor_get("cargos","0,00");
	$ls_modageret = $_SESSION["la_empresa"]["modageret"];
	unset($io_fun_scb);
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
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

function ue_calcular(fila)
{
	f=document.formulario;
	marcado=eval("f.chkdeduccion"+fila+".checked");
	if(marcado==true)
	{
		baseimponible=eval("f.txtmonobjret"+fila+".value");
		baseimponible=ue_formato_calculo(baseimponible);
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
	if (tipo=='CMPRETIVA')
	   {
	     numrecdoc="";
		 subtotal="";
		 cargos="";
		 procede="";
		 presupuestario="";
		 contable="";
	   }
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	// Div donde se van a cargar los resultados
	divgrid = document.getElementById('resultados');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_scb_c_catalogo_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";
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
			  "&subtotal="+subtotal+"&cargos="+cargos+"&procede="+procede+"&presupuestario="+presupuestario+"&contable="+contable);
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