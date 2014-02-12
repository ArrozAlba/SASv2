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
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_r_constanciatrabajo.php",$ls_permisos,$la_seguridad,$la_permisos);
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
<title >Reporte Constancia de Trabajo</title>
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
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table>	 </td>
  </tr>
   <?php

	if (isset($_GET["valor"]))
	{ $ls_valor=$_GET["valor"];	}
	else
	{ $ls_valor="";}
	
	if ($ls_valor!='srh')
	{
	   print ('<tr>');
	   print ('<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>' );
	   print ('</tr>');
	}
	
	
  ?>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title='Imprimir' alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript:ue_print_word();"><img src="../shared/imagebank/tools20/word.JPG" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <?php

	if (isset($_GET["valor"]))
	{ $ls_valor=$_GET["valor"];	}
	else
	{ $ls_valor="";}
	
	if ($ls_valor!='srh')
	{
	    print ('<td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>' );	}
	else
	{
	 print ('<td class="toolbar" width="25"><div align="center"><a href="javascript: close();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>' );	}
	
  ?>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="650" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Reporte Constancia de Trabajo</td>
        </tr>
        <tr style="display:none">
          <td height="20"><div align="right">Reporte en
            
          </div></td>
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
          <td height="20" colspan="4" class="titulo-celdanew">Modelo de Constancia </td>
        </tr>
        <tr class="formato-blanco">
          <td height="20" ><div align="right">Constancia</div></td>
          <td height="20" colspan="3" ><div align="left">
            <input name="txtcodcont" type="text" id="txtcodcont" size="6" maxlength="3" value="" readonly>
            <a href="javascript: ue_buscarconstancia();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtdescont" type="text" class="sin-borde" id="txtdescont" size="50" maxlength="120" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">N&oacute;mina</td>
          </tr>
        <tr class="formato-blanco">
          <td height="20" ><div align="right">N&oacute;mina</div></td>
          <td height="20" colspan="3" ><div align="left">
            <input name="txtcodnom" type="text" id="txtcodnom" size="8" maxlength="4">
            <a href="javascript: ue_buscarnomina();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" name="personal" width="15" height="15" border="0" id="personal"></a>
            <input name="txtdesnom" type="text" class="sin-borde" id="txtdesnom" size="50" maxlength="120">
            <input name="txtrac" type="hidden" id="txtrac">
            <input name="txtnomrtf" type="hidden" id="txtnomrtf">
            <input name="txtmesactual" type="hidden" id="txtmesactual">
            <input name="txtanocurnom" type="hidden" id="txtanocurnom">
			<input name="txtparametros" type="hidden" id="txtparametros">
          </div></td>
          </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de Personal </td>
          </tr>
        <tr>
          <td width="135" height="22"><div align="right"> Desde </div></td>
          <td width="149">
            <div align="left">
              <input name="txtcodperdes" type="text" id="txtcodperdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarpersonaldesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="89"><div align="right">Hasta </div></td>
          <td width="225"><div align="left">
            <input name="txtcodperhas" type="text" id="txtcodperhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarpersonalhasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22" colspan="4" class="titulo-celdanew">Personal en lote </td>
        </tr>
        <tr>
          <td height="22" colspan="4"><div align="center">
		  <img src="../shared/imagebank/mas.gif" width="9" height="17" border="0">
		   <a href="javascript:uf_delete_all();"> Buscar Personal por lote</a></div></td>
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
		codcont=f.txtcodcont.value;
		codnom=f.txtcodnom.value;
		rac=f.txtrac.value;		
		if((codcont!="")&&(codnom!=""))
		{
			codperdes=f.txtcodperdes.value;
			codperhas=f.txtcodperhas.value;
			tiporeporte=f.cmbbsf.value;
			mesactual=f.txtmesactual.value;
			anocurnom=f.txtanocurnom.value
			fecha=0;
			parametro=0;
			if(f.chkfecha.checked)
			{
				fecha=1;
			}
			if(codperdes<=codperhas)
			{
			    if (codperdes=="")
				{
					parametro=f.txtparametros.value;
				}
				pagina="reportes/sigesp_snorh_rpp_constanciatrabajo.php?codperdes="+codperdes+"&codperhas="+codperhas+"&tiporeporte="+tiporeporte;
				pagina=pagina+"&codcont="+codcont+"&codnom="+codnom+"&rac="+rac+"&fecha="+fecha+"&mesactual="+mesactual+"&anocurnom="+anocurnom+"&parametro="+parametro;
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			}
			else
			{
				alert("El rango del personal está erroneo");
			}
		}
		else
		{
			alert("Debe Seleccionar una Constancia y una Nómina");
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
		li_imprimir=f.imprimir.value;
		if(li_imprimir==1)
		{	
			codcont=f.txtcodcont.value;
			codnom=f.txtcodnom.value;
			rac=f.txtrac.value;
			mesactual=f.txtmesactual.value;
			anocurnom=f.txtanocurnom.value;
			parametro=0;		    
			if((codcont!="")&&(codnom!=""))
			{
				codperdes=f.txtcodperdes.value;
				codperhas=f.txtcodperhas.value;
				fecha=0;
				if(f.chkfecha.checked)
				{
					fecha=1;
				}
				if(codperdes<=codperhas)
				{
					if (codperdes=="")
					{
						parametro=f.txtparametros.value;
					}
					pagina="reportes/sigesp_snorh_rpp_constanciatrabajo_word.php?codperdes="+codperdes+"&codperhas="+codperhas;
					pagina=pagina+"&codcont="+codcont+"&codnom="+codnom+"&rac="+rac+"&fecha="+fecha+"&mesactual="+mesactual+"&anocurnom="+anocurnom+"&parametro="+parametro;
					window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
				}
				else
				{
					alert("El rango del personal está erroneo");
				}
			}
			else
			{
				alert("Debe Seleccionar una Constancia y una Nómina");
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operación");
		}		
	}
	else
	{
		alert("Esta Constancia no tiene plantilla rtf.");
	}		
}

function ue_buscarconstancia()
{
	window.open("sigesp_snorh_cat_constanciatrabajo.php?tipo=repconttrab","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarnomina()
{
	window.open("sigesp_snorh_cat_nomina.php?tipo=repconttrab","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonaldesde()
{
	f=document.form1;
	codnom=f.txtcodnom.value;
	if(codnom!="")
	{
		window.open("sigesp_snorh_cat_personal.php?codnom="+codnom+"&tipo=repconttrabdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe Seleccionar una nómina.");
	}
}

function ue_buscarpersonalhasta()
{
	f=document.form1;
	codnom=f.txtcodnom.value;
	if((codnom!="")&&(f.txtcodperdes.value!=""))
	{
		window.open("sigesp_snorh_cat_personal.php?codnom="+codnom+"&tipo=repconttrabhas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe Seleccionar una nómina y un Personal desde.");
	}
}

function uf_delete_all()
{   
	f=document.form1;
	codnom=f.txtcodnom.value;
	codperdes=f.txtcodperdes.value;
	if (codperdes=="")
	{
		if (codnom!="")
		{
			window.open("sigesp_snorh_sel_catpersonal.php?codnom="+codnom,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
		}
		else
		{
			alert("Debe Seleccionar una nómina.");
		}
	}
	else
	{
		alert("Ha seleccionado un Rango de Personal, NO puede utilizar esta opción");
		f.txtcodperdes.value="";
		f.txtcodperhas.value="";
		
	}
}
</script> 
</html>