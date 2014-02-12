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
$io_fun_activo->uf_load_seguridad("SIM","sigesp_sim_r_almacenes.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Listado de Almacenes</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
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
.Estilo2 {
	color: #6699CC;
	font-size: 14px;
}
-->
</style>
</head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" colspan="4" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="3" bgcolor="#E7E7E7" class="cd-menu"><span class="Estilo2 descripcion_sistema"><strong>Sistema de Inventario</strong></span></td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div>      <div align="right"></div>      <div align="right"></div>      <div align="right"></div></td>
    <td width="1" height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"></div></td>
  </tr>
  <tr>
    <td height="20" colspan="3" bgcolor="#E7E7E7" class="cd-menu">&nbsp;</td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
    <td height="20" colspan="4" bgcolor="#E7E7E7" class="cd-menu"><div align="right"></div></td>
  </tr>
  <tr>
    <td height="20" colspan="4" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="4" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td width="26" height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:uf_mostrar_reporte();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></td>
    <td width="32" bgcolor="#FFFFFF" class="toolbar"><a href="../sim/sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
    <td width="443" bgcolor="#FFFFFF" class="toolbar"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></td>
    <td width="277" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
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
	if ($ls_operacion=="REPORT")
	{
		$ls_evento="REPORT";
		$ls_descripcion="Generó un reporte de listado de almacenes";
		$lb_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
								$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
								$la_seguridad["ventanas"],$ls_descripcion);
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

  <table width="454" height="18" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="440" colspan="2" class="titulo-ventana">Listado de Almacenes</td>
    </tr>
  </table>
  <table width="449" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td colspan="3"></td>
    </tr>
    <tr>
      <td colspan="5" align="center"><table width="430" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="2"><strong>Almac&eacute;n</strong></td>
        </tr>
        <tr>
          <td width="53"><div align="right">Desde</div></td>
          <td width="375" height="22"><div align="left">
              <input name="txtcoddesde" type="text" id="txtcoddesde" size="11" maxlength="10"  style="text-align:center ">
              <a href="javascript:uf_catalogotipoarticulo('txtcoddesde','txtdendesde');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> 
              <input name="txtdendesde" type="text" class="sin-borde" id="txtdenart2" size="45" readonly>
</div>
            <div align="left"> </div></td>
        </tr>
        <tr>
          <td height="10"><div align="right"><span class="style1 style14">Hasta</span></div></td>
          <td height="22"><div align="left">
              <input name="txtcodhasta" type="text" id="txtcodprov22" size="11" maxlength="10"  style="text-align:center">
              <a href="javascript:uf_catalogotipoarticulo('txtcodhasta','txtdenhasta');"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> 
              <input name="txtdenhasta" type="text" class="sin-borde" id="txtdenhasta2" size="45" readonly>
</div>
            <div align="left"> </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td width="112" height="19" align="center"><div align="right" class="style1 style14"></div></td>
      <td colspan="2" align="left">&nbsp;        </td>
      <td width="71" align="center"><div align="right" class="style1 style14"></div></td>
      <td width="255" align="center"><div align="left">
        <input name="hidunidad" type="hidden" id="hidunidad">
        <input name="hidstatus" type="hidden" id="hidstatus">
</div></td>
    </tr>
    <tr>
      <td colspan="5" align="center"><div align="left">
        <table width="430" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr>
            <td colspan="2"><span class="style14"><strong>Ordenado Por</strong></span></td>
          </tr>
          <tr>
            <td width="175"><div align="right"></div>              <div align="right"></div>              <div align="right">C&oacute;digo
                    <input name="radioordenalm" type="radio" class="sin-borde" value="radiobutton" checked>
                </div></td>
            <td width="235">&nbsp;</td>
          </tr>
          <tr>
            <td><div align="right"></div>              <div align="right"></div>              <div align="right">Denominaci&oacute;n
                    <input name="radioordenalm" type="radio" class="sin-borde" value="radiobutton">
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

	function uf_catalogotipoarticulo(ls_coddestino,ls_dendestino)
	{
		window.open("sigesp_catdinamic_almacend.php?coddestino="+ ls_coddestino +"&dendestino="+ ls_dendestino +"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,left=120,top=70,location=no,resizable=yes");
	}
	
	function uf_mostrar_reporte()
	{
		f=document.form1;
		li_imprimir=f.imprimir.value;
		if(li_imprimir==1)
		{
			ls_coddesde= f.txtcoddesde.value;
			ls_codhasta= f.txtcodhasta.value;
			if(f.radioordenalm[0].checked)
			{
				li_ordenalm=0;
			}
			else
			{
				li_ordenalm=1;
			}
	
			window.open("reportes/sigesp_sim_rpp_almacenes.php?ordenalm="+li_ordenalm+"&coddesde="+ls_coddesde+"&codhasta="+ls_codhasta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
			f.operacion.value="REPORT";
			f.action="sigesp_sim_r_almacenes.php";
			//f.submit();
		}
		else
		{
			alert("No tiene permiso para realizar esta operación");
		}
	}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}
</script>
</html>
