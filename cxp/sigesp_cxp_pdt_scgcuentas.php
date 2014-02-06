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
	unset($io_fun_cxp);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalles Contables</title>
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
      <td height="20" colspan="2" class="titulo-ventana">Detalles Contables </td>
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
          <input name="txtnumrecdoc" type="text" id="txtnumrecdoc" value="<?php print $ls_numrecdoc; ?>" size="20" maxlength="15" readonly>
          <input name="sccuentaprov" type="hidden" id="sccuentaprov" value="<?php print $ls_sccuentaprov; ?>">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Cuenta</div></td>
        <td height="22"><div align="left">
          <input name="txtscgcuenta" type="text" id="txtscgcuenta" style="text-align:center" size="27" maxlength="30" readonly>
          <a href="javascript:ue_cuentasscg();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas de Gastos"></a> 
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" size="55">
        </div></td>
      <tr>
        <td height="22"><div align="right">Operaci&oacute;n:</div></td>
        <td height="22"><label>
          <select name="cmboperacion" id="cmboperacion">
            <option value="D" selected>Debe</option>
            <option value="H">Haber</option>
            </select>
        </label></td>
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
	cuenta=f.txtscgcuenta.value;
	if(cuenta!="")
	{
		if(parseFloat(monto)>0)
		{
			// Datos del formulario
			opener.document.formulario.cerrarasiento.value="0";
			monto=f.txtmonto.value;
			ls_cuenta=f.txtscgcuenta.value;
			ls_operacion=f.cmboperacion.value;
			sccuentaprov=f.sccuentaprov.value;
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
				tipbieordcom=eval("opener.document.formulario.txttipbieordcom"+j+".value");
				estint=eval("opener.document.formulario.txtestint"+j+".value");
				cuentaint=eval("opener.document.formulario.txtcuentaint"+j+".value");
				if(cargo=="0")
				{
					li_subtotal=eval(li_subtotal+"+"+ue_formato_calculo(spgmonto));
				}
				parametros=parametros+"&txtspgnrocomp"+j+"="+spgnrocomp+"&txtprogramatica"+j+"="+programatica+""+"&txtestcla"+j+"="+estcla+""+
						   "&txtspgcuenta"+j+"="+spgcuenta+"&txtspgmonto"+j+"="+spgmonto+""+
						   "&txtcodpro"+j+"="+codpro+"&txtcargo"+j+"="+cargo+""+"&txtoriginal"+j+"="+original+
						   "&txtspgsccuenta"+j+"="+spgsccuenta+"&txtspgprocededoc"+j+"="+procededoc+
						   "&txtcodfuefin"+j+"="+codfuefin+"&txtestint"+j+"="+estint+"&txtcuentaint"+j+"="+cuentaint+"&txttipbieordcom"+totrowspg+"="+tipbieordcom;
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
			li_i=li_i+1;
			if(ls_operacion=="D")
			{
				mondeb=monto;
				monhab=0;
				li_subtotal=eval(li_subtotal+"+"+ue_formato_calculo(monto));
			}
			else
			{
				mondeb=0;
				monhab=monto;
				/*if(ls_proceso!="CONTABLE")
				{*/
					li_subtotal=eval(li_subtotal+"-"+ue_formato_calculo(monto));
				//}
			}
			parametros=parametros+"&txtscgnrocomp"+li_i+"="+ls_numrecdoc+"&txtscgcuenta"+li_i+"="+ls_cuenta+""+
								  "&txtmondeb"+li_i+"="+mondeb+"&txtmonhab"+li_i+"="+monhab+"&txtdebhab"+li_i+"="+ls_operacion+
								  "&txtestatus"+li_i+"=M&txtscgprocededoc"+li_i+"=CXPRCD";
			totrowscg=li_i+1;
			parametros=parametros+"&totrowscg="+totrowscg+"";
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
		else
		{
			alert("El monto debe ser mayor a cero");
		}
	}
	else
	{
		alert("Debe seleccionar la Cuenta Contable");
	}
}

function ue_cuentasscg()
{
	f=document.formulario;
	pagina="sigesp_cxp_cat_cuentasscg.php";
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=610,height=400,resizable=yes,location=no");
	f.txtmonto.value="0,00";
}
</script>
</html>