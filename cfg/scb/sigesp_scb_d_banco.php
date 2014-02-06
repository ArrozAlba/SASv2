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
<title>Definición de Bancos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div>      <div align="center"></div>      <div align="center"></div>      <div align="center"></div>      <div align="center"></div></td>
  </tr>
</table>
<?php
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("sigesp_scb_c_banco.php");
require_once("../../shared/class_folder/class_funciones_db.php");
require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");
require_once("../../shared/class_folder/sigesp_c_generar_consecutivo.php");
$io_keygen= new sigesp_c_generar_consecutivo();
$io_msg         = new class_mensajes();
$io_servicioect = new sigesp_include();//Instanciando la Sigesp_Include.
$conn           = $io_servicioect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql         = new class_sql($conn);//Instanciando la Clase Class Sql.
$fun_db         = new class_funciones_db($conn);
$io_chkrel      = new sigesp_c_check_relaciones($conn);
/////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre       = $_SESSION["la_empresa"];
	$ls_empresa = $arre["codemp"];
	$ls_codemp  = $ls_empresa;
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	$ls_sistema="CFG";
	$ls_ventanas="sigesp_scb_d_banco.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventanas;

$in_classbanco  = new sigesp_scb_c_banco($la_security);

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos           = $_POST["permisos"];
			$la_accesos["leer"]    = $_POST["leer"];
			$la_accesos["incluir"] = $_POST["incluir"];
			$la_accesos["cambiar"] = $_POST["cambiar"];
			$la_accesos["eliminar"]= $_POST["eliminar"];
			$la_accesos["imprimir"]= $_POST["imprimir"];
			$la_accesos["anular"]  = $_POST["anular"];
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
	//Inclusión de la clase de seguridad.
	
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////


	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_codigo   = $_POST["txtcodigo"];
		$ls_nombre   = $_POST["txtnombre"];
		$ls_direccion= $_POST["txtdireccion"];
		$ls_gerente  = $_POST["txtgerente"];
		$ls_telefono = $_POST["txttelefono"];
		$ls_celular  = $_POST["txtcelular"];
		$ls_email    = $_POST["txtemail"];
		$ls_codsude  = $_POST["txtcodsude"];
		if (array_key_exists("chktesoreria",$_POST))
		   {
			 $lb_tesoreria=1;	
			 $lb_tesoreria_checked="checked";
		   }
		else
		   {
			 $lb_tesoreria=0;
			 $lb_tesoreria_checked="";
		   }
		$ls_status=$_POST["status"];
		$readonly    = "";
	}
	else
	{
		$ls_operacion= "NUEVO";
		$ls_codigo   = "";
		$ls_nombre   = "";
		$ls_direccion= "";
		$ls_gerente  = "";
		$ls_telefono = "";
		$ls_celular  = "";
		$ls_email    = "";
		$ls_codsude  = "";
		$ls_status="N";
		$lb_tesoreria=0;
		$lb_tesoreria_checked="";
		$readonly="";
	}

	if($ls_operacion == "NUEVO")
	{
		$ls_codigo= $io_keygen->uf_generar_numero_nuevo("CFG","scb_banco","codban","CFGBAN",3,"","","");
		$ls_nombre            = "";
		$ls_direccion         = "";
		$ls_gerente           = "";
		$ls_telefono          = "";
		$ls_celular           = "";
		$ls_email             = "";
		$ls_status            = "N";
		$lb_tesoreria         = 0;
		$lb_tesoreria_checked = "";
		$ls_codsude  = "";
		$readonly             = "";
	}
	if($ls_operacion == "GUARDAR")
	{
		$ls_codigoaux=$ls_codigo;
		$lb_valido=$in_classbanco->uf_guardar_banco($ls_codigo,$ls_nombre,$ls_direccion,$ls_gerente,$ls_telefono,$ls_celular,$ls_email,$lb_tesoreria,$ls_status,$ls_codsude);
		if(($lb_valido)&&($ls_codigoaux!=$ls_codigo))
		{
			$io_msg->message("Se le asigno el codigo ".$ls_codigo);
		}
		$io_msg->message($in_classbanco->is_msg_error);
		$readonly="readonly";
			
	}
	if($ls_operacion == "ELIMINAR")
	{
 	    $lb_existe = $in_classbanco->uf_select_banco($ls_codigo);
		if ($lb_existe)
		   {
		     $ls_condicion = " AND (column_name='codban')";//Nombre del o los campos que deseamos buscar.
	         $ls_mensaje   = "";                              //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
	         $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_codemp,$ls_condicion,'scb_banco',$ls_codigo,$ls_mensaje);//Verifica los movimientos asociados a la cuenta  
		     if (!$lb_tiene)
		        {
				  $lb_valido = $in_classbanco->uf_delete_banco($ls_codigo,$ls_nombre,$ls_direccion,$ls_gerente,$ls_telefono,$ls_celular,$ls_email,$ls_status,$ls_codsude);
				  if ($lb_valido)
				     {
					   $io_sql->commit();
				       $io_msg->message("Registro Eliminado !!!");
					   $ls_codigo= $io_keygen->uf_generar_numero_nuevo("CFG","scb_banco","codban","CFGBAN",3,"","","");
					   $ls_nombre   = "";
					   $ls_direccion= "";
					   $ls_gerente  = "";
					   $ls_telefono = "";
					   $ls_celular  = "";
					   $ls_email    = "";
					   $ls_codsude  = "";
				     }
				  else
				     {
				       $io_sql->rollback();
				       $io_msg->message($io_servicio->is_msg_error);
					 } 
				}
		     else
			    {
			      $io_msg->message($io_chkrel->is_msg_error);
				}
		   }
		else
		   {
		     $io_msg->message($in_classbanco->is_msg_error);
		     $readonly="";
		   }
	}
?>
<p>&nbsp;</p>
<div align="center">
  <table width="601" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="601" height="221" valign="top"><form name="form1" method="post" action="">
				<?php 
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer     value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir  value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar  value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular   value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
          <p>&nbsp;</p>
          <table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="3">Definici&oacute;n de Bancos </td>
              </tr>
              <tr class="formato-blanco">
                <td height="22">&nbsp;</td>
                <td width="450" height="22" colspan="2">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td width="114" height="22" style="text-align:right">C&oacute;digo</td>
                <td height="22" colspan="2"><input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center " value="<?php print $ls_codigo?>" size="8" maxlength="3" onBlur="javascript:rellenar_cad(this.value,3,'cod')" <?php print $readonly ?>  onKeyPress="return keyRestrict(event,'1234567890');"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" style="text-align:right">Nombre</td>
                <td height="22" colspan="2" style="text-align:left"><input name="txtnombre" type="text" id="txtnombre" style="text-align:left" value="<?php print $ls_nombre?>" size="65" maxlength="60" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@#/'+'´áéíóú');"></td>
              </tr>
			  
              <tr class="formato-blanco">
                <td height="22" style="text-align:right">Direcci&oacute;n</td>
                <td height="22" colspan="2" style="text-align:left"><input name="txtdireccion" type="text" id="txtdireccion" value="<?php print $ls_direccion;?>" size="82" maxlength="80" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@#/'+'´áéíóú');"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" style="text-align:right">Gerente</td>
                <td height="22" colspan="2" style="text-align:left"><input name="txtgerente" type="text" id="txtgerente" value="<?php print $ls_gerente;?>" size="65" maxlength="60" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@#/'+'´áéíóú');"></td>
              </tr>
            <tr class="formato-blanco">
                <td height="22" style="text-align:right">Tel&eacute;fono</td>
                <td height="22" colspan="2" style="text-align:left"><input name="txttelefono" type="text" id="txttelefono" value="<?php print $ls_telefono;?>" size="24" maxlength="20" onKeyPress="return keyRestrict(event,'1234567890'+'.,-');"></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" style="text-align:right">Movil de Contacto</td>
              <td height="22" colspan="2" style="text-align:left"><input name="txtcelular" type="text" id="txtcelular" value="<?php print $ls_celular;?>" size="24" maxlength="20" onKeyPress="return keyRestrict(event,'1234567890');"></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" style="text-align:right">Email de Contacto</td>
              <td height="22" colspan="2" style="text-align:left"><input name="txtemail" type="text" id="txtemail" value="<?php print $ls_email;?>" size="65" maxlength="60" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+',-.;*&?¿!¡+()[]{}%@#/'+'´áéíóú');"></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" style="text-align:right">C&oacute;digo Sudeban</td>
              <td height="22" colspan="2" style="text-align:left"><input name="txtcodsude" type="text" id="txtcodsude" value="<?php print $ls_codsude ?>" size="8" maxlength="4" onKeyPress="return keyRestrict(event,'1234567890');"  style="text-align:center " onBlur="javascript:rellenar_cad(this.value,4,'codsud');"></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22" style="text-align:right">Tesoreria Nacional</td>
              <td height="22" colspan="2" style="text-align:left"><input name="chktesoreria" type="checkbox" id="chktesoreria" value="1" <?php print $lb_tesoreria_checked;?>></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22">&nbsp;</td>
              <td height="22" colspan="2">&nbsp;</td>
            </tr>
          </table>
            <p>&nbsp;</p>
            <p align="center">
            <input name="operacion" type="hidden" id="operacion">
            <input name="status" type="hidden" id="status" value="<?php print $ls_status;?>">
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
	if (li_incluir==1)
	   {	
         f.operacion.value ="NUEVO"; 
         f.action="sigesp_scb_d_banco.php";
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
	lb_status=f.status.value;
	if (((lb_status=="C")&&(li_cambiar==1))||(lb_status=="N")&&(li_incluir==1))
	   {
         ls_codigo=f.txtcodigo.value;
         ls_nombre=f.txtnombre.value;
         ls_direccion=f.txtdireccion.value;
         ls_gerente=f.txtgerente.value;
         ls_telefono=f.txttelefono.value;
         ls_celular=f.txtcelular.value;
         ls_email=f.txtemail.value;
		 ls_codsude=f.txtcodsude.value;
	     if ((ls_nombre!="")&&(ls_nombre!="")&&(ls_direccion!="")&&(ls_gerente!="")&&(ls_telefono!="")&&(ls_celular!="")&&(ls_codsude!=""))
	        {
	 	      f.operacion.value ="GUARDAR";
		      f.action="sigesp_scb_d_banco.php";
		      f.submit();
		      f.txtcodigo.focus(true);
	        }
	     else
	        {
		      alert("No ha completado los datos");	
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
if (li_eliminar==1)
   {	
     if (confirm("¿ Está seguro de eliminar este registro ?"))
		{
	      ls_codigo=f.txtcodigo.value;
	      ls_nombre=f.txtnombre.value;
	      ls_direccion=f.txtdireccion.value;
	      ls_gerente=f.txtgerente.value;
	      ls_telefono=f.txttelefono.value;
	      ls_celular=f.txtcelular.value;
	      ls_email=f.txtemail.value;
		  ls_codsude=f.txtcodsude.value;
	      if ((ls_codigo!="")&&(ls_nombre!=""))
 	         {
		       f.operacion.value ="ELIMINAR";
		       f.action="sigesp_scb_d_banco.php";
		       f.submit();
	         }
	      else
 	         {
		       alert("No ha seleccionado registro a eliminar");		
	         }
	   }
	else
	   {
	     alert("Eliminación Cancelada !!!");
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
 	     window.open("sigesp_scb_cat_bancos.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
       }
	else
	   {
		 alert("No tiene permiso para realizar esta operacion");
	   }   
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

//Funciones de validacion de fecha.
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
		if(campo=="doc")
		{
			document.form1.txtdocumento.value=cadena;
		}
		if(campo=="cmp")
		{
			document.form1.txtcomprobante.value=cadena;
		}
		if(campo=="cod")
		{
			document.form1.txtcodigo.value=cadena;
		}
		if(campo=="codsud")
		{
			document.form1.txtcodsude.value=cadena;
		}
	}
</script>
</html>