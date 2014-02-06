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
	$io_fun_nomina->uf_load_seguridad("SNR","sigesp_snorh_d_periodoadicional.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/09/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codperi,$ld_fecdesper,$ld_fechasper,$ls_operacion,$ls_existe,$io_fun_nomina;
		
		$ls_codperi="";
		$ld_fecdesper="";
		$ld_fechasper="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 12/09/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codnom, $ls_desnom,$ls_codperi,$ld_fecdesper,$ld_fechasper;
		
		$ls_codnom=$_POST["txtcodnom"];
		$ls_desnom=$_POST["txtdesnom"];
		$ls_codperi=$_POST["txtcodperi"];
		$ld_fecdesper=$_POST["txtfecdesper"];
		$ld_fechasper=$_POST["txtfechasper"];
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
<title >Definici&oacute;n de Periodo Adicional</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script language="javascript1.2" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_nominas.php");
	$io_nomina=new sigesp_snorh_c_nominas();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "NUEVO":
		 	$ls_codnom=$_GET["codnom"];
			$ls_desnom=$_GET["desnom"];
			$ls_tippernom=$_GET["tippernom"];
			$lb_valido=$io_nomina->uf_seleccionar_periodoadicional($ls_codnom,$ls_tippernom,$ls_codperi,$ld_fecdesper,$ld_fechasper);
			break;

		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_nomina->uf_guardar_periodoadicional($ls_existe,$ls_codnom,$ls_codperi,$ld_fecdesper,$ld_fechasper,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
				$ls_codnom=$_POST["txtcodnom"];
				$ls_desnom=$_POST["txtdesnom"];
			}
			break;
	}
	$io_nomina->uf_destructor();
	unset($io_nomina);
?>
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
        </table>	 </td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"></a><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_volver();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"></a><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_eliminar();"></a></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigesp_snorh_d_nominas.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="550" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="500" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr>
          <td colspan="2"><input name="txtdesnom" type="text" class="sin-borde2" id="txtdesnom" value="<?php print $ls_desnom;?>" size="60" readonly>
            <input name="txtcodnom" type="hidden" id="txtcodnom" value="<?php print $ls_codnom;?>"></td>
        </tr>
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Definici&oacute;n de Periodo Adicional </td>
        </tr>
        <tr>
          <td width="97" height="22">&nbsp;</td>
          <td width="397">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">C&oacute;digo</div></td>
          <td><div align="left">
            <input name="txtcodperi" type="text" id="txtcodperi" size="10" maxlength="3" value="<?php print $ls_codperi;?>" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha Desde </div></td>
          <td><input name="txtfecdesper" type="text" id="txtfecdesper" size="20" maxlength="10" value="<?php print $ld_fecdesper;?>" readonly></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha Hasta </div></td>
          <td><div align="left">
            <input name="txtfechasper" type="text" id="txtfechasper" size="20" maxlength="10" value="<?php print $ld_fechasper;?>" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td><input name="operacion" type="hidden" id="operacion">
            <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>"></td>
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
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		codnom = ue_validarvacio(f.txtcodnom.value);
		codperi = ue_validarvacio(f.txtcodperi.value);
		fecdesper = ue_validarvacio(f.txtfecdesper.value);
		fechasper = ue_validarvacio(f.txtfechasper.value);
		if ((codnom!="")&&(codperi!="")&&(fecdesper!="")&&(fechasper!=""))
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_snorh_d_periodoadicional.php";
			f.submit();
		}
		else
		{
			alert("Debe llenar todos los datos.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_volver()
{
	f=document.form1;
	f.operacion.value="BUSCAR";
	f.existe.value="TRUE";	
	codnom=ue_validarvacio(f.txtcodnom.value);
	f.action="sigesp_snorh_d_nominas.php?codnom="+codnom;
	f.submit();
}
</script> 
<?php
if (($ls_operacion=="GUARDAR")&&($lb_valido))
{
	print "<script language=JavaScript>";
	print "   ue_volver();";
	print "</script>";
}
?>		  
</html>