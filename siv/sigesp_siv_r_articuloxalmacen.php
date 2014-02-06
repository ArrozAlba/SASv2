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
require_once("class_funciones_inventario.php");
$io_fun_activo=new class_funciones_inventario();
$io_fun_activo->uf_load_seguridad("SIV","sigesp_siv_r_articuloxalmacen.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Niveles de Existencia de Art&iacute;culos</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="css/siv.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
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
.Estilo1 {font-weight: bold}
.Estilo2 {font-size: 12px}
-->
</style></head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" colspan="4" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="4" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo2">Sistema de Inventario </td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" colspan="4" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="7" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td width="20" height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_mostrar_reporte();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><a href="../siv/sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
    <td width="20" bgcolor="#FFFFFF" class="toolbar"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
    <td width="718" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
</table>
<?php
require_once("../shared/class_folder/sigesp_include.php");
$io_in=new sigesp_include();
$con=$io_in->uf_conectar();

require_once("../shared/class_folder/class_datastore.php");
$io_ds=new class_datastore();

require_once("../shared/class_folder/class_sql.php");
$io_sql=new class_sql($con);

require_once("../shared/class_folder/class_mensajes.php");
$io_msg=new class_mensajes();

require_once("../shared/class_folder/class_funciones.php");
$io_funcion=new class_funciones(); 

require_once("../shared/class_folder/grid_param.php");
$grid=new grid_param();

$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
}
else
{
	$ls_operacion="";	
}

?>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	

  <table width="446" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="442" colspan="2" class="titulo-ventana">Niveles de Existencia de Art&iacute;culos </td>
    </tr>
  </table>
  <table width="437" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td colspan="3"></td>
    </tr>
    <tr>
      <td colspan="5" align="center">
        <div align="left"></div></td>
    </tr>
    <tr>
      <td colspan="5" align="center">      <div align="left">
        <table width="430" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="2"><strong>Tipo de Busqueda </strong></td>
            </tr>
          <tr>
            <td width="55"><div align="right">Art&iacute;culo</div></td>
            <td width="373" height="22"><div align="left">
              <input name="txtcodart" type="text" id="txtcodart" size="21" maxlength="20"  style="text-align:center "  onBlur="javascript:rellenar_cad(this.value,10,document.form1.txtcodprov1.name)">
              <a href="javascript:uf_catalogoarticulo();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
              <input name="txtdenart" type="text" class="sin-borde" id="txtdenart2" size="34" readonly>
            </div>
              <div align="left">                      </div></td>
            </tr>
          <tr>
            <td height="19"><div align="right"><span class="style1 style14">Almac&eacute;n</span></div></td>
            <td height="22"><div align="left">
              <input name="txtcodalm" type="text" id="txtcodprov22" size="12" maxlength="12"  style="text-align:center"  onBlur="javascript:rellenar_cad(this.value,10,document.form1.txtcodprov2.name)">
              <a href="javascript:uf_catalogoalmacen();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
              <input name="txtnomfisalm" type="text" class="sin-borde" id="txtnomfisalm2" size="40" readonly>
            </div>              
              <div align="left">                </div></td>
            </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td width="77" align="center"><div align="right" class="style1 style14"></div></td>
      <td width="146" colspan="2" align="left">&nbsp;        </td>
      <td width="49" align="center"><div align="right" class="style1 style14"></div></td>
      <td width="165" align="center"><div align="left">
        <input name="txtdesalm" type="hidden" id="txtdesalm">
        <input name="txttelalm" type="hidden" id="txttelalm">
        <input name="txtubialm" type="hidden" id="txtubialm">
        <input name="txtnomresalm" type="hidden" id="txtnomresalm">
        <input name="txttelresalm" type="hidden" id="txttelresalm">
        <input name="hidstatus" type="hidden" id="hidstatus">
        <input name="hidunidad" type="hidden" id="hidunidad">
      </div></td>
    </tr>
    <tr>
      <td colspan="5" align="center"><div align="left" class="style14"></div></td>
    </tr>
    <tr>
      <td colspan="5" align="center"><div align="left">
        <table width="430" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="5"><span class="style14"><strong>Ordenado Por</strong></span></td>
            </tr>
          <tr>
            <td colspan="2"><div align="center"><span class="style1"><strong>Almac&eacute;n</strong></span></div></td>
            <td colspan="2"><div align="center"><strong>Art&iacute;culo</strong></div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="55"><div align="right"></div></td>
            <td width="102" height="22"><div align="right"><span class="style1">C&oacute;digo
                  <input name="radioordenalm" type="radio" class="sin-borde" value="0" checked  código>
            </span></div></td>
            <td width="47">&nbsp;</td>
            <td width="118"><div align="right">C&oacute;digo
                  <input name="radioordenart" type="radio" class="sin-borde" value="radiobutton" checked>
            </div></td>
            <td width="88">&nbsp;</td>
          </tr>
          <tr>
            <td><div align="right"></div></td>
            <td height="22"><div align="right">Nombre
                  <input name="radioordenalm" type="radio" class="sin-borde" value="1"  Nombre>
            </div></td>
            <td>&nbsp;</td>
            <td><div align="right">Denominaci&oacute;n
                  <input name="radioordenart" type="radio" class="sin-borde" value="radiobutton">
            </div></td>
            <td>&nbsp;</td>
          </tr>
        </table>
      </div></td>
    </tr>
    <tr>
      <td height="24" colspan="5" align="center"><div align="right">
      <input name="operacion"   type="hidden"   id="operacion"   value="<?php print $ls_operacion;?>">
      </div></td>
    </tr>
    <tr>
      <td colspan="5" align="center">
        <div align="center">
          <p><span class="Estilo1">
          </span></p>
      </div></td>
    </tr>
  </table>
  <div align="center"></div>
  <div align="left"></div>
  <p align="center">
<input name="total" type="hidden" id="total" value="<?php print $totrow;?>">
</p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
	function ue_search()
	{
	  f=document.form1;
	  f.operacion.value="BUSCAR";
	  f.action="sigesp_rpc_r_provxespecia.php";
	  f.submit();
	}

	function uf_catalogoarticulo()
	{
		window.open("sigesp_catdinamic_articulom.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=120,top=70,location=no,resizable=yes");
	}
	
	function uf_catalogoalmacen()
	{
		window.open("sigesp_catdinamic_almacen.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=120,top=70,location=no,resizable=yes");
	}
	
	function uf_mostrar_reporte()
	{
		f=document.form1;
		ls_codart=f.txtcodart.value;
		ls_codalm=f.txtcodalm.value;
		if(f.radioordenalm[0].checked)
		{
			li_ordenalm=0;
		}
		else
		{
			li_ordenalm=1;
		}

		if(f.radioordenart[0].checked)
		{
			li_ordenart=0;
		}
		else
		{
			li_ordenart=1;
		}					  
		window.open("reportes/sigesp_siv_rpp_existencias.php?codart="+ls_codart+"&codalm="+ls_codalm+"&ordenalm="+li_ordenalm+"&ordenart="+li_ordenart,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
	}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

</script>
</html>