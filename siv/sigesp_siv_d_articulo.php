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
require_once("class_funciones_inventario.php");
$io_fun_activo=new class_funciones_inventario();
$io_fun_activo->uf_load_seguridad("SIV","sigesp_siv_d_articulo.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		/////////////////////////////////////////////////////////////////////////////////////
   		global $ls_codart,$ls_denart,$ls_codtipart,$ld_feccreart,$ls_obsart,$li_exiart,$li_eximinart,$li_eximaxart,$ls_codunimed;
   		global $li_prearta,$li_preartb,$li_preartc,$li_preartd,$ld_fecvenart,$ls_spg_cuenta,$li_pesart,$li_altart,$li_ancart,$li_proart;
		global $ls_fotart,$li_exiiniart,$li_ultcosart,$li_cosproart,$disabled,$ls_dentipart,$ls_denunimed;
   		global $ls_codcatsig,$ls_dencatsig,$li_estnum,$ls_sccuenta,$ls_densccuenta,$li_reoart;
		global $ls_fotowidth,$ls_fotoheight,$ls_foto,$lb_abrircargos,$ls_codprod,$ls_denprod,$ls_serart,$ls_fabart,$ls_ubiart,$ls_docart,$ls_movi;
        global $ls_clasificacion;
		$ls_codart="";
		$ls_denart="";
		$ls_codtipart="";
		$ls_codunimed="";
		$ls_dentipart="";
		$ls_denunimed="";
		$ld_feccreart=date("d/m/Y");
		$ls_obsart="";
		$li_exiart="";
		$li_eximinart="";
		$li_eximaxart="";
		$li_reoart="";
		$li_codunimed="";
		$li_prearta="";
		$li_preartb="";
		$li_preartc="";
		$li_preartd="";
		$ld_fecvenart="";
		$ls_spg_cuenta="";
		$ls_sccuenta="";
		$ls_densccuenta="";
		$li_pesart="";
		$li_altart="";
		$li_ancart="";
		$li_proart="";
		$ls_fotart="";
		$li_exiiniart="";
		$li_ultcosart="";
		$li_cosproart="";
		$ls_codcatsig="";
		$ls_dencatsig="";
		$li_estnum="";
		$lb_abrircargos=false;
		$disabled="disabled";
		$ls_fotowidth="121";
		$ls_fotoheight="94";
		$ls_foto="blanco.jpg";
		$ls_codprod="";
		$ls_denprod="";
		$ls_serart="";
		$ls_fabart="";
		$ls_ubiart="";
		$ls_docart="";
		$ls_movi="";
		$ls_clasificacion="";
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Art&iacute;culo </title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funciones.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<link href="css/siv.css" rel="stylesheet" type="text/css">
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
.Estilo1 {font-size: 12px}
-->
</style>
</head>

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Inventario </td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>	
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fun= new class_funciones_db($con);
	require_once("../shared/class_folder/class_funciones.php");
	$io_func= new class_funciones($con);
	require_once("sigesp_siv_c_articulo.php");
	$io_siv= new sigesp_siv_c_articulo();
	require_once("class_funciones_inventario.php");
	$io_funciones_inventario= new class_funciones_inventario();
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];
	$ls_fotowidth="121";
	$ls_fotoheight="94";
	$ls_foto ="blanco.jpg";
	$ls_operacion=$io_funciones_inventario->uf_obteneroperacion();
	uf_limpiarvariables();
	$li_catalogo=$io_siv->uf_siv_select_catalogo($li_estnum,$li_estcmp);
	$ls_movimiento=$io_siv->uf_verificarmovimientos();
	switch ($ls_operacion) 
	{
		case "NUEVO":
			if($li_catalogo)
			{
				print("<script language=JavaScript>");
				print "window.open('sigesp_siv_cat_sigecof.php','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes');";			
				print("</script>");
			}
			if($li_estnum)
			{
				$ls_emp="";
				$ls_codemp="";
				$ls_tabla="siv_articulo";
				$ls_columna="codart";
			
				$ls_codart=$io_fun->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
			}
			$ls_readonly="";
		break;
		
		case "GUARDAR":
			$ls_valido= false;
			if($li_catalogo)
			{
				$ls_readonly="readonly";
			}
			else
			{
				$ls_readonly="";
			}
			
			$ls_codart=$io_funciones_inventario->uf_obtenervalor("txtcodart","");
			$ls_denart=$io_funciones_inventario->uf_obtenervalor("txtdenart","");
			$ls_codtipart=$io_funciones_inventario->uf_obtenervalor("txtcodtipart","");
			$ls_codunimed=$io_funciones_inventario->uf_obtenervalor("txtcodunimed","");
			$ls_dentipart=$io_funciones_inventario->uf_obtenervalor("txtdentipart","");
			$ls_denunimed=$io_funciones_inventario->uf_obtenervalor("txtdenunimed","");
			$ld_feccreart=$io_funciones_inventario->uf_obtenervalor("txtfeccreart","");
			$ls_obsart=$io_funciones_inventario->uf_obtenervalor("txtobsart","");					
			$li_exiart=$io_funciones_inventario->uf_obtenervalor("txtexiart","");
			$li_exiiniart=$io_funciones_inventario->uf_obtenervalor("txtexiiniart","");
			$li_eximinart=$io_funciones_inventario->uf_obtenervalor("txteximinart","");
			$li_eximaxart=$io_funciones_inventario->uf_obtenervalor("txteximaxart","");
			$li_prearta=$io_funciones_inventario->uf_obtenervalor("txtprearta","");
			$li_preartb=$io_funciones_inventario->uf_obtenervalor("txtpreartb","");
			$li_preartc=$io_funciones_inventario->uf_obtenervalor("txtpreartc","");
			$li_preartd=$io_funciones_inventario->uf_obtenervalor("txtpreartd","");
			$ld_fecvenart=$io_funciones_inventario->uf_obtenervalor("txtfecvenart","");
			$ls_codcatsig=$io_funciones_inventario->uf_obtenervalor("txtcodcatsig","");
			$ls_dencatsig=$io_funciones_inventario->uf_obtenervalor("txtdencatsig","");
			$ls_spg_cuenta=$io_funciones_inventario->uf_obtenervalor("txtspg_cuenta","");
			$ls_sccuenta=$io_funciones_inventario->uf_obtenervalor("txtsccuenta","");
			//$ls_densccuenta=$io_funciones_inventario->uf_obtenervalor("txtspg_cuenta",""); 
			$ls_densccuenta=$io_funciones_inventario->uf_obtenervalor("txtdensccuenta","");
			$li_pesart=$io_funciones_inventario->uf_obtenervalor("txtpesart","");
			$li_altart=$io_funciones_inventario->uf_obtenervalor("txtaltart","");
			$li_ancart=$io_funciones_inventario->uf_obtenervalor("txtancart","");
			$li_proart=$io_funciones_inventario->uf_obtenervalor("txtproart","");
			$ls_status=$io_funciones_inventario->uf_obtenervalor("hidstatusc","");
			$li_ultcosart=$io_funciones_inventario->uf_obtenervalor("txtultcosart","");
			$li_cosproart=$io_funciones_inventario->uf_obtenervalor("txtcosproart","");
			$ls_nomfot=$HTTP_POST_FILES['txtfotart']['name']; 
			$ls_serart=$io_funciones_inventario->uf_obtenervalor("txtserart","");
			$ls_fabart=$io_funciones_inventario->uf_obtenervalor("txtfabart","");
			$ls_ubiart=$io_funciones_inventario->uf_obtenervalor("txtubiart","");
			$ls_docart=$io_funciones_inventario->uf_obtenervalor("txtdocart","");
			$li_reoart=$io_funciones_inventario->uf_obtenervalor("txtreoart","");
			if ($ls_nomfot!="")
			{
				$ls_nomfot=$ls_codart.substr($ls_nomfot,strrpos($ls_nomfot,"."));
			}
			$ls_tipfot=$HTTP_POST_FILES['txtfotart']['type']; 
			$ls_tamfot=$HTTP_POST_FILES['txtfotart']['size']; 
			$ls_nomtemfot=$HTTP_POST_FILES['txtfotart']['tmp_name'];
			$ls_codprod=$io_funciones_inventario->uf_obtenervalor("txtcodprod","");
		
			if(($ls_codart=="")||($ld_feccreart=="")||($ls_codtipart=="")||($ls_codunimed=="")||($ls_denart=="")||($li_exiiniart=="")||($li_eximinart=="")||($li_eximaxart=="")||($ls_spg_cuenta==""))
			{
				$io_msg->message("Debe completar todos los campos requeridos");
				$disabled="disabled";
			}
			else
			{
				$lb_valido=$io_siv->uf_siv_select_cuentaspg($ls_codemp,$ls_spg_cuenta);
				if($lb_valido)
				{				
					$li_exiart=    str_replace(".","",$li_exiart);
					$li_exiart=    str_replace(",",".",$li_exiart);
					$li_exiiniart= str_replace(".","",$li_exiiniart);
					$li_exiiniart= str_replace(",",".",$li_exiiniart);
					$li_eximinart= str_replace(".","",$li_eximinart);
					$li_eximinart= str_replace(",",".",$li_eximinart);
					$li_eximaxart= str_replace(".","",$li_eximaxart);
					$li_eximaxart= str_replace(",",".",$li_eximaxart);
					$li_prearta=   str_replace(".","",$li_prearta);
					$li_prearta=   str_replace(",",".",$li_prearta);
					$li_preartb=   str_replace(".","",$li_preartb);
					$li_preartb=   str_replace(",",".",$li_preartb);
					$li_preartc=   str_replace(".","",$li_preartc);
					$li_preartc=   str_replace(",",".",$li_preartc);
					$li_preartd=   str_replace(".","",$li_preartd);
					$li_preartd=   str_replace(",",".",$li_preartd);
					$li_pesart=    str_replace(".","",$li_pesart);
					$li_pesart=    str_replace(",",".",$li_pesart);
					$li_altart=    str_replace(".","",$li_altart);
					$li_altart=    str_replace(",",".",$li_altart);
					$li_ancart=    str_replace(".","",$li_ancart);
					$li_ancart=    str_replace(",",".",$li_ancart);
					$li_proart=    str_replace(".","",$li_proart);
					$li_proart=    str_replace(",",".",$li_proart);
					$li_reoart=    str_replace(".","",$li_reoart);
					$li_reoart=    str_replace(",",".",$li_reoart);
					/////////////////////////////////////////////////////////////////
					$ld_feccreart=$io_func-> uf_formatovalidofecha($ld_feccreart);
					$ld_fecvenart=$io_func-> uf_formatovalidofecha($ld_fecvenart);
					/////////////////////////////////////////////////////////////////
					$ld_feccreart=$io_func->uf_convertirdatetobd($ld_feccreart);
					$ld_fecvenart=$io_func->uf_convertirdatetobd($ld_fecvenart);					
					if ($ls_status=="C")
					{
						$lb_valido=$io_siv->uf_siv_update_articulo($ls_codemp, $ls_codart, $ls_denart, $ls_codtipart, $ls_codunimed,
																   $ld_feccreart, $ls_obsart, $li_exiart, $li_exiiniart, $li_eximinart,
																   $li_eximaxart, $li_prearta, $li_preartb, $li_preartc, $li_preartd, 
																   $ld_fecvenart, $ls_spg_cuenta, $li_pesart, $li_altart, $li_ancart,
																   $li_proart, $ls_nomfot, $ls_codcatsig, $ls_sccuenta, $la_seguridad,
																   $ls_codprod,$ls_serart,$ls_fabart,$ls_ubiart,$ls_docart,$li_reoart);
						if($lb_valido)
						{
							$lb_valido=$io_siv->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
						}
		
						if($lb_valido)
						{
							$io_msg->message("El artículo fue actualizado.");
							$disabled="";
							uf_limpiarvariables();
							$ls_readonly="readonly";							
						}	
						else
						{
							$io_msg->message("El artículo no pudo ser actualizado.");
							$disabled="disabled";
							uf_limpiarvariables();
							$ls_readonly="readonly";
						}
					}
					else
					{
						$lb_encontrado=$io_siv->uf_siv_select_articulo($ls_codemp,$ls_codart);
						if ($lb_encontrado)
						{
							$io_msg->message("El artículo ya existe."); 
							$disabled="disabled";
	
						}
						else
						{
							$lb_valido=$io_siv->uf_siv_insert_articulo($ls_codemp, $ls_codart, $ls_denart, $ls_codtipart, $ls_codunimed,
																	   $ld_feccreart, $ls_obsart, $li_exiart, $li_exiiniart, $li_eximinart,
																	   $li_eximaxart, $li_prearta, $li_preartb, $li_preartc, $li_preartd, 
																	   $ld_fecvenart, $ls_spg_cuenta, $li_pesart, $li_altart, $li_ancart,
																	   $li_proart, $ls_nomfot, $ls_codcatsig, $ls_sccuenta, $la_seguridad,
																	   $ls_codprod,$ls_serart,$ls_fabart,$ls_ubiart,$ls_docart,$li_reoart);
	
							if($lb_valido)
							{
								$lb_valido=$io_siv->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
							}
							if ($lb_valido)
							{
								$io_msg->message("El artículo fue registrado.");
								$lb_abrircargos=true;
								//uf_limpiarvariables();
								$ls_readonly="readonly";
								$disabled="";
								$ls_clasificacion=$io_siv->uf_clasificacionarticulo($ls_codtipart);								
							}
							else
							{
								$io_msg->message("No se pudo incluir el artículo.");
								$disabled ="disabled";
								//uf_limpiarvariables();
								$ls_readonly="readonly";
							}
						
						}
					}
				}
				else
				{
					$io_msg->message("Debe incluir una cuenta presupuestaria valida");
					$disabled="disabled";
					$ls_readonly="readonly";
				}
			}
			/////////////////////////////////////////////////////////////////
			$ld_feccreart=$io_func-> uf_formatovalidofecha($ld_feccreart);
			$ld_fecvenart=$io_func-> uf_formatovalidofecha($ld_fecvenart);
			/////////////////////////////////////////////////////////////////
			$ld_feccreart=$io_func->uf_convertirfecmostrar($ld_feccreart);
			$ld_fecvenart=$io_func->uf_convertirfecmostrar($ld_fecvenart);

		break;

		case "ELIMINAR":
			$ls_codart=    $io_funciones_inventario->uf_obtenervalor("txtcodart","");
		
			$lb_valido=$io_siv->uf_siv_delete_articulo($ls_codemp,$ls_codart, $la_seguridad);
	
			if($lb_valido)
			{
				$io_msg->message("El artículo fue eliminado.");
				uf_limpiarvariables();
				$ls_readonly="readonly";
			}	
			else
			{
				$io_msg->message("No se pudo eliminar el artículo.");
				uf_limpiarvariables();
				$ls_readonly="readonly";
			}
		break;
		
		case "VERIFICAR":
			$lb_valido=$io_siv->uf_verificarmovimientos();
	
			if($lb_valido==1)
			{
				$io_msg->message("No se puede actulizar la unidad de medida ya que posee movimientos.");
				uf_limpiarvariables();
				$ls_readonly="readonly";
			}	
			
		break;
	}
	
	
?>

<p>&nbsp;</p>
<div align="center">
  <form name="form1" method="post" action="" enctype="multipart/form-data">
    <table width="683" height="647" border="0" class="formato-blanco">
      <tr>
        <td height="15" colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td height="583" colspan="2"><div align="left">
            <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
            <table width="620" border="0" align="center" cellpadding="1" cellspacing="1" class="formato-blanco">
              <tr>
                <td colspan="4" class="titulo-ventana">Definici&oacute;n de Art&iacute;culo </td>
              </tr>
              <tr class="formato-blanco">
                <td height="13" colspan="4"> <div align="center">Los Campos en (*) son necesarios para la Incluir el Art&iacute;culo </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="13" colspan="4"><input name="hidstatusc" type="hidden" id="hidstatusc">
                  <input name="hidstatus" type="hidden" id="hidstatus">
                  <input name="hidmovimiento" type="hidden" id="hidmovimiento" value="<?php print $ls_movimiento?>"></td>
				  <input name="txtcatalogo" type="hidden" id="txtcatalogo" value="<?php print $li_catalogo?>">
				  <input name="txtclasif" type="hidden" id="txtclasif" value="<?php print $ls_clasificacion?>">
              </tr>
			  <tr class="formato-blanco">
			  <?php
			  	if($li_estnum)
				{?>
                <td height="22"><div align="right">(*)C&oacute;digo</div></td>
                <td height="22"><input name="txtcodart" type="text" id="txtcodart" value="<?php print $ls_codart?>" size="25" maxlength="20" <?php print $ls_readonly?> onKeyPress="return keyRestrict(event,'1234567890');"  <?php if($li_estcmp==1){?> onBlur="ue_rellenarcampo(this,'20');"<?php } ?>></td>
  			  <?php
			  	}
				else
				{ 
			  ?>
                <td height="22"><div align="right">(*)C&oacute;digo</div></td>
                <td height="22"><input name="txtcodart" type="text" id="txtcodart" value="<?php print $ls_codart?>" size="25" maxlength="20" <?php print $ls_readonly?> onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz-');"  <?php if($li_estcmp==1){?> onBlur="ue_rellenarcampo(this,'20');"<?php } ?>></td>
			  <?php
			  	}
			  ?>
                <td width="104" rowspan="6"><div align="center"><img name="foto" id="foto" src="fotosarticulos/<?php print $ls_foto?>" width="121" height="94" class="formato-blanco"></div></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Denominaci&oacute;n</div></td>
                <td height="22"><input name="txtdenart" type="text" id="txtdenart" value="<?php print $ls_denart?>" size="45" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ()#!%/[]*-+_.,:;');"></td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Tipo de Art&iacute;culo </div></td>
                <td height="22"><input name="txtcodtipart" type="text" id="txtcodtipart" value="<?php print $ls_codtipart?>" size="6" maxlength="4" readonly>
                <a href="javascript: ue_catatipart();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> <input name="txtdentipart" type="text" class="sin-borde" id="txtdentipart" value="<?php print $ls_dentipart?>" size="30" readonly>
                <input name="txtobstipart" type="hidden" id="txtobstipart"></td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td height="21"><div align="right"> (*)Unidad de Medida</div></td>
                <td height="21"><input name="txtcodunimed" type="text" id="txtcodunimed" value="<?php print $ls_codunimed?>" size="6" maxlength="4" readonly>
                  <a href="javascript: ue_cataunimed();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> <input name="txtdenunimed" type="text" class="sin-borde" id="txtdenunimed" value="<?php print $ls_denunimed?>" size="30" readonly>
                <input name="txtunidad" type="hidden" id="txtunidad">
                <input name="txtobsunimed" type="hidden" id="txtobsunimed"></td>
                <td height="21">&nbsp;</td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Fecha de Creaci&oacute;n </div></td>
                <td height="22"><input name="txtfeccreart" type="text" id="txtfeccreart" value="<?php print $ld_feccreart?>" size="15" maxlength="10" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true"></td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td height="26"><div align="right">
                    <p>Observaciones</p>
                </div></td>
                <td colspan="3" rowspan="2"><textarea name="txtobsart" cols="45" rows="3" id="txtobsart" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz ()#!%/[]*-+_.,:;');"><?php print $ls_obsart?></textarea></td>
              </tr>
              <tr>
                <td height="27">&nbsp;</td>
              </tr>
			  <?php
			  	if($li_catalogo==1)
				{?>
              <tr>
                <td height="22" align="right">(*)SIGECOF</td>
                <td height="22" colspan="3"><label>
                  <input name="txtcodcatsig" type="text" id="txtcodcatsig" style="text-align:center" value="<?php print $ls_codcatsig?>" size="25" readonly>
                  <a href="javascript: ue_sigecof();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                  <input name="txtdencatsig" type="text" class="sin-borde" id="txtdencatsig" value="<?php print $ls_dencatsig?>" size="50" readonly>
                </label></td>
              </tr>
			  <?php
			  	}
			  ?>
              <tr>
                <td height="22"><div align="right"> (*)Cuenta Presupestario </div></td>
                <td height="22" colspan="3"><input name="txtspg_cuenta" type="text" id="txtspg_cuenta" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_spg_cuenta?>" size="25" maxlength="25" readonly style="text-align:center ">
			  <?php
				if($li_catalogo!=1)
				{
				?>

                    <a href="javascript: ue_cataspg();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></td>
			  <?php
			  	}
			  ?>
              </tr>
              <tr>
                <td height="22"><div align="right">Catalogo Est&aacute;ndar de las Naciones unidas </div></td>
                <td height="22" colspan="3"><input name="txtcodprod" type="text" id="txtcodprod" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print $ls_codprod ?>" size="10" maxlength="10" readonly style="text-align:center ">
                <a href="javascript: ue_catalogo_nacunid();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtdenprod" type="text" class="sin-borde" id="txtdenprod" value="<?php print $ls_denprod?>" size="50" readonly>                </td>
              </tr>
              <tr>
                <td height="22"><div align="right"> Cuenta Contable</div></td>
                <td height="22" colspan="3"><input name="txtsccuenta" type="text" id="txtsccuenta" value="<?php print $ls_sccuenta?>" size="25" style="text-align:center" readonly>
                <a href="javascript: ue_catascg();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                <input name="txtdensccuenta" type="text" class="sin-borde" id="txtdensccuenta"  value="<?php print $ls_densccuenta?>" size="50" readonly></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Existencia Actual </div></td>
                <td height="22" colspan="3"><input name="txtexiart" type="text" id="txtexiart" onKeyUp="javascript: ue_validarcomillas(this);" value="<?php print number_format($li_exiart,2,',','.');?>" size="12" readonly style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Existencia Inicial </div></td>
                <td height="22" colspan="3"><input name="txtexiiniart" type="text" id="txtexiiniart" onKeyPress="return(ue_formatonumero(this,'.',',',event));" value="<?php print number_format($li_exiiniart,2,',','.');?>" size="12" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Existencia M&iacute;nima </div></td>
                <td height="22" colspan="3"><input name="txteximinart" type="text" id="txteximinart" value="<?php print number_format($li_eximinart,2,',','.');?>" size="12" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Existencia M&aacute;xima</div></td>
                <td height="22" colspan="3"><input name="txteximaxart" type="text" id="txteximaxart" value="<?php print number_format($li_eximaxart,2,',','.');?>" size="12" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">(*)Punto de Reorden </div></td>
                <td height="22" colspan="3"><input name="txtreoart" type="text" id="txtreoart" value="<?php print number_format($li_reoart,2,',','.');?>" size="12"  onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right"></td>
              </tr>
              <tr>
                <td height="22"><div align="right"> (*)Precio A </div></td>
                <td height="22" colspan="3"><input name="txtprearta" type="text" id="txtprearta" value="<?php print number_format($li_prearta,2,',','.');?>" size="20" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Precio B </div></td>
                <td height="22" colspan="3"><input name="txtpreartb" type="text" id="txtpreartb" value="<?php print number_format($li_preartb,2,',','.');?>" size="20" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">
                    <p>Precio C</p>
                </div></td>
                <td height="22" colspan="3"><input name="txtpreartc" type="text" id="txtpreartc" value="<?php print number_format($li_preartc,2,',','.');?>" size="20" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Precio D </div></td>
                <td height="22" colspan="3"><input name="txtpreartd" type="text" id="txtpreartd" value="<?php print number_format($li_preartd,2,',','.');?>" size="20" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Serial</div></td>
                <td height="22" colspan="3"><div align="left">
                    <input name="txtserart" type="text" id="txtserart" value="<?php print $ls_serart; ?>" size="30" maxlength="25"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz ()#!%/[]*-+_.,:;');">
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Fabricante</div></td>
                <td height="22" colspan="3"><div align="left">
                    <input name="txtfabart" type="text" id="txtfabart" value="<?php print $ls_fabart; ?>" size="50" maxlength="100"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz ()#!%/[]*-+_.,:;');">
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Ubicaci&oacute;n</div></td>
                <td height="22" colspan="3"><div align="left">
                    <input name="txtubiart" type="text" id="txtubiart" value="<?php print $ls_ubiart; ?>" size="15" maxlength="10"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz ()#!%/[]*-+_.,:;');">
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Documento</div></td>
                <td height="22" colspan="3"><div align="left">
                    <input name="txtdocart" type="text" id="txtdocart" value="<?php print $ls_docart; ?>" size="25" maxlength="20"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz ()#!%/[]*-+_.,:;');">
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Fecha de Vencimiento </div></td>
                <td height="22" colspan="3"><input name="txtfecvenart" type="text" id="txtfecvenart"  value="<?php print $ld_fecvenart?>" size="15" maxlength="10" onKeyPress="ue_separadores(this,'/',patron,true);" datepicker="true" style="text-align:center "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">
                  <p>Peso</p>
                </div></td>
                <td height="22" colspan="3"><input name="txtpesart" type="text" id="txtpesart" value="<?php print number_format($li_pesart,2,',','.');?>" size="12"onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right ">
                  Kg.</td>
              </tr>
              <tr>
                <td height="22"><div align="right">Altura</div></td>
                <td height="22" colspan="3"><input name="txtaltart" type="text" id="txtaltart" value="<?php print number_format($li_altart,2,',','.');?>" size="12" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right ">
                  mt.</td>
              </tr>
              <tr>
                <td height="22"><div align="right">Ancho</div></td>
                <td height="22" colspan="3"><input name="txtancart" type="text" id="txtancart" value="<?php print number_format($li_ancart,2,',','.');?>" size="12" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right ">
                mt.</td>
              </tr>
              <tr>
                <td height="22"><div align="right">Profundidad</div></td>
                <td height="22" colspan="3"><input name="txtproart" type="text" id="txtproart" value="<?php print number_format($li_proart,2,',','.');?>" size="12" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right ">
                mt.</td>
              </tr>
              <tr>
                <td height="22"><div align="right"><p>Foto</p>
                </div></td>
                <td height="22" colspan="3"><input name="txtfotart" id="txtfotart" type="file"></td>
              </tr>
              <tr>
                <td height="22"><div align="right">
                    <p>Ultimo Costo</p>
                </div></td>
                <td height="22" colspan="3"><input name="txtultcosart" type="text" id="txtultcosart"  value="<?php print number_format($li_ultcosart,2,',','.');?>" size="20" readonly style="text-align:right "></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Costo Promedio</div></td>
                <td height="22" colspan="3"><input name="txtcosproart" type="text" id="txtcosproart"  value="<?php print number_format($li_cosproart,2,',','.');?>" size="20" readonly style="text-align:right "></td>
              </tr>
            </table>
            <div align="center"> </div>
        </div></td>
      </tr>
      <tr>
        <td width="316" height="39">
          <div align="center">
            <input name="operacion" type="hidden" id="operacion4">
            <input name="btnregistrar" type="button" class="boton" id="btnregistrar" value="Registrar Componentes" onClick="javascript: ue_abrircomponentes(this);" <?php print $disabled?>>
        </div></td>
        <td width="355"><div align="center">
          <input name="btncargos" type="button" class="boton" id="btncargos" value="Agregar Cr&eacute;ditos" onClick="javascript: ue_abrircargos(this);" <?php print $disabled?>>
        </div></td>
		<td width="175" align="center"><input name="btnregact" type="button" class="boton" id="btnregact" value="Registar Activo Fijo"  onClick="javascript: ue_abriractivo(this);"></td>
      </tr>
    </table>
  </form>
  <?php
  	if($lb_abrircargos)
	{
		print "<script language=JavaScript>";
		print "f=document.form1;";
		print "codart=f.txtcodart.value;";
		print "denart=f.txtdenart.value;";
		print "window.open('sigesp_siv_d_cargos.php?codart='+codart+'&denart='+denart+'','catalogo','menubar=no,toolbar=no,scrollbars=yes,width=700,height=290,left=60,top=70,location=no,resizable=no');";
		print "</script>";
	}
  ?>
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//Funciones de operaciones 
function ue_catatipart()
{
	window.open("sigesp_catdinamic_tipoarticulo.php?tipo=articulo","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_cataunimed()
{
	f=document.form1;
	ls_status=f.hidstatusc.value;
	ls_movimiento=f.hidmovimiento.value;
	if((ls_status!="C")||(ls_movimiento=="0"))
	{
	    window.open("sigesp_catdinamic_unidadmedida.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
	  alert("Ya existen movimientos de inventario");
	}
}

function ue_cataspg()
{
	window.open("sigesp_siv_cat_ctasspg.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_catascg()
{
	window.open("sigesp_siv_cat_ctasscg.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_sigecof()
{
	window.open("sigesp_siv_cat_sigecof.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_catalogo_nacunid()
{
	window.open("sigesp_siv_cat_producto.php?destino=destino","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("sigesp_siv_cat_articulo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		f.operacion.value="NUEVO";
		f.action="sigesp_siv_d_articulo.php";
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
	li_cambiar=f.cambiar.value;
	li_eximinart=f.txteximinart.value;
	li_eximaxart=f.txteximaxart.value;
	li_eximinart=li_eximinart.replace(".","");
	li_eximinart=li_eximinart.replace(",",".");
	li_eximaxart=li_eximaxart.replace(".","");
	li_eximaxart=li_eximaxart.replace(",",".");
	lb_status=f.hidstatusc.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		
		if(parseFloat(li_eximinart) <= parseFloat(li_eximaxart))
		{
			f.operacion.value="GUARDAR";
			f.action="sigesp_siv_d_articulo.php";
			f.submit();
		}
		else
		{
			alert("La existencia maxima no puede ser menor que la existencia minima");		
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_eliminar()
{
	f=document.form1;
	li_eliminar=f.eliminar.value;
	if(li_eliminar==1)
	{	
		if(confirm("¿Seguro desea eliminar el registro?"))
		{
			f=document.form1;
			f.operacion.value="ELIMINAR";
			f.action="sigesp_siv_d_articulo.php";
			f.submit();
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

function ue_abrircomponentes()
{
	f=document.form1;
	codart=ue_validarvacio(f.txtcodart.value);
	denart=ue_validarvacio(f.txtdenart.value)
	if (codart!="")
	{
		window.open("sigesp_siv_d_componentes.php?codart="+codart+"&denart="+denart+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=290,left=60,top=70,location=no,resizable=yes");
	}
	else
	{
		alert("Debe seleccionar un articulo.");	
	}
}

function ue_abrircargos()
{
	f=document.form1;
	codart=ue_validarvacio(f.txtcodart.value);
	denart=ue_validarvacio(f.txtdenart.value)
	if (codart!="")
	{
		window.open("sigesp_siv_d_cargos.php?codart="+codart+"&denart="+denart+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=290,left=60,top=70,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un articulo.");	
	}
}

function ue_imprimirbarras()
{
	f=document.form1;
	codart=ue_validarvacio(f.txtcodart.value);
	window.open("genera_barras.php?codigo="+codart+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=290,left=60,top=70,location=no,resizable=no");
}
//--------------------------------------------------------
//	Función que limpia las cajas de texto de las fechas
//--------------------------------------------------------
function ue_limpiar(fecha)
{
	f=document.form1;
	if(fecha=="creacion")
	{
		f.txtfeccreart.value="";
	}
	else
	{
		if(fecha=="vencimiento")
		{
			f.txtfecvenart.value="";
		}
	}
	
}

function catalogo_estpro1()
{
	   pagina="sigesp_siv_cat_estpro1.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
}
function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_siv_cat_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 1");
	}
}
function catalogo_estpro3()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	codestpro2=f.codestpro2.value;
	denestpro2=f.denestpro2.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
	{
    	pagina="sigesp_siv_cat_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
}

//--------------------------------------------------------
//	Función que valida una fecha
//--------------------------------------------------------
function valSep(oTxt){ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
   } 

   function finMes(oTxt){ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes){ 
     case 1: nRes = 31; break; 
     case 2: nRes = 28; break; 
     case 3: nRes = 31; break; 
     case 4: nRes = 30; break; 
     case 5: nRes = 31; break; 
     case 6: nRes = 30; break; 
     case 7: nRes = 31; break; 
     case 8: nRes = 31; break; 
     case 9: nRes = 30; break; 
     case 10: nRes = 31; break; 
     case 11: nRes = 30; break; 
     case 12: nRes = 31; break; 
    } 
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
   } 

   function valDia(oTxt){ 
    var bOk = false; 
    var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
    return bOk; 
   } 

   function valMes(oTxt){ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
   } 

   function valAno(oTxt){ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk){ 
     for (var i = 0; i < nAno.length; i++){ 
      bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
    return bOk; 
   } 

   function valFecha(oTxt){ 
    var bOk = true; 
	
		if (oTxt.value != ""){ 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk){ 
		  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/2005"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }

//--------------------------------------------------------
//	Función que coloca los separadores (/) de las fechas
//--------------------------------------------------------
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums)
{
	if(d.valant != d.value)
	{
		val = d.value
		largo = val.length
		val = val.split(sep)
		val2 = ''
		for(r=0;r<val.length;r++){
			val2 += val[r]	
		}
		if(nums){
			for(z=0;z<val2.length;z++){
				if(isNaN(val2.charAt(z))){
					letra = new RegExp(val2.charAt(z),"g")
					val2 = val2.replace(letra,"")
				}
			}
		}
		val = ''
		val3 = new Array()
		for(s=0; s<pat.length; s++){
			val3[s] = val2.substring(0,pat[s])
			val2 = val2.substr(pat[s])
		}
		for(q=0;q<val3.length; q++){
			if(q ==0){
				val = val3[q]
			}
			else{
				if(val3[q] != ""){
					val += sep + val3[q]
					}
			}
		}
	d.value = val
	d.valant = val
	}
}
//--------------------------------------------------------------------------------------
function ue_abriractivo()
{
	f=document.form1;
	codart=f.txtcodart.value; 
	denart=f.txtdenart.value; 	
	li_catalogo=f.txtcatalogo.value; 
	li_clasificacion=f.txtclasif.value;	
	
	if (li_clasificacion==1)
	{	   
		if (li_catalogo==1)
		{ 
		  sigecof=f.txtcodcatsig.value; 
		  densigecof=f.txtdencatsig.value; 
		  spg_cta=f.txtspg_cuenta.value;  
		
		   if (codart!="")
			{  
			window.open("sigesp_siv_d_registraractivo.php?codart="+codart+"&denart="+denart+"&sigecof="+sigecof+"&densigecof="+densigecof+"&spg_cta="+spg_cta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=290,left=60,top=70,location=no,resizable=yes");
			}
		   else
		  {
		  alert("Debe seleccionar un articulo.");	
		  }
		}
		if (li_catalogo!=1)
		{	
		  if (codart!="")
		  {    
		  window.open("sigesp_siv_d_registraractivo.php?codart="+codart+"&denart="+denart+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=290,left=60,top=70,location=no,resizable=yes");
		  }
		  else
		  {
		  alert("Debe seleccionar un articulo.");	
		  }
		}
	}
	else
	{
		alert("Este Artículo NO es un BIEN");
	}
}
//-------------------------------------------------------------------------------------------------------------------------
</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>