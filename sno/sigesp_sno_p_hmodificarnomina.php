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
	$io_fun_nomina->uf_load_seguridad("SNO","sigesp_sno_p_hmodificarnomina.php",$ls_permisos,$la_seguridad,$la_permisos);
	require_once("sigesp_sno.php");
	$io_sno=new sigesp_sno();
	$lb_valido=$io_sno->uf_crear_sessionhnomina();		
	unset($io_sno);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creación: 01/01/2006 								Fecha Última Modificación : 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   		global $ls_desnom,$ls_desper,$ls_codnom,$ls_nombre,$ls_consulnom,$ls_descomnom,$ls_codpronom,$ls_codbennom;
   		global $ls_conaponom,$ls_cueconnom,$ls_notdebnom,$ls_numvounom,$ls_recdocnom,$ls_tipdocnom,$ls_recdocapo,$ls_tipdocapo;
   		global $ls_perresnom,$ls_operacion,$ls_existe,$io_fun_nomina,$io_nomina,$ls_activo_contabilizacion;
		global $la_tippernom, $la_tipnom, $la_consulnom, $la_conaponom, $la_descomnom,$li_conta_global,$li_contabilizado, $ls_confidnom, $la_confidnom;
		global $ls_recdocfid, $ls_tipdocfid, $ls_codbenfid, $ls_cueconfid,$li_genrecdocpagperche,$ls_tipdocpagperche, $li_estctaalt;

		require_once("sigesp_sno_c_ajustarcontabilizacion.php");
		$io_ajustar=new sigesp_sno_c_ajustarcontabilizacion();
		$li_contabilizado=$io_ajustar->uf_contabilizado();
		unset($io_ajustar);
		$ls_desnom=$_SESSION["la_nomina"]["desnom"];
		$ls_desper=$_SESSION["la_nomina"]["descripcionperiodo"];
		$ls_codnom="";
		$ls_nombre="";
		$ls_consulnom="OCP";
		$la_consulnom[0]="";
		$la_consulnom[1]="";
		$la_consulnom[2]="";
		$la_consulnom[3]="";
		$ls_descomnom="";
		$la_descomnom[0]="";
		$la_descomnom[1]="";
		$la_descomnom[2]="";
		$ls_codpronom="----------";
		$ls_codbennom="----------";
		$ls_conaponom="OCP";
		$la_conaponom[0]="";
		$la_conaponom[1]="";
		$la_conaponom[2]="";
		$la_conaponom[3]="";
		$ls_cueconnom="";
		$ls_notdebnom="";
		$ls_numvounom="";
		$ls_recdocnom="";
		$ls_tipdocnom="";
		$ls_recdocapo="";
		$ls_tipdocapo="";
		$ls_perresnom="";
		$ls_cueconfid="";
		$ls_confidnom="OC"; 
		$la_confidnom[0]="";	
		$li_genrecdocpagperche=0;
		$li_estctaalt=0;
		$ls_tipdocpagperche="";	
		$ls_recdocfid="";
		$ls_tipdocfid="";
		$ls_codbenfid="----------";
		$ls_activo_contabilizacion="";
		$ls_operacion=$io_fun_nomina->uf_obteneroperacion();
		$ls_existe=$io_fun_nomina->uf_obtenerexiste();
		$li_conta_global=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I");
		if($li_conta_global=="0")
		{
			$li_estctaalt=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","UTILIZAR_CTA.CONTA_REC_DOC_PROV_BEN","0","I"));	
			$li_genrecdocpagperche=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO PAGO PERSONAL CHEQUE","0","I"));	
		    $ls_tipdocpagperche=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO PAGO PERSONAL CHEQUE","","C"));	
			$ls_consulnom=$io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION","OCP","C");
			$ls_notdebnom=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR NOTA DEBITO","1","I");
			$ls_recdocnom=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO","0","I");
			$ls_recdocapo=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO APORTE","0","I");
			$ls_recdocfid=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO FIDEICOMISO","0" ,"I");
			$ls_tipdocnom=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO NOMINA","","C");
			$ls_tipdocapo=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO APORTE","","C");
			$ls_tipdocfid=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO FIDEICOMISO","","C");
			$ls_conaponom=$io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION APORTES","OCP","C");
			$ls_cueconnom=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","CTA.CONTA","XXXXXXXXXXXXX","C"));
			$ls_cueconfid=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","CTA.CONTABLE_FIDEICOMISO","XXXXXXXXXXXXX","C"));
			$ls_descomnom=trim($io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION DESTINO","","C"));
			$ls_codbenfid=trim($io_nomina->io_sno->uf_select_config("SNO","NOMINA","DESTINO FIDEICOMISO","----------","C"));
			switch (substr($ls_descomnom,0,1))
			{
				case "P":
					$ls_codpronom=substr($ls_descomnom,1,strlen($ls_descomnom)-1);
					$ls_codbennom="----------";
					break;
					
				case "B":
					$ls_codbennom=substr($ls_descomnom,1,strlen($ls_descomnom)-1);
					$ls_codpronom="----------";
					break;
			}
			$ls_activo_contabilizacion="disabled";
		}		
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
		// Fecha Creación: 18/03/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codnom,$ls_nombre,$ls_consulnom,$ls_descomnom,$ls_codpronom,$ls_codbennom,$ls_conaponom,$ls_cueconnom;
   		global $ls_notdebnom,$ls_numvounom,$ls_recdocnom,$ls_tipdocnom,$ls_recdocapo,$ls_tipdocapo;
		global $io_fun_nomina,$io_nomina, $ls_confidnom, $ls_recdocfid, $ls_tipdocfid, $ls_cueconfid, $ls_codbenfid, $li_genrecdocpagperche, $ls_tipdocpagperche,$li_estctaalt;
		
		$ls_codnom=$_POST["txtcodnom"];
		$ls_nombre=$io_fun_nomina->uf_obtenervalor("txtdesnom","");
		$ls_tipdocpagperche=$io_fun_nomina->uf_obtenervalor("txttipdocpagper","");
		$li_genrecdocpagperche=$io_fun_nomina->uf_obtenervalor("chkgenrecdocpagper","0");
		$li_estctaalt=$io_fun_nomina->uf_obtenervalor("chkestctaalt","0");
		$li_conta_global=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","CONTA GLOBAL","0","I");
		if($li_conta_global=="0")
		{
			$li_estctaalt=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","UTILIZAR_CTA.CONTA_REC_DOC_PROV_BEN","0","I"));
			$li_genrecdocpagperche=trim($io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO PAGO PERSONAL CHEQUE","0","I"));
		    $ls_tipdocpagperche=trim($io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO PAGO PERSONAL CHEQUE","","C"));	
			$ls_consulnom=$io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION","OCP","C");
			$ls_notdebnom=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR NOTA DEBITO","1","I");
			$ls_recdocnom=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO","0","I");
			$ls_recdocfid=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO FIDEICOMISO","0" ,"I");			
			$ls_recdocapo=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","GENERAR RECEPCION DOCUMENTO APORTE","0","I");
			$ls_tipdocnom=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO NOMINA","","C");
			$ls_tipdocapo=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO APORTE","","C");
			$ls_tipdocfid=$io_nomina->io_sno->uf_select_config("SNO","CONFIG","TIPO DOCUMENTO FIDEICOMISO","","C");			
			$ls_conaponom=$io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION APORTES","OCP","C");
			$ls_cueconnom=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","CTA.CONTA","XXXXXXXXXXXXX","C"));
			$ls_cueconfid=trim($io_nomina->io_sno->uf_select_config("SNO","CONFIG","CTA.CONTABLE_FIDEICOMISO","XXXXXXXXXXXXX","C"));
			$ls_descomnom=trim($io_nomina->io_sno->uf_select_config("SNO","NOMINA","CONTABILIZACION DESTINO","","C"));
			$ls_codbenfid=trim($io_nomina->io_sno->uf_select_config("SNO","NOMINA","DESTINO FIDEICOMISO","----------","C"));			
			switch (substr($ls_descomnom,0,1))
			{
				case "P":
					$ls_codpronom=substr($ls_descomnom,1,strlen($ls_descomnom)-1);
					$ls_codbennom="----------";
					break;
					
				case "B":
					$ls_codbennom=substr($ls_descomnom,1,strlen($ls_descomnom)-1);
					$ls_codpronom="----------";
					break;
			}
		}
		else
		{
			$ls_consulnom=$_POST["cmbconsulnom"];
			$ls_descomnom=$_POST["cmbdesconnom"];
			switch (substr($ls_descomnom,0,1))
			{
				case "P":
					$ls_codpronom=$_POST["txtcodproben"];
					$ls_codbennom="----------";
					break;
					
				case "B":
					$ls_codbennom=$_POST["txtcodproben"];
					$ls_codpronom="----------";
					break;
			}
			$ls_conaponom=$_POST["cmbconaponom"];
			$ls_cueconnom=$_POST["txtcueconnom"];
			$ls_notdebnom=$io_fun_nomina->uf_obtenervalor("chknotdebnom","0");
			$ls_numvounom="0";
			$ls_recdocnom=$io_fun_nomina->uf_obtenervalor("chkrecdocnom","0");
			$ls_tipdocnom=$_POST["txttipdocnom"];
			$ls_recdocapo=$io_fun_nomina->uf_obtenervalor("chkrecdocapo","0");
			$ls_tipdocapo=$_POST["txttipdocapo"];
			$ls_codbenfid=$_POST["txtcodbenfid"];
			$ls_confidnom=$_POST["cmbconfidnom"];
			$ls_recdocfid=$io_fun_nomina->uf_obtenervalor("chkrecdocfid","0");
			$ls_tipdocfid=$_POST["txttipdocfid"];
			$ls_cueconfid=$_POST["txtcueconfid"];
		}
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
<title>Modificar N&oacute;minas Hist&oacute;rias</title>
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
<link href="css/nomina.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_nomina.js"></script>
<style type="text/css">
<!--
.Estilo2 {font-size: 9px}
-->
</style>
</head>
<body>
<?php 
	require_once("sigesp_snorh_c_nominas.php");
	$io_nomina=new sigesp_snorh_c_nominas();
	uf_limpiarvariables();
	$ld_ano=substr($_SESSION["la_empresa"]["periodo"],0,4);
	$ld_fechasnom=substr($_SESSION["la_nomina"]["fechasper"],0,4);
	if($ld_fechasnom!=$ld_ano)
	{
		print("<script language=JavaScript>");
		print(" alert('Este proceso esta desactivo para Períodos de años Diferentes al Periodo de la Empresa.');");
		print(" location.href='sigespwindow_blank_hnomina.php'");
		print("</script>");
	}	
	switch ($ls_operacion) 
	{
		case "NUEVO":
			$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_consulnom,$la_consulnom,4);	
			$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_conaponom,$la_conaponom,4);	
			$io_fun_nomina->uf_seleccionarcombo("OC",$ls_confidnom,$la_confidnom,1);	
			$io_fun_nomina->uf_seleccionarcombo(" -P-B",substr($ls_descomnom,0,1),$la_descomnom,3);	
			break;

		case "GUARDAR":
			uf_load_variables();
																							
			$lb_valido=$io_nomina->uf_update_nomina_historico($ls_codnom,$ls_consulnom,substr($ls_descomnom,0,1),
															  $ls_codpronom,$ls_codbennom,$ls_conaponom,$ls_cueconnom,
											  		 		  $ls_notdebnom,$ls_numvounom,$ls_recdocnom,$ls_tipdocnom,
											  		 		  $ls_recdocapo,$ls_tipdocapo,$ls_confidnom,$ls_recdocfid,
															  $ls_tipdocfid,$ls_codbenfid,$ls_cueconfid,$li_genrecdocpagperche,
															  $ls_tipdocpagperche,$li_estctaalt,$la_seguridad);
			if($lb_valido)
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			else
			{
				$io_fun_nomina->uf_seleccionarcombo("0-1-2-3",$ls_tippernom,$la_tippernom,4);			
				$io_fun_nomina->uf_seleccionarcombo("1-2-3-4-5-6-7-8-9",$ls_tipnom,$la_tipnom,9);	
				$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_consulnom,$la_consulnom,4);	
				$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_conaponom,$la_conaponom,4);	
				$io_fun_nomina->uf_seleccionarcombo("OC",$ls_confidnom,$la_confidnom,1);	
				$io_fun_nomina->uf_seleccionarcombo(" -P-B",substr($ls_descomnom,0,1),$la_descomnom,3);	
			}
			break;

		case "BUSCAR":
			$ls_codnom=$_POST["txtcodnom"];
			$li_total="1";
			$ls_informa="";
			$lb_valido=$io_nomina->load_nomina_historico($li_conta_global,$ls_existe,$ls_codnom,$ls_nombre,								  
	   											   		 $ls_consulnom,$ls_descomnom,$ls_codpronom,$ls_codbennom,
											   			 $ls_conaponom,$ls_cueconnom,$ls_notdebnom,$ls_numvounom,
											   			 $ls_recdocnom,$ls_tipdocnom,$ls_recdocapo,$ls_tipdocapo,
														 $ls_confidnom,$ls_recdocfid,$ls_tipdocfid,$ls_codbenfid,
														 $ls_cueconfid,$ls_informa,$li_genrecdocpagperche,
														 $ls_tipdocpagperche,$li_estctaalt);
			if($lb_valido)
			{
				$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_consulnom,$la_consulnom,4);	
				$io_fun_nomina->uf_seleccionarcombo("CP-OCP-OC-O",$ls_conaponom,$la_conaponom,4);	
				$io_fun_nomina->uf_seleccionarcombo("OC",$ls_confidnom,$la_confidnom,1);	
				$io_fun_nomina->uf_seleccionarcombo(" -P-B",substr($ls_descomnom,0,1),$la_descomnom,3);	
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
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7"><table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			<td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema"><?php print $ls_desnom;?></td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><?php print $ls_desper;?></span></div></td>
			 <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td></tr>
	  </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu_hnomina.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" title='Guardar 'alt="Grabar" width="20" height="20" border="0"></a></div></td>
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
  <table width="760" height="223" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td>
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_nomina->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank_hnomina.php'");
	unset($io_fun_nomina);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<p>&nbsp;</p>
<table width="710" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
		  <tr class="titulo-ventana">
			<td height="20" colspan="4">Modificar N&oacute;minas Hist&oacute;ricas </td>
		  </tr>
<tr>
			<td height="22"><div align="right" >
				<p>Codigo</p>
			</div></td>
			<td colspan="3"><div align="left" >
			  <input name="txtcodnom" type="text" id="txtcodnom" value="<?php print $ls_codnom;?>" size="6" maxlength="4" onKeyUp="javascript: ue_validarnumero(this);" onBlur="javascript: ue_rellenarcampo(this,4);" readonly>
              <a href="javascript: ue_buscarnomina();"><img id="concepto" src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Concepto"></a></div></td>
          </tr>
              <tr >
                <td height="22"><div align="right">Denominaci&oacute;n</div></td>
                <td colspan="3"><div align="left">
                  <input name="txtdesnom" type="text" id="txtdesnom" value="<?php print $ls_nombre;?>" onKeyPress="javascript: ue_validarcomillas(this);" size="70" maxlength="100" readonly>
                </div></td>
              </tr>
			<tr class="titulo-celdanew">
                <td height="20" colspan="4"><div align="center" class="titulo-celdanew">Configuraci&oacute;n Contabilizaci&oacute;n de N&oacute;mina </div></td>
            </tr>
				<tr>
				  <td width="129" height="22"><div align="right">N&oacute;mina</div></td>
				  <td height="22" colspan="3"><select name="cmbconsulnom" id="cmbconsulnom" onChange="javascript: ue_contabilizacionnomina();" <?php print $ls_activo_contabilizacion;?>>
                    <option value="CP" <?php print $la_consulnom[0]; ?>>Causar y Pagar</option>
                    <option value="OCP" <?php print $la_consulnom[1]; ?>>Compromete, Causa y Paga</option>
                    <option value="OC" <?php print $la_consulnom[2]; ?>>Compromete y Causa</option>
                    <option value="O" <?php print $la_consulnom[3]; ?>>Compromete</option>
                  </select>	</td>
		  </tr>
				<tr>
				  <td height="22"><div align="right">Generar Recepci&oacute;n de Documento a la N&oacute;mina</div></td>
				  <td height="22" colspan="3"><div align="left">
				    <input name="chkrecdocnom" type="checkbox" class="sin-borde" id="chkrecdocnom" value="1" onChange="javascript: ue_recepcionnomina();" <?php if($ls_recdocnom=="1"){ print " checked ";} print $ls_activo_contabilizacion;?>>
			      </div></td>
		  </tr>
				<tr>
				  <td height="22"><div align="right">Tipo de Documento N&oacute;mina</div></td>
				  <td height="22" colspan="3">
				    <div align="left">
				      <input name="txttipdocnom" type="text" id="txttipdocnom" value="<?php print $ls_tipdocnom;?>" readonly>
			        <a href="javascript: ue_buscartipodocumento('NOMINA');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> </div>				    <div align="right"></div>			        <div align="left"></div></td>
			    </tr>
              <tr>
                <td height="22"><div align="right">
                  <div align="right">Cuenta Contable</div>
                </div></td>
                <td width="193" height="22"><div align="left">
                  <input name="txtcueconnom" type="text" id="txtcueconnom" value="<?php print $ls_cueconnom;?>" readonly>
                  <a href="javascript: ue_buscarcuentacontable('NOMINA');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
                <td width="174" height="22"><div align="right">Generar Nota D&eacute;bito en bancos</div></td>
                <td width="204" height="22"><div align="left">
                  <input name="chknotdebnom" type="checkbox" class="sin-borde" id="chknotdebnom" value="1" onChange="javascript: ue_notadebito();" <?php if($ls_notdebnom=="1"){ print " checked ";} print $ls_activo_contabilizacion;?>>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Destino Contabilizaci&oacute;n</div></td>
                <td height="22" colspan="3"><div align="left">
                  <select name="cmbdesconnom" id="cmbdesconnom" onChange="javascript: ue_limpiar();" <?php print $ls_activo_contabilizacion; ?>>
                    <option value=" " <?php print $la_descomnom[0]; ?>> </option>
                    <option value="P" <?php print $la_descomnom[1]; ?>>PROVEEDOR</option>
                    <option value="B" <?php print $la_descomnom[2]; ?>>BENEFICIARIO</option>
                  </select>
                  <input name="txtcodproben" type="text" id="txtcodproben" value="<?php if(substr($ls_descomnom,0,1)=="P"){ print $ls_codpronom;} if(substr($ls_descomnom,0,1)=="B"){ print $ls_codbennom;} ?>" readonly>
                  <a href="javascript: ue_buscardestino();"><img  src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                  <input name="txtnombre" type="text" class="sin-borde" id="txtnombre" size="50" maxlength="30" readonly>
</div>                  <div align="right"></div>                  <div align="left"><a href="javascript: ue_buscartipodocumento('NOMINA');"></a></div></td>
              </tr>
			  
			   <tr>
		   <td height="22"><div align="right">Utilizar Cuenta Contable para el registro del Gasto por pagar</div>          </td>
		  <td>
            <div align="left">
              <input name="chkestctaalt" type="checkbox" class="sin-borde" id="chkestctaalt" value="1" <?php  if($li_estctaalt=="1"){print "checked";} ?>  onClick="javascript:ue_chequear_nomina_beneficiario();">          
            </div></td>			
			<tr> 
			  
			    <tr>
		   <td height="22"><div align="right">Generar Recepci&oacute;n de Documento para el Pago del Personal con Cheque</div>          </td>
		  <td>
            <div align="left">
              <input name="chkgenrecdocpagper" type="checkbox" class="sin-borde" id="chkgenrecdocpagper" value="1" <?php  if($li_genrecdocpagperche=="1"){print "checked";} print $ls_activo_contabilizacion;?>>          
            </div></td>
			<td height="22"><div align="right">Tipo de Documento del Pago de Personal</div></td>
		  <td>
		    <input name="txttipdocpagper" type="text" id="txttipdocpagper" value="<?php print $ls_tipdocpagperche;?>" readonly>
		    <a href="javascript: ue_buscartipodocumento('PAGOPERSONAL');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> </td>
		  </tr>
                <tr class="titulo-celdanew">
                  <td height="22" colspan="4"><div align="center" class="titulo-celdanew">Configuraci&oacute;n Contabilizaci&oacute;n de Aportes </div></td>
                </tr>
              <tr>
                <td height="22"><div align="right">Aportes</div></td>
                <td height="22" colspan="3"><div align="left">
                  <select name="cmbconaponom" id="cmbconaponom" onChange="javascript: ue_contabilizacionaportes();"  <?php print $ls_activo_contabilizacion; ?>>
                    <option value="CP" <?php print $la_conaponom[0]; ?>>Causar y Pagar</option>
                    <option value="OCP" <?php print $la_conaponom[1]; ?>>Compromete, Causa y Paga</option>
                    <option value="OC" <?php print $la_conaponom[2]; ?>>Compromete y Causa</option>
                    <option value="O" <?php print $la_conaponom[3]; ?>>Compromete</option>
                  </select>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Generar Recepci&oacute;n de Documento a los aportes</div></td>
                <td height="22" colspan="3"><div align="left">
                  <input name="chkrecdocapo" type="checkbox" class="sin-borde" id="chkrecdocapo" value="1" onChange="javascript: ue_recepcionaportes();"  <?php if($ls_recdocapo=="1"){ print " checked ";} print $ls_activo_contabilizacion; ?>>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Tipo de Documento Aporte</div></td>
                <td height="22" colspan="3"><div align="left">
                  <input name="txttipdocapo" type="text" id="txttipdocapo" value="<?php print $ls_tipdocapo;?>" readonly>
                <a href="javascript: ue_buscartipodocumento('APORTE');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
              </tr>
              <tr>
                <td height="22" colspan="4" class="titulo-celdanew">C&oacute;nfiguraci&oacute;n Prestaci&oacute;n Antiguedad </td>
              </tr>
              <tr>
                <td height="22"><div align="right">Prestaci&oacute;n Antiguedad </div></td>
                <td height="22" colspan="3"><div align="left">
                  <select name="cmbconfidnom" id="cmbconfidnom" onChange="javascript: ue_contabilizacionfideicomiso();" <?php print $ls_activo_contabilizacion; ?>>
                    <option value="OC" <?php print $la_confidnom[0]; ?>>Compromete y Causa</option>
                  </select>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Generar Recepcion de Documentos </div></td>
                <td height="22"><div align="left">
                  <input name="chkrecdocfid" type="checkbox" class="sin-borde" id="chkrecdocfid" value="1" onChange="javascript: ue_recepcionfideicomiso();" <?php if($ls_recdocfid=="1"){ print " checked ";} print $ls_activo_contabilizacion;?>>
                </div></td>
                <td height="22"><div align="right">Tipo de Documento </div></td>
                <td height="22"><div align="left">
                  <input name="txttipdocfid" type="text" id="txttipdocfid" value="<?php print $ls_tipdocfid;?>" readonly>
                <a href="javascript: ue_buscartipodocumento('FIDEICOMISO');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a> </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Beneficiario</div></td>
                <td height="22" colspan="3"><div align="left">
                  <div align="left">
                    <input name="txtcodbenfid" type="text" id="txtcodbenfid" value="<?php print $ls_codbenfid; ?>" readonly>
                    <a href="javascript: ue_buscarbeneficiario();"><img  src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a>
                  </div>
                </div></td>
              </tr>
              <tr>
                <td height="22"><div align="right">Cuenta Contable </div></td>
                <td height="22" colspan="3"><div align="left">
                  <input name="txtcueconfid" type="text" id="txtcueconfid" value="<?php print $ls_cueconfid;?>" readonly>
                <a href="javascript: ue_buscarcuentacontable('FIDEICOMISO');"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="15" height="15" border="0"></a></div></td>
              </tr>
              <tr>
                <td height="22">&nbsp;</td>
                <td height="22" colspan="3">&nbsp;</td>
              </tr>
              <tr>
                <td height="22">&nbsp;</td>
                <td height="22" colspan="3"><div align="left"></div></td>
              </tr>
          </table>
            <p align="center">
              <input name="operacion" type="hidden" id="operacion">
              <input name="existe" type="hidden" id="existe" value="<?php  print $ls_existe; ?>">
              <input name="activo" type="hidden" id="activo" value="<?php  print $ls_activo_contabilizacion; ?>">
			  <input name="contabilizado" type="hidden" id="contabilizado" value="<?php print $li_contabilizado;?>">
          </p>
        </td>
      </tr>
  </table>
</form>  
</div>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_guardar()
{
	f=document.form1;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	if(f.contabilizado.value=="0")
	{
		if(f.activo.value=="")
		{
			if((lb_existe=="TRUE")&&(li_cambiar==1))
			{
				codnom = ue_validarvacio(f.txtcodnom.value);
				desnom = ue_validarvacio(f.txtdesnom.value);
				desconnom = ue_validarvacio(f.cmbdesconnom.value);
				codproben = ue_validarvacio(f.txtcodproben.value);
				if ((codnom!="")&&(desnom!="")&&(desconnom!="")&&(codproben!=""))
				{
					f.operacion.value="GUARDAR";
					f.action="sigesp_sno_p_hmodificarnomina.php";
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
		else
		{
			alert("La Configuración de la Contabilización de las Nóminas esta global. No se puede hacer ningún cambio.");
		}
	}
	else
	{
		alert("El período está contabilizado. no se puede hacer ningún cambio.");
	}
}

function ue_cerrar()
{
	location.href = "sigespwindow_blank_hnomina.php";
}

function ue_buscarnomina()
{	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_snorh_cat_nomina.php?tipo=HISTORICO","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_ayuda()
{
	width=(screen.width);
	height=(screen.height);
	//window.open("../hlp/index.php?sistema=SNO&subsistema=SNR&nomfis=sno/sigesp_hlp_snr_profesion.php","Ayuda","menubar=no,toolbar=no,scrollbars=yes,width="+width+",height="+height+",resizable=yes,location=no");
}

function ue_contabilizacionnomina()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		f.chkrecdocnom.checked=false;
		f.txttipdocnom.value="";
		f.txtcueconnom.value="";
		f.chknotdebnom.checked=false;
	}
}

function ue_recepcionnomina()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		consulnom=ue_validarvacio(f.cmbconsulnom.value);
		if((consulnom!="OC"))
		{
			f.chkrecdocnom.checked=false;
		}
		else
		{
			f.txttipdocnom.value="";
			f.txtcueconnom.value="";
		}
	}
}

function ue_buscartipodocumento(tipo)
{
	f=document.form1;
	valido=false;
	if(f.activo.value=="")
	{
		if(tipo=="NOMINA")
		{
			if(f.chkrecdocnom.checked)
			{
				valido=true;
			}
		}
		if(tipo=="APORTE")
		{
			if(f.chkrecdocapo.checked)
			{
				valido=true;
			}
		}
		if(tipo=="FIDEICOMISO")
		{
			if(f.chkrecdocfid.checked)
			{
				valido=true;
			}
		}
		if(tipo=="PAGOPERSONAL")
		{
			if(f.chkgenrecdocpagper.checked)
			{
				valido=true;
			}
		}
	}
	if(valido)
	{
		window.open("sigesp_snorh_cat_tipodocumento.php?tipo="+tipo+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_buscarcuentacontable(tipo)
{
	f=document.form1;
	if(f.activo.value=="")
	{
		if(tipo=='NOMINA')
		{
			if(f.chkrecdocnom.checked==false)
			{
				consulnom=ue_validarvacio(f.cmbconsulnom.value);
				if((consulnom=="OC"))
				{
					window.open("sigesp_sno_cat_cuentacontable.php?tipo="+tipo,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
				}
			}
		}
		else
		{
			if(f.chkrecdocfid.checked==false)
			{
				window.open("sigesp_sno_cat_cuentacontable.php?tipo="+tipo,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}
		}
	}
}

function ue_notadebito()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		consulnom=ue_validarvacio(f.cmbconsulnom.value);
		if((consulnom=="OCP")||(consulnom=="CP"))
		{
			//f.chknotdebnom.checked=true;
		}
		else
		{
			f.chknotdebnom.checked=false;	
		}
	}
}

function ue_buscardestino()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		descon=ue_validarvacio(f.cmbdesconnom.value);
		if(descon!="")
		{
			if(descon=="P")
			{
				window.open("sigesp_catdinamic_prove.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}
			else
			{
				window.open("sigesp_catdinamic_bene.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
			}	
		}
		else
		{
			alert("Debe seleccionar un destino de Contabilización.");
		}
	}
}

function ue_limpiar()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		f.txtcodproben.value="";
		f.txtnombre.value="";
	}
}

function ue_contabilizacionaportes()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		f.chkrecdocapo.checked=false;
		f.txttipdocapo.value="";
	}
}

function ue_recepcionaportes()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		conaponom=ue_validarvacio(f.cmbconaponom.value);
		if((conaponom!="OC"))
		{
			f.chkrecdocapo.checked=false;
			f.txttipdocapo.value="";
		}
		else
		{
			f.txttipdocapo.value="";
		}
	}
}

function ue_contabilizacionfideicomiso()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		f.chkrecdocfid.checked=false;
		f.txttipdocfid.value="";
	}
}

function ue_recepcionfideicomiso()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		confidnom=ue_validarvacio(f.cmbconfidnom.value);
		if((confidnom!="OC"))
		{
			f.chkrecdocfid.checked=false;
		}
		else
		{
			f.txttipdocfid.value="";
			f.txtcueconfid.value="";
		}
	}
}

function ue_buscarbeneficiario()
{
	f=document.form1;
	if(f.activo.value=="")
	{
		window.open("sigesp_catdinamic_bene.php?tipo=FIDEICOMISO","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
}

function ue_chequear_nomina_beneficiario()
{
	f=document.form1;	
	if(((f.cmbconsulnom.value!="OC")||(f.chkrecdocnom.checked==false))&&(f.chkestctaalt.checked))
	{
		alert("Esta Opción es valida solo para Nóminas Compromete y Causa que Generen Recepción de Documento.");
		f.chkestctaalt.checked=false;
	}
}

</script>
<script language="javascript1.2" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>