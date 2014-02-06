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
	$ls_numrecdoc=$io_fun_cxp->uf_obtenervalor_get("documento","");
	$ls_sccuentaprov=$io_fun_cxp->uf_obtenervalor_get("sccuenta","");
	$ls_codestpro1=$io_fun_cxp->uf_obtenervalor_get("codestpro1","");
	$ls_codestpro2=$io_fun_cxp->uf_obtenervalor_get("codestpro2","");
	$ls_codestpro3=$io_fun_cxp->uf_obtenervalor_get("codestpro3","");
	$ls_codestpro4=$io_fun_cxp->uf_obtenervalor_get("codestpro4","");
	$ls_codestpro5=$io_fun_cxp->uf_obtenervalor_get("codestpro5","");
	$ls_estcla=$io_fun_cxp->uf_obtenervalor_get("estcla","");
	$ls_estint=$io_fun_cxp->uf_obtenervalor_get("estint","");
	$ls_activo=$io_fun_cxp->uf_obtenervalor_get("activo","-");
	$ls_cuentaint=$io_fun_cxp->uf_obtenervalor_get("cuentaint","");
 	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	$li_len1=0;
	$li_len2=0;
	$li_len3=0;
	$li_len4=0;
	$li_len5=0;
	$ls_titulo="";
	$lb_valido=$io_fun_cxp->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
	$ls_auxlen1=25-$li_len1;
	$ls_auxlen2=25-$li_len2;
	$ls_auxlen3=25-$li_len3;
	$ls_auxlen4=25-$li_len4;
	$ls_auxlen5=25-$li_len5;
	$ls_codestpro1aux=substr($ls_codestpro1,$ls_auxlen1,$li_len1);
	$ls_codestpro2aux=substr($ls_codestpro2,$ls_auxlen2,$li_len2);
	$ls_codestpro3aux=substr($ls_codestpro3,$ls_auxlen3,$li_len3);
	$ls_codestpro4aux=substr($ls_codestpro4,$ls_auxlen4,$li_len4);
	$ls_codestpro5aux=substr($ls_codestpro5,$ls_auxlen5,$li_len5);
	unset($io_fun_cxp);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalles Presupuestarios</title>
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
<body>
<form name="formulario" method="post" action="">
  <table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td height="20" colspan="2" class="titulo-ventana">Detalles Presupuestarios </td>
    </tr>
  </table>
<br>
<table width="650" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="116" height="22">&nbsp;</td>
        <td width="528" height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">N&uacute;mero de Documento </div></td>
        <td height="22"><div align="left">
          <label>
          <input name="txtnumrecdoc" type="text" id="txtnumrecdoc" value="<?php print $ls_numrecdoc; ?>" size="20" maxlength="15" readonly>
          </label>
          <input name="sccuentaprov" type="hidden" id="sccuentaprov" value="<?php print $ls_sccuentaprov; ?>">
          <input name="estmodest" type="hidden" id="estmodest" value="<?php print $ls_modalidad; ?>">
		   <input name="codestpro1" type="hidden" id="codestpro1" size="30" maxlength="25" value="<?php print $ls_codestpro1; ?>">
		   <input name="codestpro2" type="hidden" id="codestpro2" size="30" maxlength="25" value="<?php print $ls_codestpro2; ?>">
		   <input name="codestpro3" type="hidden" id="codestpro3" size="30" maxlength="25" value="<?php print $ls_codestpro3; ?>">
           <input name="codestpro4" type="hidden" id="codestpro4" size="30" maxlength="25" value="<?php print $ls_codestpro4; ?>">
           <input name="codestpro5" type="hidden" id="codestpro5" size="30" maxlength="25" value="<?php print $ls_codestpro5; ?>">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro1"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro1" type="text" id="txtcodestpro1" style="text-align:center" value="<?php print $ls_codestpro1aux; ?>" size="<?php print ($li_len1+10); ?>" maxlength="<?php print $li_len1; ?>" readonly><a href="javascript:ue_estructura1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
          <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" size="53" readonly>
          <input name="estcla" type="hidden" id="estcla" value="<?php print $ls_estcla; ?>">
          <input name="estint" type="hidden" id="estint" value="<?php print $ls_estint; ?>">
          <input name="cuentaint" type="hidden" id="cuentaint" value="<?php print $ls_cuentaint; ?>">
          <input name="activo" type="hidden" id="activo" value="<?php print $ls_activo; ?>">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro2"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro2" type="text" id="txtcodestpro2" value="<?php print $ls_codestpro2aux; ?>" size="<?php print ($li_len2+10); ?>" maxlength="<?php print $li_len2; ?>" style="text-align:center" readonly><a href="javascript:ue_estructura2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
          <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" size="53" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro3"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro3" type="text" id="txtcodestpro3"  value="<?php print $ls_codestpro3aux; ?>" size="<?php print ($li_len3+10); ?>" maxlength="<?php print $li_len3; ?>"  style="text-align:center" readonly><a href="javascript:ue_estructura3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
          <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" size="53" readonly>
        </div></td>
      </tr>
<?php if($ls_modalidad=="1") // Por Proyecto
	  {?>
 				<input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="00">
 				<input name="txtdenestpro4" type="hidden" id="txtdenestpro4" value="">
 				<input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="00">
 				<input name="txtdenestpro5" type="hidden" id="txtdenestpro5" value="">
<?php }
	  else
	  {?>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro4"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro4" type="text" id="txtcodestpro4"  value="<?php print $ls_codestpro4aux; ?>" size="<?php print ($li_len4+10); ?>" maxlength="<?php print $li_len4; ?>"  style="text-align:center" readonly>
          <?php if($ls_codestpro4==""){?><a href="javascript:ue_estructura4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a><?php }?>
          <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" size="53" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro5"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro5" type="text" id="txtcodestpro5"  value="<?php print $ls_codestpro5aux; ?>" size="<?php print ($li_len5+10); ?>" maxlength="<?php print $li_len5; ?>"  style="text-align:center" readonly>
         <?php if($ls_codestpro5==""){?> <a href="javascript:ue_estructura5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a><?php }?>
          <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" size="53" readonly>
        </div></td>
      </tr>
<?php }?>
      <tr>
        <td height="22"><div align="right">Cuenta</div></td>
        <td height="22"><div align="left">
          <input name="txtspgcuenta" type="text" id="txtspgcuenta" style="text-align:center" size="27" maxlength="25" readonly>
          <a href="javascript:ue_cuentasspg();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas de Gastos"></a> 
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" size="55">
          <input name="sccuenta" type="hidden" id="sccuenta">
          <input name="disponibilidad" type="hidden" id="disponibilidad">
          <input name="estvaldis" type="hidden" id="estvaldis" value="<?php print $_SESSION["la_empresa"]["estvaldis"]?>">
        </div></td>
      <tr>
        <td height="22"><div align="right">Monto</div></td>
        <td height="22"><div align="left">
          <input name="txtmonto" type="text" id="txtmonto" style="text-align:right" size="22" onKeyPress="return(ue_formatonumero(this,'.',',',event));">
          <a href="javascript: ue_aceptar();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar Detalle Presupuestario" width="15" height="15" border="0"></a>
		  <a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools15/eliminar.gif" width="15" height="15" border="0" alt="Cancelar Registro de Detalle Presupuestario"></a></div></td>
    <tr>
      <td height="22">&nbsp;</td>
      <td>&nbsp;</td>
  </table> 
	<p>
    </p>
</form>      
</body>
<script language="JavaScript">
function ue_cerrar()
{
	close();
}

function ue_aceptar()
{
	f=document.formulario;
	monto=ue_formato_calculo(f.txtmonto.value);
	if(parseFloat(monto)>0)
	{
		opener.document.formulario.cerrarasiento.value="0";
		//monto=f.txtmonto.value;
		disponible=f.disponibilidad.value;
		//monto=ue_formato_calculo(monto);
		disponible=ue_formato_calculo(disponible);
		presupuestario=opener.document.formulario.estatuspresupuesto.value;
		contable=opener.document.formulario.estatuscontable.value;
		causadoparcial=opener.document.formulario.causadoparcial.value;
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
		estvaldis=f.estvaldis.value;
		valdisponible=false;
		if(estvaldis==0)
		{
			valdisponible=true;
		}
		else
		{
			if(parseFloat(monto)<=parseFloat(disponible))
			{
				valdisponible=true;
			}
		}
		//if(parseFloat(monto)<=parseFloat(disponible))
		if(valdisponible)
		{
			//---------------------------------------------------------------------------------
			// Verificamos que la cuenta spg no esté en el formulario
			//---------------------------------------------------------------------------------
			valido=true;
			if(f.txtcodestpro4.value=="")
			{
				f.txtcodestpro4.value="00";
			}
			if(f.txtcodestpro5.value=="")
			{
				f.txtcodestpro5.value="00";
			}
			f.codestpro1.value=f.txtcodestpro1.value;
			f.codestpro2.value=f.txtcodestpro2.value;
			f.codestpro3.value=f.txtcodestpro3.value;
			f.codestpro4.value=f.txtcodestpro4.value;
			f.codestpro5.value=f.txtcodestpro5.value;
			ue_rellenarcampo(f.codestpro1,25);
			ue_rellenarcampo(f.codestpro2,25);
			ue_rellenarcampo(f.codestpro3,25);
			ue_rellenarcampo(f.codestpro4,25);
			ue_rellenarcampo(f.codestpro5,25);
			codestpro=f.codestpro1.value+f.codestpro2.value+f.codestpro3.value+f.codestpro4.value+f.codestpro5.value;
			ls_estcla=f.estcla.value;
			cuentapresupuesto=f.txtspgcuenta.value;
			numrecdoc=f.txtnumrecdoc.value;
			sccuentaprov=f.sccuentaprov.value;
			sccuenta=f.sccuenta.value;
			estint=f.estint.value;
			cuentaint=f.cuentaint.value;
			tipbieordcom=f.activo.value;
			procede=opener.document.formulario.procede.value;
			ls_codfuefin=opener.document.formulario.txtcodfuefin.value;
			generarcontable=opener.document.formulario.generarcontable.value;
			totrowspg=ue_calcular_total_fila_opener("txtspgnrocomp");
			opener.document.formulario.totrowspg.value=totrowspg;
			totrowscg=ue_calcular_total_fila_opener("txtscgnrocomp");
			opener.document.formulario.totrowscg.value=totrowscg;
			existe=false;
			li_subtotal=0;
			li_total=0;
			li_totalgeneral=0;
			//---------------------------------------------------------------------------------
			// Cargar el grid de las cuentas presupuestarias
			//---------------------------------------------------------------------------------
			parametros="";
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
				codfuefin=eval("opener.document.formulario.txtcodfuefin"+j+".value");
				tipbieordcomgrid=eval("opener.document.formulario.txttipbieordcom"+j+".value");
				estintgrid=eval("opener.document.formulario.txtestint"+j+".value");
				cuentaintgrid=eval("opener.document.formulario.txtcuentaint"+j+".value");
				if((codpro==codestpro)&&(estcla==ls_estcla)&&(spgcuenta==cuentapresupuesto))
				{
					spgmonto=ue_formato_calculo(spgmonto);
					spgmonto=eval(spgmonto+"+"+monto);
					spgmonto=redondear(spgmonto,2);
					spgmonto=uf_convertir(spgmonto);
					existe=true;
				}			
				cargo=eval("opener.document.formulario.txtcargo"+j+".value");
				if(cargo=="0")
				{
					li_subtotal=eval(li_subtotal+"+"+ue_formato_calculo(spgmonto));
				}
				parametros=parametros+"&txtspgnrocomp"+j+"="+spgnrocomp+"&txtprogramatica"+j+"="+programatica+""+"&txtestcla"+j+"="+estcla+""+
						   "&txtspgcuenta"+j+"="+spgcuenta+"&txtspgmonto"+j+"="+spgmonto+""+
						   "&txtcodpro"+j+"="+codpro+"&txtcargo"+j+"="+cargo+""+"&txtoriginal"+j+"="+original+
						   "&txtspgsccuenta"+j+"="+spgsccuenta+"&txtspgprocededoc"+j+"="+procededoc+
						   "&txtcodfuefin"+j+"="+codfuefin+"&txtestint"+j+"="+estintgrid+"&txtcuentaint"+j+"="+cuentaintgrid+"&txttipbieordcom"+totrowspg+"="+tipbieordcomgrid;
			}
			if(existe==false)
			{
				totalcuentasspg=eval(totrowspg+"+1");
				li_subtotal=eval(li_subtotal+"+"+monto);
				monto=f.txtmonto.value;
				parametros=parametros+"&txtspgnrocomp"+totrowspg+"="+numrecdoc+"&txtprogramatica"+totrowspg+"="+codestpro+""+"&txtestcla"+totrowspg+"="+ls_estcla+""+
									  "&txtspgcuenta"+totrowspg+"="+cuentapresupuesto+"&txtspgmonto"+totrowspg+"="+monto+""+
									  "&txtcodpro"+totrowspg+"="+codestpro+"&txtcargo"+totrowspg+"=0"+
									  "&txtoriginal"+totrowspg+"="+monto+"&txtspgsccuenta"+totrowspg+"="+sccuenta+""+
									  "&txtspgprocededoc"+totrowspg+"="+procede+"&totrowspg="+totalcuentasspg+
									  "&txtcodfuefin"+totrowspg+"="+ls_codfuefin+"&txtestint"+totrowspg+"="+estint+
									  "&txtcuentaint"+totrowspg+"="+cuentaint+"&txttipbieordcom"+totrowspg+"="+tipbieordcom;
			}
			else
			{
				parametros=parametros+"&totrowspg="+totrowspg+"";
			}
			//---------------------------------------------------------------------------------
			// Cargamos el grid de las cuentas contables
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
						if(ls_proceso!="CONTABLE")
						{
							li_subtotal=eval(li_subtotal+"-"+ue_formato_calculo(monhab))
						}
					}
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
				ajax.send("proceso=COMPROMETECAUSA"+parametros);
				opener.document.formulario.totrowspg.value=totalcuentasspg;
			}
		}
		else
		{
			alert("El monto ingresado es mayor que el Disponible.");
		}
	}
	else
	{
		alert("El monto debe ser mayor a cero");
	}
}

function ue_estructura1()
{
	window.open("sigesp_cxp_cat_estpre1.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
}

function ue_estructura2()
{
	f=document.formulario;
	codestpro1=f.txtcodestpro1.value;
	estcla=f.estcla.value;
	denestpro1=f.txtdenestpro1.value;
	if(codestpro1!="")
	{
		pagina="sigesp_cxp_cat_estpre2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&estcla="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");		
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura3()
{
	f=document.formulario;
	codestpro1=f.txtcodestpro1.value;
	estcla=f.estcla.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	estmodest=f.estmodest.value;
	if(estmodest==2)
	{
		if((codestpro1!="")&&(codestpro2!=""))
		{
			pagina="sigesp_cxp_cat_estpre3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione la Estructura de nivel Anterior");
		}
	}
	else
	{
		pagina="sigesp_cxp_cat_estpre3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&estcla="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
}

function ue_estructura4()
{
	f=document.formulario;
	codestpro1=f.txtcodestpro1.value;
	estcla=f.estcla.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	codestpro3=f.txtcodestpro3.value;
	denestpro3=f.txtdenestpro3.value;
	if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!=""))
	{
    	pagina="sigesp_cxp_cat_estpre4.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+
			   "&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&estcla="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
		f.txtcodestpro5.value="";
		f.txtdenestpro5.value="";
		f.txtspgcuenta.value="";
		f.txtdenominacion.value="";
		f.sccuenta.value="";
		f.disponibilidad.value=0;
		f.txtmonto.value="0,00";
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura5()
{
	f=document.formulario;
	estcla=f.estcla.value;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	codestpro3=f.txtcodestpro3.value;
	denestpro3=f.txtdenestpro3.value;
	codestpro4=f.txtcodestpro4.value;
	denestpro4=f.txtdenestpro4.value;
	pagina="sigesp_cxp_cat_estpre5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+
		   "&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4+"&denestpro4="+denestpro4+"&estcla="+estcla;
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	f.txtspgcuenta.value="";
	f.txtdenominacion.value="";
	f.sccuenta.value="";
	f.disponibilidad.value=0;
	f.txtmonto.value="0,00";
}

function ue_cuentasspg()
{
	f=document.formulario;
	estmodest=f.estmodest.value;
	if(estmodest==1) // Si es por proyecto
	{
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!=""))
		{
			pagina="sigesp_cxp_cat_cuentasspg.php";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione la Estructura Presupuestaria");
		}
	}
	else
	{
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;
		codestpro4=f.txtcodestpro4.value;
		codestpro5=f.txtcodestpro5.value;
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!=""))
		{
			pagina="sigesp_cxp_cat_cuentasspg.php";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione la Estructura Presupuestaria");
		}
	}
}
</script>
</html>