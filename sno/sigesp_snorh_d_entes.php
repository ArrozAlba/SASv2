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
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_entes.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------

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
<title>Definici&oacute;n de Entes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #EAEAEA;
	margin-left: 0px;
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
 	<!-- LIBS -->

</style>
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script language="JavaScript" type="text/JavaScript" src="js/sigesp_sno_js_d_entes.js"></script>
<script language="JavaScript" type="text/JavaScript" src="../shared/js/js_ajax.js"></script>
<script language="JavaScript" src="../shared/js/sigesp_js.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Nómina</td>
			<td width="346" bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
        </table>
	 </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: nuevox();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: guarda_modifica();"><img src="../shared/imagebank/tools20/grabar.gif"  title="Guardar" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: buscar();"><img src="../shared/imagebank/tools20/buscar.gif"  title="Buscar" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0" id="boton_eliminar"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: salirx();"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" title="Ayuda" alt="Ayuda" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<div align="center">
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
   <div align="center">  
    <table width="700" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td>
		  <p>&nbsp;</p>  	
    
	
        <table width="600" border="0" align="center" cellpadding="0" cellspacing="3" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Entes </td>
        </tr>
        <tr>
          <td width="108" height="22">&nbsp;</td>
          <td width="336">&nbsp;</td>
        </tr>
        <tr>
          <td height="14"><div align="right">Código Ente</div></td>
          <td><div align="left">
            <input name="txt_codente" type="text" id="txt_codente" size="12" maxlength="10" value="" onKeyUp="javascript: ue_validarnumero(this);" onBlur="sigesp_rellenar_cadena(this.value,9,this.id,'izquierda')">
          </div></td>
        </tr>
        <tr>
          <td height="14"><div align="right">Ente</div></td>
          <td><div align="left">
            <input name="txt_ente" type="text" id="txt_ente" size="60" maxlength="120" onKeyUp="ue_validarcomillas(this);" value="">
          </div></td>
        </tr>
        <tr>
          <td height="14"><div align="right">Porcentaje del Descuento</div></td>
          <td><div align="left">
              <input name="txt_porcentaje_ente" type="text" id="txt_porcentaje_ente" size="12" maxlength="5" onKeyUp="javascript: ue_validarnumero(this);" value="">
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td height="22"><input name="operacion" type="hidden" id="operacion"><input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
        </tr>
      </table>    
        <p>&nbsp;</p></td>
      </tr>
     </table>
	    <p>&nbsp;</p>
          </td>
      </tr>
  </table>
   </div>
   
   <div align="center">
     <p>
	   <input name="formid"  type="hidden" id="frm_tipo_ente"  value="frm_tipo_ente">
	   <input name="hid_cod_ente"  type="hidden" id="hid_cod_ente"  value="<?php echo $cod_ente ?>">
	 </p>
   </div>
   <div id="resultados" align="center"></div>
 </form>
</body>
</html>