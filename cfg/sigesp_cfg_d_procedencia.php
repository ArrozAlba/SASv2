<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php'";
	print "</script>";		
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
<html>
<head>
<title>Registro de Procedencias</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
<style type="text/css">
<!--
.Estilo1 {
	font-size: 14;
	color: #6699CC;
}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-negrita"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right" class="letras-negrita"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?php
require_once("class_folder/sigesp_cfg_c_procedencias.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_c_check_relaciones.php");
$io_procedencias = new sigesp_cfg_c_procedencias();
$io_conect       = new sigesp_include();
$con             = $io_conect-> uf_conectar ();
$la_emp          = $_SESSION["la_empresa"];
$io_msg          = new class_mensajes(); //Instanciando la clase mensajes 
$io_sql          = new class_sql($con); //Instanciando  la clase sql
$io_dsest        = new class_datastore(); //Instanciando la clase datastore
$lb_valido       = "";
$io_chkrel       = new sigesp_c_check_relaciones($con);


//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CFG";
	$ls_ventanas = "sigesp_cfg_d_procedencia.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
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
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////


if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion             = $_POST["operacion"];
	 $ls_codigo                = $_POST["txtcodigo"];
	 $lr_datos["codigo"]       = $ls_codigo;
	 $ls_codigosistema         = $_POST["txtcodigosistema"];
	 $lr_datos["codsis"]       = $ls_codigosistema;
	 $ls_operacionsis          = $_POST["txtoperacion"];
	 $lr_datos["operacionsis"] = $ls_operacionsis;
	 $ls_descripcion           = $_POST["txtdescripcion"];
	 $lr_datos["descripcion"]  = $ls_descripcion;
	 $ls_estatus               = $_POST["status"];
   }
else
   {
	 $ls_operacion     = "";
	 $ls_codigo        = "";
     $ls_codigosistema = "";
	 $ls_operacionsis  = "";
	 $ls_descripcion   = "";
	 $ls_estatus       = "NUEVO";
   }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 if ($ls_operacion=="GUARDAR")
	{ 
      $lb_valido = $io_procedencias->uf_guardar_procedencia($lr_datos,$la_seguridad);
      $ls_codigo="";
	  $ls_codigosistema="";
	  $ls_operacionsis="";
	  $ls_descripcion="";
    }
	   
if ($ls_operacion=="ELIMINAR")
   {
	  $lb_existe = $io_procedencias->uf_select_procedencia($ls_codigo);
      if ($lb_existe)
	     {
		   $ls_condicion = " AND (column_name='procede' OR column_name='procede_doc')";//Nombre del o los campos que deseamos buscar.
	       $ls_mensaje   = "";  //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
	       $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_empresa,$ls_condicion,'sigesp_procedencias',$ls_codigo,$ls_mensaje);//Verifica los movimientos asociados a la cuenta
	       if (!$lb_tiene)
	          {
		        $lb_valido = $io_procedencias->uf_delete_procedencia($ls_codigo,$la_seguridad);
		        if ($lb_valido)
	               {
			         $io_sql->commit();
			         $io_msg->message("Registro Eliminado !!!");
			         $ls_codigo        = "";
		             $ls_codigosistema = "";
		             $ls_operacionsis  = "";
		             $ls_descripcion   = "";
			       }
		        else
			       { 
			         $io_sql->rollback();
			         $io_msg->message($io_procedencias->is_msg_error);
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
<table width="611" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="616" height="221"><table width="564"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="22" colspan="2" class="titulo-ventana">Procedencias</td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td height="22"><input name="status" type="hidden" id="status" value="<?php print $ls_estatus ?>"></td>
        <tr>
          <td width="177" height="22" align="right">C&oacute;digo</td>
          <td width="385" height="22"><input name="txtcodigo" type="text" id="txtcodigo" size="10" maxlength="6" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz');" value="<?php print $ls_codigo ?>"  style="text-align:center ">
            <input name="operacion" type="hidden" id="operacion"></td>
        <tr>
          <td height="22" align="right">C&oacute;digo Sistema</td>
          <td height="22" colspan="2"><input name="txtcodigosistema" type="text" id="txtcodigosistema" size="6" maxlength="3"  value="<?php print $ls_codigosistema ?>"  style="text-align:center" readonly  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz');">
            <a href="javascript:catalogo_sistemas();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> </td>
        </tr>
        <tr>
          <td height="22" align="right">Operaci&oacute;n de Procedencia</td>
          <td height="22" colspan="2"><input name="txtoperacion" type="text" id="txtoperacion" size="6" maxlength="3" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz');" value="<?php print $ls_operacionsis ?>"  style="text-align:center "></td>
        </tr>
        <tr>
          <td height="22" align="right">Descripci&oacute;n</td>
          <td height="22" colspan="2"><input name="txtdescripcion" type="text" id="txtdescripcion" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz '+'.,-');" value="<?php print $ls_descripcion ?>" size="60" maxlength="100"></td>
        </tr>
        <tr>
          <td height="22" align="right">&nbsp;</td>
          <td height="22" colspan="2">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>

<script language="javascript">

function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.txtcodigo.readOnly=false;
	   f.txtcodigo.value="";
	   f.txtcodigosistema.value="";
	   f.txtoperacion.value="";
	   f.txtdescripcion.value="";
	   f.txtcodigo.focus(); 
	}
  else
	{
	  alert("No tiene permiso para realizar esta operación");
	}
}

function ue_guardar()
{//1
  var resul="";
 
f          = document.form1;
li_incluir = f.incluir.value;
li_cambiar = f.cambiar.value;
lb_status  = f.status.value;
if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status!="GRABADO")&&(li_incluir==1))
   {
     with (document.form1)
	      {
            if (valida_null(txtcodigo,"El Código de la Procedencia esta vacio !!!")==false)
               {
                 f.txtcodigo.focus();
               }
            else
               {
	             if (valida_null(txtcodigosistema,"El Código del Sistema esta vacio  !!!")==false)
	                {
	                  f.txtcodigosistema.focus();
	                }
	             else  
	                {
                      if (valida_null(txtoperacion,"La Operación de Procedencia esta vacia !!!")==false)
  		                 {
 	                       f.txtoperacion.focus();
	    		         }
		              else
		                 {
		                   if (valida_null(txtdescripcion,"La Descripción de la Procedencia esta vacia !!!")==false)
  		                      {
 	                            f.txtoperacion.focus();
	    		              }
				           else
				              {
					            f=document.form1;
					            f.operacion.value="GUARDAR";
					            f.action="sigesp_cfg_d_procedencia.php";
					            f.submit();
			                  }
				         }
			        }
	           }
	      }
   }
  else
	{
	  alert("No tiene permiso para realizar esta operación");
	}
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
			   f.operacion.value="ELIMINAR";
			   f.action="sigesp_cfg_d_procedencia.php";
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

function ue_buscar()
{
	f=document.form1;
    li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     f.operacion.value="";			
	     pagina="sigesp_cfg_cat_procedencia.php";
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
       }
	else
	   {
	     alert("No tiene permiso para realizar esta operación");
	   }   
}

function catalogo_sistemas()
{
	pagina="sigesp_cfg_cat_sistemas.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

</script>
</html>