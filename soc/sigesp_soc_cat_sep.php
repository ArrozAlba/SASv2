<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cat&aacute;logo de Solicitudes de Ejecuci&oacute;n Presupuestaria</title>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<!--<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>-->
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
if (array_key_exists("hidoperacion",$_POST))
   {
     $ls_numsep    = $_POST["txtnumsep"];
	 $ls_operacion = $_POST["hidoperacion"];
     $ls_codproben = $_POST["txtcodproben"];
	 $ls_nomproben = $_POST["txtnomproben"];
	 $ls_fecdes    = $_POST["txtfecdes"];
	 $ls_fechas    = $_POST["txtfechas"];
     $ls_coduniadm = $_POST["txtcodunieje"];
	 $ls_denuniadm = $_POST["txtdenunieje"];
	 $ls_tipo      = $_POST["tipo"];
   }
else
   {
     $ls_numsep    = ""; 
	 $ls_operacion = "";
     $ls_codproben = "";
	 $ls_nomproben = "";
	 $ls_fecdes    = '01/'.date("m/Y");
	 $ls_fechas    = date("d/m/Y");
     $ls_coduniadm = "";
	 $ls_denuniadm = "";
     $ls_tipo      = $_GET["tipo"];
   }
?>
<form id="formulario" name="formulario" method="post" action="">
<input name="campoorden" type="hidden" id="campoorden" value="numsol" />
<input name="orden" type="hidden" id="orden" value="ASC" />
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>" />
<br />
  <table width="580" height="149" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
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
      <td height="22"><div align="right">Nro Solicitud </div></td>
      <td height="22"><label>
        <input name="txtnumsep" type="text" id="txtnumsep" style="text-align:center" value="<?php print $ls_numsep ?>" size="20" maxlength="15" />
      </label></td>
      <td height="22" style="text-align:right">Fecha</td>
      <td height="22">Desde 
        <input name="txtfecdes" type="text" id="txtfecdes"  value="<?php print $ls_fecdes ?>" size="13" maxlength="10" datepicker="true" onkeypress="currencyDate(this);" style="text-align:left">
        &nbsp;&nbsp; 
        Hasta
<input name="txtfechas" type="text" id="txtfechas" value="<?php print $ls_fechas ?>" size="13" maxlength="10" datepicker="true" onkeypress="currencyDate(this);" style="text-align:left"></td>
    </tr>
    <tr>
      <td width="82" height="22"><div align="right">Tipo Destino </div></td>
      <td width="168" height="22"><label>
        <select name="cmbtipdes" id="cmbtipdes" onchange="javascript:uf_cambiar();" style="width:120px">
          <option value="-">---seleccione---</option>
          <option value="P">Proveedor</option>
        </select>
      </label></td>
      <td width="57" height="22">&nbsp;</td>
      <td width="271" height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">C&oacute;digo</div></td>
      <td height="22" colspan="3"><label>
        <input name="txtcodproben" type="text" id="txtcodproben" style="text-align:center" value="<?php print $ls_codproben ?>" size="20" maxlength="10" />
        <img src="../shared/imagebank/tools15/buscar.gif" name="buscar" width="15" height="15" id="buscar" style="visibility:hidden" onclick="javascript:uf_catalogo_proben();" /></label>        <label>
        <input name="txtnomproben" type="text" class="sin-borde" id="txtnomproben" value="<?php print $ls_nomproben ?>" size="60" readonly />
        </label></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Departamento</div></td>
      <td height="22" colspan="3"><input name="txtcodunieje" type="text" id="nombre" onkeypress="javascript: ue_mostrar(this,event);" value="<?php print $ls_coduniadm;?>" size="20" readonly="readonly" style="text-align:center" />
      <a href="javascript: ue_catalogo_unidad_ejecutora();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0" /></a>      <input name="txtdenunieje" type="text" class="sin-borde" id="txtdenunieje" value="<?php print $ls_denuniadm;?>" size="60" readonly="readonly" /></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" onclick="ue_search()" />Buscar Solicitud</a></div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <div id="resultados" align="center"></div>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
f   = document.formulario;
fop = opener.document.formulario;

function ue_search()
{
	// Cargamos las variables para pasarlas al AJAX
	ls_numsep    = f.txtnumsep.value;
	ls_coduniadm = f.txtcodunieje.value;
	ls_fecregdes = ue_validarvacio(f.txtfecdes.value);
	ls_fecreghas = ue_validarvacio(f.txtfechas.value);
	tipord       = f.orden.value;
	ls_tipsolcot = opener.document.formulario.cmbtipsolcot.value;
	if (ls_tipsolcot=='B')
	   {
	     if (opener.document.formulario.radiotipbiesol[0].checked==true)
		    {
			  ls_tipsolbie = opener.document.formulario.radiotipbiesol[0].value; 
			}
	     else
		    {
			  if (opener.document.formulario.radiotipbiesol[1].checked==true)
				 {
				   ls_tipsolbie = opener.document.formulario.radiotipbiesol[1].value;
				 }
				 else
				 {
				   ls_tipsolbie = '-';
				 }
			}
	   }
	else
	   {
	     ls_tipsolbie = '-';
	   } 
	orden        = f.orden.value;
	campoorden   = f.campoorden.value;
	ls_tipdes    = f.cmbtipdes.value;
	ls_tipo      = f.tipo.value;
	if((ls_fecregdes!="")&&(ls_fecreghas!=""))
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
		ajax.send("catalogo=PRESUPUESTARIA-SOLICITUD&numsep="+ls_numsep+"&coduniadm="+ls_coduniadm+"&fecregdes="+ls_fecregdes+"&tipo="+ls_tipo+
				  "&fecreghas="+ls_fecreghas+"&hidtipsolcot="+ls_tipsolcot+"&tipord="+tipord+"&orden="+orden+"&campoorden="+campoorden+"&tipdes="+ls_tipdes+"&tipsolbie="+ls_tipsolbie);
	}
	else
	{
		alert("Debe seleccionar un rango de Fecha.");
	}
}

function ue_aceptar(ls_numsep,ls_consep,ld_monsep,ls_unieje,ls_denuni,ls_codigo,ls_nombre,ls_direccion,ls_telefono,as_codestpro1,as_codestpro2,as_codestpro3,as_codestpro4,as_codestpro5,as_estcla)
{
    lb_existe_sol = false;
	valido        = true;
	parametros    = "";
	li_totrowsep  = ue_calcular_total_fila_opener("txtnumsep")
	if (li_totrowsep==0)
	   {
		 fop.txtcodunieje.value  = ls_unieje;
		 fop.txtdenunieje.value  = ls_denuni;
		 fop.txtcodestpro1.value = as_codestpro1;
		 fop.txtcodestpro2.value = as_codestpro2;
		 fop.txtcodestpro3.value = as_codestpro3;
		 fop.txtcodestpro4.value = as_codestpro4;
		 fop.txtcodestpro5.value = as_codestpro5;
		 fop.hidestcla.value     = as_estcla;
         ls_codestpro            = as_codestpro1+as_codestpro2+as_codestpro3+as_codestpro4+as_codestpro5;
		 li_totrowsep = 2;
     	 ls_uniejeaso = fop.txtuniejeaso.value;
	     ls_uniejeaso = ls_uniejeaso+" "+"Nro. SEP:"+ls_numsep+". Unidad Ejecutora: "+ls_unieje+" - "+ls_denuni+";";
	     fop.txtuniejeaso.value = ls_uniejeaso;
		 parametros = parametros+"&txtnumsep1="+ls_numsep+"&txtdensep1="+ls_consep+"&txtmonsep1="+ld_monsep+"&txtunieje1="+ls_unieje+"&txtdenuni1="+ls_denuni+"&hidcodestpro1="+ls_codestpro+"&estcla1="+as_estcla;
	     parametros = parametros+"&txtnumsep2="+""+"&txtdensep2="+""+"&txtmonsep2="+""+"&txtunieje2="+""+"&hidcodestpro2="+""+"&estcla2="+"";
	     parametros = parametros+"&totalsep=2";
	     fop.totrowsep.value = li_totrowsep ;
	   }
	else
	   {
		 for (i=1;i<li_totrowsep;i++)
		     {
			   ls_numsol = eval("fop.txtnumsep"+i+".value");
			   if (ls_numsol==ls_numsep)
			      {
				    lb_existe_sol = true;
				  }
			   ls_densep    = eval("fop.txtdensep"+i+".value");
			   ld_totsep    = eval("fop.txtmonsep"+i+".value");
			   ls_codunieje = eval("fop.txtunieje"+i+".value");
			   ls_denunieje = eval("fop.txtdenuni"+i+".value");
			   ls_estcla    = eval("fop.estcla"+i+".value");
			   ls_codestpro = eval("fop.hidcodestpro"+i+".value");
			   parametros   = parametros+"&txtnumsep"+i+"="+ls_numsol+"&txtdensep"+i+"="+ls_densep+"&txtmonsep"+i+"="+ld_totsep+"&txtunieje"+i+"="+ls_codunieje+"&txtdenuni"+i+"="+ls_denunieje+"&hidcodestpro"+i+"="+ls_codestpro+"&estcla"+i+"="+ls_estcla;
			 }
	     if (!lb_existe_sol)
		    {
		      ls_codestpro1 = fop.txtcodestpro1.value;
			  ls_codestpro2 = fop.txtcodestpro2.value;
			  ls_codestpro3 = fop.txtcodestpro3.value;
			  ls_codestpro4 = fop.txtcodestpro4.value;
			  ls_codestpro5 = fop.txtcodestpro5.value;
			  ls_estcla     = fop.hidestcla.value;
			  ls_codunieje  = fop.txtcodunieje.value;
			  if (ls_codunieje!=ls_unieje)
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
			  ls_uniejeaso = fop.txtuniejeaso.value;
			  ls_uniejeaso = ls_uniejeaso+" - "+"Nro. SEP = "+ls_numsep+". Unidad Ejecutora :"+ls_unieje+" - "+ls_denuni+";";
			  fop.txtuniejeaso.value = ls_uniejeaso;
			  parametros = parametros+"&txtnumsep"+i+"="+ls_numsep+"&txtdensep"+i+"="+ls_consep+"&txtmonsep"+i+"="+ld_monsep+"&txtunieje"+i+"="+ls_unieje+"&txtdenuni"+i+"="+ls_denuni+"&hidcodestpro"+i+"="+ls_codestpro+"&estcla"+i+"="+ls_estcla;
			  li_fila = (parseInt(i)+1);
			  parametros = parametros+"&txtnumsep"+li_fila+"="+""+"&txtdensep"+li_fila+"="+""+"&txtmonsep"+li_fila+"="+""+"&txtunieje"+li_fila+"="+""+"&txtdenuni"+li_fila+"="+"";
			  parametros = parametros+"&totalsep="+li_fila;
			  fop.totrowsep.value = li_fila;
			  
			  ls_tipsolcot = fop.cmbtipsolcot.value;
			  if (ls_tipsolcot=='B')
			     {
				   //--------------------------------------------------------------------------------
				   // Incorporamos los detalles existentes de los Bienes/Materiales en el formulario
				   //--------------------------------------------------------------------------------
				   li_totrowbienes = ue_calcular_total_fila_opener("txtcodart");
				   fop.totrowbienes.value = li_totrowbienes;
				   if (li_totrowbienes>1)
					  { 
					    for (j=1;(j<=li_totrowbienes)&&(valido);j++)
						    {  
							  ls_codart  = eval("fop.txtcodart"+j+".value");
							  ls_denart  = eval("fop.txtdenart"+j+".value");
							  ls_canart  = eval("fop.txtcanart"+j+".value");
							  ls_numsol  = eval("fop.hidnumsep"+j+".value");
							  ls_codestpro  = eval("fop.hidcodestpro"+j+".value");
							  ls_codunieje  = eval("fop.hidcodunieje"+j+".value");
							  ls_estcla  = eval("fop.estcla"+j+".value");
							  parametros = parametros+"&txtcodart"+j+"="+ls_codart+"&txtdenart"+j+"="+ls_denart+"&txtcanart"+j+"="+ls_canart+"&hidnumsep"+j+"="+ls_numsol+"&hidcodestpro"+j+"="+ls_codestpro+"&hidcodunieje"+j+"="+ls_codunieje+"&estcla"+j+"="+ls_estcla;		   
						    }
					    parametros  = parametros+"&totalbienes="+li_totrowbienes;		   
				 	  }
			     }
			  else
			     {
				   if (ls_tipsolcot=='S')
					  {
					    //--------------------------------------------------------------------------------
					    // Incorporamos los detalles existentes de los servicios en el formulario
					    //--------------------------------------------------------------------------------
					    li_totrowservicios        = ue_calcular_total_fila_opener("txtcodser");
					    fop.totrowservicios.value = li_totrowservicios;
					    if (li_totrowservicios>1)
						   {
							 for (j=1;(j<=li_totrowservicios)&&(valido);j++)
								 {
								   ls_codser  = eval("fop.txtcodser"+j+".value");
								   ls_denser  = eval("fop.txtdenser"+j+".value");
								   ld_canser  = eval("fop.txtcanser"+j+".value");
								   ls_numsol  = eval("fop.hidnumsep"+j+".value");
								   ls_codestpro  = eval("fop.hidcodestpro"+j+".value");
								   ls_codunieje  = eval("fop.hidcodunieje"+j+".value");
								   ls_estcla  = eval("fop.estcla"+j+".value");
								   parametros = parametros+"&txtcodser"+j+"="+ls_codser+"&txtdenser"+j+"="+ls_denser+"&txtcanser"+j+"="+ld_canser+"&hidnumsep"+j+"="+ls_numsol+"&hidcodestpro"+j+"="+ls_codestpro+"&hidcodunieje"+j+"="+ls_codunieje+"&estcla"+j+"="+ls_estcla;
								 }
							 parametros = parametros+"&totalservicios="+li_totrowservicios;
						   }	   
					  }
			     }
			}
		 else
		    {
			  alert("La Solicitud de Ejecución Presupuestaria ya fue incluida !!!");
			  valido=false;
			}
	   }
	
    li_totrowpro = ue_calcular_total_fila_opener("txtcodpro");
	fop.totrowproveedores.value= li_totrowpro;	
	
	for (j=1;(j<li_totrowpro)&&(valido);j++)
	    { 
		  ls_codpro  = eval("fop.txtcodpro"+j+".value");
		  ls_nompro  = eval("fop.txtnompro"+j+".value");
		  ls_dirpro  = eval("fop.txtdirpro"+j+".value");
		  ls_telpro  = eval("fop.txttelpro"+j+".value");
		  parametros = parametros+"&txtcodpro"+j+"="+ls_codpro+"&txtnompro"+j+"="+ls_nompro+"&txtdirpro"+j+"="+ls_dirpro+"&txttelpro"+j+"="+ls_telpro;
	    }

	lb_existe = false;
	for (j=1;(j<li_totrowpro)&&(valido);j++)
	    {
		  codprogrid = eval("opener.document.formulario.txtcodpro"+j+".value");
		  if (codprogrid==ls_codigo)
		     {
			   lb_existe = true;
		     }
	    }
		
	if ((!lb_existe) && (ls_codigo!='----------'))
	   {
		 parametros = parametros+"&txtcodpro"+li_totrowpro+"="+ls_codigo+"&txtnompro"+li_totrowpro+"="+ls_nombre+"&txtdirpro"+li_totrowpro+"="+ls_direccion+"&txttelpro"+li_totrowpro+"="+ls_telefono;	   
		 li_totrowpro++;
		 parametros = parametros+"&txtcodpro"+li_totrowpro+"="+""+"&txtnompro"+li_totrowpro+"="+""+"&txtdirpro"+li_totrowpro+"="+""+"&txttelpro"+li_totrowpro+"="+"";	   	     
	   }
	parametros = parametros+"&totalproveedores="+li_totrowpro;
	if((parametros!="")&& valido)
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("bienesservicios");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_soc_c_solicitud_cotizacion_ajax.php",true);
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
		ajax.send("proceso=AGREGARSEP"+parametros+"&numsep="+ls_numsep+"&tipo="+ls_tipsolcot);
	}
}

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

function uf_catalogo_proben()
{
  ls_tipdes = f.cmbtipdes.value;//Si la solicitud a buscar Proviene de un proveedor o beneficiario.
  if (ls_tipdes=='P')
     {   //Abrir el Catálogo de Proveedores.
	     pagina="sigesp_soc_cat_proveedor.php?tipo="+"BASICO";
	 }
  else
     {
	   if (ls_tipdes=='B')//Abrir el Catálogo de Beneficiarios.
	      {
	        pagina="sigesp_soc_cat_sep.php?hidtipsolcot="+ls_tipsolcot;
		  }
	 }
  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=no,width=600,height=400,resizable=yes,location=no,left=50,top=50");
}

function aceptar_reportedesde(ls_numsep)
{
	fop.txtnumsepdes.value = ls_numsep;
	close();
}

function aceptar_reportehasta(ls_numsep) 
{
	fop.txtnumsephas.value = ls_numsep;
	close();
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>