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
$io_fun_activo=new class_funciones_activos("../");
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_d_materiales.php",$ls_permisos,$la_seguridad,$la_permisos);
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
   		global $ls_codtipest, $ls_dentipest, $ls_existe; 
		$ls_codtipest="";
		$ls_dentipest="";
		if(array_key_exists("existe",$_POST))
		{
			$ls_existe=$_POST["existe"];
		}
		else
		{
			$ls_existe="FALSE";			
		}			
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
<title >Definici&oacute;n Estructuras Predominantes del Inmueble</title>
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
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>    
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="580">&nbsp;</td>
  </tr>
</table>
<?php
    require_once("class_funciones_activos.php");
	$io_fac= new class_funciones_activos("../");
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("sigesp_saf_c_materiales.php");
	$io_material= new sigesp_saf_c_materiales();
	require_once("../shared/class_folder/sigesp_include.php");
	$in=     new sigesp_include();
	$con= 	 $in->uf_conectar();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="NUEVO";		
	}//FIN DEL IF
	
	if ($ls_operacion=="GUARDAR")
	{
		$ls_codtipest=$io_fac->uf_obtenervalor("txtcodtipest","");
		$ls_dentipest=$io_fac->uf_obtenervalor("txtdentipest","");
		$ls_existe=$io_fac->uf_obtenervalor("existe",""); 
		$ls_valido=$io_material->guardar($ls_codtipest, $ls_dentipest, $ls_existe, $la_seguridad);
		if ($ls_valido)
		{
			$ls_existe="TRUE";
		}
		else
		{
			$ls_existe="FALSE";
		}
	}
	elseif ($ls_operacion=="ELIMINAR")
	{
		$ls_codtipest=$io_fac->uf_obtenervalor("txtcodtipest","");		
		$ls_existe=$io_fac->uf_obtenervalor("existe","");
		if  ($ls_existe=="TRUE")
		{ 
			$ls_valido=$io_material->uf_elimina_materiales($ls_codtipest, $la_seguridad);
			if ($ls_valido)
			{
				$ls_existe="FALSE";
				uf_limpiarvariables();
			}
			else
			{
				$ls_existe="TRUE";
				$ls_dentipest=$io_fac->uf_obtenervalor("txtdentipest","");
			}
		}// FIN DEL IF
	}
	elseif($ls_operacion=="NUEVO")
	{
		uf_limpiarvariables();		
	}
?>

<p>&nbsp;</p>
<div align="center">
  <p>&nbsp;</p>
  <table width="596" height="209" border="0" class="formato-blanco">
    <tr>
      <td width="588" height="203"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="588" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td colspan="2" class="titulo-ventana">Definici&oacute;n de Estructuras Predominantes de los Inmuebles </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="111" height="19">&nbsp;</td>
                    <td width="408">                  </tr>
                  <tr class="formato-blanco">
                    <td height="29"><div align="right">C&oacute;digo</div></td>
                    <td><input name="txtcodtipest" type="text" id="txtcodtipest" value="<?php print $ls_codtipest?>" size="8" maxlength="4" style="text-align:center " onBlur="ue_rellenarcampo(this,4);"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="32"><div align="right">Denominaci&oacute;n</div></td>
                    <td><input name="txtdentipest" type="text" id="txtdentipest" value="<?php print $ls_dentipest?>" size="50" onKeyUp="javascript: ue_validarcomillas(this);" onBlur="javascript: ue_validarcomillas(this);">                    </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="18">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="18">&nbsp;</td>
                    <td><input name="btncomp" type="button" class="boton" id="btncomp" value="      Materiales Predominantes       " height="100px"  onClick="ue_abrircomponente();"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td>&nbsp;</td>
</tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table>
              <input name="operacion" type="hidden" id="operacion">
			  <input name="existe" type="hidden" id="existe" value="<? print $ls_existe ?>">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones 
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_saf_cat_materiales.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
	li_codtipest=f.txtcodtipest.value;	
	li_dentipest=f.txtdentipest.value;
	if((li_cambiar==1)&&(li_incluir==1))
	{
		if ((li_codtipest!="")&&(li_dentipest!=""))
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_saf_d_materiales.php";
			f.submit();
		}
		else
		{
			alert("Debe completar TODOS los Datos");
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
		if(confirm("¿Seguro desea eliminar el Material?"))
		{
			f.operacion.value="ELIMINAR";
			f.action="sigesp_saf_d_materiales.php";
			f.submit();
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

function ue_abrircomponente()
{
	f=document.form1;
	codtipest=ue_validarvacio(f.txtcodtipest.value);
	existe=ue_validarvacio(f.existe.value);	
	if (existe=="TRUE")
	{
		window.open("sigesp_saf_d_comp_material.php?codtipest="+codtipest+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=680,height=300,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("El Material debe estar grabado");	
	}
}

</script> 
</html>