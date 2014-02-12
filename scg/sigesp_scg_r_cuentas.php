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
	require_once("class_funciones_scg.php");
	$io_fun_scg=new class_funciones_scg();
	$io_fun_scg->uf_load_seguridad("SCG","sigesp_scg_r_cuentas.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
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
<title>Listado de Cuentas </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
.Estilo1 {color: #6699CC}
-->
</style></head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
   <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Contabilidad Patrimonial</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript:ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" alt="Excel" title="Excel" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scg->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_scg);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="535" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
   
    <tr>
      <td width="435"></td>
    </tr>
    <tr class="titulo-ventana">
      <td width="435" height="13" colspan="4" align="center"><div align="center">Listado de Cuentas Contables </div></td>
    </tr>
    <tr>
      <td height="13" colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="center"><table width="520" border="0" cellspacing="0" class="formato-blanco">
        <tr class="pie-pagina">
          <td colspan="4"><div align="center" class="titulo-celdanew"><strong>Cuentas Contables </strong></div></td>
          </tr>
        <tr>
          <td width="84" height="22"><div align="right">Desde</div></td>
          <td width="170"><div align="left">
            <input name="txtcuentadesde" type="text" id="txtcuentadesde"  size="22" readonly style="text-align:center">
            <a href="javascript:cat_desde()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a>
          </div></td>
          <td width="88"><div align="right">Hasta</div></td>
          <td width="168"><div align="left">
            <input name="txtcuentahasta" type="text" id="txtcuentahasta" size="22" readonly style="text-align:center">
            <a href="javascript:cat_hasta()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4" align="center"><div align="right">     <span class="Estilo1">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></div></td>
    </tr>
  </table>
 
</table>
</p>
</form>      
</body>
<script language="JavaScript">
function ue_search()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		ls_cuentadesde  = f.txtcuentadesde.value;
		ls_cuentahasta  = f.txtcuentahasta.value;
		pagina="reportes/sigesp_scg_rpp_cuentas.php?cuentadesde="+ls_cuentadesde+"&cuentahasta="+ls_cuentahasta;
		window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}

function ue_openexcel()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		ls_cuentadesde  = f.txtcuentadesde.value;
		ls_cuentahasta  = f.txtcuentahasta.value;
		pagina="reportes/sigesp_scg_rpp_cuentas_excel.php?cuentadesde="+ls_cuentadesde+"&cuentahasta="+ls_cuentahasta;
		window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación");
   	}		
}

function cat_desde()
{
	f=document.form1;
	window.open("sigesp_cat_scgall.php?obj=txtcuentadesde","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function cat_hasta()
{
	f=document.form1;
	window.open("sigesp_cat_scgall.php?obj=txtcuentahasta","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
   
function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
