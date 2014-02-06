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
	require_once("class_folder/class_funciones_mis.php");
	$oi_fun_integrador=new class_funciones_mis();
	$oi_fun_integrador->uf_load_seguridad("MIS","sigesp_mis_p_configuracion.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_select_campos()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_select_campos
		//		   Access: private
		//	  Description: Función que selecciona todos los campos de configuración
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 03/04/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $oi_fun_integrador,$li_bco_feccontdep,$li_sob_feccontcon;
		
		//----------------------------------------------CAJA Y BANCO-------------------------------------------
		$li_bco_feccontdep=trim($oi_fun_integrador->uf_select_config("MIS","BANCO","FECHA_CONT_DEPOSITOS","0","C"));
		//-----------------------------------------------------------------------------------------------------

		//-------------------------------------------------OBRAS-----------------------------------------------
		$li_sob_feccontcon=trim($oi_fun_integrador->uf_select_config("MIS","OBRAS","FECHA_CONT_CONTRATOS","0","C"));
		//-----------------------------------------------------------------------------------------------------
   }// end function uf_select_campos
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que obtiene el valor de los campos 
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 05/04/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		global $li_bco_feccontdep,$oi_fun_integrador,$li_sob_feccontcon;

		$li_bco_feccontdep=$oi_fun_integrador->uf_obtenervalor("chkbco_feccontdep","0");	
		$li_sob_feccontcon=$oi_fun_integrador->uf_obtenervalor("chksob_feccontcon","0");
   }// end function uf_load_variables
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
<title >Configuraci&oacute;n</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
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
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script language="javascript" src="../shared/js/valida_tecla.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-size: 12px}
-->
</style>
</head>
<body>
<?php 
	$ls_operacion=$oi_fun_integrador->uf_obteneroperacion();
	switch ($ls_operacion) 
	{
		
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$oi_fun_integrador->uf_guardar_configuracion($li_bco_feccontdep,$li_sob_feccontcon,$la_seguridad);
			if($lb_valido)
			{
				$oi_fun_integrador->io_mensajes->message("La configuración fue registrada.");
			}
			else
			{
				$oi_fun_integrador->io_mensajes->message("Ocurrio un error al guardar la configuración.");
			}
			break;
	}
	uf_select_campos();
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><span class="sin-borde2">M&oacute;dulo Integrador - <i>Configuraci&oacute;n</i></span></td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequeñas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	      <tr>
	  	        <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	        <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
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
    <td width="25" height="20" class="toolbar"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$oi_fun_integrador->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($oi_fun_integrador);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="760" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="710" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Configuraci&oacute;n</td>
        </tr>
        <tr class="titulo-celdanew">
          <td height="20" colspan="2"><div align="center">Caja y Banco </div></td>
          </tr>
		<tr>
          <td width="277" height="22"><div align="right">Contabilizar los Depositos con la fecha del Sistema </div></td>
          <td width="427"><div align="left"><input name="chkbco_feccontdep" type="checkbox" class="sin-borde" id="chkbco_feccontdep" value="1" <?PHP if($li_bco_feccontdep!="0"){print "checked";} ?>></div></td>
          </tr>
        <tr>
          <td colspan="2" class="titulo-celdanew">Obras</td>
          </tr>
        <tr>
          <td><div align="right">Contabilizar los Contratos con la fecha del Sistema </div></td>
          <td><div align="left"><input name="chksob_feccontcon" type="checkbox" class="sin-borde" id="chksob_feccontcon" value="1" <?PHP if($li_sob_feccontcon!="0"){print "checked";} ?>></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td><div align="right"></div></td>
          <td>
		      <div align="left">
		        <input name="operacion" type="hidden" id="operacion">
	          </div></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_guardar()
{
	f=document.form1;
	li_cambiar=f.cambiar.value;
	if(li_cambiar==1)
	{
		f=document.form1;
		f.operacion.value="GUARDAR";
		f.action="sigesp_mis_p_configuracion.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_cerrar()
{
	location.href="sigespwindow_blank.php";
}
</script> 
</html>