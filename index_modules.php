<?php 
session_start(); 
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION))||(!array_key_exists("la_logusr",$_SESSION))||(!array_key_exists("la_empresa",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='index.php'";
	print "</script>";		
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Agregado para suicheo de bases de datos entre distintas aplicaciones
require_once("sigesp_config.php");

$posicion=$_SESSION["gi_posicion"];
$_SESSION["ls_nombrelogico"] = $empresa["nombre_logico"][$posicion];
$_SESSION["ls_database"] = $empresa["database"][$posicion];
$_SESSION["ls_hostname"] = $empresa["hostname"][$posicion];
$_SESSION["ls_login"]    = $empresa["login"][$posicion];
$_SESSION["ls_password"] = $empresa["password"][$posicion];
$_SESSION["ls_gestor"]   = strtoupper($empresa["gestor"][$posicion]);	
$_SESSION["ls_port"]     = $empresa["port"][$posicion];	
$_SESSION["ls_width"]    = $empresa["width"][$posicion];
$_SESSION["ls_height"]   = $empresa["height"][$posicion];	
$_SESSION["ls_logo"]     = $empresa["logo"][$posicion];	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Sistema Administrativo HUAYRA -**- C.V.A.L -**- , <?php print $_SESSION["ls_nombrelogico"] ?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/principal.css"/>
<script>
function cerrarse()
{
	window.close();
}
function A()
{
	window.onerror=B
	window.opener.focus();
	window.focus();
}
function B()
{
	var url = document.location.href;
	partes = url.split('/');
	pagina=partes[partes.length-1];
	alert("No ha iniciado sesión para esta ventana");
	location.href=url.replace(pagina,"pagina_blanco.php");
	return true;
} 
A();
</script> 
</head>

<body class="fondo_contenido_capa1">
<table width="655" height="521" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td ><div align="center"><img src="shared/imagebank/header_banner.png" width="509" height="40">        </div><!--<div align="center" class="estilo_titulo">HUAYRA</div>--></td>
  </tr>
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td width="655" class="fondo">
    <div align="center" class="Estilo_ubicacion">Modulos</div><br />
    <table width="581" border="0" align="center" height="337" class="fondo_contenido">
      <tr>
        <td width="267">&nbsp;</td>
        <td width="99">&nbsp;</td>
        <td width="201">&nbsp;</td>
      </tr>
      <tr>
        <td height="54"><div align="center" id="buttom-box">
                      <a href="index_modules_administracion.php" class="button_presupuesto" target="_self"></a>
             </div></td>
        <td colspan="2"><div align="center" class="Estilo9">Sistema para la Gesti&oacute;n Administrativa y Financiera de la Corporaci&oacute;n Venezolana de Alimentos S.A.</div></td>
        </tr>
      <tr>
        <td height="56"><div id="buttom-box" align="center">
              <a href="index_modules_comp_alm_act.php" target="_self" class="button_compras"></a>
          </div></td>
        <td colspan="2"><div align="center" class="Estilo9">"HUAYRA!" es uno de los gritos de guerra de los indios caribes del   siglo XVI,que significa "Venceremos!".</div></td>
        </tr>
      <tr>
        <td height="54"><div id="buttom-box" align="center">
                      <a href="index_modules_tal_humano.php" target="_self" class="button_trabajo"></a>
             </div></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="54"><div id="buttom-box" align="center">
                      <!--a href="index_modules_comercializacion.php" target="_self" class="button_comercializacion"></a-->
					   <a href="javascript:ue_cargar_tiendasycajas();" class="button_comercializacion" target="_self"></a>
             </div></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="58"><div id="buttom-box" align="center">
                      <a href="index_modules_herramientas.php" target="_self" class="button_config"></a>
             </div></td>
        <td>&nbsp;</td>
        <td><div id="buttom-box" align="center">
                      <a href="index.php" target="_self" class="button_salirreg"></a>
             </div></td>
      </tr>
      <tr>
        <td height="14">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <!--<p><img src="shared/imagebank/panel.jpg" width="800" height="570" border="0" usemap="#MapMap2">
      <map name="MapMap2">
        <area shape="rect" coords="28,440,305,507" href="index_modules_herramientas.php" target="_self" alt="Sistema de Caja y Bancos">
        <area shape="rect" coords="574,501,774,547" href="window.close();" alt="Sistema de Caja y Bancos">
        <area shape="rect" coords="29,285,303,350" href="index_modules_rec_humanos.php" target="_self" alt="Sistema de Caja y Bancos">
        <area shape="rect" coords="31,363,308,430" href="index_modules_comercializacion.php" target="_self" alt="Sistema de Caja y Bancos">
        <area shape="rect" coords="34,206,300,274" href="index_modules_comp_alm_act.php" target="_self" alt="Sistema de Caja y Bancos">
        <area shape="rect" coords="30,127,303,194" href="index_modules_administracion.php" target="_self" alt="Sistema de Caja y Bancos">
      </map>
    </p>--></td>
  </tr>
</table>
</body>
<script language="javascript">
//---------------------------------------------------------------------
//     Funcion abre una ventana como un winpopop
//---------------------------------------------------------------------
var popupwin = null;
function popupWin(url,name,ancho,alto) {

	popupwin = window.open(url,name,"menubar=no,toolbar=no,scrollbars=yes,width="+ancho+",height="+alto+",resizable=no,location=no,top=40,left=40,modal=yes,dialog=yes,minimizable=no");

	if (!document.all) {
	document.captureEvents (Event.CLICK);
	}
	document.onclick = function() {
	if (popupwin != null && !popupwin.closed) {

	popupwin.focus();
	}
}
}

function ue_cargar_tiendasycajas()
{
	pagina="sigesp_cat_tienda_caja.php";
	popupWin(pagina,"catalogo",850,450);
}
</script>
</html>
</html>
