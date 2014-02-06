<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_conf_select_cartaorden.php",$ls_permisos,$la_seguridad,$la_permisos);
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Selecci&oacute;n de Formato de Impresi&oacute;n de Carta Orden</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td width="778" height="20" colspan="11" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	$msg=new class_mensajes();

	require_once("sigesp_scb_c_config.php");
	$in_classconfig=new sigesp_scb_c_config($la_seguridad);

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
	}

	if($ls_operacion == "GUARDAR")
	{
		$ls_codigo=$_POST["cmbformatos"];
		$lb_valido=$in_classconfig->uf_guardar_formato_cartaorden($ls_codigo);
		$msg->message($in_classconfig->is_msg_error);
		if($lb_valido)
		{
			$in_classconfig->io_sql->commit();
					
		}
		else
		{
			$in_classconfig->io_sql->rollback();
		}			
	}	
?>
<p>&nbsp;</p>
<div align="center">
  <table width="601" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="601" height="163" valign="top">
<form name="form1" method="post" action="">
<p>
<?php 
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?></p>
<br>
<table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="3">Selecci&oacute;n de Formato de Impresi&oacute;n de Carta Orden</td>
              </tr>
              <tr class="formato-blanco">
                <td width="269" height="18">&nbsp;</td>
                <td width="295" colspan="2">&nbsp;</td>
              </tr>
              
              <tr class="formato-blanco">
                <td height="22"><div align="right">Formato de Impresi&oacute;n de Carta Orden </div>
                  <div align="right"></div></td>
                <td colspan="2"><div align="left">
                 <?php $in_classconfig->uf_select_formatos_cartaorden();	?>
                </div></td>
              </tr>
			  
            
            <tr class="formato-blanco">
              <td height="20">&nbsp;</td>
              <td colspan="2">&nbsp;</td>
            </tr>
          </table>
            <p><input name="operacion" type="hidden" id="operacion">
              <input name="status" type="hidden" id="status" value="<?php print $ls_status;?>"></p>
        </form></td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">

function ue_guardar()
{
	f=document.form1;
    f.operacion.value ="GUARDAR";
    f.submit();
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

</script>
</html>