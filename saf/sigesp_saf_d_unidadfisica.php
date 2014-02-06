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
	require_once("class_funciones_activos.php");
	$io_fun_activo=new class_funciones_activos();
	//$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_p_entregaunidad.php",$ls_permisos,$la_seguridad,$la_permisos);
	$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_d_activos.php",$ls_permisos,$la_seguridad,$la_permisos);
	require_once("sigesp_saf_c_activo.php");
	$ls_codemp = $_SESSION["la_empresa"]["codemp"];
	$io_saf_tipcat= new sigesp_saf_c_activo();
	$ls_rbtipocat=$io_saf_tipcat->uf_select_valor_config($ls_codemp);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	function uf_limpiarvariables()
	{
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
		global $ls_coduniadm,$ls_denuniadm,$ls_estatus;
		
		$ls_coduniadm="";
		$ls_denuniadm="";
		$ls_estatus="";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Unidades Fisicas de Activos Fijos</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		var alt  = window.event.altKey;
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Activos Fijos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
  <?php 
    if ($ls_rbtipocat == 1) 
    {
   ?>
   <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_csc.js"></script></td>
  <?php 
    }
	elseif ($ls_rbtipocat == 2)
	{
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_cgr.js"></script></td>
  <?php 
	}
	else
	{
   ?>
	<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php 
	}
   ?>
   <!-- <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td> -->
  </tr>
  <tr>
    <td height="13" colspan="8" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" width="20" title="Buscar" height="20" class="sin-borde"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" class="sin-borde"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("sigesp_saf_c_unidadfisica.php");
	$io_saf= new sigesp_saf_c_unidadfisica();
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$io_keygen= new sigesp_c_generar_consecutivo();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_codusureg=$_SESSION["la_logusr"];
	$ls_operacion=$io_fun_activo->uf_obteneroperacion();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
			$ls_coduniadm= $io_keygen->uf_generar_numero_nuevo("SAF","saf_unidadadministrativa","coduniadm","SAF",10,"","codemp",$ls_codemp);
		break;
		
		case "GUARDAR";
			$ls_coduniadm= $io_fun_activo->uf_obtenervalor("txtcoduniadm","");
			$ls_denuniadm= $io_fun_activo->uf_obtenervalor("txtdenuniadm","");
			$ls_estatus= $io_fun_activo->uf_obtenervalor("hidstatus","");
			$lb_valido=$io_saf->uf_saf_procesar_unidadfisica($ls_coduniadm,$ls_denuniadm,$ls_estatus,$la_seguridad);
			if ($lb_valido)
			{
				uf_limpiarvariables();
			}
		break;
		case "GUARDAR";
			$ls_coduniadm= $io_fun_activo->uf_obtenervalor("txtcoduniadm","");
			$ls_denuniadm= $io_fun_activo->uf_obtenervalor("txtdenuniadm","");
			$ls_estatus= $io_fun_activo->uf_obtenervalor("hidstatus","");
			$lb_valido=$io_saf->uf_saf_delete_unidadfisica($ls_coduniadm,$la_seguridad);
			if ($lb_valido)
			{
				uf_limpiarvariables();
			}
		break;
	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
  <table width="599" height="159" border="0" class="formato-blanco">
    <tr>
      <td width="620" height="153"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="584" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td colspan="2" class="titulo-ventana">Unidades Fisicas de Activos Fijos </td>
  </tr>
  <tr class="formato-blanco">
    <td width="113" height="19">&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">C&oacute;digo</div></td>
    <td height="22">        <input name="txtcoduniadm" type="text" id="txtcoduniadm" value="<?php print $ls_coduniadm; ?>" maxlength="15" onBlur="javascript: ue_rellenarcampo(this,'15')" style="text-align:center ">      
    <input name="hidstatus" type="hidden" id="hidstatus"></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Denominaci&oacute;n</div></td>
    <td height="22"><div align="left">
      <input name="txtdenuniadm" type="text" id="txtdenuniadm" value="<?php print $ls_denuniadm; ?>" size="50">
    </div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22">&nbsp;</td>
    <td height="22">&nbsp;</td>
  </tr>
</table>
<input name="operacion" type="hidden" id="operacion">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_buscar()
{
	window.open("sigesp_saf_cat_unidadfisica.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscarunidad()
{
	window.open("sigesp_catdinamic_unidad.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscarresponsableactual()
{
	window.open("sigesp_saf_cat_personal.php?destino=responsableactual","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_catapersonalnew()
{
	f=document.form1;
	codres=f.txtcodresact.value;
	if(codres=="")
	{
		alert("Debe seleccionar el responsable actual");
	}
	else
	{
		window.open("sigesp_saf_cat_personal.php?destino=responsablenuevo","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_saf_d_unidadfisica.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="")&&(li_incluir==1))||(lb_status=="C")&&(li_cambiar==1))
	{
		coduniadm=f.txtcoduniadm.value;
		denuniadm=f.txtdenuniadm.value;
		if((coduniadm!="")&&(denuniadm!=""))
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_saf_d_unidadfisica.php";
			f.submit();
		}
		else
		{
			alert("Debe completar los datos");
		}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>