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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_vacacionvencida.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 15/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codperdes,$ls_codperhas,$ls_nomperdes,$ls_nomperhas,$li_totper,$ls_peractnom,$ld_fecdesper,$ld_fechasper;
		global $ls_operacion,$ls_desnom,$io_fun_nomina,$ls_desper;
		
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
			$ld_fecdesper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fecdesper"]);
			$ld_fechasper=$io_sno->io_funciones->uf_convertirfecmostrar($_SESSION["la_nomina"]["fechasper"]);
		}
		$ls_codperdes="";
		$ls_codperhas="";
		$ls_nomperdes="";
		$ls_nomperhas="";
		$li_totper=0;
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
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
<title >Generar Vacaciones Vencidas</title>
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 

	require_once("sigesp_sno_c_vacacion.php");
	$io_vacacion=new sigesp_sno_c_vacacion();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "PROCESAR":
			$ls_codperdes=$_POST["txtcodperdes"];
			$ls_nomperdes=$_POST["txtnomperdes"];
			$ls_codperhas=$_POST["txtcodperhas"];
			$ls_nomperhas=$_POST["txtnomperhas"];
			$ld_fecdes=$_SESSION["la_nomina"]["fecdesper"];
			$ld_fechas=$_SESSION["la_nomina"]["fechasper"];
			$lb_valido=$io_vacacion->uf_procesar_vencidas($ls_codperdes,$ls_codperhas,$la_seguridad);
			break;

		case "BUSCAR":
			$ls_codperdes=$_POST["txtcodperdes"];
			$ls_nomperdes=$_POST["txtnomperdes"];
			$ls_codperhas=$_POST["txtcodperhas"];
			$ls_nomperhas=$_POST["txtnomperhas"];
			break;
	}
	$lb_valido=$io_vacacion->uf_load_totalpersonal($ls_codperdes,$ls_codperhas,$li_totper);
	$io_vacacion->uf_destructor();
	unset($io_vacacion);
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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title="Ejecutar" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
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
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_nomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
<table width="630" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="580" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="6" class="titulo-ventana">Generar Vacaciones Vencidas </td>
        </tr>
        <tr>
          <td width="128" height="22">&nbsp;</td>
          <td colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Per&iacute;odo</div></td>
          <td width="30"><div align="left">
            <input name="txtperactnom" type="text" class="sin-borde3" id="txtperactnom" value="<?php print $ls_peractnom;?>" size="6" maxlength="3" readonly>
          </div></td>
          <td width="66"><div align="right">Fecha Inicio</div></td>
          <td width="70">
            <div align="left">
              <input name="txtfecdesper" type="text" class="sin-borde3" id="txtfecdesper" value="<?php print  $ld_fecdesper;?>" size="13" maxlength="10" readonly>
                </div></td><td width="59"><div align="right">Fecha Fin </div></td>
                <td width="213">
                    <div align="left">
                      <input name="txtfechasper" type="text" class="sin-borde3" id="txtfechasper" value="<?php print  $ld_fechasper;?>" size="13" maxlength="10" readonly>
                          </div></td></tr>
        <tr>
          <td height="22"><div align="right">Personal Desde </div></td>
          <td colspan="5">
            <div align="left">
              <input name="txtcodperdes" type="text" id="txtcodperdes" size="13" maxlength="10" value="<?php print $ls_codperdes;?>" readonly>
              <a href="javascript: ue_buscarpersonaldesde();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtnomperdes" type="text" class="sin-borde" id="txtnomperdes" size="63" maxlength="100" value="<?php print $ls_nomperdes;?>" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Personal Hasta </div></td>
          <td colspan="5">
            <div align="left">
              <input name="txtcodperhas" type="text" id="txtcodperhas" value="<?php print $ls_codperhas;?>" size="13" maxlength="10" readonly>
              <a href="javascript: ue_buscarpersonalhasta();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
              <input name="txtnomperhas" type="text" class="sin-borde" id="txtnomperhas" size="63" maxlength="100" value="<?php print $ls_nomperhas;?>" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right">Total de Personas</div></td>
          <td colspan="5">
            <div align="left">
              <input name="txttotper" type="text" id="txttotper" value="<?php print $li_totper;?>" size="10" maxlength="5" readonly>
              </div></td></tr>
        <tr>
          <td height="22"><div align="right"></div></td>
          <td colspan="5"><div align="left">
            <input name="operacion" type="hidden" id="operacion" value="<?php print $ls_operacion;?>">            
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
function ue_procesar()
{
	f=document.form1;
	li_ejecutar=f.ejecutar.value;
	if (li_ejecutar==1)
   	{
		if(f.txttotper.value>0)
		{
			f.operacion.value="PROCESAR";
			f.action="sigesp_sno_p_vacacionvencida.php";
			f.submit();
		}
		else
		{
			alert("No hay personal a quien generarle las vacaciones.");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}		
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
}

function ue_buscarpersonaldesde()
{
	window.open("sigesp_sno_cat_personalnomina.php?tipo=vacaciondes","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpersonalhasta()
{
	window.open("sigesp_sno_cat_personalnomina.php?tipo=vacacionhas","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
</script> 
</html>