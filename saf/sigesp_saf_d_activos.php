<?php
session_start();
/*if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
*/
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codact,$ls_date,$ls_fecreg,$ls_denact,$ls_maract,$ls_modact,$ls_feccom,$ls_cosact,$ls_codrot,$ls_codcon;
		global $ls_codpai,$ls_codest,$ls_codmun,$ls_nomarch,$ls_tiparch,$ls_tamarch,$ls_radiotipo,$ls_obsact,$ls_catalogo;
		global $ls_codgru,$ls_codsubgru,$ls_codsec,$ls_numord,$ls_codpro,$ls_monord;
		
			$ls_codact="";
			$ls_date=date('d/m/y');
			$ls_fecreg="";
			$ls_denact="";
			$ls_maract="";
			$ls_modact="";
			$ls_feccom="";
			$ls_cosact="";
			$ls_codrot="";
			$ls_codcon="";
			$ls_codpai="";
			$ls_codest="";
			$ls_codmun="";
			//datos de la foto/ 
			$ls_nomarch =""; 
			$ls_tiparch ="";
			$ls_tamarch =""; 
			////////////////
			$ls_radiotipo=""; 
			$ls_obsact=""; 
			$ls_catalogo=""; 
			$ls_codgru=""; 
			$ls_codsubgru=""; 
			$ls_codsec=""; 
			$ls_numord=""; 
			$ls_codpro=""; 
			$ls_monord=""; 
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Definici&oacute;n de Activos</title>
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
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="1535" height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
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
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="13" bgcolor="#E7E7E7" class="toolbar"><table width="160" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="20"><img src="../shared/imagebank/tools20/nuevo-off.gif" width="20" height="20" title="Nuevo" /></td>
        <td width="20"><img src="../shared/imagebank/tools20/grabar.gif" width="20" height="20"  title="Guardar"/></td>
        <td width="20"><img src="../shared/imagebank/tools20/buscar.gif" width="20" height="20" title="Buscar" /></td>
        <td width="20"><img src="../shared/imagebank/tools20/ejecutar.gif" width="20" height="20" title="Ejecutar" /></td>
        <td width="20"><img src="../shared/imagebank/tools20/eliminar.gif" width="20" height="20" title="Eliminar" /></td>
        <td width="20"><img src="../shared/imagebank/tools20/imprimir.gif" width="20" height="20" title="Imprimir" /></td>
        <td width="20"><img src="../shared/imagebank/tools20/salir.gif" width="20" height="20" title="Salir" /></td>
        <td width="20"><img src="../shared/imagebank/tools20/ayuda.gif" width="20" title="Ayuda" height="20" /></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_sql.php");
	require_once("../shared/class_folder/class_mensajes.php");
	require_once("sigesp_saf_c_activo.php");
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/class_funciones_db.php");
  //////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SAF";
	$ls_ventanas="sigesp_saf_d_activos.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	$io_msg= new class_mensajes();
	$io_saf= new sigesp_saf_c_activo();
	$io_fun= new class_funciones();
	$io_fundb= new class_funciones_db($con);
	$arr=array_keys($_SESSION);	
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	$li_count=count($arr);
	$li_pos=0;
	$ls_usuario="";
	$ls_evento="";
	$ls_datedesde="";
	$ls_datehasta="";

	if (array_key_exists("operacionact",$_POST))
	{
		$ls_operacion=$_POST["operacionact"];	
	}
	else
	{
		$ls_operacion="";
		uf_limpiarvariables();
	}

	if($ls_operacion=="GUARDAR")
	{
		$ls_codact=trim($_POST["txtcodact"]);
		$ls_fecreg=trim($_POST["txtfecreg"]);
		$ls_denact=trim($_POST["txtdenact"]);
		$ls_maract=trim($_POST["txtmaract"]);
		$ls_modact=trim($_POST["txtmodact"]);
		$ls_feccom=trim($_POST["txtfeccom"]);
		$ls_cosact=trim($_POST["txtcosact"]);
		$ls_cosact=str_replace(".","",$ls_cosact);
		$ls_cosact=str_replace(",",".",$ls_cosact);
		$ls_codrot=$_POST["txtcodrot"];
		$ls_codcon=$_POST["txtcodcon"];
		$ls_codpai=$_POST["txtcodpai"];
		$ls_codest=$_POST["txtcodest"];
		$ls_codmun=$_POST["txtcodmun"];
		/////  datos del arhivo  ///////////////////////// 
		$ls_nomarch = $HTTP_POST_FILES['txtfoto']['name']; 
		$ls_tiparch = $HTTP_POST_FILES['txtfoto']['type']; 
		$ls_tamarch = $HTTP_POST_FILES['txtfoto']['size']; 
		//////////////////////////////////////////////////
		if(array_key_exists("hidradio",$_POST))
		{
			$ls_radio=$_POST["hidradio"];
			switch ($ls_radio) 
			{
				case 0:
					$ls_radiotipo="1";
					break;
				case 1:
					$ls_radiotipo="2";
					break;
				case 2:
					$ls_radiotipo="3";
					break;
			}
		}
		$ls_obsact=trim($_POST["txtobsact"]);
		$ls_catalogo=$_POST["txtcatalogo"];
		$ls_spgcuenta=$_POST["txtcuenta"];
		$ls_codgru=$_POST["txtcodgru"];
		$ls_codsubgru=$_POST["txtcodsubgru"];
		$ls_codsec=$_POST["txtcodsec"];
		$ls_numord=trim($_POST["txtnumord"]);
		$ls_codpro=trim($_POST["txtcodpro"]);
		$ls_denpro=trim($_POST["txtdenpro"]);
		$ls_monord=trim($_POST["txtmonord"]);
		$ls_monord=str_replace(".","",$ls_monord);
		$ls_monord=str_replace(",",".",$ls_monord);
		// FIN DE OBTENER DATOS
		$ls_fecreg1=$io_fun->uf_convertirdatetobd($ls_fecreg);
		$ls_feccom1=$io_fun->uf_convertirdatetobd($ls_feccom);

		
		if(($ls_codact=="")||($ls_fecreg1=="")||($ls_denact=="")||($ls_feccom1=="")||($ls_cosact=="")||($ls_codact=="")||($ls_codrot=="")||($ls_codcon=="")||($ls_codpai=="")||($ls_codest=="")||($ls_codmun=="")||($ls_codact=="")||($ls_catalogo=="")||($ls_codgru=="")||($ls_codsubgru=="")||($ls_codsec==""))
		{
			$io_msg->message("Faltan campos por llenar");
		}
		else
		{	
			$lb_encontrado=$io_saf->uf_saf_select_activo($ls_codemp,$ls_codact);
			if($lb_encontrado)
			{	
				$lb_valido=$io_saf->uf_saf_update_activo($ls_codemp,$ls_fecreg1,$ls_codact,$ls_denact,$ls_maract,$ls_modact,$ls_feccom1,$ls_cosact,$ls_codrot,
								$ls_codcon,$ls_codpai,$ls_codest,$ls_codmun,$ls_radiotipo,$ls_obsact,$ls_catalogo,$ls_codgru,$ls_codsubgru,
								$ls_codsec,$ls_numord,$ls_codpro,$ls_denpro,$ls_monord,$ls_nomarch,$ls_spgcuenta,$la_seguridad);
				if ($lb_valido)
				{
					$io_msg->message("El activo fue actualizado.");
					$ls_status="";
					uf_limpiarvariables();
				}
				else
				{
				$io_msg->message("No se pudo incluir el registro");
				}
			}
			else
			{
				$lb_valido=$io_saf->uf_saf_insert_activo($ls_codemp,$ls_fecreg1,$ls_codact,$ls_denact,$ls_maract,$ls_modact,$ls_feccom1,$ls_cosact,$ls_codrot,
								$ls_codcon,$ls_codpai,$ls_codest,$ls_codmun,$ls_radiotipo,$ls_obsact,$ls_catalogo,$ls_codgru,$ls_codsubgru,
								$ls_codsec,$ls_numord,$ls_codpro,$ls_denpro,$ls_monord,$ls_nomarch,$ls_spgcuenta,$la_seguridad);

				if ($lb_valido)
				{
					$io_msg->message("El activo fue grabado.");
					$ls_status="";
					uf_limpiarvariables();				
				}
				else
				{
				$io_msg->message("No se pudo incluir el registro");
				}
			
			}
		}
	}
	if ($ls_operacion=="ELIMINAR")
		{
			$ls_codact=$_POST["txtcodact"];
			$io_msg=new class_mensajes();
			
			$lb_valido=$io_saf->uf_saf_delete_activo($ls_codemp,$ls_codact,$la_seguridad);
	
			if($lb_valido)
			{
				$io_msg->message("El registro fue eliminado");
				uf_limpiarvariables();		
			}	
			else
			{
				$io_msg->message("No se pudo eliminar el registro");
			
			}
			
		}
		if ($ls_operacion=="NUEVO")
		{
			uf_limpiarvariables();
			$ls_emp="";
			$ls_codemp="";
			$ls_tabla="saf_activo";
			$ls_columna="codact";
		
			$ls_codact=$io_fundb->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
		}


?>
<p>&nbsp;</p>
<div align="center">
  <table width="596" height="720" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="588" height="690" colspan="2"><div align="left">
          <form action="" method="post" enctype="multipart/form-data" name="form1">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	
}
else
{
	
/*	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
*/}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>	
<table width="571" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td class="titulo-ventana">Definici&oacute;n de Activos </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="titulo-celdanew">Datos</td>
              </tr>
              <tr>
                <td><table width="573" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="200"><input name="hidstatusact" type="hidden" id="hidstatusact"></td>
                    <td width="373">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">C&oacute;digo</div></td>
                    <td><input name="txtcodact" type="text" id="txtcodact" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $ls_codact?>" size="19" maxlength="15"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Fecha de Registro </div></td>
                    <td><input name="txtfecreg" type="text" id="txtfecreg" onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ls_fecreg?>" size="17" maxlength="10" datepicker="true"></td>
                  </tr>
                  <tr>
                    <td><div align="right">Denominaci&oacute;n</div></td>
                    <td rowspan="2"><textarea name="txtdenact" cols="50" rows="2" id="txtdenact" onKeyUp="ue_validarcomillas(this);"><?php print $ls_denact?></textarea></td>
                  </tr>
                  <tr>
                    <td><div align="right"></div></td>
                    </tr>
                  <tr>
                    <td height="22"><div align="right">Marca</div></td>
                    <td><input name="txtmaract" type="text" id="txtmaract" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_maract?>" size="15" maxlength="90"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Modelo</div></td>
                    <td><input name="txtmodact" type="text" id="txtmodact" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_modact?>" size="15" maxlength="90"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Fecha de Compra </div></td>
                    <td><input name="txtfeccom" type="text" id="txtfeccom" onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ls_feccom?>" size="17" maxlength="10" datepicker="true"></td>
                  </tr>
                  <tr>
                    <td height="23"><div align="right">Costo</div></td>
                    <td><input name="txtcosact" type="text" id="txtcosact" value="<?php print $ls_cosact?>" size="15" onKeyPress="return(ue_formatonumero(this,'.',',',event));"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Rotulaci&oacute;n</div></td>
                    <td><input name="txtcodrot" type="text" id="txtcodrot" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codrot?>" size="5" maxlength="1" readonly>
                      <a href="javascript: ue_buscarrotulacion();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                      <input name="txtdenrot" type="text" class="sin-borde" id="txtdenrot" size="40" readOnly="true">
                      <input name="txtempleo" type="hidden" id="txtempleo"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Condici&oacute;n</div></td>
                    <td><input name="txtcodcon" type="text" id="txtcodcon" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codcon?>" size="5" maxlength="1" readonly>
                      <a href="javascript: ue_buscarcondicion();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                      <input name="txtdencon" type="text" class="sin-borde" id="txtdencon" size="40" readOnly="true">
                      <input name="txtdescripcion" type="hidden" id="txtdescripcion"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Pa&iacute;s</div></td>
                    <td><input name="txtcodpai" type="text" id="txtcodpai" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codpai?>" size="5" maxlength="3" readonly>                      
                    <a href="javascript: ue_buscarpais();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                      <input name="txtdespai" type="text" class="sin-borde" id="txtdespai" readOnly="true"></td>
                  </tr>
                  <tr>
                    <td height="28"><div align="right">Estado</div></td>
                    <td><input name="txtcodest" type="text" id="txtcodest" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codest?>" size="5" maxlength="3" readonly>
                      <a href="javascript: ue_buscarestado();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                      <input name="txtdesest" type="text" class="sin-borde" id="txtdesest" readOnly="true"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Municipio</div></td>
                    <td><input name="txtcodmun" type="text" id="txtcodmun" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codmun?>" size="5" maxlength="3" readonly>
                      <a href="javascript: ue_buscarmunicipio();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                      <input name="txtdesmun" type="text" class="sin-borde" id="txtdesmun" readOnly="true"></td>
                  </tr>
                  <tr>
                    <td height="18"><div align="right">Tipo de Inmueble </div></td>
                    <td><input name="radiotipo" type="radio" class="sin-borde" value="1" onClick="actualizaValor(this)">
                    Mueble
  <input name="radiotipo" type="radio" class="sin-borde" value="2" onClick="actualizaValor(this)">
  Inmueble
<input name="radiotipo" type="radio" class="sin-borde" value="3" onClick="actualizaValor(this)">
Semoviente
<input name="hidradio" type="hidden" id="hidradio"></td>
                  </tr>
                  <tr>
                    <td height="19"><div align="right">Observaciones</div></td>
                    <td rowspan="2"><textarea name="txtobsact" cols="50" rows="2" id="txtobsact" onKeyUp="ue_validarcomillas(this);"><?php print $ls_obsact?></textarea></td>
                  </tr>
                  <tr>
                    <td><div align="right"></div></td>
                    </tr>
                  <tr>
                    <td><div align="right">Tipo de Cat&aacute;logo </div></td>
                    <td><input name="radiocata" type="radio" class="sin-borde" value="radiobutton" onClick="obtenerValor(this)">
                      Cat&aacute;logo SIGECOF
                        <input name="radiocata" type="radio" class="sin-borde" value="radiobutton" onClick="obtenerValor(this)">
                      Categor&iacute;a
                      <input name="hidradiocata" type="hidden" id="hidradiocata"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Cat&aacute;logo SIGECOF
                      </div></td>
                    <td><input name="txtcatalogo" type="text" id="txtcatalogo" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_catalogo?>" size="12" readonly>
                      <a href="javascript: ue_catalogo();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                      <input name="txtdenominacion" type="hidden" class="sin-borde" id="txtdenominacion" size="35" readOnly="true">
                      <input name="hidstatus" type="hidden" id="hidstatus"> <input name="txtcuenta" type="text" class="sin-borde" id="txtcuenta"></td>
                  </tr>
                  <tr>
                    <td height="28"><div align="right">Grupo</div></td>
                    <td><input name="txtcodgru" type="text" id="txtcodgru" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codgru?>" size="5" readonly>
                      <a href="javascript: ue_grupo();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                      <input name="txtdengru" type="text" class="sin-borde" id="txtdengru" size="50" readOnly="true"> 
                      <input name="operacion" type="hidden" id="operacion3">
                      <input name="buttonir" type="hidden" id="buttonir"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Sub Grupo </div></td>
                    <td><input name="txtcodsubgru" type="text" id="txtcodsubgru" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codsubgru?>" size="5" readonly>
                      <a href="javascript: ue_subgrupo();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                      <input name="txtdensubgru" type="text" class="sin-borde" id="txtdensubgru" size="50" readOnly="true">
                      <input name="operacion2" type="hidden" id="operacion22"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Secci&oacute;n</div></td>
                    <td><input name="txtcodsec" type="text" id="txtcodsec" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codsec?>" size="5" readonly>
                      <a href="javascript: ue_seccion();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                      <input name="txtdensec" type="text" class="sin-borde" id="txtdensec2" size="50" readOnly="true">                      
                      <input name="operacion3" type="hidden" id="operacion3"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Foto</div></td>
                    <td><input name="txtfoto" type="file">
                      <input name="buttonfoto" type="button" class="boton" id="buttonfoto" value="Ver Foto"></td>
                  </tr>
                  <tr>
                    <td><div align="right"></div></td>
                    <td>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td class="titulo-celdanew">Orden de Compra</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td height="75"><table width="571" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="199" height="18"><div align="right">Numero</div></td>
                    <td width="372"><input name="txtnumord" type="text" id="txtnumord" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $ls_numord?>" size="18" maxlength="15">
                      <a href="javascript: ue_buscarorden();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Fecha</div></td>
                    <td><input name="txtfecordcom" type="text" id="txtfecordcom" size="18" style="text-align:center "></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Proveedor</div></td>
                    <td><input name="txtcodpro" type="text" id="txtcodpro" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codpro?>" size="18" style="text-align:center " readonly>
                      <input name="txtdenpro" type="text" class="sin-borde" id="txtdenpro" size="35" readOnly="true"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Monto Orden </div></td>
                    <td><input name="txtmonord" type="text" id="txtmonord" value="<?php print $ls_monord?>" size="18" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><div align="center">
                  <input name="operacionact" type="hidden" id="operacionact">
                </div></td>
              </tr>
            </table>
          </form>
</div></td>
    </tr>
    <tr>
      <td height="15">      <div align="center">
        <input name="btndepreciacion" type="button" class="boton" id="btndepreciacion" value="Registrar Depreciaci&oacute;n" onClick="ue_abrirdepreciacion();">      
      </div></td>
      <td><div align="center">
        <input name="btnseriales" type="button" class="boton" id="btnseriales" value="Registrar Seriales y Partes" onClick="ue_abrirseriales();">
      </div></td>
    </tr>
    <tr>
      <td height="15" colspan="2">&nbsp;</td>
    </tr>
  </table> 
</div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
//--------------------------------------------
//  funciones de apertura de catalogos
//--------------------------------------------
function ue_abrirdepreciacion()
{
	f=document.form1;
	codact=ue_validarvacio(f.txtcodact.value);
	status=ue_validarvacio(f.hidstatusact.value);
	if (status=="C")
	{
		window.open("sigesp_saf_d_depreciacion.php?codact="+codact+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=625,height=275,left=60,top=70,location=no,resizable=no");
	}
	else
	{
		alert("El activo debe estar grabado");	
	}
}
function ue_abrirseriales()
{
	f=document.form1;
	codact=ue_validarvacio(f.txtcodact.value);
	denact=ue_validarvacio(f.txtdenact.value)
	status=ue_validarvacio(f.hidstatusact.value);
	if (status=="C")
	{
		window.open("sigesp_saf_d_seriales.php?codact="+codact+"&denact="+denact+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=680,height=350,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("El activo debe estar grabado");	
	}
}
function ue_buscarrotulacion()
{
	window.open("sigesp_saf_cat_rotulacion.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}
function ue_buscarcondicion()
{
	window.open("sigesp_saf_cat_condicion.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}
function ue_buscarpais()
{
	f=document.form1;
	window.open("sigesp_cat_pais.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	f.txtcodest.value="";
	f.txtdesest.value="";
	f.txtcodmun.value="";
	f.txtdesmun.value="";
}

function ue_buscarestado()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	if(codpai!="")
	{
		window.open("sigesp_cat_estado.php?codpai="+codpai+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais.");
	}
	f.txtcodmun.value="";
	f.txtdesmun.value="";

}

function ue_buscarmunicipio()
{
	f=document.form1;
	codpai=ue_validarvacio(f.txtcodpai.value);
	codest=ue_validarvacio(f.txtcodest.value);
	if((codpai!="")||(codest!=""))
	{
		window.open("sigesp_cat_municipio.php?codpai="+codpai+"&codest="+codest+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais y un estado.");
	}
}

function ue_catalogo()
{
	f=document.form1;
	if(f.hidradiocata.value==0)
	{
		window.open("sigesp_catdinamic_catalogos.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No se ha seleccionado la busqueda de catalogo SIGECOF");
	}
}

function ue_grupo()
{
	f=document.form1;
	if(f.hidradiocata.value==1)
	{
		window.open("sigesp_saf_cat_grupo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		f.txtcodsubgru.value="";
		f.txtdensubgru.value="";
		f.txtcodsec.value="";
		f.txtdensec.value="";
	}
	else
	{
		alert("No se ha seleccionado la busqueda de catalogo por categoría");	
	}
}
function ue_subgrupo()
{
	f=document.form1;
//	codigo=f.txtcodgru.value;
	codgru=ue_validarvacio(f.txtcodgru.value);
	if(codgru!="---")
	{
		window.open("sigesp_saf_cat_subgrupo.php?codigo="+codgru+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		f.txtcodsec.value="";
		f.txtdensec.value="";
	}
	else
	{
		alert("Debe seleccionar un grupo.");
	}
}

function ue_seccion()
{
	f=document.form1;
	codgru=ue_validarvacio(f.txtcodgru.value);
	codsubgru=ue_validarvacio(f.txtcodsubgru.value);
	if((codgru!="---")||(codsubgru!="---"))
	{
		window.open("sigesp_saf_cat_seccion.php?codigo="+codgru+"&codsubgru="+codsubgru+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("Debe seleccionar un grupo y un subgrupo.");
	}
}

function ue_buscarorden()
{
	window.open("sigesp_saf_cat_ordencompra.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}
//--------------------------------------------------------
//  Funciones de las operaciones de la páginas
//--------------------------------------------------------
function ue_nuevo()
{
	f=document.form1;
	f.operacionact.value="NUEVO";
	f.action="sigesp_saf_d_activos.php";
	f.submit();
}
function ue_guardar()
{
	f=document.form1;
	f.operacionact.value="GUARDAR";
	f.action="sigesp_saf_d_activos.php";
	f.submit();
}

function ue_buscar()
{
	window.open("sigesp_saf_cat_activo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=570,height=400,left=50,top=50,location=no,resizable=yes");
}

function ue_eliminar()
{
	if(confirm("¿Seguro desea eliminar el Usuario?"))
	{
		f=document.form1;
		f.operacionact.value="ELIMINAR";
		f.action="sigesp_saf_d_activos.php";
		f.submit();
	}
}

function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}

//--------------------------------------------------------
//	Función que valida que no se incluyan comillas simples 
//	en los textos ya que dañana la consulta SQL
//--------------------------------------------------------
function ue_validarcomillas(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if(texto != "'")
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

////////////////////////    Validar la Fecha     ///////////////////////////
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

////////////////////////    Validar la Fecha     ///////////////////////////
var patron = new Array(2,2,4)
var patron2 = new Array(1,3,3,3,3)
function ue_separadores(d,sep,pat,nums){
if(d.valant != d.value){
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
//--------------------------------------------------------
//	Función que valida que solo se incluyan números en los textos
//--------------------------------------------------------
function ue_validarnumero(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}
//--------------------------------------------------------
//	Función que valida que el texto no esté vacio
//--------------------------------------------------------
function ue_validarvacio(valor)
{
	var texto;
	while(''+valor.charAt(0)==' ')
	{
		valor=valor.substring(1,valor.length)
	}
	texto = valor;
	return texto;
}
//--------------------------------------------------------
//	Función que obtiene el valor de el radio button
//--------------------------------------------------------
   function actualizaValor(oRad)
   { 
    var i 
	f=document.form1;
    for (i=0;i<f.radiotipo.length;i++)
	{ 
       if (f.radiotipo[i].checked) 
          break; 
    } 
    valor= i;
	f.hidradio.value=i;
   } 
//---------------------------------------------------------------
//	Función que obtiene el valor de el radio button de catalogos 
//---------------------------------------------------------------
   function obtenerValor(oRad)
   { 
    var i 
	f=document.form1;
    for (i=0;i<f.radiocata.length;i++)
	{ 
       if (f.radiocata[i].checked) 
          break; 
    } 
    valor= i;
	if (valor==0)
	{
		f.txtcodgru.value="---";
		f.txtcodsubgru.value="---";
		f.txtcodsec.value="---";
		f.txtcatalogo.value="";
	}
	else if (valor==1)
	{
		f.txtcatalogo.value="---------------";
		f.txtcodgru.value="";
		f.txtcodsubgru.value="";
		f.txtcodsec.value="";
	}
	f.hidradiocata.value=i;
   } 
//--------------------------------------------------------
//	Función que limpia las cajas de texto de las fechas
//--------------------------------------------------------
function ue_limpiar(periodo)
{
	f=document.form1;
	if(periodo=="registro")
	{
		f.txtfecreg.value="";
	}
	else
	{
		if(periodo=="compra")
		{
			f.txtfeccom.value="";
		}
	}
	
}

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>