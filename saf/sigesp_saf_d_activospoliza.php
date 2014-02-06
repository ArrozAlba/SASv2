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
require_once("class_funciones_activos.php");
$io_fun_activo=new class_funciones_activos();
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_d_activospoliza.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
		global $ls_rifase,$ls_numpolase,$ls_percobase,$li_moncobase,$ld_fecvigase;
		
		$ls_rifase="";
		$ls_numpolase="";
		$ls_percobase="";
		$li_moncobase="";
		$ld_fecvigase="";
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Datos de la P&oacute;liza de Seguro </title>
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
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones.php");
	$io_fun= new class_funciones();
	require_once("sigesp_saf_c_activoanexos.php");
	$io_saf= new sigesp_saf_c_activoanexos();
	require_once("class_funciones_activos.php");
	$io_fac= new class_funciones_activos();
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$ls_denact=$io_fac->uf_obtenervalor_get("denact","Ninguno");
	$ls_codact=$io_fac->uf_obtenervalor_get("codact","");
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
		$ls_denact=$_POST["txtdenact"];
		$ls_codact=$_POST["txtcodact"];
		
	}
	else
	{
		$ls_operacion="NUEVO";
	}
	
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
			$lb_valido=$io_saf->uf_saf_select_activopoliza($ls_codemp,$ls_codact,$ls_rifase,$ls_numpolase,$ls_percobase,$li_moncobase,
														   $ld_fecvigase);
			$ld_fecvigase=$io_fun->uf_convertirfecmostrar($ld_fecvigase);
		break;

		case "GUARDAR":
			$ls_codact=$io_fac->uf_obtenervalor("txtcodact","");
			$ls_rifase=$io_fac->uf_obtenervalor("txtrifase","");
			$ls_numpolase=$io_fac->uf_obtenervalor("txtnumpolase","");
			$ls_percobase=$io_fac->uf_obtenervalor("txtpercobase","");
			$li_moncobase=$io_fac->uf_obtenervalor("txtmoncobase","");
			$ld_fecvigase=$io_fac->uf_obtenervalor("txtfecvigase","");
			$ld_fecvigaseaux=$io_fun->uf_convertirdatetobd($ld_fecvigase);
			$li_moncobase=str_replace(".","",$li_moncobase);
			$li_moncobase=str_replace(",",".",$li_moncobase);
			$ls_percobase=str_replace(".","",$ls_percobase);
			$ls_percobase=str_replace(",",".",$ls_percobase);

			$lb_valido=$io_saf->uf_saf_update_activopoliza($ls_codemp,$ls_codact,$ls_rifase,$ls_numpolase,$ls_percobase,$li_moncobase,
														   $ld_fecvigaseaux,$la_seguridad);
			if($lb_valido)
			{
				$io_msg->message("El activo ha sido actualizado");
			}
			else
			{
				$io_msg->message("No se pudo actualizar el activo");
			}
		break;
	}

?>
<div align="center">
  <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"close();");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="534" height="175" border="0" class="formato-blanco">
      <tr>
        <td width="526" height="169"><div align="left">
            <table width="493" height="180" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td height="27" colspan="2"><input name="txtcodact" type="hidden" id="txtcodact" value="<?php print $ls_codact ?>">
                <input name="txtdenact" type="text" class="sin-borde2" id="txtdenact2" value="<?php print $ls_denact?>" size="60" readonly></td>
              </tr>
              <tr>
                <td height="17" colspan="2" class="titulo-ventana">Datos de la P&oacute;liza de Seguro </td>
              </tr>
              <tr class="formato-blanco">
                <td width="145" height="19">
                  <div align="left">                    <input name="txtcodemp" type="hidden" id="txtcodemp" value="<?php print $la_codemp?>">
                </div></td>
                <td width="364">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td height="19"><div align="right">R.I.F. Aseguradora</div></td>
                <td><input name="txtrifase" type="text" id="txtrifase" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz-');" value="<?php print $ls_rifase ?>" size="20" style="text-align:center "></td>
              </tr>
              <tr class="formato-blanco">
                <td height="19"><div align="right">N&uacute;mero de P&oacute;liza </div></td>
                <td><input name="txtnumpolase" type="text" id="txtnumpolase" value="<?php print $ls_numpolase ?>" style="text-align:center "  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz-');"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="19"><div align="right">Periodo de Cobertura </div></td>
                <td><input name="txtpercobase" type="text" id="txtpercobase" style="text-align:right "  onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php print number_format($ls_percobase,2,',','.') ?>" size="15">
                A&ntilde;os</td>
              </tr>
              <tr class="formato-blanco">
                <td height="19"><div align="right">Monto</div></td>
                <td><input name="txtmoncobase" type="text" id="txtmoncobase" style="text-align:right "  onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php print number_format($li_moncobase,2,',','.') ?>" size="20"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="19"><div align="right">Fecha de Vigencia </div></td>
                <td><input name="txtfecvigase" type="text" id="txtfecvigase" value="<?php print $ld_fecvigase ?>" size="18" datepicker="true" style="text-align:center "></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" colspan="2"><div align="right">                  </div>
                    <div align="right"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();">Guardar</a><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar" width="15" height="15" border="0"></a><a href="javascript: ue_cancelar();">Cancelar</a> </div></td>
              </tr>
            </table>
            <div align="center"></div>
            <div align="center"></div>
            <div align="center">
              <input name="operacion" type="hidden" id="operacion2">
            </div>
        </div></td>
      </tr>
    </table>
  </form>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

//Funciones de operaciones  
function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if((li_cambiar==1)||(li_incluir==1))
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_saf_d_activospoliza.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function ue_cancelar()
{
	window.close();
}

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>