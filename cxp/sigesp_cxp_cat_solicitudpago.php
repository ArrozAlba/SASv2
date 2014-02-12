<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 19/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codprovben,$ls_nomprovben,$ls_tipodestino,$ls_operacion,$io_fun_cxp;
		require_once("../shared/class_folder/class_generar_id_process_sol.php");
		$io_id_process= new class_generar_id_process_sol();
		
		$ls_codprovben="";
		$ls_nomprovben="";
		$ls_tipodestino="";
		$ls_operacion=$io_fun_cxp->uf_obteneroperacion();
   }
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	uf_limpiarvariables();
	$ls_tipo=$io_fun_cxp->uf_obtenertipo();
	unset($io_fun_cxp);
	$ld_fecdes="01/".date("m")."/".date("Y");
	$ld_fechas=date("d/m/Y");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Solicitudes de Pago </title>
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
</head>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="numsol">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<table width="530" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="526" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Solicitudes de Pago </td>
    </tr>
  </table>
  <br>
    <table width="520" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="135" height="22" align="right"><select name="cmbtipdes" id="cmbtipdes" onChange="javascript: ue_cambiardestino();">
            <option value="-" selected>---seleccione---</option>
            <option value="P" <?php if($ls_tipodestino=="P"){ print "selected";} ?>>PROVEEDOR</option>
            <option value="B" <?php if($ls_tipodestino=="B"){ print "selected";} ?>>BENEFICIARIO</option>
          </select>        </td>
        <td height="22" colspan="2" align="left"><div align="left">
            <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codprovben;?>" size="15" maxlength="10" readonly>
            <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" value="<?php print $ls_nomprovben;?>" size="45" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="21"><div align="right">N&uacute;mero de Solicitud </div></td>
        <td width="217" height="21"><input name="txtnumsol" type="text" id="txtnumsol" onKeyPress="javascript: ue_mostrar(this,event);"></td>
        <td width="160" rowspan="3"><table width="145" border="0" align="right" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="22" colspan="2"><div align="center">Fecha de Emisi&oacute;n </div></td>
          </tr>
          <tr>
            <td width="58" height="22"><div align="right">Desde</div></td>
            <td width="85"><input name="txtfecdes" type="text" id="txtfecdes" size="15" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"  datepicker="true" value="<?php print $ld_fecdes;?>"></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Hasta</div></td>
            <td><input name="txtfechas" type="text" id="txtfechas" size="15" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"  datepicker="true" value="<?php print $ld_fechas;?>"></td>
          </tr>
        </table>
        <div align="right"></div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Concepto</div></td>
        <td height="22"><input name="txtconsol" type="text" id="txtconsol" size="35"></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22">&nbsp;</td>
      </tr>
	  <tr>
        <td colspan="3"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
	  </tr>
	</table> 
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>      
</body>
<script language="JavaScript">
function ue_cambiardestino()
{
	f=document.formulario;
	// Se verifica si el destino es un proveedor ó beneficiario y se carga el catalogo
	// dependiendo de esa información
	f.txtcodigo.value="";
	f.txtnombre.value="";
	tipdes=ue_validarvacio(f.cmbtipdes.value);
	if(tipdes!="-")
	{
		tipo="SOLICITUDPAGO";
		if(tipdes=="P")
		{
			window.open("sigesp_cxp_cat_proveedor.php?tipo="+tipo+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,location=no,resizable=no");
		}
		else
		{
			window.open("sigesp_cxp_cat_beneficiario.php?tipo="+tipo+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,location=no,resizable=no");
		}	
	}
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	numsol=f.txtnumsol.value;
	fecemides=ue_validarvacio(f.txtfecdes.value);
	fecemihas=ue_validarvacio(f.txtfechas.value);
	tipdes=ue_validarvacio(f.cmbtipdes.value);
	codproben=ue_validarvacio(f.txtcodigo.value);
	tipo=f.tipo.value;
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	if((fecemides!="")&&(fecemihas!=""))
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
		ajax.send("catalogo=SOLICITUDPAGO&numsol="+numsol+"&fecemides="+fecemides+"&fecemihas="+fecemihas+
				  "&tipdes="+tipdes+"&codproben="+codproben+"&tipo="+tipo+"&orden="+orden+"&campoorden="+campoorden);
	}
	else
	{
		alert("Debe seleccionar un rango de Fecha.");
	}
}

function ue_aceptar(ls_numsol,ls_codfuefin,ls_denfuefin,ls_codigo,ls_nombre,li_monsol,ls_estprosol,ls_estaprosol,ld_fecemisol,
					ls_estatus,ls_tipodestino,li_i,ls_rifproben,ls_numordpagmin,ls_codtipfon)
{
	opener.document.formulario.txtnumsol.value=ls_numsol;
	opener.document.formulario.txtcodfuefin.value=ls_codfuefin;
	opener.document.formulario.txtdenfuefin.value=ls_denfuefin;
	opener.document.formulario.txtcodigo.value=ls_codigo;
	opener.document.formulario.txtnombre.value=ls_nombre;
	opener.document.formulario.cmbtipdes.value=ls_tipodestino;
	opener.document.formulario.cmbtipdes.disabled=true;
	opener.document.formulario.txttipdes.value=ls_tipodestino;
	f=document.formulario;
	opener.document.formulario.txtconsol.value=eval("f.txtconsol"+li_i+".value;");
	opener.document.formulario.txtobssol.value=eval("f.txtobssol"+li_i+".value;");
	opener.document.formulario.estapr.value=ls_estaprosol;
	opener.document.formulario.txtrifproben.value=ls_rifproben;
	opener.document.formulario.txtestatus.value=ls_estatus;
	opener.document.formulario.txtfecemisol.value=ld_fecemisol;
	opener.document.formulario.txtfecemisol.disabled=true;
	opener.document.formulario.txtfecha.value=ld_fecemisol;
	opener.document.formulario.txtnumordpagmin.value=ls_numordpagmin;
	opener.document.formulario.txtcodtipfon.value=ls_codtipfon;
	opener.document.formulario.existe.value="TRUE";
	parametros="";
	parametros=parametros+"&numsol="+ls_numsol+"&total="+li_monsol;
	proceso="LOADRECEPCIONES";
	if(parametros!="")
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("recepciones");
		divlocal = document.getElementById("resultados");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_cxp_c_solicitudpago_ajax.php",true);
 		ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divlocal.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
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
	ajax.send("proceso="+proceso+""+parametros);
	}
}
function aceptarrepdes(ls_numsol)
{
	opener.document.formulario.txtnumsoldes.value=ls_numsol;
	close();
}
function aceptarrephas(ls_numsol)
{
	opener.document.formulario.txtnumsolhas.value=ls_numsol;
	close();
}

function aceptarncnd(ls_numord,ls_tipproben,ls_codpro,ls_cedbene,ls_nomproben,ls_cuenta,ls_denscg,as_rifproben)
{
	f=opener.document.formulario;
	f.txtnumord.value=ls_numord;
	if(ls_tipproben=='P')
	{
		f.tipproben[0].checked=true;
		f.txtcodproben.value=ls_codpro;	
	}
	else
	{
		f.tipproben[1].checked=true;
		f.txtcodproben.value=ls_cedbene;	
	}
	f.txtrifproben.value=as_rifproben;
	f.txtcuentaprov.value=ls_cuenta;
	f.txtdenctascg.value =ls_denscg;
	f.txtnumrecdoc.value="";
	f.txtnomproben.value=ls_nomproben;
	f.txttipdoc.value   ="";
	f.txtdentipdoc.value="";
	f.txtconnota.value  ="";
	opener.uf_cargar_dtrecepcion_blanco('','','','','');
	//close();	
}

function aceptarmodcmpret(ls_numsol)
{
	li_indice=opener.document.formulario.txtindice.value;
	eval("opener.document.formulario.txtnumsop"+li_indice+".value='"+ls_numsol+"'");
	close();
}
</script>
</html>