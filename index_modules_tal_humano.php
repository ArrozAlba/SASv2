<?php 
session_start(); 
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION))||(!array_key_exists("la_logusr",$_SESSION))||(!array_key_exists("la_empresa",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='index.php'";
	print "</script>";		
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Sistema Administrativo HUAYRA -**- C.V.A.L -**- , <?php print $_SESSION["ls_database"] ?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="css/principal.css"/>
<style type="text/css">
<!--
body{color:#666666;font-family:Tahoma, Verdana, Arial;font-size:11px;margin:0px;background-color:#EAEAEA;}
.titulo {
	font-family: Tahoma, Verdana, Arial;
	font-size: 16px;
	font-weight: bold;
	color: #666666;
}
.style1 {font-size: 12px}
.style6 {font-size: 16px}
.style7 {color: #FF0000}
.Estilo1 {
	font-size: 10;
	color: #898989;
}
-->
</style>
</head>
<script language="javascript">

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
    <div align="center" class="Estilo_ubicacion">Trabajo Digno</div><br />
    <table width="581" border="0" align="center" height="337" class="fondo_contenido">
      <tr>
        <td width="267">&nbsp;</td>
        <td width="99">&nbsp;</td>
        <td width="201">&nbsp;</td>
      </tr>
      <tr>
        <td height="54"><div align="center" id="buttom-box">
                      <a href="sno/sigespwindow_blank.php" class="button_nomina" target="_self"></a>
             </div></td>
        <td colspan="2"><div align="center" class="Estilo9">Sistema para la Gesti&oacute;n Administrativa y Financiera de la Corporaci&oacute;n Venezolana de Alimentos S.A.</div></td>
        </tr>
      <tr>
        <td height="56"><div id="buttom-box" align="center">
              <a href="srh/pages/vistas/pantallas/sigespwindow_blank.php" target="_self" class="button_rechumanos"></a>
          </div></td>
        <td colspan="2"><div align="center" class="Estilo9">"HUAYRA!" es uno de los gritos de guerra de los indios caribes del   siglo XVI,que significa "Venceremos!".</div></td>
        </tr>
      <tr>
        <td height="54"><div id="buttom-box" align="center">
                      <a href="#" target="_self" class="button_autogest"></a>
             </div></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="54">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="58">&nbsp;</td>
        <td>&nbsp;</td>
        <td><div id="buttom-box" align="center">
                      <a href="index_modules.php" target="_self" class="button_regmenu"></a>
             </div></td>
      </tr>
      <tr>
        <td height="14">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    <!--<p><img src="shared/imagebank/panel5.jpg" width="800" height="570" border="0" usemap="#Map">
      <map name="Map"><area shape="rect" coords="494,488,755,541" href="index_modules.php" target="_self" alt="Sistema de Proveedores y Beneficiarios">
        <area shape="rect" coords="31,208,305,269" href="rpc/sigespwindow_blank.php" target="_self" alt="Sistema de Proveedores y Beneficiarios">
        <area shape="rect" coords="33,129,303,193" href="sno/sigespwindow_blank.php" target="_self" alt="Sistema de N&oacute;mina">
      </map>
    </p>--></td>
  </tr>
</table>


</body>
</html>
