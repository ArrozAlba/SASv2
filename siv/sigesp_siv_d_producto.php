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
require_once("class_funciones_inventario.php");
$io_fun_inv=new class_funciones_inventario;
$io_fun_inv->uf_load_seguridad("SIV","sigesp_siv_d_producto.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_codemp = $_SESSION["la_empresa"]["codemp"];

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codseg,$ls_codclase,$ls_codfam,$ls_codprod,$ls_desprod; 
		$ls_codseg="";
		$ls_codclase="";
		$ls_codfam="";
		$ls_codprod="";
		$ls_desprod="";	
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
<title >Definici&oacute;n de Producto</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../saf/js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../saf/js/funciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <!--<tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Inventario </td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
    </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td> 
  </tr>-->
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="580">&nbsp;</td>
  </tr>
</table>
<?php
    require_once("class_funciones_inventario.php");
    $io_fun_inv=new class_funciones_inventario;
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("sigesp_siv_c_producto.php");
	$io_producto= new sigesp_siv_c_producto();
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
		$ls_codfam=$_POST["txtcodfam"];
		$ls_codprod =$_POST["txtcodprod"];
		$ls_codseg=$_POST["txtcodseg"];
		$ls_codclase=$_POST["txtcodclase"];
		$ls_codsegm=$_POST["hidcodseg"];
		$ls_desprod=$_POST["txtdesprod"];
	}
	else
	{
		$ls_operacion="NUEVO";	
		$ls_codfam="";
		$ls_desprod="";
		$ls_codseg="";
		$ls_codclase="";
		$ls_codsegm="";
		$ls_codprod="";
	}//FIN DEL IF
	$ls_codsegmento=$io_fun_inv->uf_obtenervalor_get("codseg","");
	$ls_codfami=$io_fun_inv->uf_obtenervalor_get("codfam",""); 
	$ls_codigoclase=$io_fun_inv->uf_obtenervalor_get("codclase",""); 
	
	if ($ls_operacion=="GUARDAR")
	{
		$ls_codseg=$io_fun_inv->uf_obtenervalor("txtcodseg","");
		$ls_codfam=$io_fun_inv->uf_obtenervalor("txtcodfam","");
		$ls_codprod=$io_fun_inv->uf_obtenervalor("txtcodprod","");
		$ls_codclase=$io_fun_inv->uf_obtenervalor("txtcodclase","");
		$ls_existe=$io_fun_inv->uf_obtenervalor("existe","");
		$ls_desprod=$io_fun_inv->uf_obtenervalor("txtdesprod","");
		$lb_valido=$io_producto->uf_siv_select_producto($ls_empresa,$ls_codseg,$ls_codfam,$ls_codclase,$ls_codprod);
		if($lb_valido)
		{ 
		   $ls_valido=$io_producto->uf_actualizar_producto($ls_empresa,$ls_codseg,$ls_codfam,$ls_desprod,$ls_codclase,$ls_codprod,
		                                                   $la_seguridad);
			if ($ls_valido)
			{
				$io_msg->message("El producto fue actualizado.");
				$ls_codseg="";
				$ls_codclase="";
				$ls_codfam="";
				$ls_codprod="";
				$ls_desprod="";							
			}
			else
			{
				$io_msg->message("No se pudo actualizar el producto.");
				$ls_codseg="";
				$ls_codclase="";
				$ls_codfam="";
				$ls_codprod="";
				$ls_desprod="";
			}
		}
		else
		{
		   $ls_valido=$io_producto->uf_guardar_producto($ls_empresa,$ls_codseg,$ls_codfam,$ls_desprod,$ls_codclase,$ls_codprod,$la_seguridad);
			if ($ls_valido)
			{
				$io_msg->message("El producto fue registrado.");
				$ls_codseg="";
				$ls_codclase="";
				$ls_codfam="";
				$ls_codprod="";
				$ls_desprod="";							
			}
			else
			{
				$io_msg->message("No se pudo registar el producto.");
				$ls_codseg="";
				$ls_codclase="";
				$ls_codfam="";
				$ls_codprod="";
				$ls_desprod="";
			}
		}
		
	}
	elseif ($ls_operacion=="ELIMINAR")
	{
		$ls_codseg=$io_fun_inv->uf_obtenervalor("txtcodseg","");
		$ls_codfam=$io_fun_inv->uf_obtenervalor("txtcodfam","");
		$ls_codprod=$io_fun_inv->uf_obtenervalor("txtcodprod","");
		$ls_codclase=$io_fun_inv->uf_obtenervalor("txtcodclase","");
		$ls_desprod=$io_fun_inv->uf_obtenervalor("txtdesprod","");		
		$ls_existe=$io_fun_inv->uf_obtenervalor("existe","");
		if  ($ls_existe=="TRUE")
		{ 
			$ls_valido=$io_producto->uf_elimina_producto($ls_empresa,$ls_codseg,$ls_codfam,$ls_codclase,$ls_codprod,$la_seguridad);
			if ($ls_valido)
			{
				$ls_existe="FALSE";
				uf_limpiarvariables();
				$io_msg->message("El producto fue eliminado.");
			}
			else
			{
				$ls_existe="TRUE";
				$io_msg->message("El producto no pudo ser eliminado.");
				uf_limpiarvariables();
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
	$io_fun_inv->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_inv);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="588" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td colspan="4" class="titulo-ventana">Definici&oacute;n del Producto para el Catalogo de Productos y Servicios Est&aacute;ndar de  las Naciones Unidas </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="111" height="19">&nbsp;</td>
                    <td colspan="3">                  </tr>
					<?
					  if (($ls_codsegmento!="")&&($ls_codfami!="")&&($ls_codigoclase!=""))
					  { 
					     $ls_codseg=$ls_codsegmento; 
						 $ls_codfam=$ls_codfami; 
						 $ls_codclase=$ls_codigoclase;
						 $ls_codprod=$ls_codigoclase;
					  }else
					  {
					     $ls_codseg="";
						 $ls_codfam="";
						 $ls_codprod="";
					  }
					
					?>
                  <tr class="formato-blanco">
                    <td height="25"><div align="right">C&oacute;digo del Segmento</div></td>
                    <td colspan="3"><input name="txtcodseg" type="text" id="txtcodseg" value="<?php print $ls_codseg?>" size="4" maxlength="2" style="text-align:center " onBlur="ue_rellenarcampo(this,2);" ></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="25"><div align="right">C&oacute;digo de la Familia </div></td>
                    <td colspan="3"><input name="txtcodfam" type="text" id="txtcodfam" value="<?php print $ls_codfam?>" size="6" maxlength="4" style="text-align:center " onBlur="ue_rellenarcampo(this,2);"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="25"><div align="right">C&oacute;digo de la Clase </div></td>
                    <td colspan="3"><input name="txtcodclase" type="text" id="txtcodclase" value="<?php print $ls_codclase?>" size="8" maxlength="6" style="text-align:center " onBlur="ue_rellenarcampo(this,2);"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="25"><div align="right">C&oacute;digo del Producto </div></td>
                    <td colspan="3"><input name="txtcodprod" type="text" id="txtcodprod" value="<?php print $ls_codprod?>" size="10" maxlength="8" style="text-align:center " onBlur="ue_rellenarcampo(this,2);"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="32"><div align="right">Denominaci&oacute;n</div></td>
                    <td colspan="3"><p>
                        <textarea name="txtdesprod" cols="45" rows="3" id="txtdesprod" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz ()#!%/[]*-+_.,:;');"><?php print $ls_desprod?></textarea>                      
                        </p></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="18">&nbsp;</td>
                    <td colspan="3">&nbsp;</td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="18">&nbsp;</td>
                    <td width="122">&nbsp;</td>
                    <td width="95">
                      <div align="center"></div></td>
                    <td width="236">&nbsp;</td>
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
              <input name="operacion" type="hidden" id="operacion" value="<? print $ls_operacion ?>">
			  <input name="existe" type="hidden" id="existe" value="<? print $ls_existe ?>">
              <input name="tiposeg" type="hidden" id="tiposeg" value="<?php print $ls_tiposeg; ?>">
		      <input name="hidcodseg" type="hidden" id="hidcodseg" value="<?php print $ls_codsegmento; ?>">
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
	codseg=ue_validarvacio(f.txtcodseg.value);
	if (li_leer==1)
   	{
		window.open("sigesp_siv_cat_producto.php?codseg="+codseg+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
	li_codseg=f.txtcodseg.value;
	li_codfam=f.txtcodfam.value;
	li_codclase=f.txtcodclase.value;
	li_codprod=f.txtcodprod.value;	
	li_desprod=f.txtdesprod.value;
	if((li_cambiar==1)&&(li_incluir==1))
	{
		if ((li_codseg!="")&&(li_codfam!="")&&(li_codclase!="")&&(li_codprod!="")&&(li_desprod!=""))
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_siv_d_producto.php";
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
		if(confirm("¿Seguro desea eliminar la familia ?"))
		{
			f.operacion.value="ELIMINAR";
			f.action="sigesp_siv_d_producto.php";
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
	window.close();
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_siv_d_producto.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
</script> 
</html>