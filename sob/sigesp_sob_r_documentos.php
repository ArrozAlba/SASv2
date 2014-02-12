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
	require_once("class_folder/class_funciones_sob.php");
	$io_fun_sob=new class_funciones_sob();
	$io_fun_sob->uf_load_seguridad("SNR","sigesp_snorh_r_constanciatrabajo.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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
<title >Reporte Documentos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sob.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="773" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
		<table width="774" border="0" align="center" cellpadding="0" cellspacing="0">
			
              <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Obras </td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </table>
	</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript:ue_print_word();"><img src="../shared/imagebank/tools20/word.JPG" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="20"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="1"><div align="center"></div></td>
    <td class="toolbar" width="8"><div align="center"></div></td>
    <td class="toolbar" width="1"><div align="center"></div></td>
    <td class="toolbar" width="14"><div align="center"></div></td>
    <td class="toolbar" width="16"><div align="center"></div></td>
    <td class="toolbar" width="16"><div align="center"></div></td>
    <td class="toolbar" width="635">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_sob->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_sob);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Reporte de Documentos </td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">Modelo de Documento </td>
        </tr>
        <tr class="formato-blanco">
          <td height="20" ><div align="right">Documento</div></td>
          <td height="20" colspan="3" ><div align="left">
            <input name="txtcoddoc" type="text" id="txtcoddoc" size="6" maxlength="3" value="" readonly>
            <a href="javascript: ue_buscardocumento();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdesdoc" type="text" class="sin-borde" id="txtdesdoc" size="50" maxlength="120" readonly>
            <input name="txtnomrtf" type="hidden" id="txtnomrtf">
          </div></td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de Contratistas </td>
          </tr>
        <tr>
          <td width="135" height="22"><div align="right"> Desde </div></td>
          <td width="149">
            <div align="left">
              <input name="txtcodcondes" type="text" id="txtcodcondes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarcontratistadesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="89"><div align="right">Hasta </div></td>
          <td width="225"><div align="left">
            <input name="txtcodconhas" type="text" id="txtcodconhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarcontratistahasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22" colspan="4" class="titulo-celdanew">&nbsp;</td>
          </tr>
        <tr>
          <td height="22"><div align="right">Mostrar Fecha y Hora </div></td>
          <td colspan="3"><label>
            <input name="chkfecha" type="checkbox" class="sin-borde" id="chkfecha" value="1">
          </label></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td colspan="3"> <div align="right"></div></td>
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
	location.href = "sigespwindow_blank.php";
}

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		coddoc=f.txtcoddoc.value;
		if(coddoc!="")
		{
			codcondes=f.txtcodcondes.value;
			codconhas=f.txtcodconhas.value;
			fecha=0;
			if(f.chkfecha.checked)
			{
				fecha=1;
			}
			if(codcondes<=codconhas)
			{
				pagina="reportes/sigesp_sob_rpp_documentos.php?codcondes="+codcondes+"&codconhas="+codconhas+"&coddoc="+coddoc;
				pagina=pagina+"&fecha="+fecha;
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				alert("El rango de Contratistas está erroneo");
			}
		}
		else
		{
			alert("Debe Seleccionar un Documento");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}

function ue_print_word()
{
	f=document.form1;
	if(f.txtnomrtf.value!="")
	{
		f=document.form1;
		li_imprimir=f.imprimir.value;
		if(li_imprimir==1)
		{	
			coddoc=f.txtcoddoc.value;
			if(coddoc!="")
			{
				codcondes=f.txtcodcondes.value;
				codconhas=f.txtcodconhas.value;
				fecha=0;
				if(f.chkfecha.checked)
				{
					fecha=1;
				}
				if(codcondes<=codconhas)
				{
					pagina="reportes/sigesp_sob_rpp_documentos.php?codcondes="+codcondes+"&codconhas="+codconhas+"&coddoc="+coddoc;
					pagina=pagina+"&fecha="+fecha;
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
				}
				else
				{
					alert("El rango de Contratistas está erroneo");
				}
			}
			else
			{
				alert("Debe Seleccionar un Documento");
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operación");
		}		
	}
	else
	{
		alert("Esta Documento no tiene plantilla rtf.");
	}		
}

function ue_buscardocumento()
{
	window.open("sigesp_sob_cat_documento.php?tipo=repdoc","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarcontratistadesde()
{
	window.open("sigesp_cat_contratista.php?tipo=repdocdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarcontratistahasta()
{
	window.open("sigesp_cat_contratista.php?tipo=repdochas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
</script> 
</html>