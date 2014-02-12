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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_r_asignacion_comp_ran.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$lb_valido=$io_sno->uf_crear_sessionnomina();		
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	$ls_desnom=$_SESSION["la_nomina"]["desnom"];
	$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
	$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
	$ls_subnom=$_SESSION["la_nomina"]["subnom"];	
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
<title >Reporte de Asinación por Componente y Rango Militar</title>
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
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7">
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
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="28" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title='Imprimir' alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="28"><div align="center"><a href="javascript: ue_openexcel();"></a><a href="javascript: ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" title="Excel" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="28"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="28"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="243"><div align="center"></div></td>
    <td class="toolbar" width="71"><div align="center"></div></td>
    <td class="toolbar" width="71"><div align="center"></div></td>
    <td class="toolbar" width="71"><div align="center"></div></td>
    <td class="toolbar" width="71"><div align="center"></div></td>
    <td class="toolbar" width="71"><div align="center"></div></td>
    <td class="toolbar" width="68"><div align="center"></div></td>
    <td class="toolbar" width="3">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="550" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Reporte de Asignaciones por Componente y Rango Militar </td>
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
<?php if($ls_subnom=='1')
{
?>
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de subnomina </td>
        </tr>
        <tr>
          <td height="20"><div align="right"> Desde </div></td>
          <td height="20"><input name="txtcodsubnomdes" type="text" id="txtcodsubnomdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarsubnominadesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
          <td height="20"><div align="right">Hasta </div></td>
          <td height="20"><input name="txtcodsubnomhas" type="text" id="txtcodsubnomhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarsubnominahasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
        </tr>
<?php } 
?>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de Componente</td>
        </tr>
        <tr>
          <td width="108" height="22"><div align="right"> Desde </div></td>
          <td width="121"><div align="left">
            <input name="txtcodcomdes" type="text" id="txtcodcomdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarcomponentedesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="96"><div align="right">Hasta </div></td>
          <td width="165"><div align="left">
            <input name="txtcodcomhas" type="text" id="txtcodcomhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarcomponentehasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de Rango </td>
        </tr>
        <tr>
          <td width="108" height="22"><div align="right"> Desde </div></td>
          <td width="121"><div align="left">
            <input name="txtcodrandes" type="text" id="txtcodrandes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarrangodesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="96"><div align="right">Hasta </div></td>
          <td width="165"><div align="left">
            <input name="txtcodranshas" type="text" id="txtcodranhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarrangohasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="20" colspan="4" class="titulo-celdanew">&nbsp;</td>
          </tr>
        <tr>
           <td height="22"><div align="right">Reporte Detallado</div></td>
          <td colspan="2"><label>
            <input type="checkbox" name="checkboxdet" value="">
          </label></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="22"></td>
          <td colspan="3"><div align="right">
		    <input name="reporte" type="hidden" id="reporte" value="<?php print $ls_reporte;?>">
 		   <input name="subnom" type="hidden" id="subnom" value="<?php print $ls_subnom;?>">
		  </div></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
}

function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		codcomdes=f.txtcodcomdes.value;
		codcomhas=f.txtcodcomhas.value;
		codrandes=f.txtcodrandes.value;
		codranhas=f.txtcodranhas.value;
		if (f.checkboxdet.checked==true)
		{
			reporte="sigesp_sno_rpp_asignacion_componente.php";
		}
		else
		{
			reporte="sigesp_sno_rpp_asignacion_com_ran.php";
		}
		tiporeporte=f.cmbbsf.value;
		subnom=f.subnom.value;
		subnomdes="";
		subnomhas="";
		if(subnom=='1')
		{
			subnomdes=f.txtcodsubnomdes.value;
			subnomhas=f.txtcodsubnomhas.value;
		}			
		pagina="reportes/"+reporte+"?codcomdes="+codcomdes+"&codcomhas="+codcomhas+"&codrandes="+codrandes+"&codranhas="+codranhas;
		pagina=pagina+"&tiporeporte="+tiporeporte;
		pagina=pagina+"&subnomdes="+subnomdes+"&subnomhas="+subnomhas;
		window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_openexcel()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		codcomdes=f.txtcodcomdes.value;
		codcomhas=f.txtcodcomhas.value;
		codrandes=f.txtcodrandes.value;
		codranhas=f.txtcodranhas.value;
		if ((codrandes!='')||(codranhas!=''))
		{
			alert ('Para este reporte el rango no se toma en cuenta solo los componentes y categorias.');
		}
		if (f.checkboxdet.checked==true)
		{
			alert ('No puede emitir este reporte en Excel');
		}
		else
		{
			reporte="sigesp_sno_rpp_asignacion_com_ran_excel.php";			
			tiporeporte=f.cmbbsf.value;
			subnom=f.subnom.value;
			subnomdes="";
			subnomhas="";
			if(subnom=='1')
			{
				subnomdes=f.txtcodsubnomdes.value;
				subnomhas=f.txtcodsubnomhas.value;
			}			
			pagina="reportes/"+reporte+"?codcomdes="+codcomdes+"&codcomhas="+codcomhas+"&codrandes="+codrandes+"&codranhas="+codranhas;
			pagina=pagina+"&tiporeporte="+tiporeporte;
			pagina=pagina+"&subnomdes="+subnomdes+"&subnomhas="+subnomhas;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}

}

function ue_buscarcomponentedesde()
{
	window.open("sigesp_snorh_cat_componente.php?tipo=componentedes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarcomponentehasta()
{
	f=document.form1;
	codcomdes=f.txtcodcomdes.value;
	if (codcomdes!="")
	{
		window.open("sigesp_snorh_cat_componente.php?tipo=componentehas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un componente desde");
	}	
}

function ue_buscarrangodesde()
{
    f=document.form1;
	codcomdes=f.txtcodcomdes.value;
	codcomhas=f.txtcodcomhas.value;
	window.open("sigesp_snorh_cat_rango.php?tipo=rangodes&codcomdes="+codcomdes+"&codcomhas="+codcomhas,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarrangodesde()
{
    f=document.form1;
	codcomdes=f.txtcodcomdes.value;
	codcomhas=f.txtcodcomhas.value;
	if ((codcomdes!="")&&(codcomhas!=""))
	{
		window.open("sigesp_snorh_cat_rango.php?tipo=rangodes&codcomdes="+codcomdes+"&codcomhas="+codcomhas,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("debe seleccionar el Componente");
	}
}

function ue_buscarrangohasta()
{
    f=document.form1;
	codcomdes=f.txtcodcomdes.value;
	codcomhas=f.txtcodcomhas.value;
	if ((codcomdes!="")&&(codcomhas!=""))
	{
		window.open("sigesp_snorh_cat_rango.php?tipo=rangohas&codcomdes="+codcomdes+"&codcomhas="+codcomhas,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("debe seleccionar el Componente");
	}
}


function ue_buscarsubnominadesde()
{
	window.open("sigesp_snorh_cat_subnomina.php?tipo=reportedesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarsubnominahasta()
{
	window.open("sigesp_snorh_cat_subnomina.php?tipo=reportehasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
</script> 
</html>