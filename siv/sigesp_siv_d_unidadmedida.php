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
$io_fun_activo->uf_load_seguridad("SIV","sigesp_siv_d_unidadmedida.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codunimed,$ls_denunimed,$ls_unidad,$ls_obsunimed;
		
		$ls_codunimed="";
		$ls_denunimed="";
		$ls_unidad="";
		$ls_obsunimed="";
		$ls_readonly="";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Unidad de Medida</title>
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
	require_once("sigesp_siv_c_unidadmedida.php");
	$io_siv= new sigesp_siv_c_unidadmedida();

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
			$ls_tabla="siv_unidadmedida";
			$ls_columna="codunimed";
		
			$ls_codunimed=$io_fun->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
			if($ls_codunimed==false)
			{
				print "<script language=JavaScript>";
				print "location.href='sigespwindow_blank.php'";
				print "</script>";
			}
		break;
		
		case "GUARDAR";
		
		$ls_valido= false;
		$ls_readonly="";
		$ls_codunimed =$_POST["txtcodunimed"];
		$ls_denunimed =$_POST["txtdenunimed"];
		$ls_unidad    =$_POST["txtunidad"];
		$ls_obsunimed =$_POST["txtobsunimed"];
		$ls_status    =$_POST["hidstatus"];
		$ls_unidad=    str_replace(".","",$ls_unidad);
		$ls_unidad=    str_replace(",",".",$ls_unidad);
		
		if( ($ls_codunimed=="")||($ls_denunimed=="")||($ls_unidad==""))
			{
				$io_msg->message("Debe compeltar los campos código, denominación y unidad");
			}
		else
			{
				if ($ls_status=="C")
				{
					$lb_valido=$io_siv->uf_siv_update_unidadmedida($ls_codunimed,$ls_denunimed,$ls_unidad,$ls_obsunimed,$la_seguridad);
	
					if($lb_valido)
					{
						$io_msg->message("La unidad de medida fue actualizada.");
						uf_limpiarvariables();
						
					}	
					else
					{
						$io_msg->message("La unidad de medida no pudo ser actualizado");
						uf_limpiarvariables();
					}
				}
				else
				{
					$lb_encontrado=$io_siv->uf_siv_select_unidadmedida($ls_codunimed);
					if ($lb_encontrado)
					{
						$io_msg->message("La unidad de medida ya existe"); 
					}
					else
					{
						$lb_valido=$io_siv->uf_siv_insert_unidadmedida($ls_codunimed,$ls_denunimed,$ls_unidad,$ls_obsunimed,$la_seguridad);

						if ($lb_valido)
						{
							$io_msg->message("La unidad de medida registrada.");
							uf_limpiarvariables();
						}
						else
						{
							$io_msg->message("La unidad de medida no pudo ser registrada");
							uf_limpiarvariables();
						}
					
					}
				}
				
			}
		break;

		case "ELIMINAR":
			$ls_codunimed=$_POST["txtcodunimed"];
			$io_msg=new class_mensajes();
			
			$lb_valido=$io_siv->uf_siv_delete_unidadmedida($ls_codunimed,$la_seguridad);
	
			if($lb_valido)
			{
				$io_msg->message("La unidad de medida fue eliminada.");
				uf_limpiarvariables();
				$ls_readonly="readonly";
			}	
			else
			{
				$io_msg->message("No se pudo eliminar la unidad de medida.");
				uf_limpiarvariables();
				$ls_readonly="readonly";
			}
		break;
		case "BUSCAR":
			$ls_codunimed =$_POST["txtcodunimed"];
			$ls_denunimed =$_POST["txtdenunimed"];
			$ls_unidad    =$_POST["txtunidad"];
			$ls_obsunimed =$_POST["txtobsunimed"];
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
                <td height="176"><table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td colspan="2" class="titulo-ventana">Definici&oacute;n de Unidad de Medida</td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="111" height="19">&nbsp;</td>
                    <td width="408">&nbsp;                      </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="23"><div align="right">C&oacute;digo</div></td>
                    <td height="22"><input name="txtcodunimed" type="text" id="txtcodunimed" value="<?php print $ls_codunimed?>" size="10" maxlength="4" <?php print $ls_readonly?> onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,4);" style="text-align:center ">
                      <input name="hidstatus" type="hidden" id="hidstatus"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="23"><div align="right">Denominaci&oacute;n</div></td>
                    <td height="22"><input name="txtdenunimed" type="text" id="txtdenunimed" value="<?php print $ls_denunimed?>" size="50" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ()#!%/[]*-+_.,:;');"></td>
                  </tr>
                  <tr>
                    <td height="23"><div align="right">Unidad</div></td>
                    <td height="22"><input name="txtunidad" type="text" id="txtunidad" value="<?php print number_format($ls_unidad,2,',','.');?>" size="10" onKeyPress="return(ue_formatonumero(this,'.',',',event));" <?php print $ls_readonly;?>></td>
                  </tr>
                  <tr>
                    <td height="23"><div align="right">Observaci&oacute;n</div></td>
                    <td rowspan="2"><textarea name="txtobsunimed" cols="50" rows="3" id="txtobsunimed" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ()#!%/[]*-+_.,:;');"><?php print $ls_obsunimed?></textarea>
                    </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="21">&nbsp;</td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
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
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("sigesp_catdinamic_unidadmedida.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
		f.action="sigesp_siv_d_unidadmedida.php";
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
		li_unidad=f.txtunidad.value;
		li_unidad=li_unidad.replace('.','');		
		li_unidad=li_unidad.replace(',','.');		
		if(li_unidad<=0)
		{
			alert("La unidad de medida debe ser mayor que 0,00");
		}
		else
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_siv_d_unidadmedida.php";
			f.submit();
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
	if(li_eliminar==1)
	{	
		if(confirm("¿Seguro desea eliminar el Registro?"))
		{
			f.operacion.value="ELIMINAR";
			f.action="sigesp_siv_d_unidadmedida.php";
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