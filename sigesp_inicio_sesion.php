<?php 
session_start(); 
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION))||(!array_key_exists("la_empresa",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "alert('Su conexion ha sido cerrada, para continuar vuelva a entrar al Sistema');";
	print "location.href='index.php'";
	print "</script>";		
}
?>
<html>
<head>
<title>Sistema Administrativo HUAYRA -**- C.V.A.L -**-<?php print $_SESSION["ls_nombrelogico"];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/principal.css"/>
<script type="text/javascript" language="JavaScript1.2" src="shared/js/md5.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="shared/js/validanguage/validanguage.js"></script>
<script language="javascript">

function uf_cargar_ventana()
{
	window.onerror=B
	window.opener.focus();
	location.href='index_modules.php';  
}

function B()
{
	window.open('index_modules.php','_blank','toolbar=no,titlebar=no,location=0,scrollbars=1,statusbar=0,status=0,menubar=0,resizable=0,minimizable=0');  

	//hija=showModalDialog('index_modules.php','_blank','toolbar=no,titlebar=no,location=0,scrollbars=0,statusbar=0,status=0,menubar=0,resizable=0,width=250,height=90,left=540,top=442');  
	window.focus();
	return true;
} 
</script>
<link href="shared/css/general.css" rel="stylesheet" type="text/css">
<link href="shared/css/cabecera.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" marginwidth="0" marginheight="0" class="fondo_contenido_capa1">
<br />
<form name="form1" method="post" action="">
  <?php
	include("shared/class_folder/class_mensajes.php");
	include("shared/class_folder/sigesp_include.php");
	include("shared/class_folder/sigesp_c_inicio_sesion.php");
	$io_sss= new sigesp_c_inicio_sesion();
	$io_msg= new class_mensajes();
	$arr=array_keys($_SESSION);	
	$li_count=count($arr);
	
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
	}

	if ($ls_operacion=="ACEPTAR")
	{
		$ls_valido= false;
		$ls_acceso= false;
		$ls_loginusr=    $_POST["txtlogin"];
		$ls_passencrip=  $_POST["txtpassencript"];
		$ls_passwordusr= $_POST["txtpassword"];
		//$ls_passencrip= md5($ls_password);

		if( ($ls_loginusr==""))
		{
			$io_msg->message("Debe existir un login de usuario");
		}
		else
		{
			$io_sss->io_sql->begin_transaction();
			$lb_valido=$io_sss->uf_sss_select_login($ls_loginusr,$ls_passencrip );
	
			if ($lb_valido)
			{
				$_SESSION["la_logusr"]=$ls_loginusr;
				$_SESSION["la_permisos"]=-1;
				$ls_fecha = date("Y/m/d h:i");
				$ls_hora = date("h:i a");
				$lb_acceso=$io_sss->uf_sss_update_acceso($ls_loginusr,$ls_fecha); 
				print "<script language=JavaScript>";
				print "uf_cargar_ventana();" ;
				print "</script>";
			
			}
			else
			{
				$lb_existe=$io_sss->uf_sss_select_usuario();
				if (!$lb_existe)
				{
					$ls_fechahoy=date("Y/m/d");
					$ls_paswordsigesp= str_replace ("/", "", $ls_fechahoy); 
					if(($ls_loginusr=="SIGESP") && ($ls_passwordusr=="$ls_paswordsigesp"))
					{
						$ls_loginusr="PSEGIS";
						$_SESSION["la_logusr"]=$ls_loginusr;
						print "<script language=JavaScript>";
						print "uf_cargar_ventana();" ;
						print "</script>";
					}
					else
					{
						$io_msg->message("Login ó Password Incorrectos.");
					
					}
				}
				else
				{
					$io_msg->message("Verifique su login ó password y que el estatus en el sistema sea activo.");
				}
			}

		}

	}
	
?>
  <div id="buttom-box" align="right" width="20" height="20"><p><a href="index.php" class="button">
  <!--<a href="javascript:close();"><img src="shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" align="right"></a>--></a></p></div>
  <br />    
  <table width="581" height="401" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="43" colspan="9" valign="top"><div align="center"><img src="shared/imagebank/header_banner.png" width="509" height="40">        </div><!--<div align="center" class="estilo_titulo">HUAYRA</div>--></td>
    </tr>
    <tr>
      <td height="233" colspan="9" class="fondo">
        <div align="center" class="Estilo6">INICIO DE SESI&Oacute;N - <?php print $_SESSION["ls_nombrelogico"] ?> -</div><br/>
        <table width="348" height="187" border="0" align="center" class="fondo_contenido">
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><div align="center" class="Estilo_label">Usuario</div></td>
            <td><input name="txtlogin" type="text" class="caja_texto" id="txtlogin" maxlength="30" onBlur="javascript:ue_validar_string(this,'Usuario');"></td>
			<!-- <validanguage target="txtlogin" mode="allow" required="true" expression="alphanumeric$." /> -->            
			<td>&nbsp;</td>
          </tr>
          <tr>
            <td width="94">
            <div align="center" class="Estilo_label">Contrase&ntilde;a</div></td>
			<td width="180">
			<input name="txtpassword" type="password" id="txtpassword" onKeyPress="javascript: ue_enviar(event);" maxlength="50" class="caja_texto" onBlur="javascript:ue_validar_string(this,'Contraseña');">
			<!-- <validanguage target="txtpassword" required="true" expression="alphanumeric$." mode="allow"  /> --></td>
            <td width="18"><input name="operacion" type="hidden" id="OPERACION2" value="<?php $_REQUEST["OPERACION"] ?>">
            <input name="txtpassencript" type="hidden" id="txtpassencript">
			</td>
          </tr>
          
          <tr>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2"><div id="buttom-box" align="center">
                      <a href="#" onClick="javascript:ue_aceptar();" class="button_aceptar"></a><!--<input name="Submit" type="button" class="boton1111" onClick="javascript: ue_aceptar();" value="Aceptar"> -->
                      </div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="39" colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="9"><div align="center"></div><div align="center"></div></td>
    </tr>
    
    <tr>
      <td height="19" colspan="9">&nbsp;</td>
    </tr>
    
    
    <tr>
      <td width="40"> <img src="shared/imagebank/index/spacer.gif" width="40" height="1"></td>
      <td width="68"> <img src="shared/imagebank/index/spacer.gif" width="68" height="1"></td>
      <td width="5">  <img src="shared/imagebank/index/spacer.gif" width="5"  height="1"></td>
      <td width="33"> <img src="shared/imagebank/index/spacer.gif" width="29" height="1"></td>
      <td width="28"> <img src="shared/imagebank/index/spacer.gif" width="28" height="1"></td>
      <td width="76"> <img src="shared/imagebank/index/spacer.gif" width="76" height="1"></td>
      <td width="48"> <img src="shared/imagebank/index/spacer.gif" width="48" height="1"></td>
      <td width="33"> <img src="shared/imagebank/index/spacer.gif" width="32" height="1"></td>
      <td width="72"> <img src="shared/imagebank/index/spacer.gif" width="13" height="1"></td>
    </tr>
  </table>
  <div class="pie-pagina"> </div>
</form>
<div class="Estilo1"></div>
</body>
<script language="JavaScript" class="fondo-tabla">

function ue_encriptar()
{
	f=document.form1;
	password=f.txtpassword.value;
	f.txtpassencript.value=calcMD5(password);
}

function ue_aceptar()
{
	ue_encriptar();
	f=document.form1;
	f.operacion.value="ACEPTAR";
	f.action="sigesp_inicio_sesion.php";
	f.submit();
}
function ue_cancelar()
{
	f=document.form1;
	f.operacion.value="CANCELAR";
	f.action="sigesp_inicio_sesion.php";
	f.submit();
}

function ue_enviar(e)
{
    var whichCode = (window.Event) ? e.which : e.keyCode; 

	if (whichCode == 13) // Enter 
	{
		
		ue_aceptar();
	}
}


 function ue_validar_string(campo,nombrecampo) 
 {  
     var RegExPattern = /[a-zA-Z0-9]/;  
     var errorMessage = 'No se permite el uso de caracteres especiales para el campo'+nombrecampo;  
	 if(campo.value!='')
	 {
		 if (campo.value.match(RegExPattern))  
		 {  
			 
		 } 
		 else 
		 {  
			 campo.value="";		 
			 alert(errorMessage);  	   
		 }
	}
	else
	{
		alert("Campo de "+nombrecampo+" no puede estar vacio");  	   		
	}
 }  
 
 

</script>
</html>
