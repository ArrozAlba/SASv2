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
	$io_fun_nomina->uf_load_seguridad_nomina("SNO","sigesp_sno_p_registrarencargaduria.php",$ls_codnom,$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	//--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function:  uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing.  María Beatriz Unda
		// Fecha Creación: 26/12/2008								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_nomper,$ls_estper,$ls_codsubnom,$ls_dessubnom,$ls_codasicar,$ls_denasicar,$ls_codcar,$ls_descar;
		global $ls_codtab,$ls_destab,$ls_codpas,$ls_codgra,$ls_coduniadm,$ls_desuniadm;
		global $ls_operacion,$ls_existe,$io_fun_nomina,$ls_desnom,$ls_codnom,$li_rac,$li_subnomina;
		global $li_tipnom,$li_calculada;
		global $li_implementarcodunirac,$ls_codunirac,$ls_tipcuebanper,$io_fun_nomina;
		global $li_loncueban, $li_valloncueban, $ls_grado;
		global $ls_coddep, $ls_dendep, $ld_fecinienc, $ld_fecfinenc;
		global $ls_codperenc,$ls_nomperenc,$ls_estperenc,$ls_codsubnomenc,$ls_dessubnomenc,$ls_codasicarenc,$ls_denasicarenc;		
		global $ls_codcarenc,$ls_descarenc,$ls_codtabenc,$ls_destabenc,$ls_codpasenc,$ls_codgraenc,$ls_coduniadmenc; 
		global $ls_desuniadmenc,$ls_coduniracenc, $ls_gradoenc,$ls_coddepenc, $ls_dendepenc,$ls_desper;
		global $li_racenc,$li_subnominaenc,$li_tipnomenc,$ls_codnomenc,$ls_obsenc,$ls_codenc,$ls_estenc,$ls_calenc,$ls_susper;
				
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_codnomenc="";
		$ls_codenc="";
		$ls_codper="";
		$ls_nomper="";
		$ls_estper="";
		$ls_codsubnom="";
		$ls_dessubnom="";
		$ls_codasicar="";
		$ls_denasicar="";
		$ls_codcar="";
		$ls_descar="";
		$ls_codtab="";
		$ls_destab="";
		$ls_codpas="";
		$ls_codgra="";		
		$ls_coduniadm="";		
		$ls_desuniadm="";	
		$ls_codunirac="";		
		$ls_grado="";
		$ls_coddep="";
		$ls_dendep="";	
		$ls_codperenc="";
		$ls_nomperenc="";
		$ls_estperenc="";
		$ls_codsubnomenc="";
		$ls_dessubnomenc="";
		$ls_codasicarenc="";
		$ls_denasicarenc="";
		$ls_codcarenc="";
		$ls_descarenc="";
		$ls_codtabenc="";
		$ls_destabenc="";
		$ls_codpasenc="";
		$ls_codgraenc="";		
		$ls_coduniadmenc="";		
		$ls_desuniadmenc="";	
		$ls_coduniracenc="";		
		$ls_gradoenc="";
		$ls_coddepenc="";
		$ls_dendepenc="";	
		$ls_estenc="";		
		$li_racenc="";
		$li_subnominaenc="";			
		$li_tipnomenc="";			
		$ld_fecinienc="dd/mm/aaaa";
		$ld_fecfinenc="dd/mm/aaaa";				
		$ls_obsenc="";
		$ls_susper="";
		$ls_calenc="disabled";
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();			
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		$li_rac=$_SESSION["la_nomina"]["racnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$li_subnomina=$_SESSION["la_nomina"]["subnom"];	
		$li_tipnom=$_SESSION["la_nomina"]["tipnom"];				
		require_once("sigesp_sno_c_calcularnomina.php");
		$io_calcularnomina=new sigesp_sno_c_calcularnomina();
		$li_calculada=str_pad($io_calcularnomina->uf_existesalida(),1,"0");
		unset($io_calcularnomina);			

   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing.  María Beatriz Unda
		// Fecha Creación: 26/12/2008 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codper,$ls_nomper,$ls_estper,$ls_codsubnom,$ls_dessubnom,$ls_codasicar,$ls_denasicar,$ls_codcar,$ls_descar;
		global $ls_codtab,$ls_destab,$ls_codpas,$ls_codgra,$ls_coduniadm,$ls_desuniadm;
		global $ls_operacion,$ls_existe,$io_fun_nomina,$ls_desnom,$ls_codnom,$li_rac,$li_subnomina;
		global $li_tipnom,$li_calculada,$io_fun_nomina;
		global $li_implementarcodunirac,$ls_codunirac,$ls_tipcuebanper, $ls_calenc;
		global $li_loncueban, $li_valloncueban, $ls_grado;
		global $ls_coddep, $ls_dendep, $ld_fecinienc, $ld_fecfinenc,$ls_obsenc,$ls_susper;
		global $ls_codperenc,$ls_nomperenc,$ls_estperenc,$ls_codsubnomenc,$ls_dessubnomenc,$ls_codasicarenc,$ls_denasicarenc;		
		global $ls_codcarenc,$ls_descarenc,$ls_codtabenc,$ls_destabenc,$ls_codpasenc,$ls_codgraenc,$ls_coduniadmenc; 
		global $ls_desuniadmenc,$ls_coduniracenc, $ls_gradoenc,$ls_coddepenc, $ls_dendepenc,$ls_codnomenc,$ls_codenc,$ls_estenc;
		
		$ls_codenc=$_POST["txtcodenc"];
		$ls_estenc=$_POST["txtestenc"];
		$ls_codper=$_POST["txtcodper"];
		$ls_nomper=$_POST["txtnomper"];
		$ls_estper=$_POST["txtestper"];
		$ls_codnomenc=$_POST["txtcodnom"];
		$ls_codunirac=$_POST["txtcodunirac"];
		$ls_codsubnom=$_POST["txtcodsubnom"];
		$ls_dessubnom=$_POST["txtdessubnom"];
		$ls_codasicar=$_POST["txtcodasicar"];
		$ls_denasicar=$_POST["txtdenasicar"];		
		$ls_codtab=$_POST["txtcodtab"];
		$ls_destab=$_POST["txtdestab"];
		$ls_codpas=$_POST["txtcodpas"];
		$ls_codgra=$_POST["txtcodgra"];
		$ls_codcar=$_POST["txtcodcar"];
		$ls_descar=$_POST["txtdescar"];			
		$ls_coduniadm=$_POST["txtcoduniadm"];
		$ls_desuniadm=$_POST["txtdesuniadm"];		
		$ls_grado=$_POST["txtgrado"];		
		$ls_coddep=$_POST["txtcoddep"];
		$ls_dendep=$_POST["txtdendep"];
		$ls_codperenc=$_POST["txtcodperenc"];
		$ls_nomperenc=$_POST["txtnomperenc"];
		$ls_estperenc=$_POST["txtestperenc"];
		$ls_coduniracenc=$_POST["txtcoduniracenc"];
		$ls_codsubnomenc=$_POST["txtcodsubnomenc"];
		$ls_dessubnomenc=$_POST["txtdessubnomenc"];
		$ls_codasicarenc=$_POST["txtcodasicarenc"];
		$ls_denasicarenc=$_POST["txtdenasicarenc"];		
		$ls_codtabenc=$_POST["txtcodtabenc"];
		$ls_destabenc=$_POST["txtdestabenc"];
		$ls_codpasenc=$_POST["txtcodpasenc"];
		$ls_codgraenc=$_POST["txtcodgraenc"];
		$ls_codcarenc=$_POST["txtcodcarenc"];
		$ls_descarenc=$_POST["txtdescarenc"];			
		$ls_coduniadmenc=$_POST["txtcoduniadmenc"];
		$ls_desuniadmenc=$_POST["txtdesuniadmenc"];		
		$ls_gradoenc=$_POST["txtgradoenc"];		
		$ls_coddepenc=$_POST["txtcoddepenc"];
		$ls_dendepenc=$_POST["txtdendepenc"];
		$ld_fecinienc=$_POST["txtfecinienc"];
		$ld_fecfinenc=$_POST["txtfecfinenc"];			
		$ls_obsenc=trim($_POST["txtobsenc"]);
		$ls_susper=$io_fun_nomina->uf_obtenervalor("chksuspernom","0");
		$ls_calenc="disabled";
		
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
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.keyCode == 17 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title >Registro de Encargadur&iacute;a</title>
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
		
	require_once("sigesp_sno_c_registrarencargaduria.php");
	$io_encargaduria=new sigesp_sno_c_registrarencargaduria();	
	uf_limpiarvariables();	
	$ls_codenc=$io_encargaduria->uf_generar_codigo_encargaduria();
	switch ($ls_operacion) 
	{
		case "NOMINA":
			uf_load_variables();
			require_once("sigesp_sno.php");
			$io_sno=new sigesp_sno();
			$ls_codnomenc=$_POST["cmbnomina"];
			$io_sno->uf_crear_sessionnomina_encargaduria($ls_codnomenc);	
			$ls_codnomenc=$_SESSION["la_nominaenc"]["codnom"];
			$li_racenc=$_SESSION["la_nominaenc"]["racnom"];
			$li_subnominaenc=$_SESSION["la_nominaenc"]["subnom"];	
			$li_tipnomenc=$_SESSION["la_nominaenc"]["tipnom"];
			unset($io_sno);
			$ls_existe="FALSE";
		break;
		
		case "BUSCAR":
			uf_load_variables();
			require_once("sigesp_sno.php");
			$io_sno=new sigesp_sno();
			$ls_codnomenc=$_POST["cmbnomina"];
			$io_sno->uf_crear_sessionnomina_encargaduria($ls_codnomenc);	
			$ls_codnomenc=$_SESSION["la_nominaenc"]["codnom"];
			$li_racenc=$_SESSION["la_nominaenc"]["racnom"];
			$li_subnominaenc=$_SESSION["la_nominaenc"]["subnom"];	
			$li_tipnomenc=$_SESSION["la_nominaenc"]["tipnom"];
			$ls_calenc="";
			$io_encargaduria->uf_load_datos_nomina_personal_encargado($ls_codnomenc,$ls_codperenc,$ls_coduniracenc,$ls_codsubnomenc,$ls_dessubnomenc,$ls_codasicarenc,$ls_denasicarenc,$ls_codtabenc,$ls_destabenc,$ls_codpasenc,$ls_codgraenc,$ls_codcarenc,$ls_descarenc,$ls_coduniadmenc,$ls_desuniadmenc,$ls_gradoenc,$ls_coddepenc,$ls_dendepenc);
			
			if($ls_susper=="1")
			{
				$ls_susper="checked";
			}	
				
			unset($io_sno);
		break;
		
		case "GUARDAR":
			uf_load_variables();
			if ($ls_existe=='FALSE')
			{
				$lb_existe=$io_encargaduria->uf_chequear_personal_encargaduria($ls_cod,$ls_codenc,$ls_codperenc,$ld_fecinienc,$ld_fecfinenc,$ls_nom);
				if ((!$lb_existe)&&($ls_cod==""))
				{
					
					$lb_existe=$io_encargaduria->uf_chequear_personal_encargaduria($ls_cod,$ls_codenc,$ls_codper,$ld_fecinienc,$ld_fecfinenc,$ls_nom);
					
					if ((!$lb_existe)&&($ls_cod==""))
					{
						
						
						$lb_valido=$io_encargaduria->uf_guardar($ls_existe,$ls_codenc,$ld_fecinienc, $ld_fecfinenc, $ls_obsenc, $ls_codper, $ls_codnomenc, $ls_codperenc,$ls_susper, $la_seguridad);
						if($lb_valido)
						{
							if($ls_susper=="1")
							{
								$ls_susper="checked";
							}	
							$ls_existe="TRUE";
							$ls_calenc="";
						}
						else
						{
							$ls_existe="FALSE";
						}
					}
					else
					{
							$io_encargaduria->io_mensajes->message("La persona ".$ls_codper." se encuentra registrada en la encargaduria ".$ls_cod." de la nómina ".$ls_nom);
					}	
				}
				else
				{
						$io_encargaduria->io_mensajes->message("La persona encargada ".$ls_codperenc." se encuentra registrada en la encargaduria ".$ls_cod." de la nómina ".$ls_nom);
				}	
			}
			else
			{
				$lb_valido=$io_encargaduria->uf_guardar($ls_existe,$ls_codenc,$ld_fecinienc, $ld_fecfinenc, $ls_obsenc, $ls_codper, $ls_codnomenc, $ls_codperenc,$ls_susper, $la_seguridad);
				if($lb_valido)
				{
					if($ls_susper=="1")
					{
						$ls_susper="checked";
					}	
					$ls_existe="TRUE";
					$ls_calenc="";
				}
				else
				{
					$ls_existe="FALSE";
				}
			}
			break;	

	}


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
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title='Nuevo' alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"></a></div></td>    
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" title='Salir' alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" title='Ayuda' alt="Ayuda" width="20" height="20"></div></td>
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
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">      <p>&nbsp;</p>
      <table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
      <tr class="titulo-ventana">
        <td height="20" colspan="4" class="titulo-ventana">Registro de Encargadur&iacute;a</td>
      </tr>
	  <tr>
        <td height="20" colspan="4" class="titulo-celdanew">Informaci&oacute;n de la Encargadur&iacute;a</td>
      </tr> 
	  <tr>
          <td width="128" height="22"><div align="right">C&oacute;digo</div></td>
          <td width="566">
                <div align="left">
                  <input name="txtcodenc" type="text" id="txtcodenc" size="15" maxlength="10" value="<?php print $ls_codenc;?>"  readonly> <input name="txtestenc" type="text" class="sin-borde2" id="txtestenc" value="<?php print trim($ls_estenc);?>" size="20" maxlength="20" readonly>                   
            </div></td>
          
        </tr>
	  <tr>
          <td height="22"><div align="right">Fecha Inicio</div></td>
          <td colspan="2"><input name="txtfecinienc" type="text" id="txtfecinienc" value="<?php print $ld_fecinienc;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true"></td>
        </tr>		
	   <tr>
          <td height="22"><div align="right">Fecha Finalizaci&oacute;n</div></td>
          <td colspan="2"><input name="txtfecfinenc" type="text" id="txtfecfinenc" value="<?php print $ld_fecfinenc;?>" size="15" maxlength="10" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" datepicker="true"></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Observaci&oacute;n</div></td>
          <td colspan="2">
            <div align="left">
              <textarea name="txtobsenc" cols="80" rows="3" id="txtobsenc" onKeyUp="javascript: ue_validarcomillas(this);"> <?php print $ls_obsenc;?></textarea>
            </div></td>
        </tr>
      <tr>
        <td height="20" colspan="4" class="titulo-celdanew">Informaci&oacute;n de Personal </td>
      </tr>
      <tr>
        <td width="128" height="22"><div align="right">C&oacute;digo</div></td>
        <td colspan="3"><input name="txtcodper" type="text" id="txtcodper" value="<?php print $ls_codper;?>" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarpersonal();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="txtestper" type="text" class="sin-borde2" id="txtestper" value="<?php print $ls_estper;?>" size="20" maxlength="20" readonly></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre y Apellido </div></td>
        <td colspan="3"><input name="txtnomper" type="text" class="sin-borde" id="txtnomper" value="<?php print $ls_nomper;?>" size="90" maxlength="120" readonly></td>
      </tr>
       <?php
		   if($li_subnomina=="1") {?>	  
      <tr>
        <td height="22"><div align="right">Subn&oacute;mina</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodsubnom" type="text" id="txtcodsubnom" value="<?php print $ls_codsubnom;?>" size="13" maxlength="10" readonly>
         
          <input name="txtdessubnom" type="text" class="sin-borde" id="txtdessubnom" value="<?php print $ls_dessubnom;?>" size="63" maxlength="60" readonly>
        </div></td>
      </tr>
	 <?php }
	  	   if($li_rac=="0") {
	 ?>	  
      <tr>
        <td height="22"><div align="right">Cargo</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodcar" type="text" id="txtcodcar" value="<?php print $ls_codcar;?>" size="13" maxlength="10"  readonly>
     	  <input name="txtdescar" type="text" class="sin-borde" id="txtdescar" value="<?php print $ls_descar;?>" size="65" maxlength="100" readonly>
        </div></td>
      </tr>
	  <?php 
		 	   if(($li_tipnom=="3")||($li_tipnom=="4"))
			   {
		 ?>	  
      <tr>
        <td height="22"><div align="right">Clasificación Obrero</div></td>
        <td colspan="3"><div align="left">
          <input name="txtgrado" type="text" id="txtgrado" value="<?php print $ls_grado;?>" size="13" maxlength="4"  readonly>
          
        </div></td>
      </tr>

		<?php
		     }
			 else
			 {
			 	 ?>	  
				  
					 <input name="txtgrado" type="hidden" id="txtgrado" value="<?php print $ls_grado;?>" size="13" maxlength="4"  readonly>
			<?php	
			 }
		   }
	 	   else
		   {
	 
				if(($li_tipnom=="3")||($li_tipnom=="4"))
				{
			 ?>	         
					<tr>
					<td height="22"><div align="right">Asignaci&oacute;n de Cargo </div></td>
					<td colspan="3"><div align="left">
					  <input name="txtcodasicar" type="text" id="txtcodasicar" value="<?php print $ls_codasicar;?>" size="10" maxlength="7"  readonly>
					  
					  <input name="txtdenasicar" type="text" class="sin-borde" id="txtdenasicar" value="<?php print $ls_denasicar;?>" size="27" maxlength="24" readonly>
					</div></td>
					</tr>
					<tr>
					<td height="22"><div align="right">Clasificación Obrero</div></td>
					<td colspan="3"><div align="left">
					  <input name="txtgrado" type="text" id="txtgrado" value="<?php print $ls_grado;?>" size="13" maxlength="4"  readonly>					 
					</div></td>
					</tr>
		 <?php 
				}
				else
				{
			?>
					<tr>
					<td height="22"><div align="right">Asignaci&oacute;n de Cargo </div></td>
					<td colspan="3"><div align="left">
					  <input name="txtcodasicar" type="text" id="txtcodasicar" value="<?php print $ls_codasicar;?>" size="10" maxlength="7"  readonly>
					  <input name="txtdenasicar" type="text" class="sin-borde" id="txtdenasicar" value="<?php print $ls_denasicar;?>" size="27" maxlength="24" readonly>
					</div></td>
					</tr>
				   
			<?php
				 }
			?>
	 <?php 
		if($li_tipnom!="3")
		 {
		   if ($li_tipnom!="4")
		   {
	  ?>
	   <?php 
		  if(($li_implementarcodunirac=="1")&&($li_rac=="1")) {?>
      <tr>
        <td height="22"><div align="right">C&oacute;digo RAC </div></td>
        <td colspan="3">
          <input name="txtcodunirac" type="text" id="txtcodunirac" size="12" maxlength="10" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_codunirac;?>" readonly> </td>
      </tr>
	 <?php }	?>  
      <tr>
        <td height="22"><div align="right">Tabulador</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodtab" type="text" id="txtcodtab" value="<?php print $ls_codtab;?>" size="25" maxlength="20" readonly>
          &nbsp;
            <input name="txtdestab" type="text" class="sin-borde" id="txtdestab" value="<?php print $ls_destab;?>" size="60" maxlength="100">
        </div></td>
      </tr>	  
      <tr>
        <td width="128"><div align="right">Grado
        </div></td>
        <td width="566"><input name="txtcodgra" type="text" id="txtcodgra" value="<?php print $ls_codgra;?>" size="18" maxlength="15" readonly>
          </td>
		  </tr>	  
      <tr>
        <td width="128"><div align="right">Paso</div>
       </td>
        <td width="566"><div align="left">
			<input name="txtcodpas" type="text" id="txtcodpas" value="<?php print $ls_codpas;?>" size="18" maxlength="15" readonly>          </div></td>
      </tr>
	   <?php 
			 }// fin del if ($li_tipnom!="4")
		 }//fin del ($li_tipnom!="3")	 
    }//fin del else ?>
     
      <tr>
        <td height="22"><div align="right">Unidad Administrativa </div></td>
        <td colspan="3"><div align="left">
          <input name="txtcoduniadm" type="text" id="txtcoduniadm" value="<?php print $ls_coduniadm;?>" size="19" maxlength="16" readonly>
      
          <input name="txtdesuniadm" type="text" class="sin-borde" id="txtdesuniadm" value="<?php print $ls_desuniadm;?>" size="65" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Departamento</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcoddep" type="text" id="txtcoddep" value="<?php print $ls_coddep;?>" size="19" maxlength="16" readonly>          <input name="txtdendep" type="text" class="sin-borde" id="txtdendep" value="<?php print $ls_dendep;?>" size="65" maxlength="100" readonly>
        </div></td>
      </tr>
	  <tr>
                <td height="22"><div align="right">Suspender Personal de N&oacute;mina</div></td>
                <td><input name="chksuspernom" type="checkbox" class="sin-borde" id="chksuspernom" value="1" <?php print $ls_susper;?>></td>
	  </tr>
	  <tr>
        <td height="20" colspan="4" class="titulo-celdanew">Informaci&oacute;n de Personal Encargado</td>
      </tr>  
	  <tr>
          <td height="22"><div align="right">N&oacute;mina</div></td>
          <td colspan="4"><?php $io_encargaduria->uf_cargarnomina($ls_codnomenc); ?></td>
        </tr>
		<tr>
        <td width="128" height="22"><div align="right">C&oacute;digo</div></td>
        <td colspan="3"><input name="txtcodperenc" type="text" id="txtcodperenc" value="<?php print $ls_codperenc;?>" size="13" maxlength="10" readonly>
            <a href="javascript: ue_buscarpersonal_encargado();"><img id="personal" src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="txtestperenc" type="text" class="sin-borde2" id="txtestperenc" value="<?php print $ls_estperenc;?>" size="20" maxlength="20" readonly></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Nombre y Apellido </div></td>
        <td colspan="3"><input name="txtnomperenc" type="text" class="sin-borde" id="txtnomperenc" value="<?php print $ls_nomperenc;?>" size="90" maxlength="120" readonly></td>
      </tr>
	  
	  
	  <?php
	  if ($ls_codnomenc!="")
	  {
		   if($li_subnominaenc=="1") {?>	  
      <tr>
        <td height="22"><div align="right">Subn&oacute;mina</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodsubnomenc" type="text" id="txtcodsubnomenc" value="<?php print $ls_codsubnomenc;?>" size="13" maxlength="10" readonly>
         
          <input name="txtdessubnomenc" type="text" class="sin-borde" id="txtdessubnomenc" value="<?php print $ls_dessubnomenc;?>" size="63" maxlength="60" readonly>
        </div></td>
      </tr>
	 <?php }
	  	   if($li_racenc=="0") {
	 ?>	  
      <tr>
        <td height="22"><div align="right">Cargo</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodcarenc" type="text" id="txtcodcarenc" value="<?php print $ls_codcarenc;?>" size="13" maxlength="10"  readonly>
     	  <input name="txtdescarenc" type="text" class="sin-borde" id="txtdescarenc" value="<?php print $ls_descarenc;?>" size="65" maxlength="100" readonly>
        </div></td>
      </tr>
	  <?php 
		 	   if(($li_tipnomenc=="3")||($li_tipnomenc=="4"))
			   {
		 ?>	  
      <tr>
        <td height="22"><div align="right">Clasificación Obrero</div></td>
        <td colspan="3"><div align="left">
          <input name="txtgradoenc" type="text" id="txtgradoenc" value="<?php print $ls_gradoenc;?>" size="13" maxlength="4"  readonly>
          
        </div></td>
      </tr>

		<?php
		     }
			 else
			 {
			 	 ?>	  
				  
					 <input name="txtgradoenc" type="hidden" id="txtgradoenc" value="<?php print $ls_gradoenc;?>" size="13" maxlength="4"  readonly>
			<?php	
			 }
		   }
	 	   else
		   {
	 
				if(($li_tipnomenc=="3")||($li_tipnomenc=="4"))
				{
			 ?>	         
					<tr>
					<td height="22"><div align="right">Asignaci&oacute;n de Cargo </div></td>
					<td colspan="3"><div align="left">
					  <input name="txtcodasicarenc" type="text" id="txtcodasicarenc" value="<?php print $ls_codasicarenc;?>" size="10" maxlength="7"  readonly>
					  
					  <input name="txtdenasicarenc" type="text" class="sin-borde" id="txtdenasicarenc" value="<?php print $ls_denasicarenc;?>" size="27" maxlength="24" readonly>
					</div></td>
					</tr>
					<tr>
					<td height="22"><div align="right">Clasificación Obrero</div></td>
					<td colspan="3"><div align="left">
					  <input name="txtgradoenc" type="text" id="txtgradoenc" value="<?php print $ls_gradoenc;?>" size="13" maxlength="4"  readonly>					 
					</div></td>
					</tr>
		 <?php 
				}
				else
				{
			?>
					<tr>
					<td height="22"><div align="right">Asignaci&oacute;n de Cargo </div></td>
					<td colspan="3"><div align="left">
					  <input name="txtcodasicarenc" type="text" id="txtcodasicarenc" value="<?php print $ls_codasicarenc;?>" size="10" maxlength="7"  readonly>
					  <input name="txtdenasicarenc" type="text" class="sin-borde" id="txtdenasicarenc" value="<?php print $ls_denasicarenc;?>" size="27" maxlength="24" readonly>
					</div></td>
					</tr>
				   
			<?php
				 }
			?>
	 <?php 
		if($li_tipnomenc!="3")
		 {
		   if ($li_tipnomenc!="4")
		   {
	  ?>
	   <?php 
		  if(($li_implementarcodunirac=="1")&&($li_racenc=="1")) {?>
      <tr>
        <td height="22"><div align="right">C&oacute;digo RAC </div></td>
        <td colspan="3">
          <input name="txtcoduniracenc" type="text" id="txtcoduniracenc" size="12" maxlength="10" onKeyUp="ue_validarcomillas(this);" value="<?php print $ls_coduniracenc;?>" readonly> </td>
      </tr>
	 <?php }	?>  
      <tr>
        <td height="22"><div align="right">Tabulador</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcodtabenc" type="text" id="txtcodtabenc" value="<?php print $ls_codtabenc;?>" size="25" maxlength="20" readonly>
          &nbsp;
            <input name="txtdestabenc" type="text" class="sin-borde" id="txtdestabenc" value="<?php print $ls_destabenc;?>" size="60" maxlength="100">
        </div></td>
      </tr>	  
      <tr>
        <td width="128"><div align="right">Grado
        </div></td>
        <td width="566"><input name="txtcodgraenc" type="text" id="txtcodgraenc" value="<?php print $ls_codgraenc;?>" size="18" maxlength="15" readonly>
         </td>
		 </tr>	  
      <tr>
        <td width="128"><div align="right">Paso</div></td>
        <td width="566"><div align="left">
			<input name="txtcodpasenc" type="text" id="txtcodpasenc" value="<?php print $ls_codpasenc;?>" size="18" maxlength="15" readonly>          
        </div></td>
      </tr>
	   <?php 
			 }// fin del if ($li_tipnom!="4")
		 }//fin del ($li_tipnom!="3")       
	}//fin del else ?>
     
      <tr>
        <td height="22"><div align="right">Unidad Administrativa </div></td>
        <td colspan="3"><div align="left">
          <input name="txtcoduniadmenc" type="text" id="txtcoduniadmenc" value="<?php print $ls_coduniadmenc;?>" size="19" maxlength="16" readonly>
      
          <input name="txtdesuniadmenc" type="text" class="sin-borde" id="txtdesuniadmenc" value="<?php print $ls_desuniadmenc;?>" size="65" maxlength="100" readonly>
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Departamento</div></td>
        <td colspan="3"><div align="left">
          <input name="txtcoddepenc" type="text" id="txtcoddepenc" value="<?php print $ls_coddepenc;?>" size="19" maxlength="16" readonly>          <input name="txtdendepenc" type="text" class="sin-borde" id="txtdendepenc" value="<?php print $ls_dendepenc;?>" size="65" maxlength="100" readonly>
        </div></td>
      </tr>
	 <?php }//fin del if?>
	  <tr>
        <td height="22"  colspan="3">&nbsp;</td>
	 </tr>
	 <tr>
       <td colspan="3"><div align="center">
            <input name="btncalenc" type="button" class="boton" id="btncalecn" value="Conceptos a Pagar por Encargadur&iacute;a" onClick="javascript: ue_calculo_encargaduria();" <?php print $ls_calenc;?>>
          </div></td>
	 </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td colspan="3"><input name="operacion" type="hidden" id="operacion">
          <input name="existe" type="hidden" id="existe" value="<?php print $ls_existe;?>">
          <input name="rac" type="hidden" id="rac" value="<?php print $li_rac;?>">
		  <input name="racenc" type="hidden" id="racenc" value="<?php print $li_racenc;?>">
          <input name="subnomina" type="hidden" id="subnomina" value="<?php print $li_subnomina;?>">
		  <input name="subnominaenc" type="hidden" id="subnominaenc" value="<?php print $li_subnominaenc;?>">
		  <input name="tipnomen" type="hidden" id="tipnomenc" value="<?php print $li_tipnomenc;?>">
          <input name="camuniadm" type="hidden" id="camuniadm" value="<?php print $li_camuniadm;?>">
          <input name="calculada" type="hidden" id="calculada" value="<?php print $li_calculada;?>">
          <input name="codunirac" type="hidden" id="codunirac" value="<?php print $li_implementarcodunirac;?>">		  
		  <input type="hidden" name="loncueban" id="loncueban" value="<?php print $li_loncueban;?>">
          <input type="hidden" name="valloncueban" id="valloncueban" value="<?php print $li_valloncueban;?>">
		  <input type="hidden" name="tiponom" id="tiponom" value="<?php print $li_tipnom;?>">
		  <input name="camdedtipper" type="hidden" id="camdedtipper" value="<?php print $li_camdedtipper;?>">		  </td>
      </tr>
    </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</form>      
<p>&nbsp;</p>
</body>
<script language="javascript">
function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_calculada=f.calculada.value;
	if(li_calculada=="0")
	{		
		if(li_incluir==1)
		{	
			f.operacion.value="NUEVO";
			f.existe.value="FALSE";		
			f.action="sigesp_sno_p_registrarencargaduria.php";
			f.submit();
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

function ue_guardar()
{
	valido=true;
	f=document.form1;
	li_calculada=f.calculada.value;
	estenc=f.txtestenc.value;
	if (estenc!='FINALIZADA')
	{
		if(li_calculada=="0")
		{		
			li_incluir=f.incluir.value;
			li_cambiar=f.cambiar.value;
			lb_existe=f.existe.value;
			if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
			{
				codper = ue_validarvacio(f.txtcodper.value);
				codperenc = ue_validarvacio(f.txtcodperenc.value);
				fecinienc = ue_validarvacio(f.txtfecinienc.value);	
				fecfinenc = f.txtfecfinenc.value;
				if ((codper!="")&&(codperenc!="")&&(fecinienc!='dd/mm/aaaa')&&(fecinienc!=''))
				{
					if ((fecfinenc!='dd/mm/aaaa')&&(fecfinenc!='01/01/1900')&&(fecfinenc!=''))
					{
						if(!ue_comparar_fechas(fecinienc,fecfinenc))
						{
							alert("La fecha de Finalizacion es menor que la Fecha de Inicio de la Encargaduría.");
							valido=false;
						}
					}
				
				}
				else
				{
					alert ("Debe llenar todos los campos");
					valido=false;
				}		
				if(codper==codperenc)
				{
					alert ("El código del Personal y el Código del Personal Encargado son iguales.");
					valido=false;
				}
				if(valido)
				{
						f.operacion.value="GUARDAR";
						f.action="sigesp_sno_p_registrarencargaduria.php";
						f.submit();			
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
	else
	{
		alert("La Encargaduria se encuentra en estatus Finalizada. No se puede modificar.");
	}
}


function ue_cerrar()
{
	location.href = "sigespwindow_blank_nomina.php";
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_sno_cat_registroencargaduria.php?tipo=REGISTRO+&subnom=<?php print $li_subnomina;?>","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_buscarpersonal()
{
	f=document.form1;
	if ((f.existe.value=="")||(f.existe.value=="FALSE"))
	{
		window.open("sigesp_sno_cat_personalnomina.php?tipo=encargaduria","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert ("No puede modificar esta el Personal");
	}
	
}

 function ue_buscarpersonal_encargado()
 {
 	f=document.form1;
	codnom=f.cmbnomina.value;
	if (codnom=="")
	{
		alert("Debe seleccionar una Nómina");
	}
	else
	{
		if ((f.existe.value=="")||(f.existe.value=="FALSE"))
		{
			window.open("sigesp_sno_cat_personalnomina.php?tipo=encargado"+"&codnom="+codnom,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
		}
		else
		{
			alert ("No puede modificar esta el Personal Encargado");
		}
		
	}
 }

function ue_cambio_nomina()
{
	f=document.form1;
	f.operacion.value="NOMINA";
	f.action="sigesp_sno_p_registrarencargaduria.php";
	f.submit();	
}

function ue_calculo_encargaduria()
{
	f=document.form1;
	codper = f.txtcodper.value;
	codperenc = f.txtcodperenc.value;
	codnomenc = f.txtcodnom.value;	
	nomperenc = f.txtnomperenc.value;
	nomper = f.txtnomper.value;
	
	window.open("sigesp_sno_pdt_calcularencargaduria.php?codper="+codper+"&codperenc="+codperenc+"&codnomenc="+codnomenc+"&nomper="+nomper+"&nomperenc="+nomperenc,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=790,height=600,left=50,top=50,location=no,resizable=no");
}


var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
</script> 
</html>