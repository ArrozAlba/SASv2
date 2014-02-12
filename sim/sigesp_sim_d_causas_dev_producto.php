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
$io_fun_activo=new class_funciones_inventario();
$io_fun_activo->uf_load_seguridad("SIM","sigesp_sim_d_causas_dev_producto.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codcausadev,$ls_descausadev,$ls_obscausadev,$ls_status,$ls_estatusregistro;
		
		$ls_codcausadev="";
		$ls_descausadev="";
		$ls_obscausadev="";
		$ls_status="";
		$ls_estatusregistro="t";
		$ls_readonly="";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Causas de Devoluci&oacute;n de Producto</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<link href="css/sim.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
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
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
.Estilo2 {
	color: #6699CC;
	font-size: 14px;
}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1 Estilo2">Sistema de Inventario </td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("sigesp_sim_c_causa_dev_producto.php");
	$io_siv= new sigesp_sim_c_causa_dev_producto();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		uf_limpiarvariables();
		$ls_readonly="readonly";
	}

	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
			$ls_readonly="";
			
			$ls_emp="";
			$ls_codemp="";
			$ls_tabla="sigesp_causadevolucionproducto";
			$ls_columna="codcaudev";
		
			$ls_codcausadev=$io_fun->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
			if($ls_codcausadev==false)
			{
				print "<script language=JavaScript>";
				print "location.href='sigespwindow_blank.php'";
				print "</script>";
			}
		break;
		
		case "GUARDAR";
		
		$ls_valido= false;
		$ls_readonly="";
		$ls_codcausadev =$_POST["txtcodcausadev"];
		$ls_descausadev =$_POST["txtdescausadev"];
		$ls_obscausadev =$_POST["txtobscausadev"];
		$ls_status     =$_POST["hidstatus"];
		$ls_estatusregistro=$_POST["hidestatusregistro"];
		
		if( ($ls_codcausadev=="")||($ls_descausadev==""))
			{
				$io_msg->message("Debe compeltar los campos código y descripción");
			}
		else
			{
				if ($ls_status=="C")
				{
					$lb_valido=$io_siv->uf_sim_update_causadevolucion($ls_codcausadev,$ls_descausadev,$ls_obscausadev,$la_seguridad);
	
					if($lb_valido)
					{
						$io_msg->message("La causa de devolución de producto fue actualizada.");
					}	
					else
					{
						$io_msg->message("La causa de devolución de producto no pudo ser actualizado");
					}
				}
				else
				{
					$lb_encontrado=$io_siv->uf_sim_select_causadevolucion($ls_codcausadev);
					if ($lb_encontrado)
					{
						$io_msg->message("La causa de devolución de producto ya existe"); 
					}
					else
					{
						$lb_valido=$io_siv->uf_sim_insert_causadevolucion($ls_codcausadev,$ls_descausadev,$ls_obscausadev,$la_seguridad);

						if ($lb_valido)
						{
							$io_msg->message("La causa de devolución de producto fue registrada.");
						}
						else
						{
							$io_msg->message("La causa de devolución de producto no pudo ser registrada");
						}
					
					}
				}
				
			}
		break;

		case "ELIMINAR":
			$ls_codcausadev=$_POST["txtcodcausadev"];
			$io_msg=new class_mensajes();
			
			$lb_valido=$io_siv->uf_sim_delete_causadevolucion($ls_codcausadev,$la_seguridad);
	
			if($lb_valido)
			{
				$io_msg->message("La causa de devolución de producto fue eliminada.");
				uf_limpiarvariables();
				$ls_readonly="readonly";
			}	
			else
			{
				$io_msg->message("No se pudo eliminar la causa de devolución de producto.");
				$ls_readonly="readonly";
			}
		break;
		case "BUSCAR":
			$ls_codcausadev =$_POST["txtcodcausadev"];
			$ls_descausadev =$_POST["txtdescausadev"];
			$ls_obscausadev =$_POST["txtobscausadev"];
			if($ls_status!="C")
			{$ls_readonly="readonly";}	
		break;
	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
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
                <td height="97"><table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td colspan="2" class="titulo-ventana">Definici&oacute;n de Causas de Devoluci&oacute;n de Producto </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="111" height="19">&nbsp;</td>
                    <td width="408">&nbsp;                      </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="23"><div align="right">C&oacute;digo</div></td>
                    <td height="22"><input name="txtcodcausadev" type="text" id="txtcodcausadev" value="<?php print $ls_codcausadev;?>" size="10" maxlength="4" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,4);" style="text-align:center ">
					  <input name="hidstatus" type="hidden" id="hidstatus" value="<?php print $ls_status;?>">
                      <input name="hidestatusregistro" type="hidden" id="hidestatusregistro" value="<?php print $ls_estatusregistro;?>"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="23"><div align="right">Descripci&oacute;n</div></td>
                    <td height="22"><input name="txtdescausadev" type="text" id="txtdescausadev" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ()#!%/[]*-+_.,:;');" value="<?php print $ls_descausadev;?>" size="70" maxlength="255"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="23"><div align="right">Observaci&oacute;n</div></td>
                    <td height="22"><label>
                      <textarea name="txtobscausadev" cols="67" id="txtobscausadev" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ()#!%/[]*-+_.,:;');" ><?php print $ls_obscausadev;?></textarea>
                    </label></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="23">&nbsp;</td>
                    <td height="22">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td height="13">&nbsp;</td>
              </tr>
            </table>
              <input name="operacion" type="hidden" id="operacion">
          </form>
      </div></td>
    </tr>
  </table>
</div>
</body>
<script language="javascript">
//Funciones de operaciones 
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("sigesp_catdinamic_causadevolucion.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_sim_d_causas_dev_producto.php";
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
	lb_status=f.hidstatus.value;
	ls_estatusregistro=f.hidestatusregistro.value;
	ls_codcaudev=f.txtcodcausadev.value;
	ls_codcaudev=f.txtcodcausadev.value;
	ls_descaudev=f.txtdescausadev.value;
	ls_obscaudev=f.txtobscausadev.value;
	if(ls_codcaudev!="" && ls_descaudev!="")
	{
		if(ls_estatusregistro=='t')
		{
			if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_sim_d_causas_dev_producto.php";
				f.submit();		
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
		}
		else
		{
			alert("El registro esta en estatus inactivo, no se puede modificar");
		}	
	}
	else
	{
		alert("Debe indicar al menos código y denominación de la causa de devolución del producto");
	}
}

function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	ls_estatusregistro=f.hidestatusregistro.value;
	lb_status=f.hidstatus.value;
	if(lb_status=='C')
	{
		if(ls_estatusregistro=='t')
		{
			if(li_eliminar==1)
			{	
				if(confirm("¿Seguro desea eliminar el Registro?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_sim_d_causas_dev_producto.php";
					f.submit();
				}
			}
			else
			{
				alert("No tiene permiso para realizar esta operacion");
			}
		}
		else
		{
			alert("El registro esta en estatus inactivo, no se puede modificar");
		}
	}
	else
	{
		alert("El registro no esta almacenado en la base de datos");	
	}
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

</script> 
</html>