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
<title>Registro de Fuentes de Financiamiento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
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
    <td height="20" class="cd-menu"></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20"></td>
  </tr>
</table>
<?php 
require_once("class_folder/sigesp_spg_c_fuentfinan.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_funciones_db.php"); 
require_once("../../shared/class_folder/class_mensajes.php");

$io_conect= new sigesp_include();//Instanciando la Sigesp_Include.
$conn=$io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql= new class_sql($conn);//Instanciando la Clase Class Sql.
$io_fuente= new sigesp_spg_c_fuentfinan($conn);//Instanciando la Clase Sigesp Definiciones.
$io_dsclas = new class_datastore();//Instanciando la Clase Class  DataStore.
$io_funcion = new class_funciones();//Instanciando la Clase Class_Funciones.
$io_funciondb = new class_funciones_db($conn);
$io_msg = new class_mensajes();
$lb_existe="";

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="CFG";
	$ls_ventanas="sigesp_spg_d_fuentfinan.php";

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
if(array_key_exists("operacion",$_POST))
{
  $ls_operacion=$_POST["operacion"];
  $ls_status=$_POST["status"];
  $lb_empresa=true;
  $ls_tabla="sigesp_spg_fuentefinanciamiento";
  $ls_campo="codfuefin"; 
 }
else
{
  $ls_operacion="NUEVO"; 
  $ls_tabla="sigesp_spg_fuentefinanciamiento";
  $ls_campo="codfuefin";
  $lb_empresa=true;
}
if(array_key_exists("txtcodigo",$_POST))
{
$ls_codfuefin=$_POST["txtcodigo"];
}
else
{
$ls_codfuefin="";
}
if(array_key_exists("txtdenominacion",$_POST))
{
$ls_denominacion=$_POST["txtdenominacion"];
}
else
{
$ls_denominacion="";
}
if(array_key_exists("txtexplicacion",$_POST))
{
$ls_explicacion=$_POST["txtexplicacion"];
}
else
{
$ls_explicacion="";
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////       Operaciones de Insercion y Actualizacion            //////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_guardar")
   { 
     $arremp=$_SESSION["la_empresa"];
	 $ls_codemp=$arremp["codemp"];
	 $lb_existe=$io_fuente->uf_select_fuente_financiamiento($ls_codemp,$ls_codfuefin);
	 if ($lb_existe)
        { 
	      $io_fuente->uf_update_fuente_financiamiento($ls_codemp,$ls_codfuefin,$ls_denominacion,$ls_explicacion,$la_seguridad,$ls_status);
	    }  
	 else  //Si no existe 
	    {  
		  $io_fuente->uf_insert_fuente_financiamiento($ls_codemp,$ls_codfuefin,$ls_denominacion,$ls_explicacion,$la_seguridad,$ls_status);
	    } 
  $ls_codfuefin="";
  $ls_denominacion="";
  $ls_explicacion="";
  $ls_operacion="NUEVO";
} 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////          Fin de las Operaciones de Insercion y Actualizacion      ///////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////    Operacion de Eliminar ////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_eliminar")
   {
      $arremp=$_SESSION["la_empresa"];
	  $ls_codemp=$arremp["codemp"];
	  $io_fuente->uf_delete_fuente_financiamiento($ls_codemp,$ls_codfuefin,$la_seguridad);
	  $ls_codfuefin="";
	  $ls_denominacion="";
	  $ls_explicacion="";
	  $ls_operacion="NUEVO";
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////           Fin Operacion de Eliminar          ////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////             Operacion  Nuevo    ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="NUEVO")
   {
     $arremp=$_SESSION["la_empresa"];
	 $ls_codemp=$arremp["codemp"];
     $ls_status='N';
	 $ls_codfuefin=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,"sigesp_fuentefinanciamiento","codfuefin",$ls_status);
	 if (empty($ls_codfuefin))
	    {
	 	  $io_msg->message($io_funciondb->is_msg_error);
	    }
   }  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////       Fin  Operacion  Nuevo     ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<p align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif"></font></p>
<p align="center">&nbsp;</p>
<form name="formulario" method="post" action="">
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
  <table width="519" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="517" height="207"><div align="center">
        <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
          <tr>
            <td height="22" colspan="2" class="titulo-ventana">Registro de Fuentes de Financiamiento </td>
          </tr>
          <tr>
            <td height="22" >&nbsp;</td>
            <td height="22" ><span class="style1"></span></td>
          </tr>
          <tr>
            <td width="115" height="22" ><div align="right">C&oacute;digo</div></td>
            <td width="353" height="22" style="text-align:left " ><input name="txtcodigo" type="text" id="txtcodigo" value="<?php print  $ls_codfuefin ?>" size="4" maxlength="2" onKeyPress="return keyRestrict(event,'1234567890');" onBlur="javascript:rellenar_cad(this.value,2)" style="text-align:center ">
                <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">
                <input name="status" type="hidden" id="status" value="<?php print $ls_status; ?>"></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Denominaci&oacute;n</div></td>
            <td height="22"  style="text-align:left "><input name="txtdenominacion" id="txtdenominacion" value="<?php print $ls_denominacion ?>" type="text" size="60" maxlength="80" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz '+'.,-')";></td>
          </tr>
          <tr>
            <td height="22"   style="text-align:right ">Explicaci&oacute;n</td>
            <td height="22"   style="text-align:left "><input name="txtexplicacion" type="text" id="txtexplicacion" value="<?php print $ls_explicacion ?>" size="60" maxlength="254"></td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td height="22">&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
  </table>
</form>
</body>

<script language="JavaScript">
f = document.formulario;
function ue_nuevo()
{
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.operacion.value="NUEVO";
	   f.txtcodigo.value="";
	   f.txtdenominacion.value="";
	   f.txtdenominacion.focus(true);
	   f.txtexplicacion.value="";
	   f.action="sigesp_spg_d_fuentfinan.php";
	   f.submit();
	 }
   else
     {
 	   alert("No tiene permiso para realizar esta operación");
	 }
}


function ue_guardar()
{
var resul="";
li_incluir = f.incluir.value;
li_cambiar = f.cambiar.value;
lb_status  = f.status.value;
if (((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
   {
     with (document.formulario)
	      {
	        if (campo_requerido(txtcodigo,"El código de la Fuente de Financiamiento debe estar lleno !!")==false)
		       {
		         txtcodigo.focus();
		       }
 	        else
		       {
		         resul=rellenar_cad(document.formulario.txtcodigo.value,2);	   
		         if (campo_requerido(txtdenominacion,"La denominación debe estar llena !!")==false)
			        {
			          txtdenominacion.focus();
			        }
		         else
			        {
			          f.operacion.value="ue_guardar";
			          f.action="sigesp_spg_d_fuentfinan.php";
			          f.submit();
			        }
		       }
	      }			
	 }
   else
     {
 	   alert("No tiene permiso para realizar esta operación !!!");
	 }
}
					
function ue_eliminar()
{
var borrar="";

li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     if (f.txtcodigo.value=="")
        {
	      alert("No ha seleccionado ningún registro para eliminar !!!");
        }
	 else
	    {
		  borrar=confirm("¿ Esta seguro de eliminar este registro ?");
		  if (borrar==true)
		     { 
			   f.operacion.value="ue_eliminar";
			   f.action="sigesp_spg_d_fuentfinan.php";
			   f.submit();
		     }
		  else
		     { 
			   alert("Eliminación Cancelada !!!");
		     }
 	    }
    }
 else
    {
 	   alert("No tiene permiso para realizar esta operación !!!");
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
		
function rellenar_cad(cadena,longitud)
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
	  document.formulario.txtcodigo.value=cadena;
}
		
function ue_buscar()
{
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     f.operacion.value="";			
	     pagina="sigesp_spg_cat_fuentefinan.php";
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=300,resizable=yes,location=no");
       }
	 else
	   {
 	     alert("No tiene permiso para realizar esta operación !!!");
	   }
}  
</script>
</html>