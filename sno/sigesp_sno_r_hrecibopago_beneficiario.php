<?php
    session_start();   
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_r_hrecibopago_beneficiario.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$lb_valido=$io_sno->uf_crear_sessionhnomina();		
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
	$li_adelanto=$_SESSION["la_nomina"]["adenom"];
	$li_divcon=$_SESSION["la_nomina"]["divcon"];
	$li_tippernom=$_SESSION["la_nomina"]["tippernom"];
	$ls_recibo=$io_sno->uf_select_config("SNO","REPORTE","RECIBO_PAGO","sigesp_sno_rpp_recibopago.php","C");
	unset($io_sno);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Reporte Recibo de Pago</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table>
	</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_hnomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title='Imprimir' alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_hnomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="670" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="620" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Reporte Recibo de Pago de Beneficiario </td>
        </tr>
        <tr style="display:none">
          <td height="20"><div align="right">Reporte en</div></td>
          <td height="20"><div align="left">
            <select name="cmbbsf" id="cmbbsf">
              <option value="0" selected>Bs.</option>
              <option value="1">Bs.F.</option>
            </select>
          </div></td>
          <td height="20">&nbsp;</td>
          <td height="20">&nbsp;</td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de Personal </td>
          </tr>
        <tr>
          <td width="133" height="22"><div align="right"> Desde </div></td>
          <td width="112"><div align="left">
            <input name="txtcodperdes" type="text" id="txtcodperdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarpersonaldesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="119"><div align="right">Hasta </div></td>
          <td width="121"><div align="left">
            <input name="txtcodperhas" type="text" id="txtcodperhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarpersonalhasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
		<tr>
          <td height="22" colspan="5" class="titulo-celdanew">Intervalo de Beneficiario </td>
          </tr><tr>
          <td height="22"><div align="right"> Desde </div></td>
          <td><div align="left">
              <input name="txtcodbenedes" type="text" id="txtcodbenedes" size="13" maxlength="10" value="" readonly>
          <a href="javascript: ue_buscarbenedesde();"><img  src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td><div align="right">Hasta </div></td>
          <td width="129"><div align="left">
              <input name="txtcodbenehas" type="text" id="txtcodbenehas" value="" size="13" maxlength="10" readonly>
          <a href="javascript: ue_buscarbenehasta();"><img  src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">&nbsp;</td>
          </tr>
<tr>
          <td height="22"><div align="right">Ubicaci&oacute;n F&iacute;sica</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodubifis" type="text" id="txtcodubifis" size="7" maxlength="4" readonly>
            <a href="javascript: ue_buscarubicacionfisica();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesubifis" type="text" class="sin-borde" id="txtdesubifis" size="60" maxlength="100" readonly>
</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">
            <div align="right">Estado</div>
          </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodest" type="text" id="txcodest" value="" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarestado();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesest" type="text" class="sin-borde" id="txtdesest" value="" size="60" maxlength="50" readonly>
            <input name="txtcodpai" type="hidden" id="txtcodpai" value="058">
</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Municipio</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodmun" type="text" id="txtcodmun" value="" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarmunicipio();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesmun" type="text" class="sin-borde" id="txtdesmun" value="" size="60" maxlength="50" readonly>
</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Parroquia</div></td>
          <td colspan="3"><div align="left">
            <input name="txtcodpar" type="text" id="txtcodpar" value="" size="6" maxlength="3" readonly>
            <a href="javascript: ue_buscarparroquia();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdespar" type="text" class="sin-borde" id="txtdespar" value="" size="60" maxlength="50" readonly>
</div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">Quitar conceptos en cero </div></td>
          <td><div align="left">
            <input name="chkconceptocero" type="checkbox" class="sin-borde" id="chkconceptocero" value="1" checked>
          </div></td>
          <td><div align="right">Mostrar Concepto P2</div></td>
          <td>            <div align="left">
            <input name="chkconceptop2" type="checkbox" class="sin-borde" id="chkconceptop2" value="1">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Incluir conceptos reporte</div></td>
          <td><div align="left">
            <input name="chkconceptoreporte" type="checkbox" class="sin-borde" id="chkconceptoreporte" value="1">
          </div></td>
          <td><div align="right">Usar t&iacute;tulo del concepto </div></td>
          <td><div align="left">
            <input name="chktituloconcepto" type="checkbox" class="sin-borde" id="chktituloconcepto" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Unidad Administrativa </div></td>
          <td colspan="3"><div align="left">
            <input name="txtcoduniadm" type="text" id="txtcoduniadm" size="19" maxlength="16" readonly>
            <a href="javascript: ue_buscaruniadm();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdenuniadm" type="text" class="sin-borde" id="txtdenuniadm" size="40" maxlength="30" readonly>
          </div></td>
          </tr>
<?php if((($li_adelanto==1)||($li_divcon==1))&&($li_tippernom==2)) { ?>		  
        <tr>
          <td height="22"><div align="right">Quincena</div></td>
          <td>        <div align="left">
            <select name="cmdquincena" id="cmdquincena">
              <option value="1">Primera Quincena</option>
              <option value="2">Segunda Quincena</option>
              <option value="3" selected>Mes Completo</option>
            </select>
          </div>        </tr>
<?php 	}
	else
	{
       print "<input name='cmdquincena' type='hidden' id='cmdquincena' value='3'>";
	}
?>		  
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew"><div align="right" class="titulo-celdanew">Ordenado por </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo del Personal </div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Apellido del Personal</div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="2">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Nombre del Personal</div></td>
          <td colspan="3"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="3">
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3"> <div align="right">
		              <input name="recibo" type="hidden" id="recibo" value="<?php print $ls_recibo;?>">
		</div></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
</body>
<script language="javascript">
function ue_cerrar()
{
	location.href = "sigespwindow_blank_hnomina.php";
}

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		codperdes=f.txtcodperdes.value;
		codperhas=f.txtcodperhas.value;
		tiporeporte=f.cmbbsf.value;
		if(codperdes<=codperhas)
		{
			codbendes=f.txtcodbenedes.value;
			codbenhas=f.txtcodbenehas.value;
			if (codbendes<=codbenhas)
			{
				conceptocero="";
				conceptop2="";
				tituloconcepto="";
				conceptoreporte="";
				coduniadm=f.txtcoduniadm.value;
				quincena=f.cmdquincena.value;
				if(f.rdborden[0].checked)
				{
					orden="1";
				}
				if(f.rdborden[1].checked)
				{
					orden="2";
				}
				if(f.rdborden[2].checked)
				{
					orden="3";
				}
				if(f.chkconceptocero.checked)
				{
					conceptocero=1;
				}
				if(f.chkconceptop2.checked)
				{
					conceptop2=1;
				}
				if(f.chktituloconcepto.checked)
				{
					tituloconcepto=1;
				}
				if(f.chkconceptoreporte.checked)
				{
					conceptoreporte=1;
				}
				codubifis=f.txtcodubifis.value;
				codpai=f.txtcodpai.value;
				codest=f.txtcodest.value;
				codmun=f.txtcodmun.value;
				codpar=f.txtcodpar.value;
				pagina="reportes/sigesp_sno_rpp_recibopago_beneficiario.php?codperdes="+codperdes+"&codperhas="+codperhas+"&conceptocero="+conceptocero+"";
				pagina=pagina+"&conceptop2="+conceptop2+"&tituloconcepto="+tituloconcepto+"&conceptoreporte="+conceptoreporte;
				pagina=pagina+"&quincena="+quincena+"&coduniadm="+coduniadm+"&orden="+orden+"&tiporeporte="+tiporeporte;
				pagina=pagina+"&codbendes="+codbendes+"&codbenhas="+codbenhas;
				pagina=pagina+"&codubifis="+codubifis+"&codpai="+codpai+"&codest="+codest+"&codmun="+codmun+"&codpar="+codpar;
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				alert("El rango del beneficiario está erroneo");
			}
		}
		else
		{
			alert("El rango del personal está erroneo");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_buscarpersonaldesde()
{
	window.open("sigesp_sno_cat_hpersonalnomina.php?tipo=reprecpagdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonalhasta()
{
	window.open("sigesp_sno_cat_hpersonalnomina.php?tipo=reprecpaghas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
function ue_buscaruniadm()
{
	window.open("sigesp_snorh_cat_uni_ad.php?tipo=reprecpag","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarubicacionfisica()
{
	window.open("sigesp_snorh_cat_ubicacionfisica.php?tipo=pagonomina","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarestado()
{
	f=document.form1;
	f.txtcodubifis.value="";
    f.txtdesubifis.value="";
	codpai=ue_validarvacio(f.txtcodpai.value);
	if(codpai!="")
	{
		window.open("sigesp_snorh_cat_estado.php?codpai="+codpai+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais.");
	}
}

function ue_buscarmunicipio()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	codest=ue_validarvacio(f.txtcodest.value);
	if((codpai!="")||(codest!=""))
	{
		window.open("sigesp_snorh_cat_municipio.php?codpai="+codpai+"&codest="+codest+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais y un estado.");
	}
}

function ue_buscarparroquia()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	codest=ue_validarvacio(f.txtcodest.value);
	codmun=ue_validarvacio(f.txtcodmun.value);
	if((codpai!="")||(codest!="")||(codmun!=""))
	{
		window.open("sigesp_snorh_cat_parroquia.php?codpai="+codpai+"&codest="+codest+"&codmun="+codmun+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais, un estado y un municipio.");
	}
}


function ue_buscarbenedesde()
{
   codper="";
   f=document.form1;
   codperdes=f.txtcodperdes.value;
   codperhas=f.txtcodperhas.value;
   if((codperdes!="")&&(codperhas!=""))
   {
   		window.open("sigesp_snorh_cat_beneficiario.php?tipo=benedes&codper="+codper+"&codperdes="+codperdes+"&codperhas="+codperhas+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   }
   else
	{
		alert("Debe seleccionar un rango de personal.");
	}
}

function ue_buscarbenehasta()
{
	f=document.form1;
	codperdes=f.txtcodperdes.value;
    codperhas=f.txtcodperhas.value;
	if((f.txtcodbenedes.value!="")&&(codperdes!="")&&(codperhas!=""))
	{
		window.open("sigesp_snorh_cat_beneficiario.php?tipo=benehas&codper="+codper+"&codperdes="+codperdes+"&codperhas="+codperhas+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un Beneficiario desde.");
	}
}


</script> 
</html>