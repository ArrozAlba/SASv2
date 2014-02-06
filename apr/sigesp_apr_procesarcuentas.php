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
	require_once("class_folder/class_funciones_apr.php");
	$io_fun_apr=new class_funciones_apr();
	$io_fun_apr->uf_load_seguridad("APR","sigesp_apr_procesarcuentas.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_ruta="resultado";
	@mkdir($ls_ruta,0755);
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
<title>Procesar Cuentas </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../spg/js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
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
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
-->
</style>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr> 
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../apr/js/menu2.js"></script></td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="../apr/sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a><a href="javascript: ue_descargar('<?PHP print $ls_ruta;?>');"><img src="../shared/imagebank/tools20/download.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php
require_once("sigesp_apr_c_procesarcuentas.php");
$io_class=new sigesp_apr_c_procesarcuentas();
if(array_key_exists("operacion",$_POST))
{
  $ls_operacion=$_POST["operacion"];
}
else
{
  $ls_operacion="";
}
if($ls_operacion=="EJECUTAR")
{
	$lb_valido=true;
	$io_class->io_sql_destino->begin_transaction();
	if($lb_valido)
	{
		if(array_key_exists("chkscg",$_POST))
		{
			$lb_valido=$io_class->uf_procesar_cuentas_scg($aa_seguridad);
		}
	}
	if($lb_valido)
	{
		if(array_key_exists("chkspg",$_POST))
		{
			$lb_valido=$io_class->uf_procesar_cuentas_spg($aa_seguridad);
		}
	}
	if($lb_valido)
	{
		if(array_key_exists("chkestpre",$_POST))
		{
			$lb_valido=$io_class->uf_procesar_estructuras($aa_seguridad);
		}
	}
	if($lb_valido)
	{
		$io_class->io_mensajes->message("La asociación de cuentas se guardo de manera exitosa."); 
		$io_class->io_sql_destino->commit();
	}
	else
	{
		$io_class->io_mensajes->message("Ocurrio un error al asociar las cuentas y/o estructuras."); 
		$io_class->io_sql_destino->rollback();	
	}
}
?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_apr->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'"); 
	unset($io_fun_apr);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="400" height="21" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="604" colspan="2" class="titulo-ventana">PROCESAR CUENTAS CONTABLES Y DE PRESUPUESTO </td>
    </tr>
  </table>
  <table width="400" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="307"></td>
    </tr>
    <tr>
      <td height="22" colspan="2" align="center"><div align="right"><strong>Actualizar Cuentas Contables </strong></div></td>
      <td width="87" height="22" align="center"><div align="center">
        <label>
        <input name="chkscg" type="checkbox" class="sin-borde" id="chkscg" value="1">
        </label>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="2" align="center"><div align="right"><strong>Actualizar Cuentas Presupuestarias </strong></div></td>
      <td height="22" align="center"><div align="center">
        <label>
        <input name="chkspg" type="checkbox" class="sin-borde" id="chkspg" value="1">
        </label>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="2" align="center"><div align="right"><strong>Actualizar Estructuras Presupuestarias </strong></div></td>
      <td height="22" align="center"><div align="center">
        <label>
        <input name="chkestpre" type="checkbox" class="sin-borde" id="chkestpre" value="checkbox">
        </label>
      </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><label>
        <input name="botaceptar" type="button" class="boton" id="botaceptar" value="EJECUTAR" onClick="uf_aceptar()">
      </label></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"><div align="right"><span class="Estilo1">
        <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </span></a></div></td>
    </tr>
  </table>
  <div align="left"></div>
  <p align="center"><strong><span class="style14"></a></span></strong> </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">

function uf_aceptar()
{
	f=document.form1;
	li_cambiar=f.cambiar.value;
	li_incluir=f.incluir.value;
	if((li_cambiar==1)||(li_incluir==1))
	{
		f.operacion.value="EJECUTAR";
		f.action="sigesp_apr_procesarcuentas.php";
		f.submit();
	}	
}

function ue_descargar(ruta)
{
	window.open("sigesp_apr_cat_directorio.php?ruta="+ruta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

</script>
</html>