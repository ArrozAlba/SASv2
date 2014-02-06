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
   		global $ls_codprovben,$ls_nomprovben,$ls_tipodestino,$ls_operacion,$io_fun_scb;
		require_once("../shared/class_folder/class_generar_id_process_sol.php");
		$io_id_process= new class_generar_id_process_sol();
		
		$ls_codprovben="";
		$ls_nomprovben="";
		$ls_tipodestino="";
		$ls_operacion=$io_fun_scb->uf_obteneroperacion();
   }
	require_once("class_funciones_banco.php");
	$io_fun_scb=new class_funciones_banco();
	uf_limpiarvariables();
	$ls_tipo=$io_fun_scb->uf_obtenertipo();
	unset($io_fun_scb);
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<body>
<form name="form1" method="post" action="" id="sigesp_scb_cat_solicitudpago.php">
<input name="campoorden" type="hidden" id="campoorden" value="numsol">
<input name="orden" type="hidden" id="orden" value="ASC">
<input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo; ?>">
<br>
    <table width="520" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr class="titulo-celda">
        <td height="22" colspan="3" style="text-align:center">Cat&aacute;logo de Solicitudes de Pago</td>
      </tr>
      <tr>
        <td height="15" align="right">&nbsp;</td>
        <td height="15" colspan="2" align="left">&nbsp;</td>
      </tr>
      <tr>
        <td width="135" height="22" align="right"><select name="cmbtipdes" id="cmbtipdes" onChange="javascript: ue_cambiardestino();">
          <option value="-">---seleccione---</option>
            <option value="P" <?php if($ls_tipodestino=="P"){ print "selected";} ?>>PROVEEDOR</option>
            <option value="B" <?php if($ls_tipodestino=="B"){ print "selected";} ?>>BENEFICIARIO</option>
          </select>        </td>
        <td height="22" colspan="2" align="left"><div align="left">
            <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codprovben; ?>" size="15" maxlength="10" readonly style="text-align:center">
            <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" value="<?php print $ls_nomprovben; ?>" size="45" readonly style="text-align:left">
        </div></td>
      </tr>
      <tr>
        <td height="21" style="text-align:right">N&uacute;mero de Solicitud</td>
        <td width="217" height="21"><input name="txtnumsol" type="text" id="txtnumsol" onKeyPress="javascript: ue_mostrar(this,event);" maxlength="15" style="text-align:center"></td>
        <td width="160" rowspan="3"><table width="145" border="0" align="right" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="22" colspan="2" style="text-align:center"><strong>Fecha de Emisi&oacute;n</strong></td>
          </tr>
          <tr>
            <td width="58" height="22" style="text-align:right">Desde</td>
            <td width="85"><input name="txtfecdes" type="text" id="txtfecdes" size="15" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);"  datepicker="true" value="<?php print $ld_fecdes;?>"></td>
          </tr>
          <tr>
            <td height="22" style="text-align:right">Hasta</td>
            <td><input name="txtfechas" type="text" id="txtfechas" size="15" onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);"  datepicker="true" value="<?php print $ld_fechas;?>"></td>
          </tr>
        </table>
        <div align="right"></div></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Concepto</td>
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
	f=document.form1;
	// Se verifica si el destino es un proveedor ó beneficiario y se carga el catalogo
	// dependiendo de esa información
	f.txtcodigo.value="";
	f.txtnombre.value="";
	tipdes=ue_validarvacio(f.cmbtipdes.value);
	if (tipdes!="-")
	   {
		 if (tipdes=="P")
		    {
			  window.open("sigesp_cat_prov_general.php?obj=txtcodigo","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,location=no,resizable=yes,dependent=yes");
		    }
		 else
		    {
			  window.open("sigesp_cat_bene_general.php?obj=txtcodigo","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,location=no,resizable=yes,dependent=yes");
		    }	
	   }
}

function ue_search()
{
	f=document.form1;
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
		ajax.open("POST","class_folder/sigesp_scb_c_catalogo_ajax.php",true);
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
		ajax.send("catalogo=SOLICITUDPAGO&numsol="+numsol+"&fecemides="+fecemides+"&fecemihas="+fecemihas+
				  "&tipdes="+tipdes+"&codproben="+codproben+"&tipo="+tipo+"&orden="+orden+"&campoorden="+campoorden);
	}
	else
	{
		alert("Debe seleccionar un rango de Fecha !!!");
	}
}

function aceptarrepdes(ls_numsol)
{
	opener.document.form1.txtnumsoldes.value=ls_numsol;
	close();
}

/*function ue_aceptar(ls_numsol,ls_codfuefin,ls_denfuefin,ls_codigo,ls_nombre,li_monsol,ls_estprosol,ls_estaprosol,ld_fecemisol,
					ls_estatus,ls_tipodestino, li_i)
{
	opener.document.form1.txtnumsol.value=ls_numsol;
	opener.document.form1.txtcodfuefin.value=ls_codfuefin;
	opener.document.form1.txtdenfuefin.value=ls_denfuefin;
	opener.document.form1.txtcodigo.value=ls_codigo;
	opener.document.form1.txtnombre.value=ls_nombre;
	opener.document.form1.cmbtipdes.value=ls_tipodestino;
	opener.document.form1.cmbtipdes.disabled=true;
	opener.document.form1.txttipdes.value=ls_tipodestino;
	f=document.form1;
	opener.document.form1.txtconsol.value=eval("f.txtconsol"+li_i+".value;");
	opener.document.form1.txtobssol.value=eval("f.txtobssol"+li_i+".value;");
	opener.document.form1.estapr.value=ls_estaprosol;
	opener.document.form1.txtestatus.value=ls_estatus;
	opener.document.form1.txtfecemisol.value=ld_fecemisol;
	opener.document.form1.txtfecemisol.disabled=true;
	opener.document.form1.txtfecha.value=ld_fecemisol;
	opener.document.form1.existe.value="TRUE";
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

/*function aceptarrephas(ls_numsol)
{
	opener.document.form1.txtnumsolhas.value=ls_numsol;
	close();
}

function aceptarncnd(ls_numord,ls_tipproben,ls_codpro,ls_cedbene,ls_nomproben,ls_cuenta,ls_denscg)
{
	f=opener.document.form1;
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
	li_indice=opener.document.form1.txtindice.value;
	eval("opener.document.form1.txtnumsop"+li_indice+".value='"+ls_numsol+"'");
	close();
}*/
</script>
</html>