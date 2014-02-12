<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_folder/sigesp_scf_c_comprobante.php");
	$io_scf=new sigesp_scf_c_comprobante("../");	
	require_once("class_folder/class_funciones_scf.php");
	$io_fun_scf=new class_funciones_scf("../");
	$ls_tipo=$io_fun_scf->uf_obtenertipo();
	$ls_procede=$io_fun_scf->uf_obtenervalor_get("procede","");
	unset($io_fun_scf);
	$ld_fecdes="01/".date("m")."/".date("Y");
	$ld_fechas=date("d/m/Y");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Comprobantes Contables</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scf.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<body>
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="comprobante">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<table width="600" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="600" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Comprobantes Contables </td>
    </tr>
  </table>
  <br>
    <table width="600" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="136" height="22"><div align="right">Comprobante</div></td>
        <td width="262" height="22"><div align="left">
          <input name="txtcomprobante" type="text" id="txtcomprobante" onKeyPress="javascript: ue_mostrar(this,event);">        
        </div></td>
        <td width="194" rowspan="3"><table width="159" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="22" colspan="2"><div align="center">Fecha de Comprobante </div></td>
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
        <td height="22"><div align="right">Procede </div></td>
        <td height="22"><div align="left">
            <?php $io_scf->uf_load_procedencias($ls_procede); ?>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Proveedor/Beneficiario</div></td>
        <td height="22"><select name="cmbtipdes" id="cmbtipdes" onChange="javascript: ue_cambiardestino();">
          <option value="" selected>-- Seleccione Uno --</option>
          <option value="P">PROVEEDOR</option>
          <option value="B">BENEFICIARIO</option>
        </select>
        <input name="txtcodigo" type="text" id="txtcodigo" value="" size="15" maxlength="10" readonly></td>
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
	tipdes=ue_validarvacio(f.cmbtipdes.value);
	if(tipdes!="-")
	{
		if(tipdes=="P")
		{
			window.open("sigesp_scf_cat_proveedor.php?tipo=catcomp","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
		}
		else
		{
			window.open("sigesp_scf_cat_beneficiario.php?tipo=catcomp","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
		}	
	}
}

function ue_aceptar(ls_comprobante,ls_procede,ld_fecha,ls_tipdes,ls_codigo,ls_nombre,ls_codban,ls_ctaban,li_i,ls_codpro,ls_cedbene)
{
	opener.document.formulario.txtcomprobante.value=ls_comprobante;
	opener.document.formulario.txtcomprobante.readOnly=true;
	opener.document.formulario.txtprocede.value=ls_procede;
	opener.document.formulario.txtprocede.readOnly=true;
	opener.document.formulario.txtfecha.value=ld_fecha;
	opener.document.formulario.txtfecha.disabled=true;
	opener.document.formulario.txtfechacon.value=ld_fecha;	
	opener.document.formulario.cmbtipdes.value=ls_tipdes;
	opener.document.formulario.txtcodigo.value=ls_codigo;
	opener.document.formulario.txtnombre.value=ls_nombre;
	f=document.formulario;
	opener.document.formulario.txtdescripcion.value=eval("f.txtdescripcion"+li_i+".value");
	opener.document.formulario.existe.value="TRUE";
	parametros="";
	parametros=parametros+"&comprobante="+ls_comprobante+"&procede="+ls_procede+"&fecha="+ld_fecha+"&tipdes="+ls_tipdes;
	parametros=parametros+"&codpro="+ls_codpro+"&cedbene="+ls_cedbene+"&codban="+ls_codban+"&ctaban="+ls_ctaban;
	if(parametros!="")
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("cuentas");
		divlocal = document.getElementById("resultados");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_scf_c_comprobante_ajax.php",true);
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
		ajax.send("proceso=LOADCOMPROBANTE"+parametros);
	}
}

function ue_aceptarrepdes(ls_comprobante)
{
	opener.document.formulario.txtcomprobantedesde.value=ls_comprobante;
	opener.document.formulario.txtcomprobantehasta.value="";
	close();
}

function ue_aceptarrephas(ls_comprobante)
{
	if(opener.document.formulario.txtcomprobantedesde.value<=ls_comprobante)
	{
		opener.document.formulario.txtcomprobantehasta.value=ls_comprobante;
		close();
	}
	else
	{
		alert("Rango de Comprobantes Inválido.");
	}
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	comprobante=f.txtcomprobante.value;
	procede=f.cmbprocede.value;
	tipdes=f.cmbtipdes.value;
	codigo=f.txtcodigo.value;
	fecdes=ue_validarvacio(f.txtfecdes.value);
	fechas=ue_validarvacio(f.txtfechas.value);
	tipo=f.tipo.value;
	orden=f.orden.value;
	campoorden=f.campoorden.value;
	if((fecdes!="")&&(fechas!=""))
	{
		// Div donde se van a cargar los resultados
		divgrid = document.getElementById('resultados');
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_scf_c_catalogo_ajax.php",true);
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
		ajax.send("catalogo=COMPROBANTE&comprobante="+comprobante+"&procede="+procede+"&tipdes="+tipdes+"&codigo="+codigo+
				  "&fecdes="+fecdes+"&fechas="+fechas+"&tipo="+tipo+"&orden="+orden+"&campoorden="+campoorden);
	}
	else
	{
		alert("Debe seleccionar un rango de Fecha.");
	}
}
</script>
</html>