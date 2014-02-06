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

	$ls_tipodestino="";
	$ls_codprovben="";
	$ls_nomprovben="";
	$ls_tipo=$io_fun_cxp->uf_obtenertipo();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Notas de Debito/Notas de Credito </title>
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<body >
<form name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="numdc">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<table width="577" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="577" height="20" colspan="2" class="titulo-ventana"><div align="center">Cat&aacute;logo de Notas de Debito/Notas de Credito </div></td>
    </tr>
  </table>
  <table width="577" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="83" height="22"><div align="right">Nro.Nota </div></td>
      <td height="22" colspan="3"><div align="left">
          <input name="txtnumnota" type="text" id="txtnumnota" onKeyPress="javascript: ue_mostrar(this,event);">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Denominaci&oacute;n</div></td>
      <td height="22" colspan="3"><input name="txtdennota" type="text" id="nombre" onKeyPress="javascript: ue_mostrar(this,event);" size="90">
      </td>
    </tr>
    <tr>
      <td height="22"><div align="right">Destino</div></td>
      <td height="22" colspan="3"><div align="left">
          <select name="cmbtipdes" id="cmbtipdes" onChange="javascript: ue_cambiardestino();">
            <option value="-" selected>-- Seleccione Uno --</option>
            <option value="P" <?php if($ls_tipodestino=="P"){ print "selected";} ?>>PROVEEDOR</option>
            <option value="B" <?php if($ls_tipodestino=="B"){ print "selected";} ?>>BENEFICIARIO</option>
          </select>
          <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codprovben;?>" size="12" maxlength="10" readonly>
          <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" value="<?php print $ls_nomprovben;?>" size="50" maxlength="30" readonly>
      </div></td>
    </tr>
    <tr>
      <td><div align="right">Fecha Desde </div></td>
      <td width="156"><div align="left">
          <input type="text" name="txtfecdesde" id="txtfecdesde" datepicker="true" size="18" value="<?php print date("d/m/Y");?>" style="text-align:center" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);">
      </div></td>
      <td width="83"><div align="right">Fecha Hasta </div></td>
      <td width="239"><div align="left">
          <input type="text" name="txtfechasta" id="txtfechasta" datepicker="true" size="18" value="<?php print date("d/m/Y");?>" style="text-align:center" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);">
      </div></td>
    </tr>
    <tr>
      <td colspan="4"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"> Buscar</a><a href="javascript: ue_close();"> <img src="../shared/imagebank/eliminar.gif" width="15" height="15" class="sin-borde">Cerrar</a></div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>
  <div id="resultados" align="center"></div>	
	</p>
  <p><br>    
  </p>
</form>  
</body>
<script language="JavaScript">
var ajax=false;
function aceptar(ls_codemp,ls_numncnd,ld_fecha,ls_numord,ls_numrecdoc,ls_codtipdoc,ls_dentipdoc,ls_tipo,ls_codpro,ls_cedbene,
                 ls_nomproben,li_estcon,li_estpre,ls_codope,ls_ctaprov,ls_dencuentaprov,ls_desope,ls_estnota,li_estapro,as_rifproben)
{
	f=opener.document.formulario;
	f.txtnumord.value=ls_numord;
	f.txtnumncnd.value=ls_numncnd;
	f.txtnumncnd.readOnly=true;
	f.txtfecregsol.value=ld_fecha;
	f.txtnumrecdoc.value=ls_numrecdoc;
	f.txttipdoc.value=ls_codtipdoc;
	f.txtdentipdoc.value=ls_dentipdoc;
	f.txtestcontipdoc.value=li_estcon;	
	f.txtestpretipdoc.value=li_estpre;
	f.txtcuentaprov.value=ls_ctaprov;
	f.txtdenctascg.value=ls_dencuentaprov;
	f.txtrifproben.value=as_rifproben;
	if (ls_tipo=='P')
	   {
		 f.txtcodproben.value=ls_codpro;
		 f.tipproben[0].checked=true;
		 ls_codproben=ls_codpro;
	   }
	else
	   {
		 f.tipproben[1].checked=true;
		 f.txtcodproben.value=ls_cedbene;
		 ls_codproben=ls_cedbene;
	   }
	if(ls_codope=='NC')
	{
		f.tiponota[0].checked=true;
	}
	else
	{
		f.tiponota[1].checked=true;
	}
	f.existe.value='TRUE';
	f.txtnomproben.value=ls_nomproben;
	f.txtconnota.value=ls_desope;
	f.txtestsol.value=ls_estnota;
	if(ls_estnota=='R')
	{
		f.txtestatus.value='REGISTRO';
	}
	else if(ls_estnota=='C')
	{
		f.txtestatus.value='CONTABILIZADA';
	}
	else
	{
		f.txtestatus.value='EMITIDA';
	}
	f.txtestapro.value=li_estapro;
	uf_cargar_dtrecepcion(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_tipo,ls_codproben,ls_numncnd,ls_numord,ld_fecha,ls_codope);

}

function aceptarrepdes(ls_numndnc)
{
	opener.document.formulario.txtnumdcdes.value=ls_numndnc;
	close();
}
function aceptarrephas(ls_numndnc)
{
	opener.document.formulario.txtnumdchas.value=ls_numndnc;
	close();
}

function uf_cargar_dtrecepcion(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_tipproben,ls_codproben,ls_numncnd,ls_numord,ld_fecha,ls_codope)
{
	f=opener.document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	divgrid = opener.document.getElementById('detallesrecepcion');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
					uf_cargar_dtnota(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_tipproben,ls_codproben,ls_numncnd,ls_numord,ld_fecha,ls_codope);
					//close();
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
	ajax.send("funcion=DTRECEPCION&codemp="+ls_codemp+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben);
	
}

function uf_cargar_dtnota(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_tipproben,ls_codproben,ls_numncnd,ls_numord,ld_fecha,ls_codope)
{
	f=opener.document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	divgrid = opener.document.getElementById('detallesnota');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
					//close();
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
	ls_campos="&codemp="+ls_codemp+"&numrecdoc="+ls_numrecdoc+"&numncnd="+ls_numncnd+"&numord="+ls_numord+"&fecha="+ld_fecha+"&codtipdoc="+ls_codtipdoc+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben+"&tiponota="+ls_codope;
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("funcion=CARGARDTNOTA"+ls_campos);
	
}

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	ls_numnota=f.txtnumnota.value;
	ls_dennota=f.txtdennota.value;
	ls_tipproben=f.cmbtipdes.value;
	ls_codproben=f.txtcodigo.value;
	ld_fecdesde =f.txtfecdesde.value;
	ld_fechasta =f.txtfechasta.value;	
	tipo=f.tipo.value;
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
			divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
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
	ajax.send("catalogo=NOTAS&numncnd="+ls_numnota+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben+"&dennota="+ls_dennota+"&fecdesde="+ld_fecdesde+"&fechasta="+ld_fechasta+
			  "&orden="+orden+"&campoorden="+campoorden+"&tipo="+tipo);
}

function ue_close()
{
	close();
}

</script>
</html>