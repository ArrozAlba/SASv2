<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Validaciones para Traspasos Presupuestarios</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../cxp/js/stm31.js"></script>
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
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset="><style type="text/css">
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
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-negrita"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right" class="letras-negrita"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../cxp/js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"></a><a href="javascript:ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"></a><a href="javascript:ue_eliminar();"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php 
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_funciones_db.php"); 
require_once("../../shared/class_folder/class_mensajes.php"); 
require_once("class_folder/sigesp_spg_c_validaciones.php");
    
$io_conect       = new sigesp_include();//Instanciando la Sigesp_Include.
$conn            = $io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_validacion   = new sigesp_spg_c_validaciones($conn);
$io_sql          = new class_sql($conn);//Instanciando la Clase Class Sql.
$io_msg          = new class_mensajes();
$io_dsclas       = new class_datastore();//Instanciando la Clase Class  DataStore.
$io_funcion      = new class_funciones();//Instanciando la Clase Class_Funciones.
$io_funciondb    = new class_funciones_db($conn);
$lb_existe       = "";

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="CFG";
	$ls_ventanas="sigesp_spg_d_validaciones.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

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

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion=$_POST["operacion"];
   }
else
	{
	  $ls_operacion="BUSCAR"; 
	}

if (array_key_exists("chkvalidacion",$_POST))
   {
     $li_validacion = $_POST["chkvalidacion"];
	 if($li_validacion == 1)
	 {
	  $ls_checked = "checked";
	 }
	 else
	 {
	  $ls_checked = "";	
	 }
   }
else
	{
	  $li_validacion = 0;
	  $ls_checked = "";	  
	}
if (array_key_exists("txtctaced",$_POST))
   {
     $ls_ctascedentes = $_POST["txtctaced"];
   }
else
	{
	  $ls_ctascedentes = "";	  
	}
if (array_key_exists("txtctarec",$_POST))
   {
     $ls_ctasreceptoras = $_POST["txtctarec"];
   }
else
	{
	  $ls_ctasreceptoras = "";	  
	}	
				
$ls_codemp  = $ls_empresa;
$lb_empresa = true;
		

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////       Operaciones de  Actualizacion            //////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($ls_operacion == "BUSCAR")
{
  $io_validacion->uf_obtener_validacion($ls_empresa,$li_validacion,$ls_ctasreceptoras,$ls_ctascedentes);
  if($li_validacion == 1)
  {
   $ls_checked = "checked";
  }
  else
  {
    $ls_checked = "";
  }
}
if ($ls_operacion=="GUARDAR")
{ 
	$lb_valido=$io_validacion->uf_activar_validacion($ls_empresa,$li_validacion,$ls_ctasreceptoras,$ls_ctascedentes,$la_seguridad);
	if ($lb_valido)
	{
	 $io_sql->commit();
	 $io_msg->message("Configuracion Registrada !!!");
	}
    else
    {
	 $io_sql->rollback();
	 $io_msg->message("Error en Configuracion !!!");
    }
	if($li_validacion == 1)
	{
	$ls_checked = "checked";
	}
	else
	{
	$ls_checked = "";
	}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////          Fin de las Operaciones de Insercion y Actualizacion      ///////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////Operaciones de Eliminar en el Data Store////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<p align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif"></font></p>
<p align="center">&nbsp;</p>
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
  <table width="578" height="255" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="576" height="153">
        <div align="justify">
          <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
            <tr>
              <td height="22" colspan="2" class="titulo-ventana">Validaciones para Traspasos de Presupuesto de Gasto </td>
            </tr>
            <tr>
              <td width="110" height="22" >&nbsp;</td>
              <td width="454" height="22" >&nbsp;</td>
            </tr>
            <tr>
              <td height="22" colspan="2">
                <div align="left">
                  <input name="chkvalidacion" type="checkbox" id="chkvalidacion" value="<?php print $li_validacion; ?>" <?php print $ls_checked; ?>>
                  Activar Validaci&oacute;n para las Modificaciones Presupuestaria </div>
                <label></label></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
            <tr>
              <td height="22"><div align="left">Cuentas Cedentes: </div></td>
              <td height="22"><label>
                <input name="txtctaced" type="text" id="txtctaced" size="50" maxlength="150" value ="<?php print $ls_ctascedentes; ?>" readonly="true">
				<input name="hidctaced" type="hidden" id="hidctaced">
              <a href="javascript:uf_catalogo_ctascedrec();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="buscar" width="15" height="15" border="0" onClick="document.form1.hidctaced.value=1" title="Agregar Cuenta Cedente"></a>
              <a href="javascript:uf_borrar_cuenta('C');"><img src="../../shared/imagebank/tools15/eliminar.gif" alt="Eliminar" width="15" height="15" border="0" title="Borrar Cuentas Cedentes"></a></label></td>
            </tr>
            <tr>
              <td height="22"><div align="left">Cuentas Receptoras: </div></td>
              <td height="22"><label>
                <input name="txtctarec" type="text" id="txtctarec" size="50" maxlength="150" value ="<?php print $ls_ctasreceptoras; ?>" readonly="true">
                <input name="hidctarec" type="hidden" id="hidctarec">
              <a href="javascript:uf_catalogo_ctascedrec();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0" onClick="document.form1.hidctarec.value=1" title="Agregar Cuenta Receptora"></a>
              <a href="javascript:uf_borrar_cuenta('R');"><img src="../../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="15" height="15" border="0" title="Borrar Cuentas Receptoras"></a></label></td>
            </tr>
            <tr>
              <td height="22">&nbsp;</td>
              <td height="22">&nbsp;</td>
            </tr>
          </table>
          <input name="operacion" type="hidden" id="operacion">
        </div></td></tr>
  </table>
  <p align="center">&nbsp;</p>
  <div align="center">  </div>
</form>
</body>

<script language="JavaScript">

function ue_guardar()
{
var resul="";
f=document.form1;
li_cambiar=f.cambiar.value;
if (li_cambiar==1)
   {
     with (document.form1)
	      {
		   if(f.chkvalidacion.checked)
		   {
	        if (campo_requerido(txtctaced,"Debe incluir seleccionar al menos una cuenta cedente !!!")==false)
		       {
		         txtctaced.focus();
			   }
			else
			{
			 if (campo_requerido(txtctarec,"Debe incluir seleccionar al menos una cuenta receptora !!!")==false)
		     {
		         txtctarec.focus();
			 }  
			 else
			   {
				f.chkvalidacion.value = 1;
				f=document.form1;
				f.operacion.value="GUARDAR";
				f.action="sigesp_spg_d_validaciones.php";
				f.submit();
				}
			  }
		   }
		   else
		   {
		    f.chkvalidacion.value = 0;
			f=document.form1;
			f.operacion.value="GUARDAR";
			f.action="sigesp_spg_d_validaciones.php";
			f.submit();
		   }	  
		  }   			
    }
  else
	{
		alert("No tiene permiso para realizar esta operación");
	}
}
					
		
function campo_requerido(field,mensaje)
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
		
function rellenar_cadena(cadena,longitud)
{
	var mystring=new String(cadena);
	cadena_ceros="";
	lencad=mystring.length;
	total=longitud-lencad;
	for (i=1;i<=total;i++)
		{
		  cadena_ceros=cadena_ceros+"0";
		}
	cadena=cadena_ceros+cadena;
	document.form1.txtcodigo.value=cadena;
}
		
function uf_catalogo_ctascedrec()
{
 	f=document.form1;
	if(f.chkvalidacion.checked)
	{
	 var ls_cuentas = "";
	 var ls_tipo = "";
	 f.chkvalidacion.value = 1;
	 if(f.hidctaced.value == 1)
	 {
	  ls_tipo = "C";
	  f.hidctarec.value = 0;
	 }
	 else
	 {
	  if(f.hidctarec.value == 1) 
	  {
	   ls_tipo = "R";
	   f.hidctaced.value = 0;
	  } 
	 }
	 ls_cuentas  = f.txtctaced.value + f.txtctarec.value
	 pagina="sigesp_cfg_cat_ctascedrec.php?ctascedrec="+ls_cuentas+"&tipo="+ls_tipo;
	 window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,top=0,left=0");
	}
	else
	{
	 alert("Debe activar la Validacion");
	} 
}
function uf_borrar_cuenta(tipo)
{
 	f=document.form1;
	if(tipo == 'C')
	{
	 f.txtctaced.value = "";
	}
	else
	{
	 f.txtctarec.value = "";
	} 
}
function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

</script>
</html>