<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";		
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_seguridad.php");
$io_fun_activo=new class_funciones_seguridad();
$io_fun_activo->uf_load_seguridad("SSS","sigesp_sss_p_eliminar_permisos.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (array_key_exists("operacion",$_POST))
{	
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Eliminar Perfil de Seguridad de Usuario </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<div ID="waitDiv" style="position:absolute;left:300;top:300;visibility:hidden"> 
<table cellpadding="6" cellspacing="0" border="1" bgcolor="#000000" bordercolor="#FFFFFF"> 
<tr><td align=center> 
<font color="#ffffff" face="Verdana" size="4">Cargando página...</font> 
</td> 
</tr></table> 
</div> 
</head>
<?php
	
	require_once("../shared/class_folder/sigesp_include.php");
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql=new class_sql($con);
	require_once("sigesp_sss_c_eliminar_permisos.php");
	$io_sss= new sigesp_sss_c_eliminar_permisos();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=new class_mensajes();

	$ls_codusu="";
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	if (array_key_exists("hidsistema",$_POST))
	{	
		$ls_sistema=$_POST["hidsistema"];
	}
	$io_sss->uf_llenar_combo_usuarios($la_usuarios);
	if($ls_operacion=="GUARDAR")
	{
		$io_sql->begin_transaction();
		$ls_codusu=$_POST["cmbusuarios"];
		$lb_valido=$io_sss->uf_sss_delete_permisos($ls_codemp,$ls_codusu);
		if($lb_valido)
		{
			//$lb_valido=$io_sss->uf_sss_delete_usuario_permisos($ls_codemp,$ls_codusu,$la_seguridad);
		}
		if($lb_valido)
		{
			$io_msg->message("Se proceso la eliminación de permisos del Usuario.");
			$io_sql->commit();
		}
		else
		{
			$io_msg->message("No se procesaron permisos de administrador.");
			$io_sql->rollback();		  
		}
	}
?>
<body>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <div align="center"><br>
  </div>
  <div align="center">
    <table width="38%" height="32" border="0" cellpadding="0" cellspacing="0"  class="formato-blanco">
      <tr >
        <td height="17"  class="titulo-ventana"><span class="titulo-celdanew">Eliminar Perfil de Seguridad de Usuario </span></td>
      </tr>
      <tr>
        <td height="13"><table width="383" height="86" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td height="19" colspan="4"><input name="hidsist" type="hidden" id="hidsist3" value="<?php print $ls_sistemas?>" size="6">
              <input name="operacion" type="hidden" id="operacion">              </td>
            </tr>
          <tr>
            <td width="102" height="19"><div align="right">Usuario</div></td>
            <td height="22" colspan="3" align="left"><?php  $io_sss->uf_pintar_combo_usuarios($la_usuarios,$ls_codusu);?>
              <div align="center"></div>
              <div align="center"></div></td>
          </tr>
          <tr>
            <td height="22" colspan="4">
			</td>
            </tr>
          <tr>
            <td height="23">&nbsp;</td>
            <td width="87">&nbsp;</td>
            <td width="126">&nbsp;</td>
            <td width="68"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools15/ejecutar.gif" width="15" height="15" border="0">Procesar</a></td>
          </tr>
        </table></td>
      </tr>
    </table>
  </div>
  <div align="center"></div>
  <p>&nbsp;</p>
</form>
</body>
<script language="JavaScript">
function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		ls_usuario = f.cmbusuarios.value;
		if(ls_usuario!="---")
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_sss_p_eliminar_permisos.php";
			f.submit();
		}
		else
		{
			alert("Debe indicar Usuario y Sistema.");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
</script>

</html>
