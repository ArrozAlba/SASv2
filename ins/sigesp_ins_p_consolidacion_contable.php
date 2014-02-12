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
	require_once("class_folder/class_funciones_ins.php");
	$io_fun_instala=new class_funciones_ins("../");
	$io_fun_instala->uf_load_seguridad("INS","sigesp_ins_p_consolidacion_contable.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
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

	//***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_column('sigesp_empresa','estempcon');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_31");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
    //***********************************************************************************************************************************
    //***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('sigesp_consolidacion');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_35");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
    //***********************************************************************************************************************************
    //***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('scg_cuentas_consolida');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_37");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
    //***********************************************************************************************************************************
    //***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido=$io_release->io_function_db->uf_select_table('scg_saldos_consolida');
		if($lb_valido==false)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_2_38");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
    //***********************************************************************************************************************************  
    //***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido = $io_release->io_function_db->uf_select_constraint('scg_cuentas_consolida','fk_scg_cuentas_consolida_scg_cuentas');
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_05");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
    //***********************************************************************************************************************************  
    //***********************************************************************************************************************************
	require_once("../shared/class_folder/sigesp_release.php");
    $io_release= new sigesp_release();
	$lb_valido=true;
	if($lb_valido)
	{
		$lb_valido = $io_release->io_function_db->uf_select_constraint('scg_saldos_consolida','pk_scg_saldos_consolida');
		if($lb_valido==true)
		{
			$io_release->io_msg->message("Debe Procesar Instala/Procesos/Mantenimiento/Release 2008_3_06");
			print "<script language=JavaScript>";
			print "location.href='../index_modules.php'";
			print "</script>";		
		}
		unset($io_release);
	}
    //***********************************************************************************************************************************  

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion = $_POST["operacion"];
   }
else
   {
     $ls_operacion = "";
   }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Consolidaci&oacute;n Contable</title>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css" />
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css" />
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css" />
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
-->
</style></head>
<!--<body onLoad="writetostatus('<?php echo "Base de Datos: ".$_SESSION["ls_database"].". Usuario: ".$_SESSION["la_logusr"];?>')">
--><?php
require_once("class_folder/sigesp_ins_c_consolidacion_contable.php");
$io_consolidacion = new sigesp_ins_c_consolidacion_contable("../");
switch ($ls_operacion){
  case 'PROCESAR':
    $io_consolidacion->uf_procesar_consolidacion_contable("../");
  break;
}

?>
<form id="sigesp_ins_p_consolidacion_contable.php" name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_instala->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_instala);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
    <tr>
      <td width="800" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" alt="Encabezado" width="800" height="40" /></td>
    </tr>
    <tr>
      <td height="20" colspan="12" bgcolor="#E7E7E7"><table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Instala</td>
            <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
          </tr>
        <tr>
            <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
          <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="20" bgcolor="#E7E7E7" class="cd-menu" style="text-align:left"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
    </tr>
    <tr>
      <td height="13" colspan="11" class="toolbar"></td>
    </tr>
    <tr style="text-align:left">
      <td width="800" height="13" colspan="11" class="toolbar" style="text-align:left"><span class="toolbar" style="text-align:left"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Procesar" width="20" height="20" border="0" title="Procesar" /></a></span><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir" /></a><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0" title="Ayuda" /></a></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <table width="247" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="22" colspan="6" class="titulo-celda"><input name="operacion" type="hidden" id="operacion" value="<?php echo $ls_operacion; ?>" />
      Consolidaci&oacute;n Contable </td>
    </tr>
    <tr>
      <td width="10">&nbsp;</td>
      <td width="16">&nbsp;</td>
      <td width="57">&nbsp;</td>
      <td width="96">&nbsp;</td>
      <td width="16">&nbsp;</td>
      <td width="50">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6"><label>
        <div align="center">
          <input name="botprocesar" type="button" class="boton" id="botprocesar" value="Procesar Consolidaci&oacute;n" onclick="javascrip:ue_procesar_consolidacion();" />
</div>
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
</body>
<script language="javascript">
f = document.formulario;
function writetostatus(input){
    window.status=input
    return true
}

function ue_procesar_consolidacion()
{
  f.operacion.value = "PROCESAR";
  f.action = "sigesp_ins_p_consolidacion_contable.php";
  f.submit();
}
</script>
</html>