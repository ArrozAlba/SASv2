<?php 
session_start(); 
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$ls_logusr=$_SESSION["la_logusr"];
require_once("../class_folder/class_funciones_cfg.php");
$io_fun_cfg=new class_funciones_cfg();
$io_fun_cfg->uf_load_seguridad("CFG","sigesp_scg_d_plan_unico.php",$ls_permisos,$la_seguridad,$la_permisos,"../../");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Plan de Cuentas Patrimoniales</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" background="imagebank/header.jpg" class="contorno">
  <tr>
    <td height="30" background="imagebank/header.jpg"><a href="imagebank/header.jpg"><img src="../../shared/imagebank/header.jpg" width="778" height="40" border="0"></a></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
   <tr>
     <td height="13" class="toolbar">&nbsp;</td>
   </tr>
   <tr>
    <td height="20" class="toolbar"><img src="imagebank/tools20/espacio.gif" width="4" height="20"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_close();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php
require_once("sigesp_scg_class_definicion.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sigesp_int.php");
require_once("../../shared/class_folder/class_sigesp_int_scg.php");
$int_scg   = new class_sigesp_int_scg();
$io_msg    = new class_mensajes(); //Instanciando la clase mensajes 
$io_plauni = new sigesp_scg_class_definicion();

//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre       = $_SESSION["la_empresa"];
	$ls_empresa = $arre["codemp"];
	$ls_codemp  = $ls_empresa;
	if (array_key_exists("la_logusr",$_SESSION))
	   {
		 $ls_logusr=$_SESSION["la_logusr"];
 	   }
	else
	   {
		 $ls_logusr="";
 	   }
	$ls_sistema     = "CFG";
	$ls_ventanas    = "sigesp_scg_d_plan_unico.php";
	$la_security[1] = $ls_empresa;
	$la_security[2] = $ls_sistema;
	$la_security[3] = $ls_logusr;
	$la_security[4] = $ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos            = $_POST["permisos"];
			$la_accesos["leer"]     = $_POST["leer"];
			$la_accesos["incluir"]  = $_POST["incluir"];
			$la_accesos["cambiar"]  = $_POST["cambiar"];
			$la_accesos["eliminar"] = $_POST["eliminar"];
			$la_accesos["imprimir"] = $_POST["imprimir"];
			$la_accesos["anular"]   = $_POST["anular"];
			$la_accesos["ejecutar"] = $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]     = "";
		$la_accesos["incluir"]  = "";
		$la_accesos["cambiar"]  = "";
		$la_accesos["eliminar"] = "";
		$la_accesos["imprimir"] = "";
		$la_accesos["anular"]   = "";
		$la_accesos["ejecutar"] = "";
		$ls_permisos            = $io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
	
//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	
$ls_format	  = $_SESSION["la_empresa"]["formplan"];
$ls_format	  = str_replace("-","",$ls_format);
$li_size_form = strlen(trim($ls_format));
if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion = $_POST["operacion"];
	 $ls_status	   = $_POST["status"];
     $ls_cuenta    = $_POST["txtcuenta"];
     $ls_dencta    = $_POST["txtdenominacion"];
   }
else
   {
	 $ls_operacion = $ls_cuenta = $ls_dencta = "";
   }
if ($ls_operacion=="GUARDAR")
   {
     $lb_valido = $int_scg->uf_insert_plan_unico_cuenta($ls_cuenta,$ls_dencta,$ls_status);
	 if ($lb_valido)
	    {
	   	  $lb_existe = $int_scg->uf_select_plan_unico_cuenta($ls_cuenta,$ls_dencta);
		  if ($lb_existe)
			 {
			   $io_msg->message("Registro Actualizado !!!");
			   $ls_evento="UPDATE";
			   $ls_descripcion="Actualizo la cuenta de plan unico $ls_cuenta, con denominacion $ls_dencta";
			 }
		  else
			 {
			   $io_msg->message("Registro Incluido !!!");
			   $ls_evento="INSERT";
			   $ls_descripcion="Inserto la cuenta de plan unico $ls_cuenta, con denominacion $ls_dencta";
			 }
		  $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas,$ls_descripcion);
		  $ls_cuenta = $ls_dencta = "";
	    }
	 else
	    {
		  $io_msg->message("".$int_scg->is_msg_error);
	    }
   }
if ($ls_operacion=="ELIMINAR")
   {
     $lb_tiene = $io_plauni->uf_load_relacion($ls_cuenta);
	 if (!$lb_tiene)
	    {
		  $lb_valido = $io_plauni->uf_delete_planunico($ls_cuenta,$ls_dencta);
		  if ($lb_valido)
			 {    
			   $io_msg->message("Registro Eliminado !!!");
			   $ls_descripcion="Elimino la cuenta de plan unico $ls_cuenta, con denominacion $ls_dencta";
			   $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,"DELETE",$ls_logusr,$ls_ventanas,$ls_descripcion);       	
			 }
		  else 
			 {
			   $io_msg->message("No se encontro el registro !!!");
			 }
		  $ls_cuenta="";
		  $ls_dencta="";
		}
     else
	    {
		  $io_msg->message("Error en Eliminación, Existen Registro asociados a esta Cuenta en el Plan de Cuentas Contable !!!");
		}
   }
if ($ls_operacion=="NUEVO")
   {
     $ls_cuenta="";
     $ls_dencta="";
     $ls_status='N';
   }
?>
<p>&nbsp;</p>
<p>&nbsp;</p> 
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cfg->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cfg);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="658" height="170" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td align="center"><table width="614" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-ventana">
            <td height="22" colspan="2" align="center">Plan de Cuentas Patrimoniales</td>
          </tr>
          <tr>
            <td width="91" height="22"></td>
            <td width="521" height="22"></td>
          </tr>
          <tr>
            <td height="22" align="right" >Codigo</td>
            <td height="22" align="left">
                <input name="txtcuenta" type="text" id="txtcuenta" value="<?php print $ls_cuenta?>" maxlength="<?php print $li_size_form;?>" onKeyPress="return keyRestrict(event,'1234567890');"  onBlur="rellenar_cad(this.value,<?php print $li_size_form;?>)" style="text-align:center ">
            <input name="status" type="hidden" id="status" value="<?php print $ls_status;?>">            </td>
          </tr>
          <tr>
            <td height="22" align="right">Denominaci&oacute;n</td>
            <td height="22" align="left">
              <input name="txtdenominacion" type="text" id="txtdenominacion" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz '+'.,-')" size="95" maxlength="250" value="<?php print $ls_dencta?>">            </td>
          </tr>
          <tr>
            <td height="22"></td>
            <td height="22"><input name="operacion" type="hidden" id="operacion" value="<?php $_POST["operacion"]?>"></td>
          </tr>
        </table></td>
      </tr>
  </table>
</form>
</body>
<script language="javascript">
function ue_guardar()
{
var resul="";
  
f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status =f.status.value;
if (((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
   {
     with (document.form1)
	      {
            if (valida_null(txtcuenta,"La cuenta esta vacia!!")==false)
               {
                 txtcuenta.focus();
               }
            else
               {
	             if (valida_null(txtdenominacion,"La denominación esta vacia!!")==false)
	                {
	                  txtdenominacion.focus();
	                }
	             else
	                {
		              f=document.form1;
		              f.operacion.value="GUARDAR";
		              f.action="sigesp_scg_d_plan_unico.php";
		              f.submit();
	                }
	           } 
          }
   }
  else
   {
     alert("No tiene permiso para realizar esta operación");
   }
}   

function ue_eliminar()
{
f=document.form1;
li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     resp=confirm("¿ Esta seguro de Eliminar este Registro ?");
     if (resp==true)
        {
	      f.operacion.value="ELIMINAR";
	      f.action="sigesp_scg_d_plan_unico.php";
	      f.submit();
        }	
      else
	    {
		  alert("Eliminación Cancelada !!!");
		}
   }
 else
   {
     alert("No tiene permiso para realizar esta operación");
   }
}

function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.operacion.value="NUEVO";
	   f.action="sigesp_scg_d_plan_unico.php";
	   f.submit();
	 }
  else
     {
 	   alert("No tiene permiso para realizar esta operación");
	 }	 
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
         pagina="sigesp_scg_cat_ctaspu.php";
         window.open(pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=450,resizable=yes,location=no")
       }
     else
	   {
 	     alert("No tiene permiso para realizar esta operación");
	   }
}

function ue_close()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

//Funciones de validacion de fecha.
function rellenar_cad(cadena,longitud)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;

	total=longitud-lencad;
	for(i=1;i<=total;i++)
	{
		cadena_ceros=cadena_ceros+"0";
	}
	cadena=cadena+cadena_ceros;	
	document.form1.txtcuenta.value=cadena;	
}
	
function valida_null(field,mensaje)
{
  with (field) 
  {
    if (value==null||value=="")
      {
        alert(mensaje);
        return false;
      }
    else
      {
   	    return true;
      }
  }
}
</script>
</html>