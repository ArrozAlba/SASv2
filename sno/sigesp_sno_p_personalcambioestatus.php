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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_personalcambioestatus.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_nomper,$ls_estactper,$ld_fecegrper,$ls_obsegrper,$ls_operacion,$ls_desnom,$ls_desper;
		global $io_fun_nomina,$li_calculada;
		
		require_once("sigesp_sno.php");
		$io_sno=new sigesp_sno();
		$lb_valido=$io_sno->uf_crear_sessionnomina();		
		$ls_desnom="";
		$ls_peractnom="";
		$ls_desper="";			
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
		}
		$ls_codper="";
		$ls_nomper="";
		$ls_estactper="";
		$ld_fecegrper="dd/mm/aaaa";
		$ls_obsegrper="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		unset($io_sno);
		require_once("sigesp_sno_c_calcularnomina.php");
		$io_calcularnomina=new sigesp_sno_c_calcularnomina();
		$li_calculada=str_pad($io_calcularnomina->uf_existesalida(),1,"0");
		unset($io_calcularnomina);
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
<title >Cambio de Estatus de Personal</title>
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
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php 
	require_once("sigesp_sno_c_personalnomina.php");
	$io_personalnomina=new sigesp_sno_c_personalnomina();
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "PROCESAR":
			$ls_codper=$_POST["txtcodper"];
			$ls_nomper=$_POST["txtnomper"];
			$ls_estactper=$_POST["txtestactper"];
			$ls_estper=$_POST["cmbestper"];
			$ld_fecegrper="1900-01-01";
			$ls_obsegrper="";
			if(($ls_estper==3)||($ls_estper==4))
			{			
				$ld_fecegrper=$_POST["txtfecegrper"];
				$ls_obsegrper=$_POST["txtobsegrper"];
			}
			$lb_valido=$io_personalnomina->uf_update_estatus($ls_codper,$ls_estper,$ld_fecegrper,$ls_obsegrper,"2",$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
			}
			break;
	}
	$io_personalnomina->uf_destructor();
	unset($io_personalnomina);	
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
    <td width="25" height="20" class="toolbar"><div align="center"><a href="javascript: ue_procesar();"><img src="../shared/imagebank/tools20/ejecutar.gif" title='Ejecutar' alt="Ejecutar" width="20" height="20" border="0"></a></div></td>
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
<table width="660" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="610" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="2" class="titulo-ventana">Cambio de Estatus  de Personal </td>
        </tr>
        <tr>
          <td width="165" height="22"><div align="right"></div></td>
          <td width="449">&nbsp;</td>
        </tr>
        <tr>
          <td height="22"><div align="right">Personal</div></td>
          <td><div align="left">
            <input name="txtcodper" type="text" id="txtcodper" size="13" maxlength="10" value="<?php print $ls_codper;?>" readonly>
            <a href="javascript: ue_buscarpersonal();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
            <input name="txtnomper" type="text" class="sin-borde" id="txtnomper" value="<?php print $ls_nomper;?>" size="60" maxlength="120" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estado Actual </div></td>
          <td><div align="left">
            <input name="txtestactper" type="text" id="txtestactper" value="<?php print $ls_estactper;?>" size="20" maxlength="20" readonly>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Estado Nuevo </div></td>
          <td><div align="left">
            <select name="cmbestper" id="cmbestper">
              <option value="" selected>--Seleccione Uno--</option>
              <option value="1">Activo</option>
              <option value="2">Vacaciones</option>
              <option value="3">Egresado</option>
              <option value="4">Suspendido</option>
            </select>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Egreso/ Suspensi&oacute;n </div></td>
          <td><div align="left">
            <input name="txtfecegrper" type="text" id="txtfecegrper" value="<?php print $ld_fecegrper;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Observaci&oacute;n</div></td>
          <td><div align="left">
            <textarea name="txtobsegrper" cols="80" rows="3" id="txtobsegrper" onKeyUp="javascript: ue_validarcomillas(this);"><?php print $ls_obsegrper;?></textarea>
          </div></td>
        </tr>
        <tr>
          <td><div align="right"></div></td>
          <td><input name="operacion" type="hidden" id="operacion">
		  <input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>">            </td>
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
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		li_ejecutar=f.ejecutar.value;
		if(li_ejecutar==1)
		{
			valor=f.cmbestper.selectedIndex;
			estactper=f.txtestactper.value;
			estper=ue_validarvacio(f.cmbestper.options[valor].text);
			valestper=ue_validarvacio(f.cmbestper.options[valor].value);
			codper = ue_validarvacio(f.txtcodper.value);
			f.txtfecegrper.value=ue_validarfecha(f.txtfecegrper.value);
			fecegrper = ue_validarvacio(f.txtfecegrper.value);
			obsegrper = ue_validarvacio(f.txtobsegrper.value);
			if((estactper==estper)||(valestper==""))
			{
				alert("No Cambió el estatus del personal");
			}
			else
			{
				if ((codper!="")&&(valestper!=""))
				{
					if((valestper=="1")||(valestper=="2"))
					{
						f.operacion.value="PROCESAR";
						f.action="sigesp_sno_p_personalcambioestatus.php";
						f.submit();
					}
					else
					{
						if((fecegrper!="")&&(obsegrper!=""))
						{
							f.operacion.value="PROCESAR";
							f.action="sigesp_sno_p_personalcambioestatus.php";
							f.submit();
						}
						else
						{
							alert("Debe ingresar la fecha, y observación del egreso ó suspensión.");
						}
					}
				}
				else
				{
					alert("Debe seleccionar el personal.");
				}
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}		
	}
	else
	{
		alert("La nómina ya se calculó reverse y vuelva a intentar");
	}
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
}

function ue_buscarpersonal()
{
	window.open("sigesp_sno_cat_personalnomina.php?tipo=cambioestatus","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
</html>