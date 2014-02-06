<script type="text/javascript" language="JavaScript1.2" src="../../librerias/js/funciones.js"></script>
<script type="text/javascript" src="../../librerias/js/ext/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="../../librerias/js/ext/ext-all.js"></script><script type="text/javascript" src="../../librerias/js/menu/sigesp_mcd_vis_menu.js"></script>
<script type="text/javascript" src="../js/sigesp_sfp_fuentefin.js"></script>
<link href="../../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../../shared/css/general.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../librerias/js/ext/resources/css/ext-all.css">
<link rel="stylesheet" type="text/css" href="../../otros/css/ExtStart.css">



<html>
<head>
	<title>
		Formulación- Fuente de financiamiento
	</title>
</head>
<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu" id='toolbar'></td>
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><img src="../../../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" id="BtnNuevo"></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../../../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0" id="BtnGrabar"></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../../../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" id="BtnCat"></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../../../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0" id="BtnElim"></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../../../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" id="BtnSalir"></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../../../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" id="BtnAyu"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="580">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	//$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	//unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="588" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
                  <tr>
                    <td colspan="2" class="titulo-ventana">Definici&oacute;n de Fuente de Financiamiento</td>
                  </tr>
                  <tr class="formato-blanco">
                    <td width="111" height="19">&nbsp;</td>
                    <td width="408" height="22"><input name="txtempresa" type="hidden" id="txtempresa" value="">
                      <input name="txtnombrevie" type="hidden" id="txtnombrevie2"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="29"><div align="right">C&oacute;digo</div></td>
                    <td height="22"><input name="txtcod" type="text" id="txtcod" value="" size="8" maxlength="1" style="text-align:center " readonly>
                      <input name="hidstatus" type="hidden" id="hidstatus">
		      
		      <input name="actualizar" type="hidden" id="actualizar">
		      
		      </td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="28"><div align="right">Denominaci&oacute;n</div></td>
                    <td height="22"><input name="txtdenfue" type="text" id="txtden"  value="" size="50" maxlength="100"></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="25"><div align="right">Explicación</div></td>
                    <td rowspan="2"><textarea name="txtexp" cols="50" id="txtexp"></textarea></td>
                  </tr>
                  <tr class="formato-blanco">
                    <td height="14">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table>
              <input name="operacion" type="hidden" id="operacion">
          </form>
<?php
?>

</body>
</html>