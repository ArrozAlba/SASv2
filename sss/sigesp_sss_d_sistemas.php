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
require_once("class_funciones_seguridad.php");
$io_fun_activo=new class_funciones_seguridad();
$io_fun_activo->uf_load_seguridad("SSS","sigespwindow_sss_sistemas.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Sistemas</title>
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
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
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

</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
      <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Seguridad</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("sigesp_sss_c_sistemas.php");
	$io_sss= new sigesp_sss_c_sistemas();

	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		$ls_codigo="";
		$ls_nombre="";
	}
	if ($ls_operacion=="GUARDAR")
	{
		$ls_valido= false;
		$ls_codigo=$_POST["txtcodigo"];
		$ls_nombre=$_POST["txtnombre"];
		$ls_status=$_POST["hidstatus"];
		$ls_codvie=$_POST["hidcodigo"];
		$ls_codigo=strtoupper($ls_codigo);
		if( ($ls_codigo=="")||($ls_nombre==""))
		{
			$io_msg->message("Debe compeltar todos los campos");
		}
		else
		{
			if ($ls_status=="C")
			{
				
				if($ls_codigo==$ls_codvie)
				{
					$lb_valido=$io_sss->uf_sss_update_sistema($ls_codigo,$ls_nombre,$la_seguridad);

					if($lb_valido)
					{
						$io_msg->message("El sistema fue actualizado.");							
					}	
					else
					{
						$io_msg->message("No se pudo actualizar el sistema");
					}
				}
				else
				{
					$io_msg->message("No se puede cambiar el campo Codigo.");
				}

			}
			else
			{
				$lb_encontrado=$io_sss->uf_sss_select_sistema($ls_codigo);
				if ($lb_encontrado)
				{
					$io_msg->message("El sistema ya existe."); 
				}
				else
				{
					$lb_valido=$io_sss->uf_sss_insert_sistema($ls_codigo,$ls_nombre,$la_seguridad);
					if ($lb_valido)
					{
						$io_msg->message("El sistema fue registrado.");
					}
					else
					{
						$io_msg->message("No se pudo registrar el sistema.");
					}
				
				}
			}
			
		}
		$ls_codigo="";
		$ls_nombre="";
	}
	elseif ($ls_operacion=="ELIMINAR")
	{
		$ls_codigo=$_POST["txtcodigo"];
		$ls_nombre=$_POST["txtnombre"];
		$lb_valido=$io_sss->uf_sss_delete_sistema($ls_codigo,$la_seguridad);
		if($lb_valido)
		{
			$io_msg->message("El sistema fue eliminado");
		}	
		else
		{
			$io_msg->message("No se pudo eliminar el sistema");		
		}
		$ls_codigo="";
		$ls_nombre="";
		
	}
	elseif ($ls_operacion=="NUEVO")
	{
		$ls_codigo="";
		$ls_nombre="";	
	}

?>

<p>&nbsp;</p>
<div align="center">
  <table width="559" height="184" border="0" align="center" class="formato-blanco">
    <tr>
      <td width="574" height="178"><div align="left">
          <form name="form1" method="post" action="">
		  
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<div align="center">
              <table width="530" height="92" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
                <tr>
                  <td height="18" colspan="2" class="titulo-ventana">Definici&oacute;n de Sistemas </td>
                </tr>
                <tr class="formato-blanco">
                  <td width="111" height="13">&nbsp;</td>
                  <td width="417">&nbsp;</td>
                </tr>
                <tr class="formato-blanco">
                  <td height="22"><div align="right">Codigo</div></td>
                  <td><div align="left">
                    <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codigo ?>" size="6" maxlength="3" onKeyUp="javascript: ue_validarcomillas(this);" style="text-transform:uppercase; text-align:center">
                    <input name="hidstatus" type="hidden" id="hidstatus">
                    <input name="hidcodigo" type="hidden" id="hidcodigo">
</div></td>
                </tr>
                <tr class="formato-blanco">
                  <td height="22"><div align="right">Nombre </div></td>
                  <td><div align="left">
                    <input name="txtnombre" type="text" id="txtnombre" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_nombre ?>" size="50" maxlength="150">                
                  </div></td>
                </tr>
                <tr class="formato-blanco">
                  <td height="15">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
            </div>
            <p align="center">
              <input name="operacion" type="hidden" id="operacion">
            </p>
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_sss_d_sistemas.php";
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
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_sss_d_sistemas.php";
		f.submit();
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
	if(li_leer==1)
	{
		window.open("sigesp_sss_cat_sistemas.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
		if(confirm("¿Seguro desea eliminar el Registro?"))
		{
			f.operacion.value="ELIMINAR";
			f.action="sigesp_sss_d_sistemas.php";
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
	window.location.href="sigespwindow_blank.php";
}
</script> 
</html>