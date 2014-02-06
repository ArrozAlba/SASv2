<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.formulario.submit();";
		print "</script>";		
	}
	require_once("class_folder/class_funciones_sob.php");
	$io_fun_sob=new class_funciones_sob();
	$ls_tipo=$io_fun_sob->uf_obtenertipo();
 	$ls_modalidad=$_SESSION["la_empresa"]["estmodest"];
	$li_len1=0;
	$li_len2=0;
	$li_len3=0;
	$li_len4=0;
	$li_len5=0;
	$ls_titulo="";
	$lb_valido=$io_fun_sob->uf_loadmodalidad(&$li_len1,&$li_len2,&$li_len3,&$li_len4,&$li_len5,&$ls_titulo);
	unset($io_fun_sob);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Detalles Presupuestarios</title>
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
</head>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sob.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<body>
<form name="formulario" method="post" action="">
  <table width="650" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td height="20" colspan="2" class="titulo-ventana">Detalles Presupuestarios </td>
    </tr>
  </table>
<br>
<table width="650" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="116" height="22">&nbsp;</td>
        <td width="528" height="22"><input name="codestpro1" type="hidden" id="codestpro1" size="30" maxlength="25">
          <input name="codestpro2" type="hidden" id="codestpro2" size="30" maxlength="25">
          <input name="codestpro3" type="hidden" id="codestpro3" size="30" maxlength="25">
          <input name="codestpro4" type="hidden" id="codestpro4" size="30" maxlength="25">
          <input name="codestpro5" type="hidden" id="codestpro5" size="30" maxlength="25">
        <input name="estmodest" type="hidden" id="estmodest" value="<?php print $ls_modalidad; ?>">
        <input name="origen" type="hidden" id="origen" value="ASIGNACION"></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro1"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro1" type="text" id="txtcodestpro1" size="<?php print ($li_len1+10); ?>" maxlength="<?php print $li_len1; ?>" style="text-align:center" readonly>
          <a href="javascript:ue_estructura1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
          <input name="txtdenestpro1" type="text" class="sin-borde" id="txtdenestpro1" size="53" readonly>
          <input name="estcla" type="hidden" id="estcla">
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro2"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro2" type="text" id="txtcodestpro2" size="<?php print ($li_len2+10); ?>" maxlength="<?php print $li_len2; ?>" style="text-align:center" readonly>
          <a href="javascript:ue_estructura2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
          <input name="txtdenestpro2" type="text" class="sin-borde" id="txtdenestpro2" size="53" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro3"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro3" type="text" id="txtcodestpro3" size="<?php print ($li_len3+10); ?>" maxlength="<?php print $li_len3; ?>"  style="text-align:center" readonly>
          <a href="javascript:ue_estructura3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
          <input name="txtdenestpro3" type="text" class="sin-borde" id="txtdenestpro3" size="53" readonly>
        </div></td>
      </tr>
<?php if($ls_modalidad=="1") // Por Proyecto
	  {?>
 				<input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="00">
 				<input name="txtdenestpro4" type="hidden" id="txtdenestpro4" value="">
 				<input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="00">
 				<input name="txtdenestpro5" type="hidden" id="txtdenestpro5" value="">
<?php }
	  else
	  {?>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro4"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro4" type="text" id="txtcodestpro4" size="<?php print ($li_len4+10); ?>" maxlength="<?php print $li_len4; ?>"  style="text-align:center" readonly>
          <a href="javascript:ue_estructura4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
          <input name="txtdenestpro4" type="text" class="sin-borde" id="txtdenestpro4" size="53" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right"><?php print $_SESSION["la_empresa"]["nomestpro5"];?></div></td>
        <td height="22"><div align="left">
          <input name="txtcodestpro5" type="text" id="txtcodestpro5" size="<?php print ($li_len5+10); ?>" maxlength="<?php print $li_len5; ?>"  style="text-align:center" readonly>
          <a href="javascript:ue_estructura5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Buscar"></a>
          <input name="txtdenestpro5" type="text" class="sin-borde" id="txtdenestpro5" size="53" readonly>
        </div></td>
      </tr>
<?php }?>
      <tr>
        <td height="22"><div align="right">Cuenta</div></td>
        <td height="22"><div align="left">
          <input name="txtspgcuenta" type="text" id="txtspgcuenta" style="text-align:center" size="27" maxlength="25" readonly>
          <a href="javascript:ue_cuentasspg();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas de Gastos"></a> 
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" size="55">
          <input name="sccuenta" type="hidden" id="sccuenta">
          <input name="disponibilidad" type="hidden" id="disponibilidad">
          <input name="estvaldis" type="hidden" id="estvaldis" value="<?php print $_SESSION["la_empresa"]["estvaldis"]?>">
        </div></td>
      <tr>
        <td height="22">&nbsp;</td>
        <td height="22"><div align="left"><a href="javascript: ue_aceptar();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar Detalle Presupuestario" width="15" height="15" border="0">Aceptar</a>
		  <a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools15/eliminar.gif" width="15" height="15" border="0" alt="Cancelar Registro de Detalle Presupuestario">Cancelar</a></div></td>
    <tr>
      <td height="22">&nbsp;</td>
      <td>&nbsp;</td>
  </table> 
	<p>
    </p>
</form>      
</body>
<script language="JavaScript">
function ue_cerrar()
{
	close();
}

function ue_aceptar()
{
	f=document.formulario;
	disponible=f.disponibilidad.value;
	disponible=ue_formato_calculo(disponible);
	codestpro1=f.txtcodestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	codestpro3=f.txtcodestpro3.value;
	codestpro4=f.txtcodestpro4.value;
	codestpro5=f.txtcodestpro5.value;
	cuenta=f.txtspgcuenta.value;
	denominacion=f.txtdenominacion.value;
	estcla=f.estcla.value;
	if(estcla=="A")
	{estcla="ACCION";}
	else
	{estcla="PROYECTO"}
	opener.ue_cargarcuenta(cuenta,denominacion,codestpro1,codestpro2,codestpro3,codestpro4,codestpro5,disponible,estcla);	
}

function ue_estructura1()
{
	window.open("sigesp_cat_estpre1.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no,top=60,left=60");
	f=document.formulario;
	f.estcla.value="";
	f.txtcodestpro2.value="";
	f.txtcodestpro3.value="";
	f.txtcodestpro4.value="";
	f.txtcodestpro5.value="";
	f.txtdenestpro2.value="";
	f.txtdenestpro3.value="";
	f.txtdenestpro4.value="";
	f.txtdenestpro5.value="";
	f.txtspgcuenta.value="";
	f.txtdenominacion.value="";
	f.sccuenta.value="";
	f.disponibilidad.value=0;
	f.txtmonto.value="0,00";
}

function ue_estructura2()
{
	f=document.formulario;
	codestpro1=f.txtcodestpro1.value;
	estcla=f.estcla.value;
	denestpro1=f.txtdenestpro1.value;
	if(codestpro1!="")
	{
		pagina="sigesp_cat_estpre2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&estcla="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no,top=60,left=60");
		f.txtcodestpro3.value="";
		f.txtcodestpro4.value="";
		f.txtcodestpro5.value="";
		f.txtdenestpro3.value="";
		f.txtdenestpro4.value="";
		f.txtdenestpro5.value="";
		f.txtspgcuenta.value="";
		f.txtdenominacion.value="";
		f.sccuenta.value="";
		f.disponibilidad.value=0;
		f.txtmonto.value="0,00";
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura3()
{
	f=document.formulario;
	codestpro1=f.txtcodestpro1.value;
	estcla=f.estcla.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	estmodest=f.estmodest.value;
	if(estmodest==2)
	{
		if((codestpro1!="")&&(codestpro2!=""))
		{
			pagina="sigesp_sob_estpre3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&estcla="+estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no,top=60,left=60");
		}
		else
		{
			alert("Seleccione la Estructura de nivel Anterior");
		}
	}
	else
	{
		pagina="sigesp_cat_estpre3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&estcla="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no,top=60,left=60");
	}
	f.txtcodestpro4.value="";
	f.txtcodestpro5.value="";
	f.txtdenestpro4.value="";
	f.txtdenestpro5.value="";
	f.txtspgcuenta.value="";
	f.txtdenominacion.value="";
	f.sccuenta.value="";
	f.disponibilidad.value=0;
	f.txtmonto.value="0,00";
}

function ue_estructura4()
{
	f=document.formulario;
	codestpro1=f.txtcodestpro1.value;
	estcla=f.estcla.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	codestpro3=f.txtcodestpro3.value;
	denestpro3=f.txtdenestpro3.value;
	if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!=""))
	{
    	pagina="sigesp_cat_estpre4.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+
			   "&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&estcla="+estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no,top=60,left=60");
		f.txtcodestpro5.value="";
		f.txtdenestpro5.value="";
		f.txtspgcuenta.value="";
		f.txtdenominacion.value="";
		f.sccuenta.value="";
		f.disponibilidad.value=0;
		f.txtmonto.value="0,00";
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

function ue_estructura5()
{
	f=document.formulario;
	estcla=f.estcla.value;
	codestpro1=f.txtcodestpro1.value;
	denestpro1=f.txtdenestpro1.value;
	codestpro2=f.txtcodestpro2.value;
	denestpro2=f.txtdenestpro2.value;
	codestpro3=f.txtcodestpro3.value;
	denestpro3=f.txtdenestpro3.value;
	codestpro4=f.txtcodestpro4.value;
	denestpro4=f.txtdenestpro4.value;
	pagina="sigesp_cat_estpre5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+
		   "&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4+"&denestpro4="+denestpro4+"&estcla="+estcla;
	window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no,top=60,left=60");
	f.txtspgcuenta.value="";
	f.txtdenominacion.value="";
	f.sccuenta.value="";
	f.disponibilidad.value=0;
	f.txtmonto.value="0,00";
}

function ue_cuentasspg()
{
	f=document.formulario;
	estmodest=f.estmodest.value;
	if(estmodest==1) // Si es por proyecto
	{
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!=""))
		{
			pagina="sigesp_cat_cuentasspg.php";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=610,height=400,resizable=yes,location=no,top=60,left=60");
		}
		else
		{
			alert("Seleccione la Estructura Presupuestaria");
		}
	}
	else
	{
		codestpro1=f.txtcodestpro1.value;
		codestpro2=f.txtcodestpro2.value;
		codestpro3=f.txtcodestpro3.value;
		codestpro4=f.txtcodestpro4.value;
		codestpro5=f.txtcodestpro5.value;
		if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(codestpro4!="")&&(codestpro5!=""))
		{
			pagina="sigesp_sob_cat_cuentasspg.php";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione la Estructura Presupuestaria");
		}
	}
	f.txtspgcuenta.value="";
	f.txtdenominacion.value="";
	f.sccuenta.value="";
	f.disponibilidad.value=0;
}
</script>
</html>