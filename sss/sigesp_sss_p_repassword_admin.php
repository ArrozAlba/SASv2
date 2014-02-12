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
$io_fun_activo->uf_load_seguridad("SSS","sigesp_c_repassword_admin.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Documento sin t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/md5.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<!--<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
-->
</head>

<body>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql= new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones.php");
	$io_funcion = new class_funciones();
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_sistema="SSS";
	$ls_ventanas="sigesp_c_repassword_admin.php";
		
	$ls_logusr=$_SESSION["la_logusr"];
	if(array_key_exists("txtcodigo",$_POST))
	{
		$ls_usuario=$_POST["txtcodigo"];
	}
	else
	{
		$ls_usuario="";
	}

	if(array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
	}
	if($ls_operacion=="ACEPTAR")
	{
		$ls_passuser  =$_POST["txtpassword"];
		$ls_repassword=$_POST["txtrepassword"];
		$ls_usuario   =$_POST["txtcodigo"];
		$ls_passencript=$_POST["txtpassencript"];
		
		if( ($ls_passuser=="")||($ls_repassword=="")||($ls_usuario==""))
		{
			$io_msg->message("Debe compeltar todos los campos");
		}
		else
		{
			$ls_sql="SELECT * FROM sss_usuarios".
					" WHERE codusu='".$ls_usuario."' ";
			$result=$io_sql->select($ls_sql);
			if($row=$io_sql->fetch_row($result))
			{
				$ls_sql="UPDATE sss_usuarios SET pwdusu='". $ls_passencript ."'".
						" WHERE codusu='".$ls_usuario."' ";
				$io_sql->begin_transaction();
				$result=$io_sql->execute($ls_sql);
				if($result===false)
				{
					$io_msg->message("CLASE->usuariosgrupos MÉTODO->uf_sss_load_usuariosasignados ERROR->".$io_funcion->uf_convertirmsg($this->io_sql->message));
					$io_sql->rollback();
				}
				else
				{
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$ls_evento="UPDATE";
					$ls_descripcion ="Actualizó la contraseña del usuario ".$ls_usuario; 
					$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($ls_codemp,$ls_sistema,$ls_evento,$ls_logusr,$ls_ventanas,$ls_descripcion);
					/////////////////////////////////         SEGURIDAD               /////////////////////////////		
					$io_sql->commit();
					$io_msg->message("Password actualizado.");
					print("<script language=JavaScript>");
					print("close();");
					print("</script>");
				}
			}
			else
			{
				$io_msg->message("El usuario no existe.");
				$ls_usuario="";
			}
		
		}
	}
	

?>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  

  <table width="398" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="436" colspan="2" class="titulo-conect">Cambio de Password </td>
    </tr>
  </table>
  <table width="395" height="197" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td width="212"><div align="right">Usuario</div></td>
      <td colspan="3"><div align="left">
          <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_usuario ?>" readonly>
          <a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo de Usuarios" width="15" height="15" border="0"></a>          </div></td>
    </tr>
    <tr>
      <td><div align="right">Nuevo Password</div></td>
      <td colspan="3"><div align="left">
          <input name="txtpassword" type="password" id="txtpassword">
          <input name="txtpassencript" type="hidden" id="txtpassencript">
      </div></td>
    </tr>
    <tr>
      <td><div align="right">Verificar Passwrod</div></td>
      <td colspan="3"><div align="left">
          <input name="txtrepassword" type="password" id="txtrepassword"   onBlur="javascript: ue_verificar();">
      </div></td>
    </tr>
    <tr>
      <td><input name="operacion" type="hidden" id="operacion"></td>
      <td width="30"><a href="javascript: ue_aceptar();"><img src="imagenes/aceptar2.png" alt="Aceptar" width="25" height="22" border="0"></a></td>
      <td width="6">&nbsp;</td>
      <td width="145"><div align="left"><a href="javascript: ue_cancelar();"><img src="imagenes/cancelar2.png" alt="Cancelar" width="25" height="22" border="0"></a></div></td>
    </tr>
    <tr>
      <td><input name="txtempresa" type="hidden" id="txtempresa">      
        <input name="txtnombre" type="hidden" id="txtnombre">
      <input name="txtloginviejo" type="hidden" id="txtloginviejo">
      <input name="txtcedula" type="hidden" id="txtcedula">
      <input name="txtapellido" type="hidden" id="txtapellido">
      <input name="txttelefono" type="hidden" id="txttelefono">
      <input name="txtnota" type="hidden" id="txtnota">
      <input name="hidfoto" type="hidden" id="hidfoto">
      <input name="hidstatus" type="hidden" id="hidstatus">
      <input name="txtultingusu" type="hidden" id="txtultingusu"></td>
      <td colspan="3"><div align="left"></div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
<script language="JavaScript">
function ue_verificar()
{
	f=document.form1
	password=f.txtpassword.value;
	repassword=f.txtrepassword.value;
	if(password!=repassword)
	{
		alert("No coinciden los password");
		f.txtpassword.value="";
		f.txtrepassword.value="";
	}
	else
	{
		f.txtpassencript.value=calcMD5(password);
	}

}

function ue_aceptar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if(li_ejecutar==1)
	{
		f.operacion.value="ACEPTAR";
		f.action="sigesp_sss_p_repassword_admin.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_cancelar()
{
	f=document.form1;
	f.txtcodigo.value="";
	f.txtpassword.value="";
	f.txtrepassword.value="";
}
function ue_buscar()
{
	window.open("sigesp_sss_cat_usuarios.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_cerrar()
{
}
</script>

</html>
