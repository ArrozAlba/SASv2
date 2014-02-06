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
	require_once("../class_folder/class_funciones_cfg.php");
	$io_fun_cfg=new class_funciones_cfg();
	$io_fun_cfg->uf_load_seguridad("CFG","sigesp_spg_d_tipomodificaciones.php",$ls_permisos,$la_seguridad,$la_permisos);
	//$io_fun_cfg->uf_load_seguridad("CXP","sigesp_cxp_p_solicitudpago.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Registro de Tipos de Modificaciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
require_once("class_folder/sigesp_spg_c_tipomodificaciones.php");
$io_cfg= new sigesp_spg_c_tipomodificaciones();
require_once("../../shared/class_folder/class_mensajes.php");
$io_msg= new class_mensajes();		

$ls_operacion=$io_fun_cfg->uf_obteneroperacion();
$ls_codtipmodpre=$io_fun_cfg->uf_obtenervalor("txtcodtipmodpre","");
$ls_dentipmodpre=$io_fun_cfg->uf_obtenervalor("txtdentipmodpre","");
$ls_pretipmodpre=$io_fun_cfg->uf_obtenervalor("txtpretipmodpre","");
$ls_contipmodpre=$io_fun_cfg->uf_obtenervalor("txtcontipmodpre","");
$ls_status=$io_fun_cfg->uf_obtenervalor("status","");
$ls_codemp=$_SESSION["la_empresa"]["codemp"];
switch ($ls_operacion)
{
	case "NUEVO":
		require_once("../../shared/class_folder/sigesp_c_generar_consecutivo.php");
		$io_keygen= new sigesp_c_generar_consecutivo();
		$ls_codtipmodpre= $io_keygen->uf_generar_numero_nuevo("SPG","spg_tipomodificacion","codtipmodpre","SPGMOD",4,"","","");
		$ls_dentipmodpre="";
		$ls_pretipmodpre="";
		$ls_contipmodpre="";
		$ls_status="";
	break;
	case "GUARDAR":
		if($ls_status=="C")
		{
		    $ls_codtipmodpreaux=$ls_codtipmodpre;
			$lb_valido=$io_cfg->uf_update_tipomodificacion($ls_codemp,$ls_codtipmodpre,$ls_dentipmodpre,$la_seguridad);
		}
		else
		{   
			$ls_codtipmodpreaux=$ls_codtipmodpre;
			$lb_valido=$io_cfg->uf_insert_tipomodificacion($ls_codemp,&$ls_codtipmodpre,$ls_dentipmodpre,$ls_pretipmodpre,
														   $ls_contipmodpre,$la_seguridad);
		}
		if($lb_valido)
		{
			if($ls_codtipmodpreaux!=$ls_codtipmodpre)
			{
				$io_msg->message("Se le asigno el codigo ".$ls_codtipmodpre);
			}
			$io_msg->message("La operacion se ejecuto satisfactoriamente");
			$ls_codtipmodpre="";
			$ls_dentipmodpre="";
			$ls_pretipmodpre="";
			$ls_contipmodpre="";
			$ls_status="";
		}
		else
		{
			$io_msg->message("Ocurrio un error al procesar la operacion");
		}
	break;
	case "ELIMINAR":
		$lb_valido=$io_cfg->uf_delete_tipomodificacion($ls_codemp,$ls_codtipmodpre,$la_seguridad);
		if($lb_valido)
		{
			$ls_codtipmodpre="";
			$ls_dentipmodpre="";
			$ls_pretipmodpre="";
			$ls_contipmodpre="";
			$ls_status="";
		}
	break;
}

?>
<p align="center"><font size="4" face="Verdana, Arial, Helvetica, sans-serif"></font></p>
<p align="center">&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cfg->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cfg);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="519" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="517" height="207"><div align="center">
        <table  border="0" cellspacing="0" cellpadding="0" class="formato-blanco" align="center">
          <tr>
            <td height="22" colspan="2" class="titulo-ventana">Registro de Tipos de Modificaciones </td>
          </tr>
          <tr>
            <td height="22" >&nbsp;</td>
            <td height="22" ><span class="style1"></span></td>
          </tr>
          <tr>
            <td width="115" height="22" ><div align="right">C&oacute;digo</div></td>
            <td width="353" height="22" style="text-align:left " ><input name="txtcodtipmodpre" type="text" id="txtcodtipmodpre" value="<?php print  $ls_codtipmodpre ?>" size="8" maxlength="4" onKeyPress="return keyRestrict(event,'1234567890');" onBlur="javascript:rellenar_cad(this.value,4)" style="text-align:center ">
                <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">
                <input name="status" type="hidden" id="status" value="<?php print $ls_status; ?>"></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Denominaci&oacute;n</div></td>
            <td height="22"  style="text-align:left "><input name="txtdentipmodpre" id="txtdentipmodpre" value="<?php print $ls_dentipmodpre ?>" type="text" size="60" maxlength="100" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz '+'.,-')";></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Prefijo</div></td>
            <td height="22"   style="text-align:left "><input name="txtpretipmodpre" type="text" id="txtpretipmodpre" value="<?php print $ls_pretipmodpre ?>" size="6" maxlength="3"  onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz ')";></td>
          </tr>
          <tr>
            <td height="22"><div align="right">Contador</div></td>
            <td height="22"><input name="txtcontipmodpre" type="text" id="txtcontipmodpre"  value="<?php print $ls_contipmodpre ?>" maxlength="12"  onKeyPress="return keyRestrict(event,'1234567890');" onBlur="javascript: ue_rellenarcampo(this,12);"></td>
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
f = document.form1;
function ue_nuevo()
{
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.operacion.value="NUEVO";
	   f.action="sigesp_spg_d_tipomodificaciones.php";
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
		with (document.form1)
		{
	        if (campo_requerido(txtcodtipmodpre,"El código no debe estar en blanco")==false)
		       {
		         txtcodigo.focus();
		       }
 	        else
		       {
		         resul=rellenar_cad(document.form1.txtcodtipmodpre.value,4);
				 document.form1.txtcodtipmodpre.value= resul;   
		         if (campo_requerido(txtdentipmodpre,"La no debe estar en blanco")==false)
			        {
			          txtdenominacion.focus();
			        }
		         else
			        {
			          f.operacion.value="GUARDAR";
			          f.action="sigesp_spg_d_tipomodificaciones.php";
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
     if (f.txtcodtipmodpre.value=="")
        {
	      alert("No ha seleccionado ningún registro para eliminar ");
        }
	 else
	    {
		  borrar=confirm("¿ Esta seguro de eliminar este registro ?");
		  if (borrar==true)
		     { 
			   f.operacion.value="ELIMINAR";
			   f.action="sigesp_spg_d_tipomodificaciones.php";
			   f.submit();
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
	return cadena;
	//document.form1.txtcodtipmodpre.value=cadena;
}
		
function ue_buscar()
{
	li_leer=f.leer.value;
	if (li_leer==1)
	   {
	     f.operacion.value="";			
	     pagina="sigesp_spg_cat_tipomodificacion.php";
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=300,resizable=yes,location=no");
       }
	 else
	   {
 	     alert("No tiene permiso para realizar esta operación !!!");
	   }
}  
function ue_rellenarcampo(valor,maxlon)
{
	var total;
	var auxiliar;
	var longitud;
	var index;
	
	total=0;
    auxiliar=valor.value;
	longitud=valor.value.length;
	total=maxlon-longitud;
	if (total < maxlon)
	{
		for (index=0;index<total;index++)
		{
		   auxiliar="0"+auxiliar;      
		}
		valor.value = auxiliar;
	}
}

</script>
</html>