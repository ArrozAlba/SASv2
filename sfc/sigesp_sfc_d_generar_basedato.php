<?php
session_start();
set_time_limit (600);
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='sigesp_inicio_sesion.php'";
	 print "</script>";
   }
$la_datemp=$_SESSION["la_empresa"];
$ls_codtie=$_SESSION["ls_codtienda"];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Generar Respaldo de Base de Datos</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<meta http-equiv="imagetoolbar" content="no" />
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Pragma" content="no-cache" />


<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">

<script language="javascript" src="js/xp_progress.js">

/***********************************************
* WinXP Progress Bar- By Brian Gosselin- http://www.scriptasylum.com/
* Script featured on Dynamic Drive- http://www.dynamicdrive.com
* Please keep this notice intact
***********************************************/

</script>


<style type="text/css">
<!--
.Estilo1 {
	color: #6699CC;
	font-size: 14px;
}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699" >


<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="490" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo1">Sistema de Facturaci&oacute;n</span></td>
    <td width="288" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">
	<a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
           <p>&nbsp;</p>


<div id="resultados" align="center">


<form name="form1" method="post" action="">
  <table width="520" height="108" border="0" cellpadding="0" cellspacing="0" class="contorno">
  <tr height="200">
  <td width="492" height="100" align="center" >

   <table width="480" border="0" cellpadding="0" cellspacing="0" class="contorno">
               <tr class="titulo-celdanew">
                 <td width="480" height="22" class="titulo-celdanew">Respaldo de Base de datos</td>
               </tr>


                 <tr>
				  <td colspan="3" align="center"><div align="left"></div></td>
    			</tr>
	<tr>
 	<td height="83" colspan="3" align="center">

	 <table width="465"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">

		<a href="javascript:generarbasedato();"><img src="../shared/imagebank/tools20/aprobado.gif" width="22" height="22" border="0">Generar Base de Datos</a>

	  </table>
	   </td>
	   </tr>


      <tr>

			     <td height="13">


<div align="right"></div></td>
      </tr>
    </table>
	</td>
	</tr>
    </table>

    <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
  </form>
</div>

               <?php



require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");



$io_in      = new sigesp_include();
$con        = $io_in->uf_conectar();
$io_ds      = new class_datastore();

$io_msg     = new class_mensajes();
$io_funcion = new class_funciones();
$la_emp     = $_SESSION["la_empresa"];

if (array_key_exists("operacion",$_POST))
   {
	 $ls_operacion=$_POST["operacion"];

   }
else
   {
	 $ls_operacion="";
   }




 if($ls_operacion=="generar_bd")
	{

		echo shell_exec('/var/script/respaldo_base.sh');

		?>
			<table align="center" >
			<tr align="center">
			<td align="center"><script language="javascript" src="js/timerbar.js"></script></td>
			</tr>
			</table>
		<?

}

?>

</body>
<script language="JavaScript">
function generarbasedato()
{
			f=document.form1;
			f.operacion.value="generar_bd";
			f.action="sigesp_sfc_d_generar_basedato.php";
			f.submit();


}

</script>
</html>