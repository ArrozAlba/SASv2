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
	require_once("class_funciones_activos.php");
	$io_fun_activo=new class_funciones_activos();
	$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_p_cambioresponsable.php",$ls_permisos,$la_seguridad,$la_permisos);
	require_once("sigesp_saf_c_activo.php");
    $ls_codemp = $_SESSION["la_empresa"]["codemp"];
    $io_saf_tipcat= new sigesp_saf_c_activo();
    $ls_rbtipocat=$io_saf_tipcat->uf_select_valor_config($ls_codemp);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_cmpmov,$ls_codres,$ls_codresnew,$ls_nomres,$ls_nomresnew,$ls_obstra,$ld_feccam,$ls_checkuso,$ls_checkprimario;
		global $ls_codact,$ls_denact,$ls_idact,$ls_seract;
		$ls_cmpmov="";
		$ls_codres="";
		$ls_codresnew="";
		$ls_nomres="";
		$ls_nomresnew="";
		$ls_obstra="";
		$ld_feccam= date("d/m/Y");
		$ls_codact="";
		$ls_denact="";
		$ls_idact="";
		$ls_seract="";
		$ls_checkuso="";
		$ls_checkprimario="checked";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Cambio de Responsable</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
		{
			window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ return false;} 
		} 
	}
</script>
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Activos Fijos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
   <?php 
    if ($ls_rbtipocat == 1) 
    {
   ?>
   <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_csc.js"></script></td>
  <?php 
    }
	elseif ($ls_rbtipocat == 2)
	{
   ?>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_cgr.js"></script></td>
  <?php 
	}
	else
	{
   ?>
	<td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  <?php 
	}
   ?>
    <!-- <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td> -->
  </tr>
  <tr>
    <td height="13" colspan="8" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"></a><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" title="Cerrar" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"></a><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" title="Ayuda"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("sigesp_saf_c_cambioresponsable.php");
	$io_saf= new sigesp_saf_c_cambioresponsable();
	require_once("../shared/class_folder/sigesp_c_generar_consecutivo.php");
	$io_keygen= new sigesp_c_generar_consecutivo();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_codusureg=$_SESSION["la_logusr"];
	$ls_operacion=$io_fun_activo->uf_obteneroperacion();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();
			$ls_cmpmov= $io_keygen->uf_generar_numero_nuevo("SAF","saf_cambioresponsable","cmpmov","SAF",15,"","codemp",$ls_codemp);
		break;
		
		case "GUARDAR";
			$ls_cmpmov= $io_fun_activo->uf_obtenervalor("txtcmpmov","");
			$ls_codact= $io_fun_activo->uf_obtenervalor("txtcodact","");
			$ls_idact= $io_fun_activo->uf_obtenervalor("txtideact","");
			$ls_denact= $io_fun_activo->uf_obtenervalor("txtdenact","");
			$ls_seract= $io_fun_activo->uf_obtenervalor("txtseract","");
			$ld_feccam= $io_fun_activo->uf_obtenervalor("txtfeccam","");
			$ls_codres= $io_fun_activo->uf_obtenervalor("txtcodres","");
			$ls_nomres= $io_fun_activo->uf_obtenervalor("txtnomres","");
			$ls_codresnew= $io_fun_activo->uf_obtenervalor("txtcodresnew","");
			$ls_nomresnew= $io_fun_activo->uf_obtenervalor("txtnomresnew","");
			$ls_obstra= $io_fun_activo->uf_obtenervalor("txtobstra","");
			$ls_tiporesponsable= $io_fun_activo->uf_obtenervalor("rdtiporesponsable","");
			if($ls_tiporesponsable==0)
			{
				$ls_checkuso="";
				$ls_checkprimario="checked";
			}
			else
			{
				$ls_checkuso="checked";
				$ls_checkprimario="";
			}
			$lb_valido=$io_saf->uf_saf_procesar_cambioresponsable($ls_codemp,$ls_cmpmov,$ls_codact,$ls_idact,$ld_feccam,$ls_obstra,
																  $ls_codusureg,$ls_codres,$ls_codresnew,$ls_tiporesponsable,$la_seguridad);
			if ($lb_valido)
			{
				uf_limpiarvariables();
			}
		break;
	}
?>
<p>&nbsp;</p>
<div align="center">
  <table width="625" height="159" border="0" class="formato-blanco">
    <tr>
      <td width="617" height="153"><div align="left">
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="599" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td colspan="4" class="titulo-ventana">Cambio de Responsable </td>
  </tr>
  <tr class="formato-blanco">
    <td width="100" height="22">&nbsp;</td>
    <td height="22" colspan="2"><div align="right"></div></td>
    <td width="303" height="22"><div align="right">
      Fecha
        <input name="txtfeccam" type="text" id="txtfeccam" style="text-align:center " onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true" value="<?php print $ld_feccam ?>" size="13" maxlength="10">
    </div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Comprobante</div></td>
    <td height="22" colspan="3">        <input name="txtcmpmov" type="text" id="txtcmpmov" value="<?php print $ls_cmpmov ?>" maxlength="15" onBlur="javascript: ue_rellenarcampo(this,'15')" style="text-align:center ">      
    <input name="hidstatus" type="hidden" id="hidstatus"></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Activo</div></td>
    <td height="22" colspan="3"><input name="txtcodact" type="text" id="txtcodact" style="text-align:center" value="<?php print $ls_codact; ?>" size="20" readonly>
      <a href="javascript: ue_buscaractivo();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtdenact" type="text" class="sin-borde" id="txtdenact" style="text-align:left" value="<?php print $ls_denact; ?>" size="60"  readonly></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Identificador</div></td>
    <td width="122" height="22"><input name="txtideact" type="text" id="txtideact" style="text-align:center" value="<?php print $ls_idact; ?>" size="20"  readonly></td>
    <td width="72"><div align="right">Serial</div></td>
    <td height="22"><div align="left">
      <input name="txtseract" type="text" id="txtseract" value="<?php print $ls_seract; ?>" size="20" readonly>
    </div></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22">&nbsp;</td>
    <td height="22" colspan="3"><input name="rdtiporesponsable" type="radio" class="sin-borde" value="0" onChange="javascript: ue_limpiarresponsable();" <?php print $ls_checkuso; ?>>
      Responsable Primario
        <input name="rdtiporesponsable" type="radio" class="sin-borde" onChange="javascript: ue_limpiarresponsable();" value="1"  <?php print $ls_checkprimario; ?>>
        Responsable por Uso </td>
    </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Responsable Actual </div></td>
    <td height="22" colspan="3"><input name="txtcodres" type="text" id="txtcodres" value="<?php print $ls_codres ?>" size="12" maxlength="10" readonly>
      <a href="javascript: ue_buscarresponsableactual();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtnomres" type="text" class="sin-borde" id="txtnomres" value="<?php print $ls_nomres ?>" size="50" readonly></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Nuevo Responsable </div></td>
    <td height="22" colspan="3"><input name="txtcodresnew" type="text" id="txtcodresnew" value="<?php print $ls_codresnew ?>" size="12" maxlength="10" readonly>
      <a href="javascript: ue_catapersonalnew();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
      <input name="txtnomresnew" type="text" class="sin-borde" id="txtnomresnew" value="<?php print $ls_nomresnew ?>" size="50" readonly></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right">Observaciones</div></td>
    <td colspan="3" rowspan="2"><textarea name="txtobstra" cols="60" rows="3" id="txtobstra"><?php print $ls_obstra ?></textarea></td>
  </tr>
  <tr class="formato-blanco">
    <td height="22"><div align="right"></div></td>
    </tr>
</table>
<input name="operacion" type="hidden" id="operacion">
          </form>
      </div></td>
    </tr>
  </table>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_limpiarresponsable()
{
	f=document.form1;
	f.txtcodres.value="";
	f.txtnomres.value="";
}
function ue_buscar()
{
	window.open("sigesp_catdinamic_tipoarticulo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscaractivo()
{
	estact="IR";
	window.open("sigesp_saf_cat_codactivoss.php?estact="+estact+"","_blank","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscarresponsableactual()
{
	f=document.form1;
	if(f.rdtiporesponsable[0].checked==true)
	{
		tiporesponsable="primario";
	}
	else
	{
		tiporesponsable="uso";
	}
	codact=f.txtcodact.value;
	idact=f.txtideact.value;
	window.open("sigesp_saf_cat_activoresponsable.php?codact="+codact+"&idact="+idact+"&tiporesponsable="+tiporesponsable,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_catapersonalnew()
{
	f=document.form1;
	codres=f.txtcodres.value;
	if(codres=="")
	{
		alert("Debe seleccionar el responsable actual");
	}
	else
	{
		window.open("sigesp_saf_cat_personal.php?destino=responsablenuevo","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.action="sigesp_saf_p_cambioresponsable.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_ejecutar=f.ejecutar.value;
	if((li_incluir==1)||(li_ejecutar==1))
	{
		cmpmov=f.txtcmpmov.value;
		codact=f.txtcodact.value;
		idact=f.txtideact.value;
		codres=f.txtcodres.value;
		codresnew=f.txtcodresnew.value;
		if((cmpmov!="")&&(codact!="")&&(idact!="")&&(codres!="")&&(codresnew!=""))
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_saf_p_cambioresponsable.php";
			f.submit();
		}
		else
		{
			alert("Debe completar la Informacion Basica");
		}
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}
function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>