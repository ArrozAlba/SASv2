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
   		global $ls_codite,$ls_denite,$disabled,$readonly;
		
		$ls_codite="";
		$ls_denite="";
		$disabled="disabled";
		$readonly="readonly";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definici&oacute;n de Secci&oacute;n</title>
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
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" title="Imprimir"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" title="Eliminar" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("sigesp_saf_c_grupo.php");
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_saf  = new sigesp_saf_c_grupo();
	$io_msg  = new class_mensajes();
	$io_func = new class_funciones();
	$ls_operacion= $io_fun_activo->uf_obtenervalor("operacion4","NUEVO");
	$ls_codgru= $io_fun_activo->uf_obtenervalor("txtcodgru","");
	$ls_dengru= $io_fun_activo->uf_obtenervalor("txtdengru","");
	$ls_codsubgru= $io_fun_activo->uf_obtenervalor("txtcodsubgru","");
	$ls_densubgru= $io_fun_activo->uf_obtenervalor("txtdensubgru","");
	$ls_codsec= $io_fun_activo->uf_obtenervalor("txtcodsec","");
	$ls_densec= $io_fun_activo->uf_obtenervalor("txtdensec","");
	$ls_codite= $io_fun_activo->uf_obtenervalor("txtcodite","");
	$ls_denite= $io_fun_activo->uf_obtenervalor("txtdenite","");
	switch($ls_operacion)
	{
		case "NUEVO":
			uf_limpiarvariables();
			$ls_longitud= 3;
			$ls_codite=$io_saf->uf_saf_generar_codigo($ls_codgru,$ls_codsubgru,"saf_item","codite");
			$ls_codite=$io_func->uf_cerosizquierda($ls_codite,$ls_longitud);
		break;
		case "GUARDAR":
			$lb_valido=$io_saf->uf_saf_guardar_item($ls_codgru,$ls_codsubgru,$ls_codsec,$ls_codite,$ls_denite,$la_seguridad);
			uf_limpiarvariables();
		break;
		case "ELIMINAR":
			$lb_valido=$io_saf->uf_saf_delete_item($ls_codgru,$ls_codsubgru,$ls_codsec,$ls_codite,$la_seguridad);
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
  <table width="601" height="278" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="601" height="278" valign="top"><form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<p>&nbsp;</p>
<table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="3">Definici&oacute;n de Item</td>
              </tr>
              <tr class="formato-blanco">
                <td height="18">&nbsp;</td>
                <td width="463" height="2" colspan="2">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" align="right">Grupo</td>
                <td height="22" colspan="2" align="left">
                  <input name="txtcodgru" type="text" id="txtcodgru"  style="text-align:center" size="4" maxlength="3" value="<?php print  $ls_codgru?>" readonly="">
                <input name="txtdengru" type="text" class="sin-borde" id="txtdengru" value="<?php print $ls_dengru?>" size="60" maxlength="80" readonly=""></td>
              </tr>
              <tr class="formato-blanco">
                <td width="101" height="22"><div align="right" >
                    <p>Sub Grupo </p>
                </div></td>
                <td height="22" colspan="2"><div align="left" >
                    <input name="txtcodsubgru" type="text" id="txtcodsubgru" style="text-align:center " value="<?php print $ls_codsubgru?>" size="4" maxlength="3" <?php print $readonly?> readonly>
                    <input name="txtdensubgru" type="text" class="sin-borde" id="txtdensubgru" style="text-align:left" value="<?php print $ls_densubgru?>" size="60" maxlength="100" readonly>
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Secci&oacute;n</div></td>
                <td height="22" colspan="2"><input name="txtcodsec" type="text" id="txtcodsec" style="text-align:center " value="<?php print $ls_codsec; ?>" size="4" maxlength="3" readonly>
                <input name="txtdensec" type="text" class="sin-borde" id="txtdensec" value="<?php print $ls_densec; ?>" size="60" maxlength="100"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">
                  <p>C&oacute;digo</p>
                </div></td>
                <td height="22" colspan="2"><div align="left">
                  <input name="txtcodite" type="text" id="txtcodite" value="<?php print $ls_codite; ?>" size="4" maxlength="3" style="text-align:center"  onBlur="javascript:rellenar_cad(this.value,3,'cod')" <?php print $readonly?> >
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="30"><div align="right">Denominaci&oacute;n</div></td>
                <td colspan="2" rowspan="2" align="left"><textarea name="txtdenite" cols="82" rows="2" id="txtdenite" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz[]{}!¡@#$%&/(),.:; ¿?');"><?php print $ls_denite; ?></textarea></td>
              </tr>
              <tr class="formato-blanco">
                <td height="13">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td height="20">&nbsp;</td>
                <td height="20" colspan="2" align="left"><input name="botestpro2" type="button" class="boton" id="botestpro2" onClick="javascript: uf_volver();" value="Volver a Seccion" >
                <input name="buttonir" type="button" class="boton" id="buttonir" onClick="javascript: uf_ir();" value="Ir a Item" <?php print $disabled?>></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20">&nbsp;</td>
                <td height="20" colspan="2" align="left">&nbsp;</td>
              </tr>
          </table>
            <p align="center">
            <input name="operacion4" type="hidden" id="operacion4">
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
		f.operacion4.value ="NUEVO";
		f.action="sigesp_saf_d_item.php";
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
		f.operacion4.value ="GUARDAR";
		f.action="sigesp_saf_d_item.php";
		f.submit();
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
			f.operacion4.value ="ELIMINAR";
			f.action="sigesp_saf_d_item.php";
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
		codgru=f.txtcodgru.value;
		dengru=f.txtdengru.value;
		codsubgru=f.txtcodsubgru.value;
		densubgru=f.txtdensubgru.value;
		codsec=f.txtcodsec.value;
		densec=f.txtdensec.value;
		window.open("sigesp_saf_cat_item.php?txtcodgru="+codgru+"&txtdengru="+dengru+"&txtcodsubgru="+codsubgru+"&txtdensubgru="+densubgru+"&txtcodsec="+codsec+"&txtdensec="+densec+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
function uf_volver()
{
	f=document.form1;
	f.action="sigesp_saf_d_secciones.php";
	f.submit();
}

//Funcion de relleno con ceros a un textfield
function rellenar_cad(cadena,longitud,campo)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	for(i=1;i<=total;i++)
	{
		cadena_ceros=cadena_ceros+"0";
	}
	cadena=cadena_ceros+cadena;
	
	if(campo=="cod")
	{
		document.form1.txtcodsec.value=cadena;
	}
}
</script>
</html>