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
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_d_activosmantenimiento.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
		global $ls_numconman,$ls_codproman,$ls_denproman,$ld_feciniman,$ld_fecfinman;
		
		$ls_numconman="";
		$ls_codproman="";
		$ls_denproman="";
		$ld_feciniman="";
		$ld_fecfinman="";
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
<title >Datos del Contrato de Mantenimiento</title>
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
			$lb_valido=$io_saf->uf_saf_select_activomantenimiento($ls_codemp,$ls_codact,$ls_numconman,$ls_codproman,
																	$ls_denproman,$ld_feciniman,$ld_fecfinman);
			if($lb_valido)
			{
				$ld_feciniman=$io_fun->uf_convertirfecmostrar($ld_feciniman);
				$ld_fecfinman=$io_fun->uf_convertirfecmostrar($ld_fecfinman);
			}
		break;

		case "GUARDAR":
			$ls_codact=$io_fac->uf_obtenervalor("txtcodact","");
			$ls_numconman=$io_fac->uf_obtenervalor("txtnumconman","");
			$ls_codproman=$io_fac->uf_obtenervalor("txtcodpro","");
			$ls_denproman=$io_fac->uf_obtenervalor("txtdenpro","");
			$ld_feciniman=$io_fac->uf_obtenervalor("txtfeciniman","");
			$ld_fecfinman=$io_fac->uf_obtenervalor("txtfecfinman","");
			$ld_fecinimanaux=$io_fun->uf_convertirdatetobd($ld_feciniman);
			$ld_fecfinmanaux=$io_fun->uf_convertirdatetobd($ld_fecfinman);
			$lb_valido=$io_saf->uf_saf_update_activomantenimiento($ls_codemp,$ls_codact,$ls_numconman,$ls_codproman,$ld_fecinimanaux,
																  $ld_fecfinmanaux,$la_seguridad);
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
            <table width="511" height="161" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td height="27" colspan="2"><input name="txtcodact" type="hidden" id="txtcodact" value="<?php print $ls_codact ?>"><input name="txtdenact" type="text" class="sin-borde2" id="txtdenact2" value="<?php print $ls_denact ?>" size="60" readonly></td>
              </tr>
              <tr>
                <td height="17" colspan="2" class="titulo-ventana">Datos del Contrato de Mantenimiento</td>
              </tr>
              <tr class="formato-blanco">
                <td width="139" height="19">
                  <div align="left">                    <input name="txtcodemp" type="hidden" id="txtcodemp" value="<?php print $la_codemp?>">
                </div></td>
                <td width="361">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td height="19"><div align="right">Numero de Contrato</div></td>
                <td height="22"><input name="txtnumconman" type="text" id="txtnumconman" style="text-align:center " onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz-');" value="<?php print $ls_numconman ?>" size="30" maxlength="25"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="19"><div align="right">Proveedor</div></td>
                <td height="22"><input name="txtcodpro" type="text" id="txtcodpro" style="text-align:center " value="<?php print $ls_codproman ?>" size="20" readonly>
                  <a href="javascript: ue_proveedor();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtdenpro" type="text" class="sin-borde" id="txtdenpro" value="<?php print $ls_denproman ?>" size="35" readonly></td>
              </tr>
              <tr class="formato-blanco">
                <td height="19"><div align="right">Fecha de Inicio </div></td>
                <td height="22"><input name="txtfeciniman" type="text" id="txtfeciniman" style="text-align:center " onKeyPress="ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_feciniman ?>" size="18" maxlength="10" datepicker="true"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="19"><div align="right">Fecha de Finalizaci&oacute;n </div></td>
                <td height="22"><input name="txtfecfinman" type="text" id="txtfecfinman" style="text-align:center " onKeyPress="ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_fecfinman ?>" size="18" maxlength="10"  datepicker="true"></td>
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
function ue_proveedor()
{
	window.open("sigesp_saf_cat_prov.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if((li_cambiar==1)||(li_incluir==1))
	{
		ld_desde=f.txtfeciniman.value;
		ld_hasta=f.txtfecfinman.value;
		lb_valido=ue_comparar_intervalo(ld_desde,ld_hasta);
		if(lb_valido)
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_saf_d_activosmantenimiento.php";
			f.submit();
		}
		else
		{
			f.txtfeciniman.value="";
			f.txtfecfinman.value="";
		}
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