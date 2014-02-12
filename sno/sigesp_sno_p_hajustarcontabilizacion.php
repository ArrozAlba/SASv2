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
	$ls_codnom=$_SESSION["la_nomina"]["codnom"];
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_hajustarcontabilizacion.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 08/11/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_desnom, $ls_peractnom, $ls_desper, $ld_fecdesper, $ld_fechasper, $ls_operacion;
		global $io_ajustar, $li_contabilizado, $io_fun_nomina;
		
		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		$lb_valido=$io_sno->uf_crear_sessionhnomina();		
		$ls_desnom="";
		$ls_peractnom="";
		$ls_desper="";			
		$ld_fecdesper="";
		$ld_fechasper="";
		if($lb_valido==false)
		{
			print "<script language=JavaScript>";
			print "location.href='sigespwindow_blank.php'";
			print "</script>";		
		}
		else
		{
			$ls_desnom=$_SESSION["la_nomina"]["desnom"];
			$ls_peractnom=$_SESSION["la_nomina"]["peractnom"];
			$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
			$ld_fecdesper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
			$ld_fechasper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
		}
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$li_contabilizado=$io_ajustar->uf_contabilizado();
		unset($io_sno);
   }
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
<title>Ajustar Contabilizaci&oacute;n</title>
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
</style>
<script type="text/javascript" language="JavaScript1.2" src="../../../swap/js/stm31.js"></script>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
	require_once("sigesp_sno_c_ajustarcontabilizacion.php");
	$io_ajustar = new sigesp_sno_c_ajustarcontabilizacion();
	require_once("../shared/class_folder/class_mensajes.php");
	$msg=new class_mensajes();
	uf_limpiarvariables();
	$ld_ano=substr($_SESSION["la_empresa"]["periodo"],0,4);
	$ld_fechasnom=substr($_SESSION["la_nomina"]["fechasper"],0,4);
	if($ld_fechasnom!=$ld_ano)
	{
		print("<script language=JavaScript>");
		print(" alert('Este proceso esta desactivo para Períodos de años Diferentes al Periodo de la Empresa.');");
		print(" location.href='sigespwindow_blank_hnomina.php'");
		print("</script>");
	}	
	switch ($ls_operacion) 
	{
		case "PROCESAR":
			$ld_fechadesde=$_SESSION["la_nomina"]["fecdesper"];
			$ld_fechahasta=$_SESSION["la_nomina"]["fechasper"];
			$ls_conpronom=$_SESSION["la_nomina"]["conpronom"];
			$ls_statusg=0;
			$ls_statusi=0;
			$lb_valido=$io_ajustar->uf_validarcierre_gastos_ingreso($ls_statusg,$ls_statusi);
			if (($lb_valido)&&($ls_statusg=="0")&&($ls_statusi=="0"))
			{
				if($ls_conpronom=="1") // contabilización por proyectos
				{
					$lb_valido=$io_ajustar->uf_procesar_ajustecontabilizacion_proceso($la_seguridad); 
				}
				else
				{
					$lb_valido=$io_ajustar->uf_procesar_ajustecontabilizacion($la_seguridad); 
				}
				if ($lb_valido)
				{
					uf_limpiarvariables();
				}
			}
			else
			{
				$msg->message("El cierre Presuepuestario de Gasto e Ingreso fue ejecutado con Anterioridad, No se puede ajustar Contabilización");	
			}
			break;
	}
	$io_ajustar->uf_destructor();
	unset($io_ajustar);
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table>
	</td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_hnomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_hnomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
		?>
  <table width="650" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="651" height="255" valign="top">
	<p>&nbsp;</p>
		<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="20" colspan="6"><div align="center">Ajustar Contabilizaci&oacute;n </div></td>
              </tr>
              <tr >
                <td width="133" height="22"><input name="botdisabled" type="hidden" id="botdisabled" value="<?php print  $ls_botdisabled; ?>"></td>
                <td colspan="5">&nbsp;</td>
              </tr>
              <tr >
                <td height="22" colspan="6"><div align="left" class="sin-borde3">
                  <div align="center">Informaci&oacute;n de la Nomina </div>
                </div></td>
              </tr>
              <tr >
                <td height="22"><div align="right">Denominaci&oacute;n</div></td>
                <td colspan="5"><div align="left">
                  <input name="txtdesnom" type="text" class="sin-borde3" id="txtdesnom" style="text-align:left" value="<?php print $ls_desnom?>" size="70" readonly>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Per&iacute;odo Actual </div></td>
                <td width="65">
                  <div align="left">
                    <input name="txtcodperi"  style="text-align:center "type="text" class="sin-borde3" id="txtcodperi" value="<?php  print $ls_peractnom ?>" size="9" maxlength="5" readonly>
                  </div></td>
                <td width="56"><div align="right">Desde</div></td>
                <td width="70"><div align="left">
                  <input name="txtfecdesper" style="text-align:center" type="text" class="sin-borde3" id="txtfecdesper" value="<?php print $ld_fecdesper; ?>" size="15" maxlength="10" readonly>
                </div></td>
                <td width="44"><div align="right">Hasta</div></td>
                <td width="244"><div align="left">
                  <input name="txtfechasper"  style="text-align:center"type="text" class="sin-borde3" id="txtfechasper" value="<?php print $ld_fechasper; ?>" size="15" maxlength="10" readonly>
                </div></td>
              </tr>
              <tr>
                <td height="22">&nbsp;</td>
                <td colspan="5">&nbsp;</td>
              </tr>
              <tr>
                <td height="22" colspan="6">
                  <div align="center">
                    <input name="botajustar" type="button" class="boton" id="botajustar" onClick="javascript: uf_ajustar();" value="Ajustar">
                  </div></td>
              </tr>
            <tr>
              <td height="21"><div align="right"></div></td>
              <td colspan="5">
			  	<input name="operacion" type="hidden" id="operacion">
				<input name="contabilizado" type="hidden" id="contabilizado" value="<?php print $li_contabilizado;?>">
			  </td>
            </tr>
          </table>
        <p>&nbsp;</p></td>
      </tr>
  </table>
  </form>
</div>
</body>
<script language="javascript">
function uf_ajustar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if (li_ejecutar==1)
   	{
		if(f.contabilizado.value=="0")
		{
			f.operacion.value ="PROCESAR";
			f.action="sigesp_sno_p_hajustarcontabilizacion.php";
			f.submit();
		}
		else
		{
			alert("El período está contabilizado. no se puede hacer ningún ajuste.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}
function ue_cerrar()
{
	location.href="sigespwindow_blank_hnomina.php";
}
</script>
</html>