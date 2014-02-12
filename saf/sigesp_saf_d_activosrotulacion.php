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
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_d_activosrotulacion.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
		global $ls_codrot,$ls_denrot,$ls_codprorot,$ls_denprorot,$ad_fecrot;
		
		$ls_codrot="";
		$ls_denrot="";
		$ls_codprorot="";
		$ls_denprorot="";
		$ad_fecrot="";
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
<title >Datos de la Rotulaci&oacute;n</title>
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
			$lb_valido=$io_saf->uf_saf_select_activorotulacion($ls_codemp,$ls_codact,$ls_codrot,$ls_denrot,$ls_codprorot,$ls_denprorot,$ld_fecrot);
			$ld_fecrot=$io_fun->uf_convertirfecmostrar($ld_fecrot);
		break;

		case "GUARDAR":
			$ls_codact=$io_fac->uf_obtenervalor("txtcodact","");
			$ls_codrot=$io_fac->uf_obtenervalor("txtcodrot","");
			$ls_denrot=$io_fac->uf_obtenervalor("txtdenrot","");
			$ls_codprorot=$io_fac->uf_obtenervalor("txtcodpro","");
			$ls_denprorot=$io_fac->uf_obtenervalor("txtdenpro","");
			$ld_fecrot=$io_fac->uf_obtenervalor("txtfecrot","");
			$ld_fecrotaux=$io_fun->uf_convertirdatetobd($ld_fecrot);
			$lb_valido=$io_saf->uf_saf_update_activorotulacion($ls_codemp,$ls_codact,$ls_codrot,$ls_codprorot,$ld_fecrotaux,$la_seguridad);
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
            <table width="509" height="142" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td height="27" colspan="2"><input name="txtcodact" type="hidden" id="txtcodact" value="<?php print $ls_codact ?>">
                <input name="txtdenact" type="text" class="sin-borde2" id="txtdenact" value="<?php print $ls_denact ?>" size="60" readonly></td>
              </tr>
              <tr>
                <td height="17" colspan="2" class="titulo-ventana">Datos de la Rotulaci&oacute;n</td>
              </tr>
              <tr class="formato-blanco">
                <td width="125" height="19">
                  <div align="left">                    <input name="txtcodemp" type="hidden" id="txtcodemp" value="<?php print $la_codemp?>">
                </div></td>
                <td width="366">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td height="19"><div align="right">Tipo</div></td>
                <td><input name="txtcodrot" type="text" id="txtcodrot" style="text-align:center " value="<?php print $ls_codrot ?>" size="5" readonly>
                  <a href="javascript: ue_buscarrotulacion();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>                <input name="txtdenrot" type="text" class="sin-borde" id="txtdenrot" value="<?php print $ls_denrot ?>" size="40" readonly>
                <input name="txtempleo" type="hidden" id="txtempleo">
                <input name="hidstatus" type="hidden" id="hidstatus"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="19"><div align="right">Rotulador</div></td>
                <td><input name="txtcodpro" type="text" id="txtcodpro" style="text-align:center " value="<?php print $ls_codprorot ?>" size="20" readonly>
                  <a href="javascript: ue_proveedor();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                  <input name="txtdenpro" type="text" class="sin-borde" id="txtdenpro" value="<?php print $ls_denprorot ?>" size="35"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="19"><div align="right">Fecha</div></td>
                <td><input name="txtfecrot" type="text" id="txtfecrot" style="text-align:center " onKeyPress="ue_formatofecha(this,'/',patron,true);" value="<?php print $ld_fecrot ?>" size="18"  datepicker="true"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22" colspan="2"><div align="right">                  </div>
                    <div align="right"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();">Guardar</a><a href="javascript: ue_cancelar();"><img src="../shared/imagebank/eliminar.gif" alt="Cancelar" width="15" height="15" border="0"></a><a href="javascript: ue_cancelar();">Cancelar</a> </div></td>
              </tr>
            </table>
            <div align="center"></div>
            <div align="center"></div>
            <div align="center">
              <input name="operacion" type="hidden" id="operacion">
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

function ue_buscarrotulacion()
{
	window.open("sigesp_saf_cat_rotulacion.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if((li_cambiar==1)||(li_incluir==1))
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_saf_d_activosrotulacion.php";
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