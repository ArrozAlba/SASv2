<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cat&aacute;logo de Solicitudes de Ejecuci&oacute;n Presupuestaria</title>
<link href="../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>

<body>
<?php
	require_once("class_folder/class_funciones_soc.php");
	$io_fun_soc=new class_funciones_soc();
	$ls_tipo=$io_fun_soc->uf_obtenertipo();
    if (array_key_exists("hidoperacion",$_POST))
    {
      $ls_numsol    = $_POST["txtnumsol"];
	  $ls_operacion = $_POST["hidoperacion"];
      $ls_codprov   = $_POST["txtcodprov"];
	  $ls_nomprov   = $_POST["txtnomprov"];
	  $ld_fecdes    = $_POST["txtfecdes"];
	  $ld_fechas    = $_POST["txtfechas"];
      $ls_codunieje = $_POST["txtcodunieje"];
	  $ls_denunieje = $_POST["txtdenunieje"];
	  $ls_tipord    = $_POST["tipord"];
    }
    else
    {
      $ls_numsol    = ""; 
	  $ls_operacion = "";
      $ls_codprov   = "";
	  $ls_nomprov   = "";
	  $ld_fecdes    = "01/".date("m")."/".date("Y");
	  $ld_fechas    = date("d/m/Y");
      $ls_codunieje = "";
	  $ls_denunieje = "";
	  $ls_tipord    = $_GET["tipord"];
    }
    unset($io_fun_soc);
?>
<form id="formulario" name="formulario" method="post" action="">
  <input name="campoorden" type="hidden" id="campoorden" value="numsol" />
  <input name="orden" type="hidden" id="orden" value="ASC" />
  <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
  <input name="tipord" type="hidden" id="tipord" value="<?php print $ls_tipord; ?>">
  <table width="600" height="149" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-celda">
      <td height="22" colspan="4">Cat&aacute;logo de Solicitudes de Ejecuci&oacute;n Presupuestaria 
      <input name="hidoperacion" type="hidden" id="hidoperacion" value="<?php print $ls_operacion ?>" /></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Nro Solicitud</td>
      <td height="22"><label>
        <input name="txtnumsol" type="text" id="txtnumsol" style="text-align:center" value="<?php print $ls_numsol ?>" size="20" maxlength="15" />
      </label></td>
      <td height="22"><div align="right">Fecha</div></td>
      <td height="22">Desde 
        <input name="txtfecdes" type="text" id="txtfecdes"  value="<?php print $ld_fecdes ?>" size="13" maxlength="10" datepicker="true" onkeypress="currencyDate(this);" style="text-align:left">
        &nbsp;&nbsp; 
        Hasta
<input name="txtfechas" type="text" id="txtfechas" value="<?php print $ld_fechas ?>" size="13" maxlength="10" datepicker="true" onkeypress="currencyDate(this);" style="text-align:left"></td>
    </tr>
    <tr>
      <td width="104" height="22" style="text-align:right">Tipo Destino</td>
      <td width="146" height="22"><label>
        <select name="cmbtipdes" id="cmbtipdes" onchange="javascript:uf_cambiar();" style="width:120px">
          <option value="-">---seleccione---</option>
          <option value="P">Proveedor</option>
        </select>
      </label></td>
      <td width="57" height="22">&nbsp;</td>
      <td width="271" height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">C&oacute;digo</td>
      <td height="22" colspan="3"><label>
        <input name="txtcodprov" type="text" id="txtcodprov" style="text-align:center" value="<?php print $ls_codprov ?>" size="20" maxlength="10" readonly/>
        <img src="../shared/imagebank/tools15/buscar.gif" name="buscar" width="15" height="15" id="buscar" style="visibility:hidden" onclick="javascript:uf_catalogo_proveedores();" /></label>        <label>
        <input name="txtnomprov" type="text" class="sin-borde" id="txtnomprov" value="<?php print $ls_nomprov ?>" size="60" readonly/>
        </label></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Unidad Ejecutora </td>
      <td height="22" colspan="3"><input name="txtcodunieje" type="text" id="txtcodunieje" onkeypress="javascript: ue_mostrar(this,event);" value="<?php print $ls_codunieje ;?>" size="20" readonly="readonly" style="text-align:center" />
      <a href="javascript: ue_catalogo_unidad_ejecutora();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0" /></a>      <input name="txtdenunieje" type="text" class="sin-borde" id="txtdenunieje" value="<?php print $ls_denunieje ?>" size="60" readonly="readonly" /></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" onclick="ue_search()" />Buscar Solicitud</a></div></td>
    </tr>
  </table>
	<p>
  <div id="resultados" align="center"></div>	
	</p>
</form>
</body>
<script language="javascript">
f   = document.formulario;

function uf_cambiar()
{
  ls_tipdes = f.cmbtipdes.value;//Si la solicitud a buscar Proviene de un proveedor o beneficiario.
  if (ls_tipdes=='-')
     {
	   eval("document.images['buscar'].style.visibility='hidden'");
	   f.txtcodproben.value = "";
	   f.txtcodproben.readOnly= true;
	 }
  else
     {
	   eval("document.images['buscar'].style.visibility='visible'");
	 }
}

function ue_catalogo_unidad_ejecutora()
{
   window.open('sigesp_cat_unidad_ejecutora.php?tipo=ESTANDAR',"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes,dependent=yes");
}

function uf_catalogo_proveedores()
{
   ls_tipdes = f.cmbtipdes.value;
   if (ls_tipdes=='P')
   {  
     window.open("sigesp_soc_cat_proveedor.php","_blank","menubar=no,toolbar=no,scrollbars=no,width=600,height=400,resizable=yes,location=no,left=50,top=50");
   }
}

function ue_aceptar(as_numsol,as_codtipsol,as_codunieje,as_codfuefin,as_estsoa,as_consol,as_tipo_destino,as_codpro,as_denunieje,
					as_denfuefin,as_nompro,as_estapro,ad_fecregsoa,ai_monto,ai_monbasinm,ai_montotcar,as_estatus,as_estope,
					as_modsep,as_codestpro1,as_codestpro2,as_codestpro3,as_codestpro4,as_codestpro5,as_estcla)
{
    fop=opener.document.formulario;
	hoy = new Date();
	dia=hoy.getDate();
	if (dia<=9)
	{
	  dia='0'+dia;
	}
	mes=hoy.getMonth()+1;
	if (mes<=9)
	{
	  mes='0'+mes;
	}
    ano=hoy.getFullYear();
	fechadesde=dia+"/"+mes+"/"+ano;
	fechahasta=dia+"/"+mes+"/"+ano;
	opener.document.formulario.txtperentdesde.value=fechadesde; 
    opener.document.formulario.txtperenthasta.value=fechahasta;
	opener.document.formulario.txtestatus.value="REGISTRO";
	ls_consep = opener.document.formulario.txtconordcom.value;
	if (ls_consep=="")
	   {
		 opener.document.formulario.txtconordcom.value = as_consol;
	   }
	else
	   {
		 opener.document.formulario.txtconordcom.value = ls_consep+"; "+as_consol;
	   }
	opener.document.formulario.txttipsol.value='SEP';
	opener.document.formulario.txtnumordcom.readOnly=true;
	ls_codunieje = opener.document.formulario.txtcodunieje.value;
    if (ls_codunieje=="")
	   {
	     opener.document.formulario.txtcodunieje.value  = as_codunieje;
		 opener.document.formulario.txtdenunieje.value  = as_denunieje;
	 	 opener.document.formulario.txtcodestpro1.value = as_codestpro1;
		 opener.document.formulario.txtcodestpro2.value = as_codestpro2;
		 opener.document.formulario.txtcodestpro3.value = as_codestpro3;
		 opener.document.formulario.txtcodestpro4.value = as_codestpro4;
		 opener.document.formulario.txtcodestpro5.value = as_codestpro5;
		 opener.document.formulario.hidestcla.value     = as_estcla;
	   }	
	else
	   { 
	     if (ls_codunieje!=as_codunieje)
		    {
		      fop.txtcodunieje.value  = '----------';
		      fop.txtdenunieje.value  = 'NINGUNA';
		      fop.txtcodestpro1.value = '-------------------------';
		      fop.txtcodestpro2.value = '-------------------------';
		      fop.txtcodestpro3.value = '-------------------------';
		      fop.txtcodestpro4.value = '-------------------------';
		      fop.txtcodestpro5.value = '-------------------------';
		      fop.hidestcla.value     = '-';
		    }
         else
		    { 
		      ls_codestpro1 = opener.document.formulario.txtcodestpro1.value;
			  ls_codestpro2 = opener.document.formulario.txtcodestpro2.value;
			  ls_codestpro3 = opener.document.formulario.txtcodestpro3.value;
			  ls_codestpro4 = opener.document.formulario.txtcodestpro4.value;
			  ls_codestpro5 = opener.document.formulario.txtcodestpro5.value;
			  ls_estcla     = opener.document.formulario.hidestcla.value;
			  if ((as_codestpro1!=ls_codestpro1 || as_codestpro2!=ls_codestpro2 || as_codestpro3!=ls_codestpro3 ||
			       as_codestpro4!=ls_codestpro4 || as_codestpro5!=ls_codestpro5 || as_estcla!=ls_estcla) && (ls_codunieje!='' && ls_codestpro1!='' 
			       && ls_codestpro2!='' && ls_codestpro3!='' && ls_codestpro4!='' && ls_codestpro5!=''))
			     {
				   fop.txtcodestpro1.value = '-------------------------';
				   fop.txtcodestpro2.value = '-------------------------';
				   fop.txtcodestpro3.value = '-------------------------';
				   fop.txtcodestpro4.value = '-------------------------';
				   fop.txtcodestpro5.value = '-------------------------';
				   fop.hidestcla.value     = '-';
			     }
			}
		}
	//---------------------------------------------------------------------------------
	// Verificamos si la sep seleccionada no esta en el formulario
	//---------------------------------------------------------------------------------
	valido=true;
	total=ue_calcular_total_fila_opener("txtnumsolord");
	opener.document.formulario.totrowbienes.value=total;
	rowbienes = opener.document.formulario.totrowbienes.value;
	for(j=1;(j<=total)&&(valido);j++)
	{
		numsolordgrid=eval("opener.document.formulario.txtnumsolord"+j+".value");
		if(numsolordgrid==as_numsol)
		{
			alert("Esta Solicitud Presupuestaria ya está registrada en el grid de la Orden de Compra !!!");
			valido=false;
		}
	}
	if(valido)
	{
     	 ls_uniejeaso = opener.document.formulario.txtuniejeaso.value;
	     ls_uniejeaso = ls_uniejeaso+" "+"Nro. SEP:"+as_numsol+". Unidad Ejecutora: "+as_codunieje+" - "+as_denunieje+";";
	     opener.document.formulario.txtuniejeaso.value = ls_uniejeaso;
	}
	tipconpro=opener.document.formulario.tipconpro.value;
	if(as_modsep=="B") // Bienes
	{
		 proceso="AGREGARBIENES-SEP";
		//---------------------------------------------------------------------------------
		// Cargar los Bienes del opener 
		//---------------------------------------------------------------------------------
		parametros="";
		for(j=1;(j<=rowbienes)&&(valido);j++)
		{ 
			codart=eval("opener.document.formulario.txtcodart"+j+".value");
			denart=eval("opener.document.formulario.txtdenart"+j+".value");
			canart=eval("opener.document.formulario.txtcanart"+j+".value");
			unidad=eval("opener.document.formulario.cmbunidad"+j+".value");
			preart=eval("opener.document.formulario.txtpreart"+j+".value");
			subtotart=eval("opener.document.formulario.txtsubtotart"+j+".value");
			carart=eval("opener.document.formulario.txtcarart"+j+".value");
			totart=eval("opener.document.formulario.txttotart"+j+".value");
			spgcuenta=eval("opener.document.formulario.txtspgcuenta"+j+".value");
			unidadfisica=eval("opener.document.formulario.txtunidad"+j+".value");
			numsolord=eval("opener.document.formulario.txtnumsolord"+j+".value");
			coduniadmsep = eval("opener.document.formulario.txtcoduniadmsep"+j+".value");
			denuniadmsep = eval("opener.document.formulario.txtdenuniadmsep"+j+".value");
			ls_codestpro = eval("opener.document.formulario.hidcodestpro"+j+".value");
			ls_estcla    = eval("opener.document.formulario.estcla"+j+".value");
			parametros=parametros+"&txtcodart"+j+"="+codart+"&txtdenart"+j+"="+denart+""+
					   "&txtcanart"+j+"="+canart+"&cmbunidad"+j+"="+unidad+""+
					   "&txtpreart"+j+"="+preart+"&txtsubtotart"+j+"="+subtotart+""+
					   "&txtcarart"+j+"="+carart+"&txttotart"+j+"="+totart+""+
					   "&txtspgcuenta"+j+"="+spgcuenta+"&txtunidad"+j+"="+unidadfisica+""+
					   "&hidcodestpro"+j+"="+ls_codestpro+"&estcla"+j+"="+ls_estcla+
					   "&txtnumsolord"+j+"="+numsolord+"&txtcoduniadmsep"+j+"="+coduniadmsep
					   +"&txtdenuniadmsep"+j+"="+denuniadmsep;
		    parametros=parametros+"&numsol="+as_numsol+"&tipsol=SEP"+"&tipo="+as_modsep+"&tipconpro="+tipconpro;
		}
	    totalbienes=eval(rowbienes);
		parametros=parametros+"&totalbienes="+totalbienes;

	}
	if(as_modsep=="S") // Servicios
	{
		 total=ue_calcular_total_fila_opener("txtcodser");
		 opener.document.formulario.totrowservicios.value=total;
		 rowservicios=opener.document.formulario.totrowservicios.value;
		 proceso="AGREGARSERVICIOS-SEP";
		//---------------------------------------------------------------------------------
		// Cargar los Servicios del opener 
		//---------------------------------------------------------------------------------
		parametros=""; 
		for(j=1;(j<rowservicios)&&(valido);j++)
		{ 
			codser=eval("opener.document.formulario.txtcodser"+j+".value");
			denser=eval("opener.document.formulario.txtdenser"+j+".value");
			canser=eval("opener.document.formulario.txtcanser"+j+".value");
			preser=eval("opener.document.formulario.txtpreser"+j+".value");
			subtotser=eval("opener.document.formulario.txtsubtotser"+j+".value");
			carser=eval("opener.document.formulario.txtcarser"+j+".value");
			totser=eval("opener.document.formulario.txttotser"+j+".value");
			spgcuenta=eval("opener.document.formulario.txtspgcuenta"+j+".value");
			numsolord=eval("opener.document.formulario.txtnumsolord"+j+".value");
			coduniadmsep=eval("opener.document.formulario.txtcoduniadmsep"+j+".value");
			denuniadmsep=eval("opener.document.formulario.txtdenuniadmsep"+j+".value");
			ls_codestpro = eval("opener.document.formulario.hidcodestpro"+j+".value"); 
			ls_estcla    = eval("opener.document.formulario.estcla"+j+".value");
			ls_hidspgcuentas= eval("opener.document.formulario.hidspgcuentas"+j+".value");
			parametros=parametros+"&txtcodser"+j+"="+codser+"&txtdenser"+j+"="+denser+""+
					   "&txtcanser"+j+"="+canser+"&txtpreser"+j+"="+preser+""+
					   "&txtsubtotser"+j+"="+subtotser+"&txtcarser"+j+"="+carser+""+
					   "&txttotser"+j+"="+totser+"&txtspgcuenta"+j+"="+spgcuenta+""+
					   "&hidcodestpro"+j+"="+ls_codestpro+"&estcla"+j+"="+ls_estcla+
					   "&txtnumsolord"+j+"="+numsolord+"&txtcoduniadmsep"+j+"="+coduniadmsep+
					   "&txtdenuniadmsep"+j+"="+denuniadmsep +"&hidspgcuentas"+j+"="+ls_hidspgcuentas;
		} 
		totalservicios=eval(rowservicios);
		parametros=parametros+"&totalservicios="+totalservicios+"&numsol="+as_numsol+"&tipsol=SEP"+"&tipo="+as_modsep+"&tipconpro="+tipconpro;
	}
	//---------------------------------------------------------------------------------
	// Cargar los Cargos del opener 
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de los cargos y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcodservic");
	opener.document.formulario.totrowcargos.value=total;
	rowcargos=opener.document.formulario.totrowcargos.value; 
	for(j=1;(j<=rowcargos)&&(valido);j++)
	{
		codservic=eval("opener.document.formulario.txtcodservic"+j+".value");
		codcar=eval("opener.document.formulario.txtcodcar"+j+".value");
		dencar=eval("opener.document.formulario.txtdencar"+j+".value");
		bascar=eval("opener.document.formulario.txtbascar"+j+".value");
		moncar=eval("opener.document.formulario.txtmoncar"+j+".value");
		subcargo=eval("opener.document.formulario.txtsubcargo"+j+".value");
		cuentacargo=eval("opener.document.formulario.cuentacargo"+j+".value");
		formulacargo=eval("opener.document.formulario.formulacargo"+j+".value");
		ls_numsep= eval("opener.document.formulario.hidnumsepcar"+j+".value");
		ls_codprocargo= eval("opener.document.formulario.codprogcargo"+j+".value");
		ls_estclacargo= eval("opener.document.formulario.estclacargo"+j+".value");
		
		parametros=parametros+"&txtcodservic"+j+"="+codservic+"&txtcodcar"+j+"="+codcar+
				   "&txtdencar"+j+"="+dencar+"&txtbascar"+j+"="+bascar+
				   "&txtmoncar"+j+"="+moncar+"&txtsubcargo"+j+"="+subcargo+
				   "&cuentacargo"+j+"="+cuentacargo+"&formulacargo"+j+"="+formulacargo+
				   "&hidnumsepcar"+j+"="+ls_numsep+"&codprogcargo"+j+"="+ls_codprocargo
				   +"&estclacargo"+j+"="+ls_estclacargo;
	}
	totalcargos=eval(rowcargos);
	parametros=parametros+"&totalcargos="+totalcargos; 
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias 
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de las cuentas y asignarlo al total row
/*	total=ue_calcular_total_fila_opener("txtcuentagas");
	opener.document.formulario.totrowcuentas.value=total;
	rowcuentas=opener.document.formulario.totrowcuentas.value;
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{
		programaticagas = eval("opener.document.formulario.txtprogramaticagas"+j+".value");
		codprogas       = eval("opener.document.formulario.txtcodprogas"+j+".value");
		cuentagas       = eval("opener.document.formulario.txtcuentagas"+j+".value");
		moncuegas       = eval("opener.document.formulario.txtmoncuegas"+j+".value");
		ls_estcla       = eval("opener.document.formulario.estclapre"+j+".value");
		parametros=parametros+"&txtprogramaticagas"+j+"="+programaticagas+"&txtcodprogas"+j+"="+codprogas+
		           "&txtcuentagas"+j+"="+cuentagas+"&txtmoncuegas"+j+"="+moncuegas+"&estclapre"+j+"="+ls_estcla;
	}
	totalcuentas=eval(rowcuentas);
	parametros=parametros+"&totalcuentas="+totalcuentas;
	//---------------------------------------------------------------------------------
	// Cargar las Cuentas Presupuestarias del Cargo del opener 
	//---------------------------------------------------------------------------------
	//obtener el numero de filas real de las cuentas y asignarlo al total row
	total=ue_calcular_total_fila_opener("txtcuentacar");
	opener.document.formulario.totrowcuentascargo.value=total;
	rowcuentas=opener.document.formulario.totrowcuentascargo.value;
	for(j=1;(j<rowcuentas)&&(valido);j++)
	{
		cargo           = eval("opener.document.formulario.txtcodcargo"+j+".value");
		programaticacar = eval("opener.document.formulario.txtprogramaticacar"+j+".value");
		cuenta          = eval("opener.document.formulario.txtcuentacar"+j+".value");
		moncue          = eval("opener.document.formulario.txtmoncuecar"+j+".value");
		codpro          = eval("opener.document.formulario.txtcodprocar"+j+".value");
		ls_estcla       = eval("opener.document.formulario.estclacar"+j+".value");
		parametros=parametros+"&txtprogramaticacar"+j+"="+programaticacar+"&txtcodcargo"+j+"="+cargo+"&txtcodprocar"+j+"="+codpro+
		           "&txtcuentacar"+j+"="+cuenta+"&txtmoncuecar"+j+"="+moncue+"&estclacar"+j+"="+ls_estcla;
	}
	totalcuentascargo=eval(rowcuentas);
	parametros=parametros+"&totalcuentascargo="+totalcuentascargo;
	//---------------------------------------------------------------------------------
	// Cargar los totales
	//---------------------------------------------------------------------------------
	subtotal=eval("opener.document.formulario.txtsubtotal.value");
	cargos=eval("opener.document.formulario.txtcargos.value");
	total=eval("opener.document.formulario.txttotal.value");
	parametros=parametros+"&subtotal="+subtotal+"&cargos="+cargos+"&total="+total;*/

	if((parametros!="")&&(valido))
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("bienesservicios");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_soc_c_registro_orden_compra_ajax.php",true);
		ajax.onreadystatechange=function()
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
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		// Enviar todos los campos a la pagina para que haga el procesamiento
		ajax.send("proceso="+proceso+""+parametros);
	}
}					

function ue_search()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	ls_numsol     = f.txtnumsol.value;
	ls_codunieje  = f.txtcodunieje.value;
	ld_fecregdes  = f.txtfecdes.value;
	ld_fecreghas  = f.txtfechas.value;
	ls_tipord     = f.tipord.value;
	ls_tipo       = f.tipo.value;
	ls_orden      = f.orden.value;
	ls_campoorden = f.campoorden.value;
	if((ld_fecregdes!="")&&(ld_fecreghas!=""))
	{
		// Div donde se van a cargar los resultados
		divgrid = document.getElementById('resultados');
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_soc_c_catalogo_ajax.php",true);
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
		ls_tipbieordcom = '-';
		if (opener.document.formulario.id=='orden_compra')
		   {
			 if (opener.document.formulario.radiotipbieordcom[0].checked)
			    {
				  ls_tipbieordcom = opener.document.formulario.radiotipbieordcom[0].value;
			    }
			 else
			    {
				  if (opener.document.formulario.radiotipbieordcom[1].checked)
					 {
					   ls_tipbieordcom = opener.document.formulario.radiotipbieordcom[1].value;
					 }
			      else
				     {
					   ls_tipbieordcom = "-";
					 }
				}
		   }
		ajax.send("catalogo=SOLICITUD-PRESUPUESTARIA&numsol="+ls_numsol+"&coduniadm="+ls_codunieje+"&fecregdes="+ld_fecregdes+
				  "&fecreghas="+ld_fecreghas+"&tipo="+ls_tipo+"&tipord="+ls_tipord+"&orden="+ls_orden+"&campoorden="+ls_campoorden+"&tipbieordcom="+ls_tipbieordcom);
	}
	else
	{
		alert("Debe seleccionar un rango de Fecha.");
	}
}
</script>
</html>