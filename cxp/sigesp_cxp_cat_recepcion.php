<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_folder/sigesp_cxp_c_recepcion.php");
	$io_cxp=new sigesp_cxp_c_recepcion("../");
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$ls_tipo=$io_fun_cxp->uf_obtenertipo();
	$ls_tipdes=$io_fun_cxp->uf_obtenervalor_get("tipdes","");
	$ls_codproben=$io_fun_cxp->uf_obtenervalor_get("codproben","");
	$ls_nombre=$io_fun_cxp->uf_obtenervalor_get("nombre","");
	$ls_numordpagmin=$io_fun_cxp->uf_obtenervalor_get("numordpagmin","-");
	$ls_codtipfon=$io_fun_cxp->uf_obtenervalor_get("codtipfon","----");
	$ls_tipodestino="";
	$ls_codprovben="";
	$ls_nomprovben="";
	if ($ls_tipo=='REPORTE_UBICACION')
	{
		$ls_tipodestino=$ls_tipdes;
		$ls_codprovben=$ls_codproben;
	}
	unset($io_fun_cxp);
	$ld_fecdes="01/".date("m")."/".date("Y");
	$ld_fechas=date("d/m/Y");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Recepciones de Documentos</title>
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
<script type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="numrecdoc">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<table width="600" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="600" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Recepciones de Documentos </td>
    </tr>
  </table>
  <br>
    <table width="600" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
<?php
	if($ls_tipo=="SOLICITUDPAGO")
	{
?>		
        <td width="136" height="22"><div align="right"><?php if($ls_tipdes=="P"){?>Proveedor<?php }else{?>Beneficiario<?php }?></div></td>
        <td width="262" height="22"><div align="left">
          <input name="txtnombre" type="text" class="sin-borde2" id="txtnombre" value="<?php print $ls_nombre; ?>" size="30" readonly>
          <input name="codproben" type="hidden" id="codproben" value="<?php print $ls_codproben; ?>">
          <input name="tipdes" type="hidden" id="tipdes" value="<?php print $ls_tipdes; ?>">
          <input name="numordpagmin" type="hidden" id="numordpagmin" value="<?php print $ls_numordpagmin; ?>">
          <input name="codtipfon" type="hidden" id="codtipfon" value="<?php print $ls_codtipfon; ?>">
        </div></td>
<?php
	}
	else
	{
?>
          <td height="22"><input name="txtnombre" type="hidden" class="sin-borde2" id="txtnombre">
          <input name="codproben" type="hidden" id="codproben">
          <input name="tipdes" type="hidden" id="tipdes"></td>
          <td height="22">&nbsp;</td>
<?php
	}
?>
          <td width="194" rowspan="4"><table width="159" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td height="22" colspan="2"><div align="center">Fecha de Registro </div></td>
            </tr>
            <tr>
              <td width="58" height="22"><div align="right">Desde</div></td>
              <td width="99"><input name="txtfecdes" type="text" id="txtfecdes" size="15" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true" value="<?php print $ld_fecdes;?>"></td>
            </tr>
            <tr>
              <td height="22"><div align="right">Hasta</div></td>
              <td><input name="txtfechas" type="text" id="txtfechas" size="15" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true" value="<?php print $ld_fechas;?>"></td>
            </tr>
          </table></td>
        </tr>
      <tr>
        <td width="136" height="22"><div align="right">N&uacute;mero de Recepci&oacute;n </div></td>
        <td width="262" height="22"><div align="left">
          <input name="txtnumrecdoc" type="text" id="txtnumrecdoc" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Estatus</div></td>
        <td height="22"><div align="left">
          <select name="cmbestprodoc">
            <option value="" selected>--Seleccione--</option>
            <option value="R">Recibidas</option>
            <option value="E">Emitidas</option>
            <option value="C">Contabilizadas</option>
            <option value="A">Anuladas</option>
          </select>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Concepto </div></td>
        <td height="22"><?php $io_cxp->uf_load_clasificacionconcepto("");?></td>
      </tr>
	  <?php if($ls_tipo!="SOLICITUDPAGO")
	  { ?>
	  <tr>
	    <td><div align="right">Destino</div></td>
        <td colspan="4"><div align="left">
          <select name="cmbtipdes" id="cmbtipdes" onChange="javascript: ue_cambiardestino();">
            <option value="-" selected>-- Seleccione Uno --</option>
            <option value="P" <?php if($ls_tipodestino=="P"){ print "selected";} ?>>PROVEEDOR</option>
            <option value="B" <?php if($ls_tipodestino=="B"){ print "selected";} ?>>BENEFICIARIO</option>
            </select>
          <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codprovben;?>" size="15" maxlength="10" readonly>
          <input name="txtnombre2" type="text" class="sin-borde" id="txtnombre2" value="<?php print $ls_nomprovben;?>" size="40" maxlength="30" readonly>
        </div></td>
      </tr>
	  <?php }?>
	  <tr>
        <td colspan="3"><div align="right"><?php  if($ls_tipo!="SOLICITUDPAGO"){?><a href="javascript: ue_search_recepcion();"><?php }else{?><a href="javascript: ue_search();"><?php }?><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
	  </tr>
	</table> 
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_cambiardestino()
{
	f=document.formulario;
	// Se verifica si el destino es un proveedor ó beneficiario y se carga el catalogo
	// dependiendo de esa información
	f.txtcodigo.value="";
	f.txtnombre.value="";
	tipdes=f.cmbtipdes.value;
	if(tipdes!="-")
	{
		if(tipdes=="P")
		{
          window.open("sigesp_cxp_cat_proveedor.php?tipo=SOLICITUDPAGO","_blank","menubar=no,toolbar=no,scrollbars=no,width=600,height=400,resizable=yes,location=no,left=50,top=50");
		}
		else
		{
            window.open("sigesp_cxp_cat_beneficiario.php?tipo=SOLICITUDPAGO","_blank","menubar=no,toolbar=no,scrollbars=no,width=600,height=400,resizable=yes,location=no,left=50,top=50");
		}	
	}
}
function ue_aceptar(numrecdoc,codtipdoc,cedbene,codpro,codcla,fecemidoc,fecregdoc,fecvendoc,montotdoc,
					mondeddoc,moncardoc,tipproben,numref,estprodoc,procede,estlibcom,estaprord,estimpmun,nombre,rif,
					sccuenta,tipocont,estcon,estpre,li_i,ls_estatus,codfuefin,denfuefin,codrecdoc,coduniadm,denuniadm,codestpro1,
					codestpro2,codestpro3,codestpro4,codestpro5,estcla,estact,numordpagmin,codtipfon)
{
	generarcontable=opener.document.formulario.generarcontable.value;
	if((generarcontable=="1")&&((estpre=="3")||(estpre=="4")))
	{
		alert("Las recepciones Tipo contables Solo pueden ser cargadas en la Opción de Recepcion->Contable");
	}
	else
	{
		opener.document.formulario.txtcodrecdoc.value=codrecdoc;
		opener.document.formulario.txtnumrecdoc.value=numrecdoc;
		opener.document.formulario.txtnumrecdoc.readOnly=true;
		opener.document.formulario.cmbcodtipdoc.value=codtipdoc+"-"+estcon+"-"+estpre;
		opener.document.formulario.cmbcodtipdoc.disabled=true;
		opener.document.formulario.codtipdoc.value=codtipdoc+"-"+estcon+"-"+estpre;
		opener.document.formulario.cmbcodcla.value=codcla;
		opener.document.formulario.codcla.value=codcla;
		opener.document.formulario.txtfecregdoc.value=fecregdoc;
		opener.document.formulario.txtfecregdoc.disabled=true;
		opener.document.formulario.fecregdoc.value=fecregdoc;
		opener.document.formulario.txtfecemidoc.value=fecemidoc;
		opener.document.formulario.txtfecemidoc.disabled=true;
		opener.document.formulario.fecemidoc.value=fecemidoc;
		opener.document.formulario.txtfecvendoc.value=fecvendoc;
		opener.document.formulario.txtfecvendoc.disabled=true;
		opener.document.formulario.fecvendoc.value=fecvendoc;
		opener.document.formulario.cmbtipdes.disabled=true;
		opener.document.formulario.cmbtipdes.value=tipproben;
		opener.document.formulario.tipdes.value=tipproben;
		opener.document.formulario.txtestatus.value=ls_estatus;
		opener.document.formulario.txtcodfuefin.value=codfuefin;
		opener.document.formulario.txtdenfuefin.value=denfuefin;
		if(tipproben=="P")
		{
			opener.document.formulario.txtcodigo.value=codpro;
		}
		else
		{
			opener.document.formulario.txtcodigo.value=cedbene;
		}
		opener.document.formulario.txtnombre.value=nombre;
		opener.document.formulario.txtrifpro.value=rif;
		opener.document.formulario.txtnumref.value=numref;
		opener.document.formulario.txtnumref.readOnly=true;
		opener.document.formulario.hidestact.value=estact;
		if(estact==1)
		{
			opener.document.formulario.chkactivos.checked=true;
		}
		else
		{
			opener.document.formulario.chkactivos.checked=false;
		}
		opener.document.formulario.chkactivos.disabled=true;
		f=document.formulario;
		opener.document.formulario.txtdencondoc.value=eval("f.txtdencondoc"+li_i+".value;");
		opener.document.formulario.estatuspresupuesto.value=estpre;
		opener.document.formulario.estatuscontable.value=estcon;
		opener.document.formulario.codigocuenta.value=sccuenta;
		opener.document.formulario.estaprord.value=estaprord;
		opener.document.formulario.procede.value=procede;
		opener.document.formulario.tipocontribuyente.value=tipocont;
		opener.document.formulario.txtcodunieje.value=coduniadm;
		opener.document.formulario.txtdenunieje.value=denuniadm;
		opener.document.formulario.txtcodestpro1.value=codestpro1;
		opener.document.formulario.txtcodestpro2.value=codestpro2;
		opener.document.formulario.txtcodestpro3.value=codestpro3;
		opener.document.formulario.txtcodestpro4.value=codestpro4;
		opener.document.formulario.txtcodestpro5.value=codestpro5;
		opener.document.formulario.hidestcla.value=estcla;
		opener.document.formulario.txtnumordpagmin.value=numordpagmin;
		opener.document.formulario.txtcodtipfon.value=codtipfon;
		opener.document.formulario.chkestlibcom.checked="";
		opener.document.formulario.chkestimpmun.checked="";
		if(estlibcom=="1")
		{
			opener.document.formulario.chkestlibcom.checked=true;
		}
		if(estimpmun=="1")
		{
			opener.document.formulario.chkestimpmun.checked=true;
		}	
		opener.document.formulario.existe.value="TRUE";
		li_total=eval(montotdoc+"+"+mondeddoc);
		li_subtotal=eval(li_total+"-"+moncardoc);
		parametros="";
		parametros=parametros+"&numrecdoc="+numrecdoc+"&codtipdoc="+codtipdoc+"&cedbene="+cedbene+"&codpro="+codpro;
		parametros=parametros+"&estcontable="+estcon+"&estpresupuestario="+estpre+"&sccuentaprov="+sccuenta;
		parametros=parametros+"&subtotal="+li_subtotal+"&cargos="+moncardoc+"&total="+li_total;
		parametros=parametros+"&deducciones="+mondeddoc+"&totgeneral="+montotdoc+"&generarcontable="+generarcontable;
		if(parametros!="")
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
							close();
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
			ajax.send("proceso=LOADRECEPCION"+parametros);
		}
	}
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	numrecdoc=f.txtnumrecdoc.value;
	estprodoc=f.cmbestprodoc.value;
	codcla=f.cmbcodcla.value;
	tipdes=ue_validarvacio(f.tipdes.value);
	codproben=ue_validarvacio(f.codproben.value);	
	fecregdes=ue_validarvacio(f.txtfecdes.value);
	fecreghas=ue_validarvacio(f.txtfechas.value);
	numordpagmin=ue_validarvacio(f.numordpagmin.value);
	codtipfon=ue_validarvacio(f.codtipfon.value);
	tipo=f.tipo.value;
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	if((fecregdes!="")&&(fecreghas!=""))
	{
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
		ajax.send("catalogo=RECEPCION&numrecdoc="+numrecdoc+"&estprodoc="+estprodoc+"&codcla="+codcla+"&fecregdes="+fecregdes+
				  "&tipdes="+tipdes+"&codproben="+codproben+"&fecreghas="+fecreghas+"&tipo="+tipo+"&orden="+orden+
				  "&campoorden="+campoorden+"&numordpagmin="+numordpagmin+"&codtipfon="+codtipfon);
	}
	else
	{
		alert("Debe seleccionar un rango de Fecha.");
	}
}
function ue_search_recepcion()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	numrecdoc=f.txtnumrecdoc.value;
	estprodoc=f.cmbestprodoc.value;
	codcla=f.cmbcodcla.value;
	tipdes=ue_validarvacio(f.cmbtipdes.value);
	codproben=ue_validarvacio(f.txtcodigo.value);	
	fecregdes=ue_validarvacio(f.txtfecdes.value);
	fecreghas=ue_validarvacio(f.txtfechas.value);
	tipo=f.tipo.value;
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	procedencia="RECEPCION";
	if((fecregdes!="")&&(fecreghas!=""))
	{
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
		ajax.send("catalogo=RECEPCION&numrecdoc="+numrecdoc+"&estprodoc="+estprodoc+"&codcla="+codcla+"&fecregdes="+fecregdes+
				  "&tipdes="+tipdes+"&codproben="+codproben+"&fecreghas="+fecreghas+"&tipo="+tipo+"&orden="+orden+
				  "&campoorden="+campoorden+"&procedencia="+procedencia);
	}
	else
	{
		alert("Debe seleccionar un rango de Fecha.");
	}
}

function ue_aceptar_solicitud(numrecdoc,dencondoc,codtipdoc,dentipdoc,montotdoc)
{
	//---------------------------------------------------------------------------------
	// Verificamos que la solicitud no esté en el formulario
	//---------------------------------------------------------------------------------
	valido=true;
	numrecdoc=trim(numrecdoc);
	total=ue_calcular_total_fila_opener("txtnumrecdoc");
	opener.document.formulario.totrowrecepciones.value=total;
	rowrecepciones=opener.document.formulario.totrowrecepciones.value;
	for(j=1;(j<=rowrecepciones)&&(valido);j++)
	{
		numrecdocgrid=eval("opener.document.formulario.txtnumrecdoc"+j+".value");
		if(numrecdocgrid==numrecdoc)
		{
			alert("La Recepción de Documentos ya está en la solicitud");
			valido=false;
			
		}
	}
	//---------------------------------------------------------------------------------
	// Cargar los Bienes del opener y el seleccionado
	//---------------------------------------------------------------------------------
	parametros="";
	mondocgrid=0;
	for(j=1;(j<rowrecepciones)&&(valido);j++)
	{
		numrecdocgrid=eval("opener.document.formulario.txtnumrecdoc"+j+".value");
		codtipdocgrid=eval("opener.document.formulario.txtcodtipdoc"+j+".value");
		dentipdocgrid=eval("opener.document.formulario.txtdentipdoc"+j+".value");
		montotdocgrid=eval("opener.document.formulario.txtmontotdoc"+j+".value");
		parametros=parametros+"&txtnumrecdoc"+j+"="+numrecdocgrid+"&txtcodtipdoc"+j+"="+codtipdocgrid+""+
				   "&txtdentipdoc"+j+"="+dentipdocgrid+"&txtmontotdoc"+j+"="+montotdocgrid+"";
		montotdocaux=ue_formato_calculo(montotdocgrid);
		mondocgrid=eval(mondocgrid+"+"+montotdocaux);
	}
	totalrecepciones=eval(rowrecepciones+"+1");
	mondoc=ue_formato_calculo(montotdoc);
	montotal=eval(mondocgrid+"+"+mondoc);
	consol=opener.document.formulario.txtconsol.value;
	if(consol="")
	{
		opener.document.formulario.txtconsol.value=dencondoc;
	}
	else
	{
		consol= consol+", "+dencondoc;
		opener.document.formulario.txtconsol.value=dencondoc;
	}
	parametros=parametros+"&txtnumrecdoc"+j+"="+numrecdoc+"&txtcodtipdoc"+j+"="+codtipdoc+""+
			   "&txtdentipdoc"+j+"="+dentipdoc+"&txtmontotdoc"+j+"="+montotdoc+"&totrowrecepciones="+totalrecepciones+"&total="+montotal+"";
	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("recepciones");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_cxp_c_solicitudpago_ajax.php",true);
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
						close();
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
		ajax.send("proceso=AGREGARRECEPCIONES"+parametros);
		opener.document.formulario.totrowrecepciones.value=totalrecepciones;
	}
}

function ue_aceptar_ubicacion(numrecdoc)
{
	opener.document.formulario.txtnumrecdoc.value=trim(numrecdoc);
	close();
}
</script>
</html>