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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_r_vacaciones.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$ls_sueint=trim($io_sno->uf_select_config("SNO","NOMINA","DENOMINACION SUELDO INTEGRAL","C",""));
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
<title >Reporte de Vacaciones</title>
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
        </table>
	 </td>
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
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_print();"><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
     <?php

	if (isset($_GET["valor"]))
	{ $ls_valor=$_GET["valor"];	}
	else
	{ $ls_valor="";}
	
	if ($ls_valor!='srh')
	{
	    print ('<td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title=Salir alt="Salir" width="20" height="20" border="0"></a></div></td>' );	   
	}
	else
	{
	 print ('<td class="toolbar" width="25"><div align="center"><a href="javascript: close();"><img src="../shared/imagebank/tools20/salir.gif" title=Salir alt="Salir" width="20" height="20" border="0"></a></div></td>' );	
	}
	
  ?>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="600" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="4" class="titulo-ventana">Reporte de Vacaciones </td>
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
          <td height="20" colspan="4" class="titulo-celdanew">Intervalo de Personal </td>
          </tr>
        <tr>
          <td width="144" height="22"><div align="right"> Desde </div></td>
          <td width="121"><div align="left">
            <input name="txtcodperdes" type="text" id="txtcodperdes" size="13" maxlength="10" value="" readonly>
            <a href="javascript: ue_buscarpersonaldesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
          <td width="98"><div align="right">Hasta </div></td>
          <td width="177"><div align="left">
            <input name="txtcodperhas" type="text" id="txtcodperhas" value="" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarpersonalhasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
        </tr>
        <tr>
          <td height="22" colspan="4" class="titulo-celdanew">Vacaciones</td>
          </tr>
        <tr>
          <td height="22"><div align="right">Vencidas</div></td>
          <td>
            <div align="left">
              <input name="chkvencida" type="checkbox" class="sin-borde" id="chkvencida" value="1">
              </div>          </td>
          <td><div align="right">Programadas</div></td>
          <td><div align="left">
            <input name="chkprogramada" type="checkbox" class="sin-borde" id="chkprogramada" value="2">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">En Vacaci&oacute;n </div></td>
          <td>
            <div align="left">
              <input name="chkvacacion" type="checkbox" class="sin-borde" id="chkvacacion" value="3">
              </div></td>
          <td>
            <div align="right">Disfrutadas</div></td>
          <td><div align="left">
            <input name="chkdisfrutada" type="checkbox" class="sin-borde" id="chkdisfrutada" value="4">
          </div></td>
        </tr>
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
          <td height="22"><input name="hidsueint" type="hidden" id="hidsueint" value="<?php print $ls_sueint;?>"></td>
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
		codperdes=f.txtcodperdes.value;
		codperhas=f.txtcodperhas.value;
		tiporeporte=f.cmbbsf.value;
		sueint=f.hidsueint.value;
		if(codperdes<=codperhas)
		{
			vencida="";
			programada="";
			vacacion="";
			disfrutada="";
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
			if(f.chkvencida.checked)
			{
				vencida=1;
			}
			if(f.chkprogramada.checked)
			{
				programada=1;
			}
			if(f.chkvacacion.checked)
			{
				vacacion=1;
			}
			if(f.chkdisfrutada.checked)
			{
				disfrutada=1;
			}

			pagina="reportes/sigesp_snorh_rpp_vacaciones.php?codperdes="+codperdes+"&codperhas="+codperhas+"&vencida="+vencida;
			pagina=pagina+"&programada="+programada+"&vacacion="+vacacion+"&disfrutada="+disfrutada+"&orden="+orden+"&tiporeporte="+tiporeporte+"&sueint="+sueint;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
			alert("El rango del personal está erroneo");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}

function ue_buscarpersonaldesde()
{
	window.open("sigesp_snorh_cat_personal.php?tipo=repvacperdes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonalhasta()
{
	window.open("sigesp_snorh_cat_personal.php?tipo=repvacperhas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
</script> 
</html>