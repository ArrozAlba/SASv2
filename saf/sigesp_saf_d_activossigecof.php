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
	require_once("../shared/class_folder/class_mensajes.php");
	$io_mensaje= new class_mensajes();
	$io_fun_activo=new class_funciones_activos();
	$io_fun_activo->uf_load_seguridad("SAF","sigesp_saf_d_activos.php",$ls_permisos,$la_seguridad,$la_permisos);
	require_once("sigesp_saf_c_activo.php");
    $ls_codemp = $_SESSION["la_empresa"]["codemp"];
    $io_saf_tipcat= new sigesp_saf_c_activo();
    $ls_rbtipocat=$io_saf_tipcat->uf_select_valor_config($ls_codemp);
	if ($ls_rbtipocat == 0)
	{
	 $io_mensaje->message("No se puede registrar ningun Activo, sin definir la configuración!!!");
	 print "<script language=JavaScript>";
	 print "location.href='sigespwindow_blank.php'";
	 print "</script>";
	}
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		/////////////////////////////////////////////////////////////////////////////////
   		global $ls_codact,$ld_fecregact,$ls_denact,$ls_maract,$ls_modact,$ld_feccmpact,$li_cosact,$ls_codconbie,$ls_codpai,$ls_codest;
		global $ls_codmun,$ls_nomarch,$ls_tiparch,$ls_tamarch,$ls_radiotipo,$ls_obsact,$ls_catalogo,$ls_numordcom,$ls_codpro;
		global $ls_monord,$ls_foto,$ls_statusact,$ls_denconbie,$ls_despai,$ls_desest,$ls_desmun,$ls_dencatsig,$ls_spgcuenta;
		global $ls_codfuefin,$ls_denfuefin,$ls_codsitcon,$ls_codconcom,$ls_densitcon,$ls_denconcom,$ls_codpro,$ls_denpro,$ld_fecordcom,$li_monord;
		global $ls_numsolpag,$ld_fecemisol,$ls_chkchecked,$ls_chkmueble,$ls_chkinmueble,$ls_chksemoviente,$ls_rbcsc,$ls_rbcgr;
		global $ls_codgru,$ls_codsubgru,$ls_codsec,$ls_codite,$ls_rbtipocat, $ls_clasif, $ls_titulo, $ls_estbt,$ls_mostrar_inm;
		
		$ls_codfuefin="";
		$ls_denfuefin="";
		$ls_codsitcon="";
		$ls_densitcon="";
		$ls_codconcom="";
		$ls_denconcom="";
		$ls_numordcom="";
		$ls_codpro="";
		$ls_denpro="";
		$ld_fecordcom="";
		$li_monord="";
		$ls_numsolpag="";
		$ld_fecemisol="";
		$ls_foto="blanco.jpg";
		$ls_codact="";
		$ld_fecregact=date('d/m/Y');
		$ls_denact="";
		$ls_maract="";
		$ls_modact="";
		$ld_feccmpact="";
		$li_cosact="0,00";
		$ls_codconbie="";
		$ls_denconbie="";
		$ls_codpai="";
		$ls_despai="";
		$ls_codest="";
		$ls_desest="";
		$ls_codmun="";
		$ls_desmun="";
		$ls_nomarch =""; 
		$ls_tiparch ="";
		$ls_tamarch =""; 
		$ls_radiotipo=""; 
		$ls_obsact=""; 
		$ls_catalogo=""; 
		$ls_dencatsig="";
		$ls_spgcuenta="";
		$ls_codpro=""; 
		$li_monord="0,00"; 
		$ls_statusact=""; 
		$ls_chkchecked="checked";
		$ls_chkmueble="";
		$ls_chkinmueble="";
		$ls_chksemoviente="";
		$ls_rbcsc="";
		$ls_rbcgr="";
		$ls_codgru="";
		$ls_codsubgru="";
		$ls_codsec="";
		$ls_codite="";
		$ls_rbtipocat="";
		$ls_mostrar_csc = "";
		$ls_mostrar_cgr = "";
		$ls_clasif="";
		$ls_mostrar_inm = 'style="display:none"';
		$ls_titulo="";
		$ls_estbt= 'style="display:none"';	
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
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
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
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
  </tr>
  <tr>
    <td height="13" colspan="11" bgcolor="#E7E7E7" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="22" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" title="Nuevo" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar"  width="20" title="Guardar" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20"  height="20" title="Buscar" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" title="Eliminar" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" title="Salir" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="22"><div align="center"></div></td>
    <td class="toolbar" width="640">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/sigesp_include.php");
	$in=     new sigesp_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_mensajes.php");
	$io_msg= new class_mensajes();
	require_once("sigesp_saf_c_activo.php");
	$io_saf= new sigesp_saf_c_activo();
	require_once("../shared/class_folder/class_funciones.php");
	$io_fun= new class_funciones();
	require_once("../shared/class_folder/class_funciones_db.php");
	$io_fundb= new class_funciones_db($con);
	require_once("class_funciones_activos.php");
	$io_fac= new class_funciones_activos("../");
	$arre=$_SESSION["la_empresa"];
	$ls_codemp=$arre["codemp"];
	

	if (array_key_exists("operacionact",$_POST))
	{
		$ls_operacion=$_POST["operacionact"];	
	}
	else
	{
		$ls_operacion="";
		uf_limpiarvariables();
		$ls_mostrar_inm = 'style="display:none"';
		$ls_edit="";
		$depreciacion="";
		$ls_rbtipocat=$io_saf->uf_select_valor_config($ls_codemp);
		$ls_mostrar_csc = "";
		$ls_mostrar_cgr =  "";
		switch ($ls_rbtipocat) 
		{
			case '0':
		        uf_limpiarvariables();
			break;
			
			case '1':
				 $ls_rbcsc="checked";
				 $ls_disabled="disabled";
				 $ls_mostrar_csc = "";
				 $ls_mostrar_cgr = 'style="display:none"';
			break;
			
			case '2':
				$ls_rbcgr="checked";
				$ls_disabled="disabled";
				$ls_mostrar_csc = 'style="display:none"';
				$ls_mostrar_cgr = "";
			break;
		}
	}

	switch ($ls_operacion) 
	{
		case "NUEVO":
			uf_limpiarvariables();				
			$ls_emp=true;
			$ls_edit = "";
			$ls_tabla="saf_activo";
			$ls_columna="codact";
			$depreciacion="";
			$ls_codact=$io_fundb->uf_generar_codigo($ls_emp,$ls_codemp,$ls_tabla,$ls_columna);
			$ls_rbtipocat=$io_saf->uf_select_valor_config($ls_codemp);
			switch ($ls_rbtipocat) 
			{
				case '0':
					uf_limpiarvariables();
				break;
				
				case '1':
					 $ls_rbcsc="checked";
					 $ls_disabled="disabled";
					 $ls_mostrar_csc = "";
				     $ls_mostrar_cgr = 'style="display:none"';
				break;
				
				case '2':
					$ls_rbcgr="checked";
					$ls_disabled="disabled";
					$ls_mostrar_csc = 'style="display:none"';
				    $ls_mostrar_cgr = "";
				break;
			}
		break;
		
		case "GUARDAR":
			uf_limpiarvariables();
			$ls_codact=$io_fac->uf_obtenervalor("txtcodact","");
			$ls_denact=$io_fac->uf_obtenervalor("txtdenact","");
			$ld_fecregact=$io_fac->uf_obtenervalor("txtfecregact","");
			$ls_maract=$io_fac->uf_obtenervalor("txtmaract","");
			$ls_modact=$io_fac->uf_obtenervalor("txtmodact","");
			$ld_feccmpact=$io_fac->uf_obtenervalor("txtfeccmpact","");
			$li_cosact=$io_fac->uf_obtenervalor("txtcosact","");
			$ls_codconbie=$io_fac->uf_obtenervalor("txtcodconbie","");
			if($ls_codconbie==""){$ls_codconbie="02";}
			$ls_denconbie=$io_fac->uf_obtenervalor("txtdenconbie","");
			if($ls_denconbie==""){$ls_denconbie="Bueno";}
			$ls_codpai=$io_fac->uf_obtenervalor("txtcodpai","");
			$ls_despai=$io_fac->uf_obtenervalor("txtdespai","");
			$ls_codest=$io_fac->uf_obtenervalor("txtcodest","");
			$ls_desest=$io_fac->uf_obtenervalor("txtdesest","");
			$ls_codmun=$io_fac->uf_obtenervalor("txtcodmun","");
			$ls_desmun=$io_fac->uf_obtenervalor("txtdesmun","");
			$ls_obsact=$io_fac->uf_obtenervalor("txtobsact","");
			$ls_catalogo=$io_fac->uf_obtenervalor("txtcatalogo","");
			$ls_dencatsig=$io_fac->uf_obtenervalor("txtdencat","");
			$ls_spgcuenta=$io_fac->uf_obtenervalor("txtcuenta","");
			$ls_codfuefin=$io_fac->uf_obtenervalor("txtcodfuefin","");
			$ls_denfuefin=$io_fac->uf_obtenervalor("txtdenfuefin","");
			$ls_codsitcon=$io_fac->uf_obtenervalor("txtcodsitcon","");
			$ls_densitcon=$io_fac->uf_obtenervalor("txtdensitcon","");
			$ls_codconcom=$io_fac->uf_obtenervalor("txtcodconcom","");
			$ls_denconcom=$io_fac->uf_obtenervalor("txtdenconcom","");
			$ls_numordcom=$io_fac->uf_obtenervalor("txtnumord","");
			$ls_codpro=$io_fac->uf_obtenervalor("txtcodpro","");
			$ls_denpro=$io_fac->uf_obtenervalor("txtdenpro","");
			$ld_fecordcom=$io_fac->uf_obtenervalor("txtfecordcom","");
			$li_monord=$io_fac->uf_obtenervalor("txtmonord","");
			$ls_numsolpag=$io_fac->uf_obtenervalor("txtnumsolpag","");
			$ld_fecemisol=$io_fac->uf_obtenervalor("txtfecemisol","");
			$ls_statusact=$io_fac->uf_obtenervalor("hidstatusact","");
			$ls_estdepact=$io_fac->uf_obtenervalor("chkdepreciable","0");
			$ls_clasif=$io_fac->uf_obtenervalor("cmbclasi","0");
			$ls_radiobt=$io_fac->uf_obtenervalor("radiotipo","0");
			switch ($ls_radiobt) 
			{
				case 1:
					$ls_radiotipo="1";
					$ls_chkmueble="checked";
					$ls_mostrar_inm = 'style="display:none"';
					$depreciacion="";
					$ls_estbt= 'style="display:none"';	
				break;
				case 2:
					$ls_radiotipo="2";
					$ls_chkinmueble="checked";
					$ls_mostrar_inm = 'style="display:compact"';
					if ($ls_clasif==3)
					{
						$depreciacion="disabled";														
					}
					if ($ls_clasif==2)
					{
						$ls_titulo="Instalaciones Fijas";
					}
					elseif ($ls_clasif==1)
					{
						$ls_titulo="Edificios";
					}elseif ($ls_clasif==3)
					{
						$ls_titulo="Terrenos";
					}
					$ls_estbt= 'style="display:compact"';
				break;
				case 3:
					$ls_radiotipo="3";
					$ls_chksemoviente="checked";
					$ls_mostrar_inm = 'style="display:none"';
					$depreciacion="";	
					$ls_estbt= 'style="display:none"';
				break;
			} 
			$ls_tipspg = 0;
			if($ls_estdepact==1)
			{
			  $ls_chkchecked="checked";
			  $ls_edit="";
			}
			else
			{ 
				$ls_chkchecked="";
				$ls_edit="disabled";
			}
			
			
			$ls_nomfot=$HTTP_POST_FILES['txtfotact']['name']; 
			if ($ls_nomfot!="")
			{
				$ls_nomfot=$ls_codact.substr($ls_nomfot,strrpos($ls_nomfot,"."));
			}
			$ls_tipfot=$HTTP_POST_FILES['txtfotact']['type']; 
			$ls_tamfot=$HTTP_POST_FILES['txtfotact']['size']; 
			$ls_nomtemfot=$HTTP_POST_FILES['txtfotact']['tmp_name'];
			/*if(array_key_exists("hidradio",$_POST))
			{
				$ls_radio=$_POST["hidradio"];
				switch ($ls_radio) 
				{
					case 0:
						$ls_radiotipo="1";
						$ls_chkmueble="checked";
						break;
					case 1:
						$ls_radiotipo="2";
						$ls_chkinmueble="checked";										
						break;
					case 2:
						$ls_radiotipo="3";
						$ls_chksemoviente="checked";
						break;
				}
			}*/
			$ls_rbtipocat=$io_fac->uf_obtenervalor("hidrbtipocat","");
			switch ($ls_rbtipocat) 
			{
				case '1':
					 $ls_rbtipocat="1";
					 $ls_rbcsc="checked";
					 $ls_mostrar_csc = "";
				     $ls_mostrar_cgr = 'style="display:none"';
				break;
				
				case '2':
					$ls_rbtipocat="2";
					$ls_rbcgr="checked";
					$ls_mostrar_csc = 'style="display:none"';
				    $ls_mostrar_cgr = "";
				break;
			}
			$ls_codgru=$io_fac->uf_obtenervalor("txtcodgru","");
			$ls_codsubgru=$io_fac->uf_obtenervalor("txtcodsubgru","");
			$ls_codsec=$io_fac->uf_obtenervalor("txtcodsec","");
			$ls_codite=$io_fac->uf_obtenervalor("txtcodite","");
			$li_cosactaux=str_replace(".","",$li_cosact);
			$li_cosactaux=str_replace(",",".",$li_cosactaux);
			$li_monordaux=str_replace(".","",$li_monord);
			$li_monordaux=str_replace(",",".",$li_monordaux); 
			$ld_fecregactaux=$io_fun->uf_convertirdatetobd($ld_fecregact);
			$ld_feccmpactaux=$io_fun->uf_convertirdatetobd($ld_feccmpact);
			$ld_fecordcomaux=$io_fun->uf_convertirdatetobd($ld_fecordcom);
			$ld_fecemisolaux=$io_fun->uf_convertirdatetobd($ld_fecemisol);
			if($ls_rbtipocat=="1")
			{
				if(($ls_codact=="")||($ld_fecregact=="")||($ls_denact=="")||($ld_feccmpact=="")||($li_cosact=="")||
				   ($ls_catalogo=="")||($ls_spgcuenta=="")||($ls_radiotipo==""))
				{
					$io_msg->message("Faltan campos por llenar");
				}
				else
				{
					$lb_valido=$io_saf->uf_saf_select_cuentaspg($ls_codemp,$ls_spgcuenta,$ls_tipspg);
					if($lb_valido)
					{
						if($ls_statusact=="")
						{
							$lb_encontrado=$io_saf->uf_saf_select_activo($ls_codemp,$ls_codact);
							if(!$lb_encontrado)
							{
								$lb_valido=$io_saf->uf_saf_insert_activo($ls_codemp,$ld_fecregactaux,$ls_codact,$ls_denact,$ls_maract,$ls_modact,
																		 $ld_feccmpactaux,$li_cosactaux,$ls_codconbie,$ls_codpai,$ls_codest,$ls_codmun,
																		 $ls_radiotipo,$ls_obsact,$ls_catalogo,$ls_numordcom,$ls_codpro,$ls_denpro,
																		 $li_monordaux,$ls_nomfot,$ls_spgcuenta,$ls_codfuefin,$ls_codsitcon,$ls_codconcom,
																		 $ld_fecordcomaux,$ls_numsolpag,$ld_fecemisolaux,$ls_estdepact,$la_seguridad,
																		 $ls_codgru,$ls_codsubgru,$ls_codsec,$ls_codite,
																		 $ls_clasif);
								if($lb_valido)
								{
									$io_msg->message("El activo ha sido registrado");
									$lb_valido=$io_saf->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
									$ls_statusact="C";
									break;
								}
								else
								{
									$io_msg->message("No se pudo registrar el activo");
								}
							}
							else
							{
								$io_msg->message("El codigo del activo ya esta registrado");
							}
							
						}
						if($ls_statusact=="C")
						{
							$lb_encontrado=$io_saf->uf_saf_select_activo($ls_codemp,$ls_codact);
							if($lb_encontrado)
							{
								$lb_valido=$io_saf->uf_saf_update_activo($ls_codemp,$ld_fecregactaux,$ls_codact,$ls_denact,$ls_maract,$ls_modact,
																		 $ld_feccmpactaux,$li_cosactaux,$ls_codconbie,$ls_codpai,$ls_codest,$ls_codmun,
																		 $ls_radiotipo,$ls_obsact,$ls_catalogo,$ls_numordcom,$ls_codpro,$ls_denpro,
																		 $li_monordaux,$ls_nomfot,$ls_spgcuenta,$ls_codfuefin,$ls_codsitcon,$ls_codconcom,
																		 $ld_fecordcomaux,$ls_numsolpag,$ld_fecemisolaux,$ls_estdepact,$la_seguridad,
																		 $ls_codgru,$ls_codsubgru,$ls_codsec,$ls_codite,
																		 $ls_clasif);
								if($lb_valido)
								{
									$io_msg->message("El activo ha sido actualizado");
									$lb_valido=$io_saf->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
								}
								else
								{
									$io_msg->message("No se pudo actualizar el activo");
								}
							}
							else
							{
								$io_msg->message("El activo no esta registrado");
							}
						}				
					}
					elseif($ls_tipspg == 0)
					{
						$io_msg->message("La cuenta presupuestaria no es del grupo de las 404 ó no existe en el plan de cuentas.");
					}
				}
						
			}
			elseif($ls_rbtipocat=="2")
			{
			   
				if(($ls_codact=="")||($ld_fecregact=="")||($ls_denact=="")||($ld_feccmpact=="")||($li_cosact=="")||
				   ($ls_spgcuenta=="")||($ls_radiotipo=="")||($ls_codgru=="")||($ls_codsubgru=="")||
				   ($ls_codsec==""))
				{
					$io_msg->message("Faltan campos por llenar");
				}
				else
				{
					if($ls_rbtipocat=="2")
					{
					   $ls_catalogo="---------------";
					}
					$lb_valido=$io_saf->uf_saf_select_cuentaspg($ls_codemp,$ls_spgcuenta,$ls_tipspg);
					if($lb_valido)
					{
						if($ls_statusact=="")
						{
							$lb_encontrado=$io_saf->uf_saf_select_activo($ls_codemp,$ls_codact);
							if(!$lb_encontrado)
							{
								$lb_valido=$io_saf->uf_saf_insert_activo($ls_codemp,$ld_fecregactaux,$ls_codact,$ls_denact,$ls_maract,$ls_modact,
																		 $ld_feccmpactaux,$li_cosactaux,$ls_codconbie,$ls_codpai,$ls_codest,$ls_codmun,
																		 $ls_radiotipo,$ls_obsact,$ls_catalogo,$ls_numordcom,$ls_codpro,$ls_denpro,
																		 $li_monordaux,$ls_nomfot,$ls_spgcuenta,$ls_codfuefin,$ls_codsitcon,$ls_codconcom,
																		 $ld_fecordcomaux,$ls_numsolpag,$ld_fecemisolaux,$ls_estdepact,$la_seguridad,
																		 $ls_codgru,$ls_codsubgru,$ls_codsec,$ls_codite, $ls_clasif);
								if($lb_valido)
								{
									$io_msg->message("El activo ha sido registrado");
									$lb_valido=$io_saf->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
									$ls_statusact="C";
									break;
								}
								else
								{
									$io_msg->message("No se pudo registrar el activo");
								}
							}
							else
							{
								$io_msg->message("El codigo del activo ya esta registrado");
							}
							
						}
						if($ls_statusact=="C")
						{
							$lb_encontrado=$io_saf->uf_saf_select_activo($ls_codemp,$ls_codact);
							if($lb_encontrado)
							{
								$lb_valido=$io_saf->uf_saf_update_activo($ls_codemp,$ld_fecregactaux,$ls_codact,$ls_denact,$ls_maract,$ls_modact,
																		 $ld_feccmpactaux,$li_cosactaux,$ls_codconbie,$ls_codpai,$ls_codest,$ls_codmun,
																		 $ls_radiotipo,$ls_obsact,$ls_catalogo,$ls_numordcom,$ls_codpro,$ls_denpro,
																		 $li_monordaux,$ls_nomfot,$ls_spgcuenta,$ls_codfuefin,$ls_codsitcon,$ls_codconcom,
																		 $ld_fecordcomaux,$ls_numsolpag,$ld_fecemisolaux,$ls_estdepact,$la_seguridad,
																		 $ls_codgru,$ls_codsubgru,$ls_codsec, $ls_codite, $ls_clasif);
								if($lb_valido)
								{
									$io_msg->message("El activo ha sido actualizado");
									$lb_valido=$io_saf->uf_upload($ls_nomfot,$ls_tipfot,$ls_tamfot,$ls_nomtemfot);
								}
								else
								{
									$io_msg->message("No se pudo actualizar el activo");
								}
							}
							else
							{
								$io_msg->message("El activo no esta registrado");
							}
						}				
					}
					elseif($ls_tipspg == 0)
					{
						$io_msg->message("La cuenta presupuestaria no es del grupo de las 404");
					}
				}
			}	
		break;
		case "ELIMINAR":
			$ls_codact=$_POST["txtcodact"];
			$lb_valido=$io_saf->uf_saf_delete_activo($ls_codemp,$ls_codact,$la_seguridad);
			if($lb_valido)
			{
				$io_msg->message("El Activo fue eliminado");
				uf_limpiarvariables();		
			}	
			else
			{
				$io_msg->message("No se pudo eliminar el Activo");
				uf_limpiarvariables();		
			}
		break;
	}
?>
<p>&nbsp;</p>
<form action="" method="post" name="form1" id="form1" enctype="multipart/form-data">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_activo->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_activo);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="752" height="784" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="750" height="690"><div align="left">
          <table width="716" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td width="714" class="titulo-ventana">Definici&oacute;n de Activos </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="titulo-celdanew">Datos</td>
            </tr>
            <tr>
              <td><table width="713" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="4" height="22"><div align="center">
                        <input name="hidstatusact" type="hidden" id="hidstatusact" value="<?php print $ls_statusact ?>">
                        <input name="hidrbtipocat" type="hidden" id="hidrbtipocat" value="<?php print $ls_rbtipocat ?>">
                    Los Campos en (*) son necesarios para la Incluir el Activo</div></td>
                  </tr>
                  <tr>
                    <td width="224" height="22"><div align="right"> (*)C&oacute;digo</div></td>
                    <td width="312"><input name="txtcodact" type="text" id="txtcodact" onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnopqrstuvwxyz');" onBlur="ue_rellenarcampo(this,15);" value="<?php print $ls_codact?>" size="19" maxlength="15" style="text-align:center "></td>
                    <td width="138" rowspan="5"><img name="foto" id="foto" src="fotosactivos/<?php print $ls_foto ?>" width="121" height="94" class="formato-blanco"></td>
                    <td width="39">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right"> (*)Fecha de Registro </div></td>
                    <td><input name="txtfecregact" type="text" id="txtfecregact" onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_fecregact ?>" size="17" maxlength="10" datepicker="true" style="text-align:center "></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><div align="right"> (*)Denominaci&oacute;n</div></td>
                    <td rowspan="2"><textarea name="txtdenact" cols="50" rows="2" id="txtdenact" onKeyUp="ue_validarcomillas(this);" onBlur="javascript: ue_validarcomillas(this);"><?php print $ls_denact ?></textarea></td>
                    <td rowspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td><div align="right"></div></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Marca</div></td>
                    <td><input name="txtmaract" type="text" id="txtmaract" onKeyUp="ue_validarcomillas(this);" onBlur="javascript: ue_validarcomillas(this);" value="<?php print $ls_maract ?>" size="15" maxlength="90"></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Modelo</div></td>
                    <td colspan="3"><input name="txtmodact" type="text" id="txtmodact" onKeyUp="ue_validarcomillas(this);" onBlur="javascript: ue_validarcomillas(this);" value="<?php print $ls_modact ?>" size="15" maxlength="90"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right"> (*)Fecha de Compra </div></td>
                    <td colspan="3"><input name="txtfeccmpact" type="text" id="txtfeccmpact" onKeyPress="ue_separadores(this,'/',patron,true);" value="<?php print $ld_feccmpact ?>" size="17" maxlength="10" datepicker="true" style="text-align:center "></td>
                  </tr>
                  <tr>
                    <td height="23"><div align="right"> (*)Costo</div></td>
                    <td colspan="3"><input name="txtcosact" type="text" id="txtcosact" value="<?php print $li_cosact ?>" size="17" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
                  </tr>
                  <tr <? print $ls_mostrar_csc ?> >
                    <td height="22"><div align="right">Fuente de Financiamiento </div></td>
                    <td colspan="3"><input name="txtcodfuefin" type="text" id="txtcodfuefin" value="<?php print $ls_codfuefin ?>" size="5" readonly style="text-align:center ">
                        <a href="javascript: ue_buscarfuente();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                        <input name="txtdenfuefin" type="text" class="sin-borde" id="txtdenfuefin" size="40" value="<?php print $ls_denfuefin ?>" readonly>                    </td>
                  </tr>
                  <tr <? print $ls_mostrar_csc ?> >
                    <td height="22"><div align="right">Situaci&oacute;n Contable </div></td>
                    <td colspan="3"><input name="txtcodsitcon" type="text" id="txtcodsitcon" size="5" value="<?php print $ls_codsitcon ?>" style="text-align:center " readonly>
                        <a href="javascript: ue_buscarsituacion();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                        <input name="txtdensitcon" type="text" class="sin-borde" id="txtdensitcon" size="40" value="<?php print $ls_densitcon ?>" readonly>                    </td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Condici&oacute;n de la Compra </div></td>
                    <td colspan="3"><input name="txtcodconcom" type="text" id="txtcodconcom" size="5" value="<?php print $ls_codconcom ?>" style="text-align:center " readonly>
                        <a href="javascript:ue_buscarcondicioncompra();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a>
                        <input name="txtdenconcom" type="text" class="sin-borde" id="txtdenconcom" size="40"  value="<?php print $ls_denconcom ?>" readonly>                    </td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Estado de Conservacion </div></td>
                    <td colspan="3"><input name="txtcodconbie" type="text" id="txtcodconbie" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codconbie ?>" size="5" maxlength="1" readonly style="text-align:center ">
                        <a href="javascript: ue_buscarcondicion();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                        <input name="txtdenconbie" type="text" class="sin-borde" id="txtdenconbie" size="40"  value="<?php print $ls_denconbie ?>" readonly>
                        <input name="txtdescripcion" type="hidden" id="txtdescripcion"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Pa&iacute;s</div></td>
                    <td colspan="3"><input name="txtcodpai" type="text" id="txtcodpai" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codpai ?>" size="5" maxlength="3" readonly style="text-align:center ">
                        <a href="javascript: ue_buscarpais();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                        <input name="txtdespai" type="text" class="sin-borde" id="txtdespai"  value="<?php print $ls_despai ?>" readonly></td>
                  </tr>
                  <tr>
                    <td height="28"><div align="right">Estado</div></td>
                    <td colspan="3"><input name="txtcodest" type="text" id="txtcodest" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codest ?>" size="5" maxlength="3" readonly style="text-align:center ">
                        <a href="javascript: ue_buscarestado();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                        <input name="txtdesest" type="text" class="sin-borde" id="txtdesest"  value="<?php print $ls_desest ?>" readonly></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Municipio</div></td>
                    <td colspan="3"><input name="txtcodmun" type="text" id="txtcodmun" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codmun ?>" size="5" maxlength="3" readonly style="text-align:center ">
                        <a href="javascript: ue_buscarmunicipio();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                        <input name="txtdesmun" type="text" class="sin-borde" id="txtdesmun"  value="<?php print $ls_desmun ?>" readonly></td>
                  </tr>
                  <tr>
                    <td height="18"><div align="right"> (*)Tipo </div></td>
                    <td colspan="3"><input name="radiotipo" type="radio" class="sin-borde" value="1" onClick="actualizaValor(this)" <?php print $ls_chkmueble ?>>
                      Mueble
                      <input name="radiotipo" type="radio" class="sin-borde" value="2" onClick="actualizaValor(this)" <?php print $ls_chkinmueble ?>>
                      Inmueble
                      <input name="radiotipo" type="radio" class="sin-borde" value="3" onClick="actualizaValor(this)" <?php print $ls_chksemoviente ?>>
                      Semoviente
                      <input name="hidradio" type="hidden" id="hidradio"></td>
                  </tr>
                  <tr >
                    <td height="22" id="fila1" <? print $ls_mostrar_inm ?>>&nbsp;</td>
                    <td colspan="3" id="fila2" <? print $ls_mostrar_inm ?>> Clasificaci&oacute;n del Inmueble
                      <select name="cmbclasi" size="1" id="cmbclasi">
					    <option value="" selected>--Seleccione una opción--</option>
                        <option <?php if($ls_clasif=="1"){ print "selected";}?> onClick="javascript: ativar_depre('1');" value="1">Edificios </option>
                        <option <?php if($ls_clasif=="2"){ print "selected";}?> onClick="javascript: ativar_depre('2');" value="2">Instalaciones Fijas</option>
                        <option <?php if($ls_clasif=="3"){ print "selected";}?> onClick="javascript: descativar_depre();" value="3">Terrenos</option>
                      </select></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Observaciones</div></td>
                    <td colspan="3" rowspan="2"><textarea name="txtobsact" cols="50" rows="2" id="txtobsact" onKeyUp="ue_validarcomillas(this);" onBlur="javascript: ue_validarcomillas(this);" onKeyPress="javascript: pulsar(this)"><?php print $ls_obsact?>  </textarea></td>
                  </tr>
                  <tr>
                    <td><div align="right"></div></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">(*)Tipo Categoria</div></td>
                    <td colspan="3"><input name="rbtipocat" type="radio" class="sin-borde" value="CSC" <?php print $ls_rbcsc; ?> disabled="disabled">
					Cat&aacute;logo SIGECOF
					<input name="rbtipocat" type="radio" class="sin-borde" value="CGR" <?php print $ls_rbcgr; ?> disabled="disabled">
					Categor&iacute;a CGR </td>
                  </tr>
				  <p>
                  <tr t <? print $ls_mostrar_csc ?>>
                    <td height="22"><div align="right"> (*)Cat&aacute;logo SIGECOF </div></td>
                    <td colspan="3"><input name="txtcatalogo" type="text" id="txtcatalogo" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_catalogo?>" size="20" readonly style="text-align:center ">
                        <a href="javascript: ue_catalogo();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                        <input name="txtdencat" type="text" class="sin-borde" id="txtdencat" size="50" value="<?php print $ls_dencatsig?>" readonly>
                        <input name="hidstatus" type="hidden" id="hidstatus">                        </td>
                  </tr>
				  <p>                  </p>
                  <tr <? print $ls_mostrar_cgr ?> >
                    <td height="22"><div align="right">(*)Grupo</div></td>
                    <td colspan="3"><div align="left">
                      <input name="txtcodgru" type="text" id="txtcodgru" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codgru?>" size="5" readonly>
                      <a href="javascript: ue_catalogo_grupo();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                      <input name="txtdengru" type="text" class="sin-borde" id="txtdengru" size="50" readOnly="true">
                    </div></td>
                  </tr>
                  <tr <? print $ls_mostrar_cgr ?> >
                    <td height="22"><div align="right">(*)SubGrupo</div></td>
                    <td colspan="3"><div align="left">
                      <input name="txtcodsubgru" type="text" id="txtcodsubgru" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codsubgru?>" size="5" readonly>
                      <a href="javascript: ue_catalogo_subgrupo();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                      <input name="txtdensubgru" type="text" class="sin-borde" id="txtdensubgru" size="50" readOnly="true">
                    </div></td>
                  </tr>
                  <tr <? print $ls_mostrar_cgr ?> >
                    <td height="22"><div align="right">(*)Seccion</div></td>
                    <td colspan="3"><div align="left">
                      <input name="txtcodsec" type="text" id="txtcodsec" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codsec?>" size="5" readonly>
                      <a href="javascript: ue_catalogo_seccion();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                      <input name="txtdensec" type="text" class="sin-borde" id="txtdensec" size="50" readOnly="true">
                    </div></td>
                  </tr>
                  <tr <? print $ls_mostrar_cgr ?> >
                    <td height="22"><div align="right">(*)Item</div></td>
                    <td colspan="3"><div align="left">
                      <input name="txtcodite" type="text" id="txtcodite" value="<?php print $ls_codite; ?>" size="5" readonly>
                      <a href="javascript: ue_catalogo_item();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                      <input name="txtdenite" type="text" class="sin-borde" id="txtdenite" size="50" readonly>
</div></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">(*)C&oacute;digo Presupuestario</div></td>
                    <td colspan="3"><input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center " size="20" value="<?php print $ls_spgcuenta?>" readonly>
                    <a href="javascript: ue_catalogo_spg();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>                      <!--<a href="javascript: ue_buscarspg();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>-->
                    <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" size="50" readonly></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Foto</div></td>
                    <td colspan="3"><input name="txtfotact" type="file" id="txtfotact"></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Activo</div></td>
                    <td colspan="3"><input name="chkdepreciable" type="checkbox" id="chkdepreciable" value="1" <?php print $ls_chkchecked; print  $ls_edit?>>
                    Depreciable</td>
                  </tr>
                  <tr>
                    <td><div align="right"></div></td>
                    <td colspan="3">&nbsp;</td>
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
              <td height="75"><table width="708" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="185" height="18"><div align="right"> Numero</div></td>
                    <td width="523"><input name="txtnumord" type="text" id="txtnumord" onKeyUp="javascript: ue_validarnumero(this);" value="<?php print $ls_numordcom ?>" size="18" maxlength="15">
                    <a href="javascript: ue_buscarorden();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Fecha</div></td>
                    <td><input name="txtfecordcom" type="text" id="txtfecordcom" size="18" style="text-align:center " value="<?php print $ld_fecordcom ?>" readonly></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Proveedor</div></td>
                    <td><input name="txtcodpro" type="text" id="txtcodpro" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codpro?>" size="18" style="text-align:center " readonly>
                        <input name="txtdenpro" type="text" class="sin-borde" id="txtdenpro" size="35" value="<?php print $ls_denpro?>" readonly></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Monto Orden </div></td>
                    <td><input name="txtmonord" type="text" id="txtmonord" value="<?php print $li_monord?>" size="18" onKeyPress="return(ue_formatonumero(this,'.',',',event));" style="text-align:right "></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td><div align="center"> </div></td>
            </tr>
            <tr>
              <td class="titulo-celdanew">Orden de Pago </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td><table width="711" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="230" height="18"><div align="right">Numero</div></td>
                    <td width="481"><input name="txtnumsolpag" type="text" id="txtnumsolpag" onKeyUp="javascript: ue_validarnumero(this);" size="18" maxlength="15" value="<?php print $ls_numsolpag?>" readonly>
                        <a href="javascript: ue_buscarpago();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Fecha</div></td>
                    <td><input name="txtfecemisol" type="text" id="txtfecemisol" size="18" style="text-align:center " value="<?php print $ld_fecemisol?>" readonly></td>
                  </tr>
              </table></td>
            </tr>
            <tr>
              <td><input name="operacionact" type="hidden" id="operacionact"></td>
            </tr>
          </table>
      </div></td>
    </tr>
    <tr>
      <td height="79"><table width="630" border="0" align="center" class="formato-blanco">
        <tr>
          <td width="157" height="32"><div align="center">
              <input name="btnbanco" type="button" class="boton" id="btnbanco" value="      Banco       " height="100px"  onClick="ue_abriractivosbanco();">
          </div></td>
          <td width="158"><div align="center">
              <input name="btnmantenimirnto" type="button" class="boton" id="btnmantenimirnto" value="Mantenimiento"   onClick="ue_abriractivosmantenimiento();">
          </div></td>
          <td width="157"><div align="center">
              <input name="btnpoliza" type="button" class="boton" id="btnpoliza" value="      P&oacute;liza      " onClick="ue_abriractivospoliza();">
          </div></td>
          <td width="158"><div align="center">
              <input name="btnrotulacion" type="button" class="boton" id="btnrotulacion" value="  Rotulaci&oacute;n  "  onClick="ue_abriractivosrotulacion();">
          </div></td>
        </tr>
        <tr>
          <td height="32"><div align="center">
            <input name="btndepreciacion" type="button" class="boton" id="btndepreciacion2" value="   Depreciaci&oacute;n   " onClick="ue_abrirdepreciacion();" <? print $depreciacion ?>>
          </div></td>
          <td id="fila3" height="32" colspan="2" <? print $ls_estbt ?>><div align="center"><input name="btnhojatrabajo" type="button" class="boton" id="btnhojatrabajo" value="   <? print $ls_titulo ?>   " onClick="ue_abririnmuebles();"></div></td>
          <td><div align="center">
            <input name="btnseriales" type="button" class="boton" id="btnseriales" value=" Seriales y Partes " onClick="ue_abrirseriales();">
          </div></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="15">&nbsp;</td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<div align="center"></div>
<p align="center">&nbsp;</p>
</body>
<script language="javascript">
function ue_abrirdepreciacion()
{
	f=document.form1;
	codact=ue_validarvacio(f.txtcodact.value);
	status=ue_validarvacio(f.hidstatusact.value);
	if (status=="C")
	{
		if(f.chkdepreciable.checked)
		{
			window.open("sigesp_saf_d_depreciacion.php?codact="+codact+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=625,height=350,left=60,top=70,location=no,resizable=no");
		}
		else
		{alert("El activo no es depreciable");}
	}
	else
	{
		alert("El activo debe estar grabado");	
	}
}

function ue_abririnmuebles()
{
	f=document.form1;
	valor=f.cmbclasi.value;	
	codact=ue_validarvacio(f.txtcodact.value);
	status=ue_validarvacio(f.hidstatusact.value);
	denact=ue_validarvacio(f.txtdenact.value)
	if (status=="C")
	{
		if (valor==1)
		{
			window.open("sigesp_saf_d_inmueble_edificio.php?codact="+codact+"&denact="+denact+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=900,height=450,left=60,top=70,location=no,resizable=no");			
		}
		
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
		window.open("sigesp_saf_d_seriales.php?codact="+codact+"&denact="+denact+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=870,height=400,left=0,top=100,location=no,resizable=yes");
	}
	else
	{
		alert("El activo debe estar grabado");	
	}
}

function ue_abriractivosbanco()
{
	f=document.form1;
	codact=ue_validarvacio(f.txtcodact.value);
	denact=ue_validarvacio(f.txtdenact.value)
	status=ue_validarvacio(f.hidstatusact.value);
	if (status=="C")
	{
		window.open("sigesp_saf_d_activosbanco.php?codact="+codact+"&denact="+denact+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=680,height=300,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("El activo debe estar grabado");	
	}
}

function ue_abriractivospoliza()
{
	f=document.form1;
	codact=ue_validarvacio(f.txtcodact.value);
	denact=ue_validarvacio(f.txtdenact.value)
	status=ue_validarvacio(f.hidstatusact.value);
	if (status=="C")
	{
		window.open("sigesp_saf_d_activospoliza.php?codact="+codact+"&denact="+denact+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=300,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("El activo debe estar grabado");	
	}
}

function ue_abriractivosrotulacion()
{
	f=document.form1;
	codact=ue_validarvacio(f.txtcodact.value);
	denact=ue_validarvacio(f.txtdenact.value)
	status=ue_validarvacio(f.hidstatusact.value);
	if (status=="C")
	{
		window.open("sigesp_saf_d_activosrotulacion.php?codact="+codact+"&denact="+denact+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=300,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("El activo debe estar grabado");	
	}
}

function ue_abriractivosmantenimiento()
{
	f=document.form1;
	codact=ue_validarvacio(f.txtcodact.value);
	denact=ue_validarvacio(f.txtdenact.value)
	status=ue_validarvacio(f.hidstatusact.value);
	if (status=="C")
	{
		window.open("sigesp_saf_d_activosmantenimiento.php?codact="+codact+"&denact="+denact+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=300,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("El activo debe estar grabado");	
	}
}

function ue_buscarcondicioncompra()
{
	window.open("sigesp_saf_cat_condicioncompra.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarsituacion()
{
	f=document.form1;
	if(f.rbtipocat[0].checked==true)
	{
		window.open("sigesp_saf_cat_situacioncontable.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
    }
	else
	{
		alert("La Configuración de Activos Fijos no Permite esta Opción");	
	}
}

function ue_buscarfuente()
{
	f=document.form1;
	if(f.rbtipocat[0].checked==true)
	{
		window.open("sigesp_saf_cat_fuentefinanciamiento.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
    }
	else
	{
		alert("La Configuración de Activos Fijos no Permite esta Opción");	
	}
}

function ue_buscarcondicion()
{
	window.open("sigesp_saf_cat_condicion.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarspg()
{
	window.open("sigesp_saf_cat_ctasspg.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpais()
{
	f=document.form1;
	window.open("sigesp_saf_cat_pais.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
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
		window.open("sigesp_saf_cat_estado.php?codpai="+codpai+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
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
		window.open("sigesp_saf_cat_municipio.php?codpai="+codpai+"&codest="+codest+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un pais y un estado.");
	}
}

function ue_catalogo()
{
    f=document.form1;
	if(f.rbtipocat[0].checked==true)
	{
	    window.open("sigesp_saf_cat_sigecof.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
    }
	else
	{
		alert("No se ha seleccionado la busqueda de catalogo sigescof");	
	}
}

function ue_buscarorden()
{
	window.open("sigesp_saf_cat_ordencompra.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarpago()
{
	window.open("sigesp_saf_cat_solicitudpago.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=no");
}
//--------------------------------------------------------
//  Funciones de las operaciones de la páginas
//--------------------------------------------------------
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacionact.value="NUEVO";
		f.action="sigesp_saf_d_activossigecof.php";
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
	lb_status=f.hidstatusact.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status=="")&&(li_incluir==1))
	{
		f.operacionact.value="GUARDAR";
		f.action="sigesp_saf_d_activossigecof.php";
		f.submit();
	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_saf_cat_activo.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=570,height=400,left=50,top=50,location=no,resizable=yes");
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
		if(confirm("¿Seguro desea eliminar el Activo?"))
		{
			f.operacionact.value="ELIMINAR";
			f.action="sigesp_saf_d_activossigecof.php";
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
	if (f.radiotipo[1].checked)
	{
		fila1=document.getElementById("fila1");
		fila2=document.getElementById("fila2");	
		fila1.style.display="";
		fila1.style.display="compact";
		fila2.style.display="";
		fila2.style.display="compact";		
	}
	else
	{
		fila1=document.getElementById("fila1");
		fila2=document.getElementById("fila2");	
		fila1.style.display="";
		fila1.style.display="none";
		fila2.style.display="";
		fila2.style.display="none";	
		f.chkdepreciable.checked=true;
	    f.chkdepreciable.value=1;
	    f.chkdepreciable.disabled=false;
		f.cmbclasi.value="";	
	}
} 
   
function ue_catalogo_grupo()
{
	f=document.form1;
	if(f.rbtipocat[1].checked==true)
	{
		window.open("sigesp_saf_cat_grupo.php?tipo=ACTIVOS","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		f.txtcodsubgru.value="";
		f.txtdensubgru.value="";
		f.txtcodsec.value="";
		f.txtdensec.value="";
	}
	else
	{
		alert("No se ha seleccionado la busqueda de catalogo por categoría CGR");	
	}
}
   
function ue_catalogo_subgrupo()
{
	f=document.form1;
	codgru=ue_validarvacio(f.txtcodgru.value);
	dengru=ue_validarvacio(f.txtdengru.value);
	if(f.rbtipocat[1].checked==true)
	{
		if((codgru!="---")||(codgru!=""))
		{
			window.open("sigesp_saf_cat_subgrupo.php?txtcodgru="+codgru+"&txtdengru="+dengru+"&tipo=ACTIVOS","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
			f.txtcodsec.value="";
			f.txtdensec.value="";
		}
		else
		{
			alert("Debe seleccionar un grupo.");
		}
	}
	else
	{
		alert("No se ha seleccionado la busqueda de catalogo por categoría CGR");	
	}
}

function ue_catalogo_seccion()
{
	f=document.form1;
	codgru=ue_validarvacio(f.txtcodgru.value);
	dengru=ue_validarvacio(f.txtdengru.value);
	codsubgru=ue_validarvacio(f.txtcodsubgru.value);
	densubgru=ue_validarvacio(f.txtdensubgru.value);
	if(f.rbtipocat[1].checked==true)
	{
		if((codgru!="---")||(codsubgru!="---")||(codgru!="")||(codsubgru!=""))
		{
			window.open("sigesp_saf_cat_seccion.php?txtcodgru="+codgru+"&txtdengru="+dengru+"&txtcodsubgru="+codsubgru+"&txtdensubgru="+densubgru+"&tipo=ACTIVOS","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else
		{
			alert("Debe seleccionar un grupo y un subgrupo.");
		}
	}
	else
	{
		alert("No se ha seleccionado la busqueda de catalogo por categoría CGR");	
	}
}

function ue_catalogo_item()
{
	f=document.form1;
	codgru=ue_validarvacio(f.txtcodgru.value);
	dengru=ue_validarvacio(f.txtdengru.value);
	codsubgru=ue_validarvacio(f.txtcodsubgru.value);
	densubgru=ue_validarvacio(f.txtdensubgru.value);
	codsec=ue_validarvacio(f.txtcodsec.value);
	densec=ue_validarvacio(f.txtdensec.value);
	if(f.rbtipocat[1].checked==true)
	{
		if(f.radiotipo[0].checked==true)
		{
			if((codgru!="")||(codsubgru!="")||(codsec!=""))
			{
				window.open("sigesp_saf_cat_item.php?txtcodgru="+codgru+"&txtdengru="+dengru+"&txtcodsubgru="+codsubgru+"&txtdensubgru="+densubgru+"&txtcodsec="+codsec+"&txtdensec="+densec+"&tipo=ACTIVOS","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
			}
			else
			{
				alert("Debe seleccionar grupo, subgrupo y seccion.");
			}
		}
		else
		{
			alert("El activo debe ser un Bien Mueble para habilitar esta opcion");
		}
	}
	else
	{
		alert("No se ha seleccionado la busqueda de catalogo por categoría CGR");	
	}
}

function ue_catalogo_spg()
{
	f=document.form1;
	codgru=ue_validarvacio(f.txtcodgru.value);
	codsubgru=ue_validarvacio(f.txtcodsubgru.value);
	codsec=ue_validarvacio(f.txtcodsec.value);
	if(f.rbtipocat[1].checked==true)
	{
		if((codgru!="---")||(codsubgru!="---")||(codsec!="---")||(codgru!="")||(codsubgru!="")||(codsec!=""))
		{
			window.open("sigesp_saf_cat_ctasspg.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else
		{
			alert("Debe seleccionar un grupo, un subgrupo y una secccion.");
		}
	}
	else
	{
		alert("No se ha seleccionado la busqueda de catalogo por categoría CGR");	
	}
}

function pulsar(e) { 
    tecla=(document.all) ? e.keyCode : e.which; 
    if (tecla==13) return false;
	 
}

function descativar_depre()
{
    f=document.form1;
	f.chkdepreciable.checked=false;
	f.chkdepreciable.value=0;
	f.chkdepreciable.disabled=true;
	fila3=document.getElementById("fila3");
	fila3.style.display="";
	f.btnhojatrabajo.value="Terrenos";	
}

function ativar_depre(valor)
{
    f=document.form1;
	f.chkdepreciable.checked=true;
	f.chkdepreciable.value=1;
	f.chkdepreciable.disabled=false;
	fila3=document.getElementById("fila3");
	fila3.style.display="";
	fila3.style.display="compact";
	fila3.style.display="compact";
	if (valor=="1")
	{
		f.btnhojatrabajo.value="Edificios";
	}
	if (valor=="2")
	{
		f.btnhojatrabajo.value="Instalaciones Fijas";
	}
	
}

 

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>