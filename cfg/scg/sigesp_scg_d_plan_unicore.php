<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Plan Único de Recursos Y Egresos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
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
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
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
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_fecha.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_sigesp_int.php");
require_once("../../shared/class_folder/class_sigesp_int_scg.php");
require_once("../../shared/class_folder/class_sigesp_int_spg.php");
$int_spg=new class_sigesp_int_spg();
$in = new sigesp_include();
$con= $in-> uf_conectar ();
$msg=new class_mensajes(); //Instanciando la clase mensajes 
$SQL=new class_sql($con); //Instanciando  la clase sql
require_once("sigesp_scg_class_definicion.php");
$def= new sigesp_scg_class_definicion();
$ds_plan=new class_datastore(); //Instanciando la clase datastore
$dat=$_SESSION["la_empresa"];
$ls_format       = $dat["formpre"];
$ls_formating    = $dat["formspi"];
$ls_formating    = str_replace("-","",$ls_formating);
$ls_format       = str_replace("-","",$ls_format);
$li_size_forming = strlen(trim($ls_formating));
$li_size_form    = strlen(trim($ls_format));
//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	$ls_sistema="CFG";
	$ls_ventanas="sigesp_scg_d_plan_unicore.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos=             $_POST["permisos"];
			$la_accesos["leer"]=     $_POST["leer"];
			$la_accesos["incluir"]=  $_POST["incluir"];
			$la_accesos["cambiar"]=  $_POST["cambiar"];
			$la_accesos["eliminar"]= $_POST["eliminar"];
			$la_accesos["imprimir"]= $_POST["imprimir"];
			$la_accesos["anular"]=   $_POST["anular"];
			$la_accesos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]="";
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

if(array_key_exists("operacion",$_POST))
{
$ls_operacion=$_POST["operacion"];
$ls_status=$_POST["status"];
}
else
{
$ls_operacion="";
$ls_status='C';
}

if(array_key_exists("txtcuenta",$_POST))
{
  $ls_cuenta=$_POST["txtcuenta"];
}
else
{
  $ls_cuenta="";
}

if(array_key_exists("txtdenominacion",$_POST))
{
$ls_denominacion=$_POST["txtdenominacion"];
}
else
{
$ls_denominacion="";
}

if (array_key_exists("chkcueing",$_POST))
{
$ls_chkcueing = $_POST["chkcueing"];
$li_size_form = $li_size_forming;
}
else
{
$ls_chkcueing = "0";
$li_size_form=strlen(trim($ls_format));
}
?>
<p class="cd-titulo">
<?php

if($ls_operacion=="GUARDAR")
{
      $lb_existe = $int_spg->uf_select_plan_unico_cuenta($ls_cuenta,$ls_denominacion);
	  $lb_valido = $int_spg->uf_insert_plan_unico_cuenta($ls_cuenta,$ls_denominacion,$ls_status);
	  if($lb_valido)
	  {
	    $ls_cuenta="";
        $ls_denominacion="";
		$msg->message("Registro Guardado");
		if($lb_existe)
		{
			$ls_evento="UPDATE";
			$ls_descripcion="Actualizo la cuenta de plan unico de recursos $ls_cuenta, con denominacion $ls_denominacion";
		}
		else
		{
			$ls_evento="INSERT";
			$ls_descripcion="Inserto la cuenta de plan unico de recursos $ls_cuenta, con denominacion $ls_denominacion";
		}
		$io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas,$ls_descripcion);
	 }
	else
	{
		$msg->message("".$int_spg->is_msg_error);
	}
}

if($ls_operacion=="ELIMINAR")
{
   $lb_valido=$def->uf_delete_planunicore($ls_cuenta,$ls_denominacion);
  
   
   if ($lb_valido)
   {   
	  $msg->message("Registro  Eliminado");   
	  $ls_evento="DELETE";
	  $ls_descripcion="Elimino la cuenta de plan unico de recursos $ls_cuenta, con denominacion $ls_denominacion";
   	  $io_seguridad->uf_sss_insert_eventos_ventana($ls_empresa,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas,$ls_descripcion);           	
   }
   else 
   {
     $msg->message("No se encontro el registro");
   }
   $ls_cuenta="";
   $ls_denominacion="";
}

if($ls_operacion=="NUEVO")
{
   $ls_cuenta="";
   $ls_denominacion="";
   $ls_status='C';
   $ls_chkcueing = $_POST["chkcueing"];
}

?>
</p>
<p class="cd-titulo">&nbsp;</p>
<p class="cd-titulo">&nbsp;  
</p>
<div align="center">
  <form name="form1" method="post" action="">
  <?php 
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=permisos value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=permisos value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=permisos value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=permisos value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=permisos value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=permisos value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=permisos value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="658" height="170" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td >
		<table width="614" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-ventana">
            <td height="22" colspan="2"><div align="center">Cat&aacute;logo de Recursos y Egresos </div></td>
          </tr>
          <tr>
            <td width="91" height="22"><div align="right"><span class="Estilo1">Cuenta Ingreso</span></div></td>
            <td width="521" height="22"><input name="chkcueing" type="checkbox" id="chkcueing" value="1" <?php print $ls_chkcueing?> ></td>
          </tr>
          <tr>
            <td height="22"><div align="right" class="fd-blanco">C&oacute;digo</div></td>
            <td height="22"><div align="left">
                <input name="txtcuenta" type="text" id="txtcuenta" value="<?php print $ls_cuenta?>" maxlength="<?php print $li_size_form;?>" onKeyPress="return keyRestrict(event,'1234567890');" onBlur="rellenar_cad(this.value,<?php print $li_size_form;?>)"  style="text-align:center ">
			    <input name="status" type="hidden" id="status" value="<?php print $ls_status ?>">
            </div></td>
          </tr>
          <tr>
            <td height="22"><p align="right" class="fd-blanco">Denominaci&oacute;n</p></td>
            <td height="22"><div align="left">
              <input name="txtdenominacion" type="text" id="txtdenominacion" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyzáéíóú '+',.-');" size="95" maxlength="250" value="<?php print $ls_denominacion?>">
            </div></td>
          </tr>
          <tr class="fd-blanco">
            <td height="22">&nbsp;</td>
            <td height="22"><span class="Estilo1">
              <input name="operacion" type="hidden" id="operacion3" value="<?php $_POST["operacion"]?>">
            </span></td>
          </tr>
        </table></td>
      </tr>
    </table>
  </form>
</div>
</body>
<script language="javascript">

function ue_guardar()
{
  var resul="";

f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.status.value;
if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status!="GRABADO")&&(li_incluir==1))
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
		              f.action="sigesp_scg_d_plan_unicore.php";
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
		  f.action="sigesp_scg_d_plan_unicore.php";
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
       f.action="sigesp_scg_d_plan_unicore.php";
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
         pagina="sigesp_scg_cat_ctaspure.php?";
         window.open(pagina,"Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,heigth=450,resizable=yes,location=no")
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