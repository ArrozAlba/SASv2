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
$io_fun_activo->uf_load_seguridad("SIV","sigesp_siv_d_almacen.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codalm,$ls_nomfisalm,$ls_desalm,$ls_telalm,$ls_ubialm,$ls_nomresalm,$ls_telresalm,$ls_readonly;
		
		$ls_codalm="";
		$ls_nomfisalm="";
		$ls_desalm="";
		$ls_telalm="";
		$ls_ubialm="";
		$ls_nomresalm="";
		$ls_telresalm="";
		$ls_readonly="";
   }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Almac&eacute;n</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<link href="css/siv.css" rel="stylesheet" type="text/css">
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
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
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
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Inventario </td>
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
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" class="sin-borde"></a></div></td>
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
	require_once("sigesp_siv_c_almacen.php");
	$io_siv= new sigesp_siv_c_almacen();

	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];

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
			
			$ls_emp=false;
			$ls_codemp="";
			$ls_tabla="siv_almacen";
			$ls_columna="codalm";
		
			$ls_codalm=$io_fun->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
			if($ls_codalm==false)
			{
				print "<script language=JavaScript>";
				print "location.href='sigespwindow_blank.php'";
				print "</script>";
			}
		break;
		
		case "GUARDAR";
		
		$ls_valido= false;
		$ls_readonly="";
		$ls_codalm    =$_POST["txtcodalm"];
		$ls_nomfisalm =$_POST["txtnomfisalm"];
		$ls_desalm    =$_POST["txtdesalm"];
		$ls_telalm    =$_POST["txttelalm"];
		$ls_ubialm    =$_POST["txtubialm"];
		$ls_nomresalm =$_POST["txtnomresalm"];
		$ls_telresalm =$_POST["txttelresalm"];
		$ls_status    =$_POST["hidstatus"];
		
		if( ($ls_codalm=="")||($ls_nomfisalm=="")||($ls_desalm=="")||($ls_telalm=="")||($ls_ubialm=="")||($ls_nomresalm=="")||($ls_telresalm==""))
			{
				$io_msg->message("Debe completar todos los campos");
			}
		else
			{
				if ($ls_status=="C")
				{
					$lb_valido=$io_siv->uf_siv_update_almacen($ls_codemp,$ls_codalm,$ls_nomfisalm,$ls_desalm,$ls_telalm,$ls_ubialm,
																	$ls_nomresalm,$ls_telresalm,$la_seguridad);
	
					if($lb_valido)
					{
						$io_msg->message("El almacén fue actualizado");
						uf_limpiarvariables();
						
					}	
					else
					{
						$io_msg->message("El almacén no pudo ser actualizado");
					}
				}
				else
				{
					$lb_encontrado=$io_siv->uf_siv_select_almacen($ls_codemp,$ls_codalm);
					if ($lb_encontrado)
					{
						$io_msg->message("El almacén ya existe"); 
					}
					else
					{
						$lb_valido=$io_siv->uf_siv_insert_almacen($ls_codemp,$ls_codalm,$ls_nomfisalm,$ls_desalm,$ls_telalm,$ls_ubialm,
																	$ls_nomresalm,$ls_telresalm,$la_seguridad);

						if ($lb_valido)
						{
							$io_msg->message("El almacén  fue registrado.");
							uf_limpiarvariables();
						}
						else
						{
						$io_msg->message("No se pudo registrar el almacen");
						}
					
					}
				}
				
			}
		break;

		case "ELIMINAR":
			$ls_codalm=$_POST["txtcodalm"];
			$io_msg=new class_mensajes();
			
			$lb_valido=$io_siv->uf_siv_delete_almacen($ls_codemp,$ls_codalm,$la_seguridad);
	
			if($lb_valido)
			{
				$io_msg->message("El almacén fue eliminado");
				uf_limpiarvariables();
				$ls_readonly="readonly";
			}	
			else
			{
				$io_msg->message("No se pudo eliminar el almacén");
				uf_limpiarvariables();
			}
		break;
	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
  <table width="575" height="264" border="0" class="formato-blanco">
    <tr>
      <td width="600" height="258"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="514" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td colspan="2" class="titulo-ventana">Definici&oacute;n de Almac&eacute;n</td>
  </tr>
  <tr class="formato-blanco">
    <td width="143" height="13">&nbsp;</td>
    <td width="352">
      <div align="left"></div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">C&oacute;digo</div></td>
    <td><div align="left">
        <input name="txtcodalm" type="text" id="txtcodalm" onBlur="javascript: ue_rellenarcampo(this,10);"  onKeyPress="return keyRestrict(event,'1234567890'); " value="<?php print $ls_codalm?>" size="17" maxlength="10" <?php print $ls_readonly?> style="text-align:center ">
        <input name="hidstatus" type="hidden" id="hidstatus">
    </div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Nombre Fiscal </div></td>
    <td><div align="left">
        <input name="txtnomfisalm" type="text" id="txtnomfisalm" value="<?php print $ls_nomfisalm?>" size="50" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyzáéíóú., ()@#!%/[]*-+_');">
    </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Descripcion</div></td>
    <td><div align="left">
        <input name="txtdesalm" type="text" id="txtdesalm" value="<?php print $ls_desalm?>" size="50" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyzáéíóú., ()@#!%/[]*-+_');">
    </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Tel&eacute;fono</div></td>
    <td><div align="left">
        <input name="txttelalm" type="text" id="txttelalm" value="<?php print $ls_telalm?>" size="17" maxlength="20" onKeyPress="return keyRestrict(event,'1234567890'+'-() '); ">
    </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Ubicaci&oacute;n</div></td>
    <td><div align="left">
        <input name="txtubialm" type="text" id="txtubialm" value="<?php print $ls_ubialm?>" size="50" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyzáéíóú., ()@#!%/[]*-+_');">
    </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Nombre del Responsable </div></td>
    <td><div align="left">
        <input name="txtnomresalm" type="text" id="txtnomresalm" value="<?php print $ls_nomresalm?>" size="30" readonly>
        <a href="javascript: ue_cata();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Tel&eacute;fono del Responsable </div></td>
    <td><div align="left">
        <input name="txttelresalm" type="text" id="txttelresalm" value="<?php print $ls_telresalm?>" size="17" maxlength="20" onKeyPress="return keyRestrict(event,'1234567890'+'-() '); " >
    </div></td>
  </tr>
</table>
<input name="operacion" type="hidden" id="operacion">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones
function ue_cata()
{
	window.open("sigesp_cat_personal.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("sigesp_catdinamic_almacen.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
		f.action="sigesp_siv_d_almacen.php";
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
		f.action="sigesp_siv_d_almacen.php";
		f.submit();
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
			f.action="sigesp_siv_d_almacen.php";
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

function ue_ayuda()
{
	//window.open("../hlp/index.php?sistema=SIV&subsistema=siv/sigesp_hlp_siv_almacen.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width=780,height=580,resizable=yes,location=no");
}

</script> 
</html>