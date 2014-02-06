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
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_conf_disponibilidad.php",$ls_permisos,$la_seguridad,$la_permisos);
$li_diasem  = date('w');
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Validación Disponibilidad Financiera</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/valida_fecha.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php
require_once("class_folder/sigesp_scb_c_disponibilidad_financiera.php");
$io_disfin = new sigesp_scb_c_disponibilidad_financiera("../");
if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion = $_POST["operacion"];
     $ls_tipvalfin = $_POST["cmbtipvalfin"];
	 $ls_selnov    = "";
	 $ls_seladv    = "";
	 $ls_selblo    = "";
	 switch ($ls_tipvalfin){
	   case "N":
	     $ls_selnov = "selected";
	   break;
	   case "A":
	     $ls_seladv = "selected";
	   break;
	   case "B":
	     $ls_selblo = "selected";
	   break;	   
	 }
   }
else
   {
     $ls_operacion = "";	
	 $ls_tipvalfin = "N";
	 $ls_selnov    = "selected";
	 $ls_seladv    = "";
	 $ls_selblo    = "";
   }

if ($ls_operacion=='GUARDAR')
   {
     $io_disfin->uf_update_validacion_disponibilidad($ls_tipvalfin,$la_seguridad);
   }
else
   {
	 $ls_tipvalfin = $io_disfin->uf_load_tipo_validacion();
	 switch ($ls_tipvalfin){
	   case "N":
	     $ls_selnov = "selected";
	   break;
	   case "A":
	     $ls_seladv = "selected";
	   break;
	   case "B":
	     $ls_selblo = "selected";
	   break;	   
	 }
   }
?>
<form id="sigesp_scb_p_conf_disponibilidad.php" name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr>
      <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" alt="Encabezado" width="778" height="40" /></td>
    </tr>
    <tr>
      <td height="20" colspan="12" bgcolor="#E7E7E7"><table width="778" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
            <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
          </tr>
        <tr>
            <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
          <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
    </tr>
    <tr>
      <td height="13" class="toolbar">&nbsp;</td>
      <td class="toolbar">&nbsp;</td>
      <td class="toolbar">&nbsp;</td>
      <td class="toolbar">&nbsp;</td>
      <td class="toolbar">&nbsp;</td>
      <td class="toolbar">&nbsp;</td>
    </tr>
    <tr>
      <td height="20" width="22" class="toolbar"><a href="javascript: uf_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0" /></a></td>
      <td class="toolbar" width="22"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0" /></a></td>
      <td class="toolbar" width="22"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20" /></td>
      <td class="toolbar" width="22">&nbsp;</td>
      <td class="toolbar" width="22">&nbsp;</td>
      <td class="toolbar" width="668">&nbsp;</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <table width="286" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="22" colspan="4" class="titulo-celda">Validaci&oacute;n Disponibilidad Financiera 
      <input name="operacion" type="hidden" id="operacion" value="<?php echo $ls_operacion; ?>" /></td>
    </tr>
    <tr>
      <td width="56" height="13">&nbsp;</td>
      <td width="92" height="13">&nbsp;</td>
      <td width="72" height="13">&nbsp;</td>
      <td width="64" height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Tipo</td>
      <td height="22" colspan="2"><label>
        <select name="cmbtipvalfin" size="1" id="cmbtipvalfin">
          <option value="N" <?php print $ls_selnov; ?>>NO VERIFICAR</option>
          <option value="A" <?php print $ls_seladv; ?>>ADVERTIR Y PERMITIR</option>
          <option value="B" <?php print $ls_selblo; ?>>BLOQUEAR</option>
        </select>
      </label></td>
      <td height="22">&nbsp;</td>
    </tr>


    <tr>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
  </table>
</form>
</body>
<script language="javascript">
f = document.form1;

function uf_guardar()
{
  f.action = "sigesp_scb_p_conf_disponibilidad.php";
  f.operacion.value = "GUARDAR";
  f.submit();
}
</script>
</html>