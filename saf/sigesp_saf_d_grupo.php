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
	$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_d_grupo.php",$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $ls_codigo,$ls_denominacion,$disabled,$readonly;
		
		$ls_codigo="";
		$ls_denominacion="";
		$disabled="disabled";
		$readonly="readonly";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definici&oacute;n de Grupo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
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
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
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
<style type="text/css">
<!--
.Estilo15 {color: #6699CC}
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
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo15">Sistema de Activos Fijos</td>
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
    <td height="20" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" title="Imprimir" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" title="Salir" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("sigesp_saf_c_grupo.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("../shared/class_folder/class_funciones.php");
	$io_saf=  new sigesp_saf_c_grupo();
	$io_msg=  new class_mensajes();
	$io_func= new class_funciones();
	$ls_operacion= $io_fun_activo->uf_obteneroperacion();
	$ls_codigo= $io_fun_activo->uf_obtenervalor("txtcodgru","");
	$ls_denominacion= $io_fun_activo->uf_obtenervalor("txtdengru","");
	switch ($ls_operacion)
	{
		case "NUEVO":
			uf_limpiarvariables();
			$ls_longitud= 3;
			$ls_codigo= $io_saf->uf_saf_generar_codigo("","","saf_grupo","codgru");
			$ls_codigo=$io_func->uf_cerosizquierda($ls_codigo,$ls_longitud);
		break;
		case "GUARDAR":
		   $lb_valido=$io_saf->uf_saf_guardar_grupo($ls_codigo,$ls_denominacion,$la_seguridad);
		   uf_limpiarvariables();
		break;
		case "ELIMINAR":
		break;
		case "":
			$lb_valido=$io_saf->uf_saf_delete_grupo($ls_codigo,$la_seguridad);
			if($lb_valido)
			{
				$io_msg->message("El registro fue eliminado");
				uf_limpiarvariables();
			}
			else
			{
				$io_msg->message("No se pudo eliminar el registro");
				uf_limpiarvariables();
			}
		break;
	}
?>
<p>&nbsp;</p>
<div align="center">
  <table width="601" height="242" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="601" height="242" valign="top"><form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
          <p>&nbsp;</p>
          <table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr class="titulo-ventana">
              <td height="22" colspan="3">Definici&oacute;n de Grupo</td>
            </tr>
            <tr class="formato-blanco">
              <td height="18">&nbsp;</td>
              <td width="463" colspan="2">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td width="101" height="23"><div align="right" >
                  <p>C&oacute;digo</p>
              </div></td>
              <td colspan="2"><div align="left" >
                  <input name="txtcodgru" type="text" id="txtcodgru" style="text-align:center " value="<?php print $ls_codigo?>" size="4" maxlength="3"<?php print $readonly ?> >
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="31"><div align="right">Denominaci&oacute;n</div></td>
              <td colspan="2" rowspan="2"><div align="left">
                  <textarea name="txtdengru" cols="70" rows="2" id="txtdengru" style="text-align:left" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz[]{}!¡@#$%&/(),.:; ¿?');"><?php print $ls_denominacion?></textarea>
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="13">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="25"><div align="right"></div></td>
              <td height="25" colspan="2" align="left"><p>
                  <label> </label>
                  <br>
              </p></td>
            </tr>
            <tr class="formato-blanco">
              <td height="20">&nbsp;</td>
              <td height="20" colspan="2" align="left">
                <div align="LEFT">
                <input name="buttonir" type="button" class="boton" id="buttonir" onClick="javascript: uf_ir();" value="Ir a Sub Grupos" <?php print $disabled?>>
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="20"><div align="right" ></div></td>
              <td colspan="2"><div align="left" ></div></td>
            </tr>
          </table>
          <p align="center"><input name="operacion" type="hidden" id="operacion">
            <input name="hidstatus" type="hidden" id="hidstatus">
          </p>
      </form></td>
    </tr>
  </table>
</div>
</body>
<script language="javascript">

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value ="NUEVO";
		f.action="sigesp_saf_d_grupo.php";
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
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status=="")&&(li_incluir==1))
	{
		codigo=f.txtcodgru.value;
		denominacion=f.txtdengru.value
		if((codigo!="")&&(denominacion!=""))
		{
			f.operacion.value ="GUARDAR";
			f.action="sigesp_saf_d_grupo.php";
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

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(confirm("¿Seguro desea eliminar el Activo?"))
		{
			f.operacion.value ="ELIMINAR";
			f.action="sigesp_saf_d_grupo.php";
			f.submit();
		}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_saf_cat_grupo.php?tipo","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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

function uf_ir()
{
	f=document.form1;
	f.action="sigesp_saf_d_subgrupo.php";
	f.submit();
}

</script>
</html>