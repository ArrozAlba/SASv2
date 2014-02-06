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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_manejoperiodo.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_desnom, $ls_peractnom, $ls_desper, $ld_fecdesper, $ld_fechasper, $ls_operacion, $li_totalnomina;
		global $lb_cerrar, $lb_abrir, $io_cierreperiodo, $li_contabilizadoant, $io_fun_nomina, $ls_conpernom;
		
		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		$lb_valido=$io_sno->uf_crear_sessionnomina();		
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
			$ls_conpernom=$_SESSION["la_nomina"]["conpernom"];
			$ld_fecdesper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
			$ld_fechasper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
		}
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$li_totalnomina=$io_cierreperiodo->uf_verificar_periodo($ls_peractnom);
		$li_contabilizadoant=$io_cierreperiodo->uf_contabilizado_ant($ls_peractnom);
		$lb_cerrar="";
		/*if($li_totalnomina==0)
		{
			$lb_cerrar="disabled";
		}*/
		$ls_perresnom=$_SESSION["la_nomina"]["perresnom"];
		$lb_abrir="";
		if(($ls_peractnom=='001'))
		{
			$lb_abrir="disabled";
		}
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
<title>Proceso Cierre de Per&iacute;odo</title>
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
	require_once("sigesp_sno_c_cierre_periodo.php");
	$io_cierreperiodo = new sigesp_sno_c_cierre_periodo();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "CERRAR_PERIODO":
			$ld_fechadesde=$_SESSION["la_nomina"]["fecdesper"];
			$ld_fechahasta=$_SESSION["la_nomina"]["fechasper"];
			$lb_valido=$io_cierreperiodo->uf_chequear_encargaduria();
			if ($lb_valido)
			{
		 		$lb_valido=$io_cierreperiodo->uf_procesar_cierre_periodo($ls_peractnom,$ld_fechadesde,$ld_fechahasta,$la_seguridad); 
				if ($lb_valido)
				{
					uf_limpiarvariables();
				}
			}
			
			break;
	}
	$io_cierreperiodo->uf_destructor();
	unset($io_cierreperiodo);
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
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_nomina.js"></script></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
		?>
  <table width="650" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="651" height="255" valign="top">
	<p>&nbsp;</p>
		<table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="20" colspan="6"><div align="center">Cierre del Per&iacute;odo </div></td>
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
                    <input name="botcerper" type="button" class="boton" id="botcerper" onClick="javascript: uf_cerrar_periodo();" value="Cerrar Per&iacute;odo" <?php print $lb_cerrar; ?>>
                    <input name="botpercer" type="button" class="boton" id="botpercer" onClick="javascript: uf_abrir_periodo();" value="Per&iacute;odos Cerrados" <?php print $lb_abrir; ?>>
                      </div></td>
              </tr>
              <tr>
                <td height="22" colspan="6">
					<div id=transferir style="visibility:hidden" align="center"><img src="../shared/imagebank/cargando.gif">Cerrando Periodo... </div>
                 </td>
              </tr>
            <tr>
              <td height="21"><div align="right"></div></td>
              <td colspan="5">
			  	<input name="operacion" type="hidden" id="operacion">
				<input name="contabilizado" type="hidden" id="contabilizado" value="<?php print $li_contabilizadoant;?>">
				<input name="validacontabilizacion" type="hidden" id="validacontabilizacion" value="<?php print $ls_conpernom;?>">
				<input name="totalnomina" type="hidden" id="totalnomina" value="<?php print $li_totalnomina;?>">
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
function  uf_cerrar_periodo()
{
	f=document.form1;
	valido=true;
	li_ejecutar=f.ejecutar.value;
	li_totalnomina=f.totalnomina.value;
	if (li_ejecutar==1)
   	{
		if(f.validacontabilizacion.value=="1")
		{
			if(f.contabilizado.value!="1")
			{
				alert("El perído anterior no está contabilizado. Debe contabilizar el período anterior y luego cerrar este período.");
				valido=false;
			}
		}
		if((li_totalnomina==0)&&(valido))
		{
			if(confirm("¿Esta seguro de cerrar la Nómina con calculo Cero (0)?"))
			{
				valido=true;
			}
			else
			{
				valido=false;
			}
		}
		if(valido)
		{
			f.botcerper.disabled=true;
			mostrar('transferir');
			f.operacion.value ="CERRAR_PERIODO";
			f.action="sigesp_sno_p_manejoperiodo.php";
			f.submit();
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function uf_abrir_periodo()
{
	window.open("sigesp_sno_p_abrir_periodo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=250,left=200,top=300,resizable=no,location=no");
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank_nomina.php";
	f.submit();
}
function mostrar(nombreCapa)
{
	capa= document.getElementById(nombreCapa) ;
	capa.style.visibility="visible"; 
} 
</script>
</html>