<?php
session_start();
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definición de Plan de Cuentas.</title>
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
.Estilo5 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo6 {
	color: #006699;
	font-size: 12px;
}
.Estilo8 {font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo10 {font-size: 10px}
.Estilo11 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo13 {font-size: 12px}
.Estilo14 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
-->
</style>
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
</head>

<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
        <?php
	   if(array_key_exists("confinstr",$_SESSION["la_empresa"]))
	  {
      if($_SESSION["la_empresa"]["confinstr"]=='A')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='V')
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2007.js"></script></td>
  <?php
      }
      elseif($_SESSION["la_empresa"]["confinstr"]=='N')
	  {
   ?>
       <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
  <?php
      }
	  	 }
	  else
	  {
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_2008.js"></script></td>
	<?php 
	}
	?>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Ejecutar" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/deshacer.gif" alt="Deshacer" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/filtrar.gif" alt="Filtrar" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?php
	//include("class_sigesp_int_scg.php");
	$dat=$_SESSION["la_empresa"];
	//$int_scg=new class_sigesp_int_scg();
	$ds=null;

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
	}
	if($ls_operacion=="GUARDAR")
	{
		
	
	}
	$ls_cuenta="";
	$ls_denominacion="";
	$ls_status="";
	$disabled="";
	$ls_asignar="checked";
	$ls_comprometer="";
	$ls_aumento="";
	$ls_causa="checked";
	$ls_disminucion="";
	$ls_pagar="";
	$ls_precompromete="checked";
	$ls_reservado="";
?>
<p>&nbsp;</p>
<div align="center">
  <table width="601" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="601" height="221" valign="top" bgcolor="#DEDBDE" class="formato-blanco"><form name="form1" method="post" action="">
          <p>&nbsp;</p>
          <table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td colspan="2">Definici&oacute;n Tipos de Operaci&oacute;n </td>
              </tr>
              <tr class="formato-blanco">
                <td height="18">&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td width="104" height="37"><div align="right" >
                    <p>Operacion</p>
                </div></td>
                <td width="460"><div align="left" >
                    <input name="txtoperacion" <?php print $disabled ?> type="text" id="txtoperacion" value="<?php print $ls_cuenta?>">
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="36"><div align="right">Denominaci&oacute;n</div></td>
                <td><div align="left">
                  <input name="txtdenominacion" type="text" id="txtdenominacion" value="<?php print $ls_denominacion?>" size="70">
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="20" colspan="2"><table width="349" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-azul">
                  <tr>
                    <td width="102"><div align="right">Asignar</div></td>
					<td width="56"><input name="cbasignar" type="checkbox" id="cbasignar" value="1" <?php print $ls_asignar; ?> ></td>
                    <td width="76"><div align="right">Comprometer</div></td>
                    <td width="103"><input name="cbcompromiso" type="checkbox" id="cbcompromiso" value="1" <?php print 	$ls_comprometer; ?> ></td>
                  </tr>
                  <tr>
                    <td><div align="right">Aumento</div></td>
                    <td><input name="cbaumento" type="checkbox" id="cbaumento" value="1" <?php print 	$ls_aumento;?> ></td>
                    <td><div align="right">Causar</div></td>
                    <td><input name="cbcausa" type="checkbox" id="cbcausa" value="1" <?php print 	$ls_causa;?> ></td>
                  </tr>
                  <tr>
                    <td><div align="right">Disminuci&oacute;n</div></td>
                    <td><input name="cbdisminucion" type="checkbox" id="cbdisminucion" value="1" <?php print $ls_disminucion;?> ></td>
                    <td><div align="right">Pagar</div></td>
                    <td><input name="cbpaga" type="checkbox" id="cbpaga" value="1" <?php print $ls_pagar;?> ></td>
                  </tr>
				  <tr>
                    <td><div align="right">Pre-Comprometer</div></td>
                    <td><input name="cbprecompromiso" type="checkbox" id="cbprecompromiso" value="1" <?php print $ls_precompromete;?> ></td>
                    <td><div align="right">Reservado</div></td>
                    <td><input name="cbreservado" type="checkbox" id="cbreservado" value="1" <?php print 	$ls_reservado;?> ></td>
                  </tr>
                                </table></td>
              </tr>
            <tr class="formato-blanco">
                <td height="20"><div align="right" ></div></td>
                <td><div align="left" >
                </div></td>
            </tr>
          </table>
            <p align="center">
            <input name="operacion" type="hidden" id="operacion">
</p>
        </form></td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">
function cat()
{
 f=document.form1;
 f.txtcuenta.readonly=false;
 f.operacion.value="CAT";
 //f.action="sigespwindow_scg_plan_ctas.php";
 window.open("sigesp_catdinamic_ctasPU.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
// f.submit();
}

function editar(cuenta , deno , status)
{
f=document.form1;
//f.txtcuenta.readonly=false;
f.txtcuenta.value=cuenta;
f.txtdenominacion.value=deno;
f.txtstatus.value=status;
f.operacion.value ="EDITAR";
f.action="sigespwindow_scg_plan_ctas.php";
f.submit();
f.txtdenominacion.focus(true);
}

/*function cambiar(cuenta , deno , status)
{
f=document.form1;
//f.txtcuenta.disabled=false;
f.txtcuenta.value=cuenta;
f.txtdenominacion.value=deno;
f.txtstatus.value=status;
f.operacion.value ="CAMBIAR";
f.action="sigespwindow_scg_plan_ctas.php";
f.submit();
f.txtdenominacion.focus(true);
}*/
function ue_nuevo()
{
}
function ue_guardar()
{
f=document.form1;
f.operacion.value ="GUARDAR";
f.action="sigesp_spg_tip_operacion.php";
f.submit();
f.txtoperacion.focus(true);
}

function ue_eliminar()
{
f=document.form1;
//f.txtcuenta.disabled=false;
f.operacion.value ="ELIMINAR";
f.action="sigespwindow_scg_plan_ctas.php";
f.submit();
//f.txtcuenta.focus(true);
}
function ue_buscar()
{
	window.open("sigesp_catdinamic_ctas.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}
function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}


</script>
</html>
