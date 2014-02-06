<?php
session_start();
/************************************************************************************************************************/
/***********************************  INICIO DE LA PAGINA E INICIO DE SESION   ******************************************/
/************************************************************************************************************************/
if (!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_conexion.php'";
	print "</script>";
}

$la_datemp=$_SESSION["la_empresa"];

/************************************************************************************************************************/
if (array_key_exists("cod_usu",$_REQUEST))
{
	$ls_logusr= $_REQUEST["cod_usu"];
}else{
	$ls_logusr= "";
}

require_once("class_folder/class_funciones_seguridad.php");
$io_seguridad= new class_funciones_seguridad();

$arre=$_SESSION["la_empresa"];
$ls_empresa=$arre["codemp"];
//$ls_logusr=$_SESSION["la_logusr"];
$ls_sistema="SFC";
$ls_ventanas="sigesp_sss_cat_nombre_onidex.php";

$la_seguridad["empresa"]=$ls_empresa;
$la_seguridad["logusr"]=$ls_logusr;
$la_seguridad["sistema"]=$ls_sistema;
$la_seguridad["ventanas"]=$ls_ventanas;

if (array_key_exists("permisos",$_POST))
{
	$ls_permisos=             $_POST["permisos"];
	$la_permisos["leer"]=     $_POST["leer"];
	$la_permisos["incluir"]=  $_POST["incluir"];
	$la_permisos["cambiar"]=  $_POST["cambiar"];
	$la_permisos["eliminar"]= $_POST["eliminar"];
	$la_permisos["imprimir"]= $_POST["imprimir"];
	$la_permisos["anular"]=   $_POST["anular"];
	$la_permisos["ejecutar"]= $_POST["ejecutar"];
}
else
{
	$la_permisos["leer"]="";
	$la_permisos["incluir"]="";
	$la_permisos["cambiar"]="";
	$la_permisos["eliminar"]="";
	$la_permisos["imprimir"]="";
	$la_permisos["anular"]="";
	$la_permisos["ejecutar"]="";
	$io_seguridad->uf_load_seguridad($ls_sistema,$ls_ventanas,$ls_logusr,$ls_permisos,$la_seguridad,$la_permisos);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Catalogo Usuarios</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="ue_cerrar();">
<form name="form1" method="post" action="">

<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer value='$la_permisos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir value='$la_permisos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar value='$la_permisos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_permisos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_permisos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular value='$la_permisos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_permisos[ejecutar]'>");
}
else
{
	print("<input type=hidden name=permisos id=permisos value='FALSE'>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>

    <input name="operacion" type="hidden" id="operacion">
    <input name="txtempresa" type="hidden" id="txtempresa">
    <input name="txtloginviejo" type="hidden" id="txtloginviejo">
    <input name="hidstatus" type="hidden" id="hidstatus2">
    <input name="txtcedula" type="hidden" id="txtcedula">
    <input name="txtapellido" type="hidden" id="txtapellido">
    <input name="txttelefono" type="hidden" id="txttelefono">

  <table width="300" border="0" align="center" cellpadding="1" cellspacing="1">
  	<tr>
      <td width="300" >&nbsp;</td>
    </tr>
    <tr>
      <td width="300" class="titulo-celda">&nbsp;</td>
    </tr>
  </table>
	<br>
    <table width="300" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" background="imagenes/Login.jpg" align="center">
      <tr>
        <td width="100" height="22" ><div align="right">Nombre</div></td>
        <td width="120" height="22">
        	<div align="left"><input name="txtnombre" type="text" id="txtnombre" size="50" maxlength="100"></div>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><div align="right"><a href="javascript: aceptar();">Aceptar</a></div></td>
      </tr>
    </table>
  <br>
<input name="hiddestino" type="hidden" id="hiddestino" value="<?php print $ls_destino?>">
</form>
</body>
<script language="JavaScript">
function aceptar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	li_leer=f.leer.value;
	if((li_ejecutar==1) || (li_leer==1))
	{
		nombre=document.form1.txtnombre.value;
		opener.opener.document.form1.txtnomcli.value=nombre;
		opener.opener.document.form1.hidreadonly.value='readonly';
		opener.opener.document.form1.hidreadonlyced.value='readonly';
	}else{
		alert('No tiene permiso para realizar esta operacion');
	}
	opener.close();
    close();
}

function ue_cerrar(){
	f = document.form1
	if(f.permisos.value=='FALSE'){
		close();
	}
}

</script>
</html>
