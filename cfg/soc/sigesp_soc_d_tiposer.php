<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='../../sigesp_conexion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de Tipos de Servicios</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
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

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-negrita"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right" class="letras-negrita"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php 
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_funciones_db.php");
require_once("class_folder/sigesp_soc_c_tiposer.php");
require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");

$io_conect    = new sigesp_include();//Instanciando la Sigesp_Include.
$conn         = $io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql       = new class_sql($conn);//Instanciando la Clase Class Sql.
$io_tipo      = new sigesp_soc_c_tiposer($conn);//Instanciando la Clase Sigesp Definiciones.
$io_msg       = new class_mensajes();//Instanciando la Clase Class  Mensajes.
$io_funciondb = new class_funciones_db($conn);//Instanciando la Clase Class_Funciones_DB.
$io_chkrel    = new sigesp_c_check_relaciones($conn);
$lb_existe    = "";
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_codemp   = $ls_empresa;
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CFG";
	$ls_ventanas = "sigesp_soc_d_tiposer.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

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
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion    = $_POST["operacion"];
	 $ls_codigo       = $_POST["txtcodigo"];
     $ls_denominacion = $_POST["txtdenominacion"];
     $ls_estatus      = $_POST["hidestatus"];
	 $ls_codmil       = $_POST["txtcodmil"];
	 $ls_denmil       = $_POST["txtdenmil"];
   }
else
   {
     $ls_operacion    = "NUEVO";
     $ls_codigo       = "";
     $ls_denominacion = "";
	 $ls_codmil       = "";
	 $ls_denmil       = "";
     $ls_estatus      = "NUEVO";	  	
   }
$lb_empresa = false;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////             Operación  Nuevo    ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="NUEVO")
   {
     $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'soc_tiposervicio','codtipser');
	 if(empty($ls_codigo))
	   {
	 	 $io_msg->message($io_funciondb->is_msg_error);
	   } 
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////       Fin  Operacion  Nuevo     ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////            Operaciones de Insercion y Actualizacion             /////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_guardar")
   {      
	 $lb_existe=$io_tipo->uf_select_tiposervicio($ls_codigo);     
	 if ($lb_existe)
        { 
	      if ($ls_estatus=="NUEVO")
		     {
			   $io_msg->message("Este Código de Tipo de Servicio ya existe !!!");  
			   $lb_valido=false;
			 }
		  elseif($ls_estatus=="GRABADO")
		     {
               $lb_valido=$io_tipo->uf_update_tiposervicio($ls_codigo,$ls_denominacion,$la_seguridad,$ls_codmil);
	           if ($lb_valido)
		          {
			        $io_msg->message("Registro Actualizado !!!");
			        $io_sql->commit();
	                $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'soc_tiposervicio','codtipser');
			        $ls_denominacion="";
			        $ls_codmil="";
			        $ls_denmil="";
		 	  	    $ls_estatus="NUEVO";
			      }
		        else
		          {
			        $io_sql->rollback();
				    $io_msg->message("Error en Actualización !!!");
			      }
			 }	 
	    } 
	  else
	    {  
		 $lb_valido=$io_tipo->uf_insert_tiposervicio($ls_codigo,$ls_denominacion,$la_seguridad,$ls_codmil);
	     if ($lb_valido)
		    {
			  $io_msg->message("Registro Incluido !!!");
			  $io_sql->commit();
	          $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'soc_tiposervicio','codtipser');
			  $ls_denominacion="";
			  $ls_codmil="";
			  $ls_denmil="";
			  $ls_estatus="NUEVO";
		    }
		 else
		    {
		      $io_sql->rollback();
			  $io_msg->message("Error en Inclusión !!!");
			}
	   }
   } 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////            Fin de las Operaciones de Insercion y Actualizacion      //////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////    Operacion  de  Eliminar       //////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ELIMINAR")
   {
	 $lb_existe=$io_tipo->uf_select_tiposervicio($ls_codigo);
	 if ($lb_existe)
	    {
		  $ls_condicion = " AND (column_name='codtipser')";//Nombre del o los campos que deseamos buscar.
	      $ls_mensaje   = "";                              //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
	      $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_codemp,$ls_condicion,'soc_tiposervicio',$ls_codigo,$ls_mensaje);//Verifica los movimientos asociados a la cuenta  
		  if (!$lb_tiene)
		     {
			   $lb_valido=$io_tipo->uf_delete_tiposervicio($ls_codemp,$ls_codigo,$ls_denominacion,$la_seguridad);
			   if ($lb_valido)
				  {
				    $io_sql->commit();
				    $io_msg->message("Registro Eliminado !!!"); 
				    $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'soc_tiposervicio','codtipser');
				    $ls_denominacion="";
			        $ls_codmil="";
			        $ls_denmil="";
				    $ls_estatus="NUEVO";
				  }
			   else
				  {
				    $io_sql->rollback();
				    $io_msg->message($io_tipo->is_msg_error);
				  }
	         }
		  else
		     {
			   $io_msg->message($io_chkrel->is_msg_error);
			 }
		}
	 else
	    {
		  $io_msg->message("Este Registro No Existe !!!");
		}	 
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////                Fin Operaciones de Eliminar          ////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<p>&nbsp;</p>
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
<table width="597" height="183" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td width="578" height="173"><div align="center">
      <table width="554" height="133"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="22" colspan="3" class="titulo-ventana"><p>Tipo de Servicios </p></td>
        </tr>
        <tr>
          <td height="22" >&nbsp;</td>
          <td width="444" height="22" colspan="2" ><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>">
            <input name="hidmaestro" type="hidden" id="hidmaestro" value="Y"></td>
        </tr>
        <tr>
          <td width="108" height="22" align="right">C&oacute;digo</td>
          <td height="22" colspan="2" ><input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codigo ?>" size="5" maxlength="4" onKeyPress="return keyRestrict(event,'1234567890');"  style="text-align:center " onBlur="javascript:rellenar_cadena(this.value,4);">
              <input name="operacion" type="hidden" class="formato-blanco" id="operacion"  value="<?php print $ls_operacion?>">          </td>
        </tr>
         <tr>
          <td height="22" align="right">Denominaci&oacute;n</td>
          <td height="22" colspan="2"><p>
              <input name="txtdenominacion" id="txtdenominacion" value="<?php print $ls_denominacion ?>" type="text" size="70" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'.,-');">
          </p></td>
        </tr>
         <tr>
           <td height="22"><div align="right">Catalogo Milco </div></td>
           <td height="22" colspan="2"><div align="left">
             <input name="txtcodmil" type="text" id="txtcodmil" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_codmil ?>" size="25" maxlength="25" readonly style="text-align:center ">
             <a href="javascript: ue_catalogo_milco();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
             <input name="txtdenmil" type="text" class="sin-borde" id="txtdenmil" value="<?php print $ls_denmil?>" size="45" readonly>
           </div></td>
         </tr>
         <tr>
          <td height="22">&nbsp;</td>
          <td height="22" colspan="2">&nbsp; </td>
        </tr>
      </table>
    </div></td>
    </tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</form>
</body>
<script language="JavaScript">
function ue_catalogo_milco()
{
	window.open("sigesp_soc_cat_milco.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if (li_incluir==1)
	   {	
         f.operacion.value="NUEVO";
		 f.txtdenominacion.value="";
		 f.txtdenominacion.focus(true);
	     f.action="sigesp_soc_d_tiposer.php";
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
f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.hidestatus.value;
if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status=="NUEVO")&&(li_incluir==1))
   {
     with (document.form1)
          {
	        if (campo_requerido(txtcodigo,"El Código debe estar lleno !!")==false)
			   {
			     txtcodigo.focus();
			   }
		    else
			   { 
			     if (campo_requerido(txtdenominacion,"La Denominación debe estar llena !!")==false)
					{
					  txtdenominacion.focus();
					}
				 else
					{
					  f=document.form1;
					  f.operacion.value="ue_guardar";
					  f.action="sigesp_soc_d_tiposer.php";
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
var borrar="";

f=document.form1;
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
			   f=document.form1;
			   f.operacion.value="ELIMINAR";
			   f.action="sigesp_soc_d_tiposer.php";
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
		
function ue_buscar()
{
    f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     f.operacion.value="";			
	     pagina="sigesp_soc_cat_tiposer.php";
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=200,resizable=yes,location=no");
       }
	else 
	   {
		 alert("No tiene permiso para realizar esta operación");
	   }   
}
</script>
</html>