<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cat&aacute;logo de Solicitudes de Cotizaciones</title>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_soc.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
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
</style>
</head>

<body>
<?php
if (array_key_exists("hidoperacion",$_POST))
   {
     $ls_numsep    = $_POST["txtnumsep"];
	 $ls_operacion = $_POST["hidoperacion"];
     $ls_numsolcot = $_POST["txtnumsolcot"];
 	 $ls_fecdes    = $_POST["txtfecdes"];
	 $ls_fechas    = $_POST["txtfechas"];
	 $ls_origen    = $_POST["origen"];
	 $ls_tipsolcot = $_POST["cmbtipsolcot"];
   }
else
   {
     $ls_numsep    = ""; 
	 $ls_operacion = "";
	 $ls_fecdes    = '01/'.date("m/Y");
	 $ls_fechas    = date("d/m/Y");
     $ls_numsolcot = "";
   	 $ls_origen    = $_GET["origen"];
 	 $ls_tipsolcot = "-";
   }
if ($ls_origen=='RC')
   {
     $ls_disabled = "disabled";
   }
?>
<form id="formulario" name="formulario" method="post" action="">
<br />
  <table width="580" height="114" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-celda">
      <td height="22" colspan="4">Cat&aacute;logo de Solicitudes de Cotizaciones
        
      <input name="hidoperacion" type="hidden" id="hidoperacion" value="<?php print $ls_operacion ?>" />
      <input name="orden" type="hidden" id="orden" value="ASC" />
      <input name="campoorden" type="hidden" id="campoorden" value="numsolcot" />
      <input name="origen" type="hidden" id="origen" value="<?php print $ls_origen ?>" /></td>
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
        <input name="txtnumsolcot" type="text" id="txtnumsolcot" style="text-align:center" value="<?php print $ls_numsolcot ?>" size="20" maxlength="15" onKeyPress="return keyRestrict(event,'0123456789');" onBlur="javascript:rellenar_cad(this.value,15)" />
      </label></td>
      <td height="22">&nbsp;</td>
      <td height="22">Desde
        <input name="txtfecdes" type="text" id="txtfecdes"  value="<?php print $ls_fecdes ?>" size="13" maxlength="10" datepicker="true" onkeypress="currencyDate(this);" style="text-align:left">
      &nbsp;</td>
    </tr>
	<tr>
      <td width="104" height="22"><div align="right">Tipo</div></td>
      <td width="146" height="22"><label>
	    <select name="cmbtipsolcot" id="cmbtipsolcot" style="width:120px" <?php print $ls_disabled ?>>
          <option value="-">---seleccione---</option>
          <option value="B">Bienes</option>
          <option value="S">Servicios</option>
        </select>
      </label></td>
      <td width="57" height="22">&nbsp;</td>
      <td width="271" height="22">Hasta    
        <input name="txtfechas" type="text" id="txtfechas" value="<?php print $ls_fechas ?>" size="13" maxlength="10" datepicker="true" onkeypress="currencyDate(this);" style="text-align:left" /></td>
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
      <td height="22"><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar Solicitud" width="20" height="20" border="0" onclick="ue_search()" />Buscar Solicitud</a></div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <div id="resultados" align="center"></div>
  <p>&nbsp;</p>
</form>
</body>
<script language="javascript">
f = document.formulario;
function ue_aceptar_solicitud(ls_numsolcot,ls_tipsolcot,ls_fecsolcot,ls_obssolcot,ls_consolcot,ls_uniejeaso,ls_cedpersol,
                              ls_nompersol,ls_codcarper,ls_soltel,ls_solfax,ls_codunieje,ls_denunieje,ls_codestpro1,ls_codestpro2,
							  ls_codestpro3,ls_codestpro4,ls_codestpro5,ls_estcla,ls_estsolcot,as_tipbiesol)
{
  fop = opener.document.formulario;
  fop.txtnumsolcot.value 	= ls_numsolcot;
  fop.cmbtipsolcot.value 	= ls_tipsolcot;
  fop.txtfecregsolcot.value = ls_fecsolcot;
  fop.txtobssolcot.value    = ls_obssolcot;
  fop.txtconsolcot.value    = ls_consolcot;
  fop.txtuniejeaso.value    = ls_uniejeaso;
  fop.txtcedper.value 	    = ls_cedpersol;
  fop.txtnompersol.value    = ls_nompersol;
  fop.txtcodcarper.value 	= ls_codcarper;
  fop.txttelpersol.value 	= ls_soltel;
  fop.txtfaxpersol.value 	= ls_solfax;
  fop.txtcodunieje.value 	= ls_codunieje;
  fop.txtdenunieje.value 	= ls_denunieje;
  fop.txtcodestpro1.value   = ls_codestpro1;
  fop.txtcodestpro2.value   = ls_codestpro2;
  fop.txtcodestpro3.value   = ls_codestpro3;
  fop.txtcodestpro4.value   = ls_codestpro4;
  fop.txtcodestpro5.value   = ls_codestpro5;
  fop.hidestcla.value       = ls_estcla;
  fop.hidestsolcot.value    = ls_estsolcot;
  if (ls_estsolcot=='R')
     {
	   fop.txtestsolcot.value = "REGISTRO"; 
	   fop.cmbtipsolcot.disabled = false;
	   fop.botsep.disabled       = false;
	   fop.txttelpersol.disabled = false;
       fop.txtfaxpersol.disabled = false;
	   fop.txtobssolcot.disabled = false;
       fop.txtconsolcot.disabled = false;
	 }
  else
     {
	   fop.txtestsolcot.value    = "PROCESADA";
	   fop.cmbtipsolcot.disabled = true;
	   fop.botsep.disabled 		 = true;
	   fop.txttelpersol.disabled = true;
       fop.txtfaxpersol.disabled = true;
	   fop.txtobssolcot.disabled = true;
       fop.txtconsolcot.disabled = true;
	 }
  fop.existe.value          = "TRUE";
  parametros				= "";
  parametros				= parametros+"&numsolcot="+ls_numsolcot;
  if (ls_tipsolcot=='B')
     {
	   proceso='LOADBIENES';
	   fop.radiotipbiesol[0].disabled = false;
	   fop.radiotipbiesol[1].disabled = false;
	   if (ls_estsolcot=='R')
	      {
		    if (as_tipbiesol=='M')
			   {
				 fop.radiotipbiesol[0].checked  = true;
				 fop.radiotipbiesol[1].checked  = false;		  
			   }
		    else
			   {
				 if (as_tipbiesol=='A')
				    {
					  fop.radiotipbiesol[0].checked  = false;
					  fop.radiotipbiesol[1].checked  = true;
				    }		  
			   }
		  }
	   else
	      {
	  	    if (as_tipbiesol=='M')
			   {
				 fop.radiotipbiesol[0].checked  = true;
				 fop.radiotipbiesol[1].checked  = false;		  
			   }
		    else
	           {
		         if (as_tipbiesol=='A')
			        {
	     	          fop.radiotipbiesol[0].checked  = false;
	   			      fop.radiotipbiesol[1].checked  = true;
					}		  
		       }
			fop.radiotipbiesol[0].disabled = true;
	        fop.radiotipbiesol[1].disabled = true;		  
		  }
	 }
  else
     {
	   proceso='LOADSERVICIOS';
	   fop.radiotipbiesol[0].checked  = false;
	   fop.radiotipbiesol[1].checked  = false;
	   fop.radiotipbiesol[0].disabled = true;
	   fop.radiotipbiesol[1].disabled = true;
	 } 
  li_estciespg = "<?php echo $_SESSION["la_empresa"]["estciespg"] ?>";
  li_estciespi = "<?php echo $_SESSION["la_empresa"]["estciespi"] ?>";
  if (li_estciespg==1 || li_estciespi==1)
     {
	   fop.cmbtipsolcot.disabled = true;
	   fop.botsep.disabled 		 = true;
	   fop.txttelpersol.disabled = true;
       fop.txtfaxpersol.disabled = true;
	   fop.txtobssolcot.disabled = true;
       fop.txtconsolcot.disabled = true;
	   fop.radiotipbiesol[0].disabled = true;
	   fop.radiotipbiesol[1].disabled = true;
	 }

  if (parametros!="")
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

function ue_aceptar_registro(ls_numsolcot,ls_codpro,ls_tipsolcot)
{
  fop        = opener.document.formulario;
  parametros = "";
  fop.hidnumsolcot.value = ls_numsolcot;
  parametros = parametros+"&numsolcot="+ls_numsolcot+"&cod_pro="+ls_codpro+"&tipsolcot="+ls_tipsolcot;
  if (ls_tipsolcot=='B')
     {
	   proceso='LOADBIENES';
	 }
  else
     {
	   proceso='LOADSERVICIOS';
	 } 
  if (parametros!="")
	 {
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("bienesservicios");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_soc_c_registro_cotizacion_ajax.php",true);
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
		ajax.send("proceso="+proceso+""+parametros);
		}
}

function ue_search()
{
    f   = document.formulario;
	fop = opener.document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	ls_numsolcot = f.txtnumsolcot.value;
	ls_fecdes    = f.txtfecdes.value;
	ls_fechas    = f.txtfechas.value;
	orden        = f.orden.value;
	ls_origen    = f.origen.value;
	ls_codpro    = "";
	ls_switch    = fop.operacion.value;
    ls_tipsolcot ="";
	if (ls_origen=='RC')
	   {
	     ls_codpro    = fop.txtcodprov.value;
	     ls_tipsolcot = fop.cmbtipcot.value; 
	   }
	else
	   {
	     if (ls_origen=='REPDES' || ls_origen=='REPHAS')
		    { 
	          if (ls_switch!='RC')
			     {
				   ls_tipsolcot = fop.cmbtipsolcot.value; 
				 }
			}
		 else
		    {
		      ls_tipsolcot = f.cmbtipsolcot.value;
			}
	   }
	campoorden   = f.campoorden.value;
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
	ajax.send("catalogo=COTIZACION_SOLICITUD&numsolcot="+ls_numsolcot+"&tipsolcot="+ls_tipsolcot+"&fecdes="+ls_fecdes+"&fechas="+ls_fechas+"&orden="+orden+
			  "&campoorden="+campoorden+"&origen="+ls_origen+"&codpro="+ls_codpro);
}

function rellenar_cad(cadena,longitud)
{
	var mystring = new String(cadena);
	cadena_ceros = "";
	lencad       = mystring.length;
    total        =longitud-lencad;
    if (cadena!="")
	   {
	     for (i=1;i<=total;i++)
		     {
			   cadena_ceros=cadena_ceros+"0";
		     }
	     cadena=cadena_ceros+cadena;
		 document.formulario.txtnumsolcot.value=cadena;
	 } 
}

function aceptar_reportedesde(ls_numsolcot)
{
	fop.txtnumsolcotdes.value = ls_numsolcot;
	close();
}

function aceptar_reportehasta(ls_numsolcot) 
{
	fop.txtnumsolcothas.value = ls_numsolcot;
	close();
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>