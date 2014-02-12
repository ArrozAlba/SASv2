<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_folder/sigesp_sep_c_solicitud.php");
	$io_sep=new sigesp_sep_c_solicitud("../");	
	require_once("class_folder/class_funciones_sep.php");
	$io_fun_sep=new class_funciones_sep();
	$ls_tipo=$io_fun_sep->uf_obtenertipo();
	$ld_fecdes="01/".date("m")."/".date("Y");
	$ld_fechas=date("d/m/Y");
    $ls_coduniadm = $io_fun_sep->uf_obtenervalor("txtcoduniadm","");
	$ls_denuniadm = $io_fun_sep->uf_obtenervalor("txtdenuniadm","");
	$ls_tipodestino = $io_fun_sep->uf_obtenervalor("cmbtipdes","-");
	$ls_codprovben = $io_fun_sep->uf_obtenervalor("txtcodigo","");
	$ls_nomprovben = $io_fun_sep->uf_obtenervalor("txtnombre","");
    unset($io_fun_sep);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Solicitudes de Ejecuci&oacute;n Presupuestaria</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sep.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="numsol">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<table width="630" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="600" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Solicitudes de Ejecuci&oacute;n Presupuestaria</td>
    </tr>
  </table>
  <br>
    <table width="630" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="92" height="22"><div align="right">N&uacute;mero</div></td>
        <td width="365" height="22"><div align="left">
          <input name="txtnumsol" type="text" id="txtnumsol" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
        <td width="165" rowspan="3"><table width="159" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="22" colspan="2"><div align="center">Fecha de Registro </div></td>
          </tr>
          <tr>
            <td width="58" height="22"><div align="right">Desde</div></td>
            <td width="99"><input name="txtfecdes" type="text" id="txtfecdes" size="15" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);"  datepicker="true" value="<?php print $ld_fecdes;?>"></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Hasta</div></td>
            <td><input name="txtfechas" type="text" id="txtfechas" size="15" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);"  datepicker="true" value="<?php print $ld_fechas;?>"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="29"><div align="right">Tipo </div></td>
        <td height="29"><div align="left">
            <?php $io_sep->uf_load_tiposolicitud(""); ?>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Unidad Ejecutora </div></td>
        <td height="22"><label></label>
        <div align="left">
          <input name="txtcoduniadm" type="text" id="nombre" onKeyPress="javascript: ue_mostrar(this,event);" value="<?php print $ls_coduniadm;?>" size="15">
          <a href="javascript: ue_catalogo_unidad_ejecutora();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0" /></a>
          <label>
          <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" value="<?php print $ls_denuniadm;?>" size="40" readonly="readonly" />
          </label>
        </div></td>
      </tr>
	  <tr>
	    <td><div align="right">Concepto</div></td>
	    <td colspan="2"><label>
	      <input type="text" name="txtconsol" id="txtconsol" size="90">
	    </label></td>
      </tr>
	  <tr>
	    <td><div align="right">Art&iacute;culo</div></td>
	    <td colspan="2"><label>
	      <input type="text" name="txtdenart" id="txtdenart" size="20">
	    </label></td>
      </tr>
	  <tr>
	    <td><div align="right">Destino</div></td>
        <td colspan="2"><select name="cmbtipdes" id="cmbtipdes" onChange="javascript: ue_cambiardestino();">
          <option value="-" selected>-- Seleccione Uno --</option>
          <option value="P" <?php if($ls_tipodestino=="P"){ print "selected";} ?>>PROVEEDOR</option>
          <option value="B" <?php if($ls_tipodestino=="B"){ print "selected";} ?>>BENEFICIARIO</option>
        </select>
        <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codprovben;?>" size="15" maxlength="10" readonly>
        <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" value="<?php print $ls_nomprovben;?>" size="50" maxlength="30" readonly></td>
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

function ue_aceptar(ls_numsol,ls_codtipsol,ls_coduniadm,ls_codfuefin,ls_estsol,ls_tipo_destino,ls_codigo,ls_denuniadm,
					ls_denfuefin,ls_nombre,ls_estapro,ld_fecregsol,li_monto,li_monbasinm,li_montotcar,ls_estatus,ls_estope,
					ls_modsep,ls_codestpro1,ls_codestpro2,ls_codestpro3,ls_codestpro4,ls_codestpro5,ls_estcla,as_consol,
					ls_nombenalt,ls_estayueco,as_tipsepbie,as_rif,contador)
{
	f=document.formulario;
	opener.document.formulario.txtnumsol.value=ls_numsol;
	opener.document.formulario.txproben.value=as_rif;
    opener.document.formulario.txtnumsol.readOnly=true;
	opener.document.formulario.radiotipbie[0].disabled = false;
	opener.document.formulario.radiotipbie[1].disabled = false;
	if (as_tipsepbie=='M')
	   {
	     opener.document.formulario.radiotipbie[0].checked  = true;
		 opener.document.formulario.radiotipbie[1].checked  = false;
		 opener.document.formulario.radiotipbie[0].disabled = true;
		 opener.document.formulario.radiotipbie[1].disabled = true;			  

	   }
	else
	   {
		 if (as_tipsepbie=='A')
		    {
              opener.document.formulario.radiotipbie[0].checked  = false;
			  opener.document.formulario.radiotipbie[1].checked  = true;
			  opener.document.formulario.radiotipbie[0].disabled = true;
		      opener.document.formulario.radiotipbie[1].disabled = true;
		    }
		 else
		    {
			  opener.document.formulario.radiotipbie[0].checked  = false;
			  opener.document.formulario.radiotipbie[1].checked  = false;
			  opener.document.formulario.radiotipbie[0].disabled = true;
			  opener.document.formulario.radiotipbie[1].disabled = true;			  
		    }	     
	   }
	opener.document.formulario.cmbcodtipsol.value=ls_codtipsol+"-"+ls_modsep+"-"+ls_estope+"-"+ls_estayueco;
	opener.document.formulario.cmbcodtipsol.disabled=true;
	opener.document.formulario.txttipsol.value=ls_codtipsol+"-"+ls_modsep+"-"+ls_estope+"-"+ls_estayueco;
	opener.document.formulario.txtfecregsol.value=ld_fecregsol;
	opener.document.formulario.txtfecregsol.disabled=true;
	opener.document.formulario.txtfecha.value=ld_fecregsol;
	opener.document.formulario.txtcoduniadm.value=ls_coduniadm;
	opener.document.formulario.txtcodfuefin.value=ls_codfuefin;
	opener.document.formulario.txtestsol.value=ls_estsol;
	concepto=eval("f.hidconsol"+contador+".value");
	opener.document.formulario.txtconsol.value=concepto;
	opener.document.formulario.cmbtipdes.value=ls_tipo_destino;
	opener.document.formulario.txtcodigo.value=ls_codigo;
	opener.document.formulario.txtdenuniadm.value=ls_denuniadm;
	opener.document.formulario.txtdenfuefin.value=ls_denfuefin;
	opener.document.formulario.txtnombre.value=ls_nombre;
	opener.document.formulario.txtestapro.value=ls_estapro;
	opener.document.formulario.txtestatus.value=ls_estatus;
	opener.document.formulario.txtcodestpro1.value=ls_codestpro1;
	opener.document.formulario.txtcodestpro2.value=ls_codestpro2;
	opener.document.formulario.txtcodestpro3.value=ls_codestpro3;
	opener.document.formulario.txtcodestpro4.value=ls_codestpro4;
	opener.document.formulario.txtcodestpro5.value=ls_codestpro5;
	opener.document.formulario.txtnombenalt.value=ls_nombenalt;
	opener.document.formulario.crearasiento.value=0;
	if(ls_estayueco=="A")
	{
	 opener.document.formulario.txtnombenalt.disabled=false;
    }
	li_estmodest = "<?php print $_SESSION["la_empresa"]["estmodest"] ?>";
	opener.document.formulario.txtestcla.value=ls_estcla;
	opener.document.formulario.existe.value="TRUE";
	parametros="";
	parametros=parametros+"&numsol="+ls_numsol;
	parametros=parametros+"&subtotal="+li_monbasinm+"&cargos="+li_montotcar+"&total="+li_monto;
	if(ls_modsep=="B") // Bienes
	{
		proceso="LOADBIENES";
	}
	if(ls_modsep=="S") // Servicios
	{
		proceso="LOADSERVICIOS";
	}
	if(ls_modsep=="O") // Conceptos
	{
		proceso="LOADCONCEPTOS";
	}
	if(parametros!="")
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("bienesservicios");
		divlocal = document.getElementById("resultados");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_sep_c_solicitud_ajax.php",true);
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

function ue_aceptar_reportedesde(ls_numsol)
{
	opener.document.formulario.txtnumsoldes.value=ls_numsol;
	close();
}

function ue_catalogo_unidad_ejecutora()
{
   window.open('sigesp_sep_cat_unidad_ejecutora.php?tipo=ESTANDAR',"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

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
          window.open("sigesp_sep_cat_proveedor.php","_blank","menubar=no,toolbar=no,scrollbars=no,width=600,height=400,resizable=yes,location=no,left=50,top=50");
		}
		else
		{
            window.open("sigesp_sep_cat_beneficiario.php","_blank","menubar=no,toolbar=no,scrollbars=no,width=600,height=400,resizable=yes,location=no,left=50,top=50");
		}	
	}
}

function ue_aceptar_reportehasta(ls_numsol)
{
	opener.document.formulario.txtnumsolhas.value=ls_numsol;
	close();
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	numsol=f.txtnumsol.value;
	coduniadm=f.txtcoduniadm.value;
	codtipsol=f.cmbcodtipsol.value;
	codigo=f.txtcodigo.value;
	tipdes=f.cmbtipdes.value;
	fecregdes=ue_validarvacio(f.txtfecdes.value);
	fecreghas=ue_validarvacio(f.txtfechas.value);
	tipo=f.tipo.value;
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	consol=f.txtconsol.value;
	denart=f.txtdenart.value;
	if((fecregdes!="")&&(fecreghas!=""))
	{
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
		ajax.send("catalogo=SOLICITUD&numsol="+numsol+"&coduniadm="+coduniadm+"&codtipsol="+codtipsol+"&fecregdes="+fecregdes+
				  "&fecreghas="+fecreghas+"&tipo="+tipo+"&orden="+orden+"&campoorden="+campoorden+"&codigo="+codigo+
				  "&tipdes="+tipdes+"&consol="+consol+"&denart="+denart);
	}
	else
	{
		alert("Debe seleccionar un rango de Fecha.");
	}
}
</script>
</html>