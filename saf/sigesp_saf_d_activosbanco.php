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
$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_d_activosbanco.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
		global $ls_codban,$ls_denban,$ls_ctaban,$ls_dencta,$ls_codtipcta,$ls_dentipcta,$ls_tippag,$ls_numregpag,$ls_checked0,$ls_checked1;
		
		$ls_codban="";
		$ls_denban="";
		$ls_ctaban="";
		$ls_dencta="";
		$ls_codtipcta="";
		$ls_dentipcta="";
		$ls_tippag="";
		$ls_numregpag="";
		$ls_checked0="";
		$ls_checked1="";
   }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<!--<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
--><title >Datos del Banco </title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
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
			$lb_valido=$io_saf->uf_saf_select_activobanco($ls_codemp,$ls_codact,$ls_codban,$ls_denban,$ls_ctaban,$ls_dencta,
														  $ls_codtipcta,$ls_dentipcta,$ls_tippag,$ls_numregpag);
			if($ls_tippag=="0")
			{$ls_checked0="checked";}
			else{$ls_checked1="checked";}
		break;

		case "GUARDAR":
			$ls_codact=$io_fac->uf_obtenervalor("txtcodact","");
			$ls_codban=$io_fac->uf_obtenervalor("txtcodban","");
			$ls_denban=$io_fac->uf_obtenervalor("txtdenban","");
			$ls_ctaban=$io_fac->uf_obtenervalor("txtctaban","");
			$ls_dencta=$io_fac->uf_obtenervalor("txtdencta","");
			$ls_codtipcta=$io_fac->uf_obtenervalor("txtcodtipcta","");
			$ls_dentipcta=$io_fac->uf_obtenervalor("txtdentipcta","");
			$ls_tippag=$io_fac->uf_obtenervalor("radiotippag","");
			$ls_numregpag=$io_fac->uf_obtenervalor("txtnumregpag","");
			$lb_valido=$io_saf->uf_saf_update_activobanco($ls_codemp,$ls_codact,$ls_codban,$ls_ctaban,$ls_codtipcta,$ls_tippag,
														  $ls_numregpag,$la_seguridad);
			if($ls_tippag=="0")
			{
				$ls_checked0="checked";
				$ls_checked1="";
			}
			else
			{
				$ls_checked0="";
				$ls_checked1="checked";
			}
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
    <table width="650" height="175" border="0" class="formato-blanco">
      <tr>
        <td width="588" height="169"><div align="left">
            <table width="624" height="196" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td height="27" colspan="2"><input name="txtcodact" type="hidden" id="txtcodact" value="<?php print $ls_codact ?>">
                <input name="txtdenact" type="text" class="sin-borde2" id="txtdenact" value="<?php print $ls_denact ?>" size="70" readonly></td>
              </tr>
              <tr>
                <td height="17" colspan="2" class="titulo-ventana">Datos del Banco </td>
              </tr>
              <tr class="formato-blanco">
                <td height="19" colspan="2">
                  <div align="left">                    <input name="txtcodemp" type="hidden" id="txtcodemp" value="<?php print $la_codemp?>">
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td width="152" height="22"><div align="right">Banco</div></td>
                <td width="425" height="22"><input name="txtcodban" type="text" id="txtcodban" style="text-align:center " value="<?php print $ls_codban ?>" size="10" readonly>
                    <a href="javascript: ue_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                    <input name="txtdenban" type="text" class="sin-borde" id="txtdenban" value="<?php print $ls_denban ?>" size="40" readonly>
                    <input name="txtcuenta" type="hidden" id="txtcuenta"></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Numero de Cuenta </div></td>
                <td height="23"><input name="txtctaban" type="text" id="txtctaban" style="text-align:center " value="<?php print $ls_ctaban ?>" size="35" readonly>
                    <a href="javascript: ue_numcuenta();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                    <input name="txtdencta" type="text" class="sin-borde" id="txtdencta" value="<?php print $ls_dencta ?>" size="40" readonly>
                    <input name="txtcuenta_scg" type="hidden" id="txtcuenta_scg">
                    <input name="txtdisponible" type="hidden" id="txtdisponible"></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Tipo de Cuenta </div></td>
                <td height="22"><input name="txtcodtipcta" type="text" id="txttipocuenta3" style="text-align:center " value="<?php print $ls_codtipcta ?>" size="10" readonly>                  
                    <input name="txtdentipcta" type="text" class="sin-borde" id="txtdentipcta" value="<?php print $ls_dentipcta ?>" size="40" readonly></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22">&nbsp;</td>
                <td height="22"><input name="radiotippag" type="radio" class="sin-borde" value="0" <?php print $ls_checked0 ?>>
              Fondos de Anticipo
                <input name="radiotippag" type="radio" class="sin-borde" value="1" <?php print $ls_checked1 ?>>
              Caja Chica </td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Numero de Registro </div></td>
                <td height="22"><input name="txtnumregpag" type="text" id="txtnumregpag" onKeyPress="return keyRestrict(event,'1234567890 -');" value="<?php print $ls_numregpag ?>"></td>
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
//Funciones de operaciones  
function uf_agregarpartes(li_row)
{
	f=document.form1;
	ls_codact=eval("f.txtcodactd"+li_row+".value");
	ls_codact=ue_validarvacio(ls_codact);
	ls_seract=eval("f.txtseractd"+li_row+".value");
	ls_seract=ue_validarvacio(ls_seract);
	ls_idactivo=eval("f.txtidactivod"+li_row+".value");
	ls_idactivo=ue_validarvacio(ls_idactivo);

	window.open("sigesp_saf_d_partes.php?codact="+ls_codact+"&seract="+ls_seract+"&id="+ls_idactivo+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=350,left=100,top=100,location=no,resizable=yes");
}

function ue_bancos()
{
	window.open("sigesp_saf_cat_bancos.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_cuentas()
{
	window.open("sigesp_saf_cat_tipoctas.php","_blank","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
}

function  ue_numcuenta()
{
	f=document.form1;
	ls_codban=f.txtcodban.value;
	ls_denban=f.txtdenban.value;
	if((ls_codban!="")&&(ls_denban!=""))
	{
		window.open("sigesp_saf_cat_ctabanco.php?codigo="+ls_codban+"&denban="+ls_denban+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("Debe tener un Banco seleccionado");
	}
}
function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	if((li_cambiar==1)||(li_incluir==1))
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_saf_d_activosbanco.php";
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
</html>