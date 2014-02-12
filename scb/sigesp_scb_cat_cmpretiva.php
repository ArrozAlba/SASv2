<?php
	session_start();
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_ano=date('Y');
	$ls_mes=date('m');
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_funciones_banco.php");
	$io_fun_scb=new class_funciones_banco();
	$io_fun_scb->uf_load_seguridad("SCB","sigesp_scb_p_modcmpret.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Comprobantes de Retenci&oacute;n</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
<form name="form1" method="post" action="" id="sigesp_scb_cat_cmpretiva.php">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scb->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_scb);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<br>
	<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-celda">
      <td height="22" colspan="4" align="center">Cat&aacute;logo de Comprobantes de Retenci&oacute;n</td>
      </tr>
    <tr>
      <td height="13" align="center">&nbsp;</td>
      <td height="13" align="center">&nbsp;</td>
      <td height="13" align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Mes</td>
      <td width="208" height="22" style="text-align:left">
        <label>
        <select name="cmbmes" id="cmbmes">
          <option value="01" <?php if($ls_mes=="01"){ print "selected";} ?>>ENERO</option>
          <option value="02" <?php if($ls_mes=="02"){ print "selected";} ?>>FEBRERO</option>
          <option value="03" <?php if($ls_mes=="03"){ print "selected";} ?>>MARZO</option>
          <option value="04" <?php if($ls_mes=="04"){ print "selected";} ?>>ABRIL</option>
          <option value="05" <?php if($ls_mes=="05"){ print "selected";} ?>>MAYO</option>
          <option value="06" <?php if($ls_mes=="06"){ print "selected";} ?>>JUNIO</option>
          <option value="07" <?php if($ls_mes=="07"){ print "selected";} ?>>JULIO</option>
          <option value="08" <?php if($ls_mes=="08"){ print "selected";} ?>>AGOSTO</option>
          <option value="09" <?php if($ls_mes=="09"){ print "selected";} ?>>SEPTIEMBRE</option>
          <option value="10" <?php if($ls_mes=="10"){ print "selected";} ?>>OCTUBRE</option>
          <option value="11" <?php if($ls_mes=="11"){ print "selected";} ?>>NOVIEMBRE</option>
          <option value="12" <?php if($ls_mes=="12"){ print "selected";} ?>>DICIEMBRE</option>
        </select>
        </label>
      </td>
      <td width="66" height="22" style="text-align:right"><strong>A&ntilde;o</strong></td>
      <td width="182" style="text-align:left"><label><input name="txtano" type="text" class="sin-borde" id="txtano" value="<?php print $ls_ano;?>" size="6" maxlength="4" readonly>
      </label></td>
    </tr>
    <tr>
      <td height="72" colspan="4" align="center"><table width="511" border="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td height="22" colspan="3"><input name="rdproben" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_limpiarproben();" checked>
            Proveedor 
            <input name="rdproben" type="radio" class="sin-borde" value="radiobutton" onClick="javascript: ue_limpiarproben();">
            Beneficiario</td>
          </tr>
        <tr>
          <td width="205" height="22"><div align="right">Desde
            <input name="txtprovbendesde" type="text" id="txtprovbendesde" size="20" readonly style="text-align:center">
              <a href="javascript: ue_catalogo_proben('DESDE');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></div></td>
          <td width="83" style="text-align:right">Hasta</td>
          <td width="215"><input name="txtprovbenhasta" type="text" id="txtprovbenhasta" size="20" readonly style="text-align:center">
            <a href="javascript: ue_catalogo_proben('HASTA');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Solicitud
              <input name="txtnumsoldes" type="text" id="txtnumsoldes" size="20" style="text-align:center">
              <a href="javascript: ue_catalogo_solicitud();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></div></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>

      </table></td>
    </tr>
    <tr>
      <td height="52" colspan="4" align="center">      <div align="left">
        <table width="511" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="22" colspan="5"><strong>Rango de Fecha </strong></td>
            </tr>
          <tr>
            <td width="136" style="text-align:right">Desde</td>
            <td width="101"><input name="txtfecdes" type="text" id="txtfecdes"  onKeyPress="ue_formatofecha(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true"></td>
            <td width="76" style="text-align:right">Hasta</td>
            <td width="95"><div align="left">
                <input name="txtfechas" type="text" id="txtfechas"  onKeyPress="ue_formatofecha(this,'/',patron,true);" size="15" maxlength="10"  datepicker="true">
            </div></td>
            <td width="101">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center"><div align="right"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" border="0">Buscar Documentos</a></div></td>
    </tr>
    <br>
	<tr>
	 <td colspan="4" align="center">
  		<div id="resultados" align="center"></div>	</td>
    </tr>
  </table>
	<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</form>      
</body>
<script language="JavaScript">
var patron = new Array(2,2,4)
function ue_limpiarproben()
{
	f=document.form1;
	f.txtprovbendesde.value="";
	f.txtprovbenhasta.value="";
}

function ue_catalogo_proben(ls_tipo)
{
	f=document.form1;
	valido=true;
	if (f.rdproben[0].checked)
	   {
	     if (ls_tipo=="DESDE")
		    {
			  window.open("sigesp_cat_prov_general.php?obj=txtprovbendesde","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,location=no,resizable=yes,dependent=yes");
			}
		 else
		    {
			  window.open("sigesp_cat_prov_general.php?obj=txtprovbenhasta","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,location=no,resizable=yes,dependent=yes");
			}
	   }
	if (f.rdproben[1].checked)
	   {
	     if (ls_tipo=="DESDE")
		    {
			  window.open("sigesp_cat_bene_general.php?obj=txtprovbendesde","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,location=no,resizable=yes,dependent=yes");
			}
		 else
		    {
			  window.open("sigesp_cat_bene_general.php?obj=txtprovbenhasta","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,location=no,resizable=yes,dependent=yes");
			}
	   }
}

function ue_catalogo_solicitud()
{
	window.open("sigesp_scb_cat_solicitudpago.php?tipo=REPDES","_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,location=no,resizable=yes,dependent=yes");
}

function ue_search()
{
	f=document.form1;
	// Cargamos las variables para pasarlas al AJAX
	if (f.rdproben[0].checked)
	   {
		tipproben="P";
	   }
	else
	{
		if(f.rdproben[1].checked)
		{
			tipproben="B";
		}		
	}
	
	codprobendes = f.txtprovbendesde.value;
	codprobenhas = f.txtprovbenhasta.value;
	fecdes		 = f.txtfecdes.value;
	fechas		 = f.txtfechas.value;
	numsol		 = f.txtnumsoldes.value;
	mes			 = f.cmbmes.value;
	anio		 = f.txtano.value;
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
	ajax.send("catalogo=RETENCIONIVA&tipproben="+tipproben+"&codprobendes="+codprobendes+"&codprobenhas="+codprobenhas+
			  "&fecdes="+fecdes+"&fechas="+fechas+"&mes="+mes+"&anio="+anio+"&numsol="+numsol);
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

function ue_aceptar(ls_numcom,ls_anofiscal,ls_mesfiscal,ls_codsujret,ls_nomsujret,ls_dirsujret,ls_rifsujret,ls_codret,ls_estcmpret)
{
	if(f.rdproben[0].checked)
	{
		ls_probene="P";
	}
	else
	{
		if(f.rdproben[1].checked)
		{
			ls_probene="B";
		}
	}
	opener.ue_cargardatos(ls_numcom,ls_anofiscal,ls_mesfiscal,ls_codsujret,ls_nomsujret,ls_dirsujret,ls_rifsujret,ls_probene,ls_estcmpret);
	parametros="";
	parametros=parametros+"&numcom="+ls_numcom;
	proceso="LOADDETALLECMP";
	if(parametros!="")
	{
		// Div donde se van a cargar los resultados
		divgrid = opener.document.getElementById("detalles");
		divlocal = document.getElementById("resultados");
		// Instancia del Objeto AJAX
		ajax=objetoAjax();
		// Pagina donde están los métodos para buscar y pintar los resultados
		ajax.open("POST","class_folder/sigesp_scb_c_modcmpret_ajax.php",true);
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
	ajax.send("proceso="+proceso+""+parametros);
	}
}
</script>
</html>