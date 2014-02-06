<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";		
}
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
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=new sigesp_include();
	$con=$in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql= new class_sql($con);
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
		
	$ls_logusr=$_SESSION["la_logusr"];
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
		$ls_passuser=   $_POST["txtpassencript"];
		$ls_repassword= $_POST["txtrepassword"];
		$ls_pasact=     $_POST["txtactencript"];

		if( ($ls_passuser=="")||($ls_repassword=="")||($ls_pasact==""))
		{
			$io_msg->message("Debe compeltar todos los campos");
		}
		else
		{
			$ls_sql="SELECT * FROM sss_usuarios".
					" WHERE codusu='".$ls_logusr."'".
					" AND  pwdusu='".$ls_pasact."'";
					
			$result=$io_sql->select($ls_sql);
			if($row=$io_sql->fetch_row($result))
			{
				$ls_sql="UPDATE sss_usuarios SET pwdusu='". $ls_passuser ."'".
						" WHERE codusu='".$ls_logusr."'".
						" AND  pwdusu='".$ls_pasact."'";
						
				$io_sql->begin_transaction();
				$result=$io_sql->execute($ls_sql);
				if ($result<=0)
				{
					$io_msg->message("No se pudo actualizar el password.");
					$io_sql->rollback();		  
				}
				else
				{
					$io_msg->message("Password actualizado.");
					$io_sql->commit();
					print("<script language=JavaScript>");
					print("close();");
					print("</script>");
				}

			}
			else
			{
				$io_msg->message("El password actual no es correcto.");
			}
		
		}
	}
	

?>
<form name="form1" method="post" action="">
  <table width="398" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="436" colspan="2" class="titulo-conect">Cambio de Password </td>
    </tr>
  </table>
  <table width="395" height="197" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td width="220"><div align="right">Password Actual </div></td>
      <td colspan="2"><div align="left">
          <input name="txtpasact" type="password" id="txtpasact" onBlur="javascript: ue_encriptarold();">
          <input name="txtactencript" type="hidden" id="txtactencript">
      </div></td>
    </tr>
    <tr>
      <td><div align="right">Nuevo Password</div></td>
      <td colspan="2"><div align="left">
          <input name="txtpassword" type="password" id="txtpassword">
          <input name="txtpassencript" type="hidden" id="txtpassencript">
      </div></td>
    </tr>
    <tr>
      <td><div align="right">Verificar Password</div></td>
      <td colspan="2"><div align="left">
          <input name="txtrepassword" type="password" id="txtrepassword"  onBlur="javascript: ue_verificar();">
      </div></td>
    </tr>
    <tr>
      <td><input name="operacion" type="hidden" id="operacion"></td>
      <td width="20"><a href="javascript: ue_aceptar();"><img src="imagenes/aceptar2.png" width="25" height="22" border="0"></a></td>
      <td width="153"><a href="javascript: ue_cancelar();"><img src="imagenes/cancelar2.png" width="25" height="22" border="0"></a></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td colspan="2"><div align="left"></div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
<script language="JavaScript">
function ue_aceptar()
{
	f=document.form1;
	f.operacion.value="ACEPTAR";
	f.action="sigesp_sss_p_repassword.php";
	f.submit();
}
function ue_cancelar()
{
	f=document.form1;
	f.operacion.value="CANCELAR";
	f.action="sigesp_sss_p_repassword.php";
	f.submit();

}
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
function ue_encriptarold()
{
	f=document.form1
	password=f.txtpasact.value;
	f.txtactencript.value=calcMD5(password);
}

</script>

</html>
