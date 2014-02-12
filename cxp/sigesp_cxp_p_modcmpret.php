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
	require_once("class_folder/class_funciones_cxp.php");
	$io_fun_cxp=new class_funciones_cxp();
	$io_fun_cxp->uf_load_seguridad("CXP","sigesp_cxp_p_modcmpret.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////

   //--------------------------------------------------------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 19/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_estatus,$ls_dirsujret,$ls_nomsujret,$ls_operacion,$ls_existe,$ls_codret,$ls_mes,$ls_indice;
		global $ls_totrowrecepciones,$io_fun_cxp,$ls_numcom,$ls_parametros,$ls_rif,$ls_ano,$ls_codigo;
		
		$ls_estatus="EMITIDO";
		$ls_numcom="";
		$ls_ano="";
		$ls_mes="";
		$ls_codigo="";
		$ls_rif="";
		$ls_nomsujret="";
		$ls_dirsujret="";
		$ls_codret="";
		$ls_indice="";
		$ls_parametros="";
		$ls_operacion=$io_fun_cxp->uf_obteneroperacion();
		$ls_existe=$io_fun_cxp->uf_obtenerexiste();
		$ls_totrowrecepciones=0;
   }
   //--------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 23/04/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_estsol,$ls_numcom,$ls_ano,$ls_mes,$ls_codigo,$ls_rif,$ls_nomsujret,$ls_dirsujret,$ls_codret,$ls_probene;
		global $io_fun_cxp,$li_totrowrecepciones,$ls_indice;
		
		$ls_estsol=$_POST["txtestatus"];
		$ls_numcom=$_POST["txtnumcom"];
		$ls_mes=$_POST["cmbmes"];
		$ls_ano=$_POST["txtano"];
		$ls_codigo=$_POST["txtcodigo"];
		$ls_rif=$_POST["txtrif"];
		$ls_nomsujret=$_POST["txtnomsujret"];
		$ls_dirsujret=$_POST["txtdirsujret"];
		$ls_codret=$_POST["cmbtipo"];
		$ls_probene=$_POST["txtprobene"];
		$ls_indice=$_POST["txtindice"];
		$li_totrowrecepciones=$_POST["totrowrecepciones"];
   }
   //--------------------------------------------------------------------------------------------------------------

   //--------------------------------------------------------------------------------------------------------------
   function uf_load_data(&$as_parametros)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Función que carga todas las variables necesarias en la página
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creación: 17/03/2007								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		global $li_totrowrecepciones,$io_fun_cxp;	
			
		for($li_i=1;$li_i<$li_totrowrecepciones;$li_i++)
		{
			$ls_codret=trim($io_fun_cxp->uf_obtenervalor("txtcodret".$li_i,""));
			$ls_numope=trim($io_fun_cxp->uf_obtenervalor("txtnumope".$li_i,""));
			$ls_fecfac=trim($io_fun_cxp->uf_obtenervalor("txtfecfac".$li_i,""));
			$ls_numfac=trim($io_fun_cxp->uf_obtenervalor("txtnumfac".$li_i,""));
			$ls_numcon=trim($io_fun_cxp->uf_obtenervalor("txtnumcon".$li_i,""));
			$ls_numnd=trim($io_fun_cxp->uf_obtenervalor("txtnumnd".$li_i,""));
			$ls_numnc=trim($io_fun_cxp->uf_obtenervalor("txtnumnc".$li_i,""));
			$ls_tiptrans=trim($io_fun_cxp->uf_obtenervalor("txttiptrans".$li_i,""));
			$ls_tot_cmp_sin_iva=trim($io_fun_cxp->uf_obtenervalor("txttotsiniva".$li_i,""));
			$ls_tot_cmp_con_iva=trim($io_fun_cxp->uf_obtenervalor("txttotconiva".$li_i,""));
			$ls_basimp=trim($io_fun_cxp->uf_obtenervalor("txtbasimp".$li_i,""));
			$ls_porimp=trim($io_fun_cxp->uf_obtenervalor("txtporimp".$li_i,""));
			$ls_totimp=trim($io_fun_cxp->uf_obtenervalor("txttotimp".$li_i,""));
			$ls_ivaret=trim($io_fun_cxp->uf_obtenervalor("txtivaret".$li_i,""));
			$ls_numsop=trim($io_fun_cxp->uf_obtenervalor("txtnumsop".$li_i,""));
			$ls_numdoc=trim($io_fun_cxp->uf_obtenervalor("txtnumdoc".$li_i,""));
			
			$as_parametros=$as_parametros."&txtcodret".$li_i."=".$ls_codret."&txtnumope".$li_i."=".$ls_numope."&txtfecfac".$li_i."=".$ls_fecfac.
			               "&txtnumfac".$li_i."=".$ls_numfac."&txtnumcon".$li_i."=".$ls_numcon."&txtnumnd".$li_i."=".$ls_numnd.
						   "&txtnumnc".$li_i."=".$ls_numnc."&txttiptrans".$li_i."=".$ls_tiptrans."&txttotsiniva".$li_i."=".$ls_tot_cmp_sin_iva.
						   "&txttotconiva".$li_i."=".$ls_tot_cmp_con_iva."&txtbasimp".$li_i."=".$ls_basimp."&txtporimp".$li_i."=".$ls_porimp.
						   "&txttotimp".$li_i."=".$ls_totimp."&txtivaret".$li_i."=".$ls_ivaret."&txtnumsop".$li_i."=".$ls_numsop.
						   "&txtnumdoc".$li_i."=".$ls_numdoc;
		}
		$as_parametros=$as_parametros."&totrowrecepciones=".$li_totrowrecepciones."";
   }
   //--------------------------------------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Comprobante de Retencion</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="javascript" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
<meta http-equiv="Content-Type" content="text/html; charset="><style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
</style></head>
<body>
<?php
    require_once("../shared/class_folder/class_mensajes.php");
	$io_msg=new class_mensajes();
	require_once("class_folder/sigesp_cxp_c_cmp_retencion.php");
	$io_cmpret=new sigesp_cxp_c_cmp_retencion("../");
	require_once("class_folder/sigesp_cxp_c_modcmpret.php");
	$io_modcmpret=new sigesp_cxp_c_modcmpret("../");
	
	require_once("../shared/class_folder/sigesp_include.php");
	$in=  new sigesp_include();
	$con= $in->uf_conectar();
	require_once("../shared/class_folder/class_sql.php");
	$io_sql= new class_sql($con);
	$ls_basdatcmp=$_SESSION["la_empresa"]["basdatcmp"];
	if($ls_basdatcmp!="")
	{
		$io_modcmpret->io_sqlaux=$io_cmpret->io_sqlaux;
		$io_sqlaux=$io_cmpret->io_sqlaux;
	}
	uf_limpiarvariables();
	$ls_basdatcmp=$_SESSION["la_empresa"]["basdatcmp"];
	switch($ls_operacion)
	{
		case "NEW":
		  uf_load_variables();
		  $ls_ano=date('Y');
	      $ls_mes=date('m');
		  $io_cmpret->uf_get_nrocomprobante($ls_codret,$ls_ano.$ls_mes,&$ls_numcom);
		  uf_load_data(&$ls_parametros);
		break;

		case "GUARDAR":
			uf_load_variables();
			$io_sql->begin_transaction();
  		    $lb_flag=true;
			if($ls_existe=="FALSE")
			{
				$ls_fecha=date('Y-m-d');
				if($ls_basdatcmp!="")
				{
					$lb_flag=$io_cmpret->uf_crear_comprobante_consolida($ls_codret,&$ls_numcom,$ls_fecha,$ls_ano.$ls_mes,$ls_codigo,
																		$ls_nomsujret,$ls_dirsujret,$ls_rif,"","1",$ls_logusr,"",
																		"M",$la_seguridad);
				}
				$lb_flag=$io_cmpret->uf_crear_comprobante($ls_codret,$ls_numcom,$ls_fecha,$ls_ano.$ls_mes,$ls_codigo,$ls_nomsujret,
														  $ls_dirsujret,$ls_rif,"","1",$ls_logusr,"","M",$la_seguridad);
			}
			if($lb_flag)
			{
				 // $lb_flag=$io_modcmpret->uf_liberar_rd($ls_codret,$ls_probene,$ls_codigo,$li_totrowrecepciones);
				 $lb_flag=$io_modcmpret->uf_liberar_recepciones($ls_codret,$ls_numcom,$ls_probene,$ls_codigo);
			}
			if($lb_flag)
			{
			  $lb_flag=$io_modcmpret->uf_update_cmpret($ls_numcom,$ls_codret, $li_totrowrecepciones,$ls_probene,$ls_codigo, $la_seguridad);
			}
//			$lb_flag=false;
			if($lb_flag)
			{
		    	$io_msg->message("El comprobante se proceso satisfactoriamente");
				$io_sql->commit();
			}
			else
			{
		    	$io_msg->message("Ocurrio un error al procesar el comprobante");
				$io_sql->rollback();
			}
			uf_load_data(&$ls_parametros);
			
		break;

		case "ELIMINAR":
		    uf_load_variables();
			$io_sql->begin_transaction();
			$ls_bdorigen=$io_modcmpret->uf_obtener_bdorigen($ls_numcom,$ls_codret);
			$lb_ulitmo=$io_modcmpret->uf_buscar_ultimo($ls_numcom,$ls_codret);
			if(($lb_ulitmo)&&($ls_bdorigen==""))
			{
			   $lb_flag=$io_modcmpret->uf_delete_cmpret($ls_numcom,$ls_codret,$la_seguridad);
			   if($lb_flag)
			    {
				  $lb_flag=$io_modcmpret->uf_liberar_rd($ls_codret,$ls_probene,$ls_codigo,$li_totrowrecepciones);
				  if($lb_flag)
				  {
				    $io_msg->message("El comprobante fue eliminado fisicamente, por ser el último registro!!");
					$io_sql->commit();
					uf_limpiarvariables();
				  }
				  else
				  {
				 	$io_msg->message("Se genero un problema al eliminar la retencion");
				   	uf_limpiarvariables();
					$io_sql->rollback();
				  }
				}
			   
			 }
			 else
			 {
			    $lb_valido=$io_modcmpret->uf_anular_cmpret($ls_numcom,$la_seguridad);
				if(($lb_valido)&&($ls_bdorigen!=""))
				{
					$lb_valido=$io_modcmpret->uf_anular_cmpret_consolida($ls_codret,$ls_numcom,$ls_probene,$ls_codigo);

				}
				if($lb_valido)
				{
					$lb_valido=$io_modcmpret->uf_liberar_rd($ls_codret,$ls_probene,$ls_codigo,$li_totrowrecepciones);
				}
				if($lb_valido)
				{
					$io_msg->message("El comprobante fue anulado, ya que no es el ultimo registro!!");
					uf_limpiarvariables();
					$io_sql->commit();
				}
				else
				{
					$io_msg->message("Se genero un problema al anular la retencion");
					uf_limpiarvariables();
					$io_sql->rollback();
				}
			 }
            uf_load_data(&$ls_parametros);
		break;
	}
?>
<table width="799" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="808" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7"><table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
        <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Cuentas por Pagar </td>
            <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque&ntilde;as"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
        <tr>
          <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
          <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
    </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="29" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" title="Nuevo"></a></div></td>
    <td class="toolbar" width="29"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0" title="Guardar"></a></div></td>
    <td class="toolbar" width="29"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" title="Buscar"></a></div></td>
    <td class="toolbar" width="29"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0" title="Eliminar"></a></div></td>
    <td class="toolbar" width="29"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></td>
    <td class="toolbar" width="29"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="29"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
    <td class="toolbar" width="594">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
  <form name="formulario" method="post" action="" id="formulario">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
    <table width="726" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="790"  height="136"><p>&nbsp;</p>
            <table width="721" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td colspan="4" class="titulo-ventana"> Comprobante de Retenci&oacute;n </td>
              </tr>
              <tr>
                <td height="22">&nbsp;</td>
                <td height="22">&nbsp;</td>
                <td height="22">&nbsp;</td>
                <td height="22">&nbsp;</td>
              </tr>
              <tr>
                <td width="147" height="22"><div align="right">Periodo</div></td>
                <td width="240" height="22"><label>
                  <select name="cmbmes" id="cmbmes">
          <option value="01" <?php if($ls_mes=="01"){ print "selected";} ?>>ENERO</option>
          <option value="02" <?php if($ls_mes=="02"){ print "selected";} ?>>FEBRERO</option>
          <option value="03" <?php if($ls_mes=="03"){ print "selected";} ?>>MARZO</option>
          <option value="04" <?php if($ls_mes=="04"){ print "selected";} ?>>ABRIL</option>
          <option value="05" <?php if($ls_mes=="05"){ print "selected";} ?>>MAYO</option>
          <option value="06" <?php if($ls_mes=="06"){ print "selected";} ?>>JUNIO</option>
          <option value="07" <?php if($ls_mes=="07"){ print "selected";} ?>>JULIO</option>
          <option value="08" <?php if($ls_mes=="08"){ print "selected";} ?>>AGOSTO</option>
          <option value="09" <?php if($ls_mes=="09"){ print "selected";} ?>>SEPTIEMBRE</option>
          <option value="10" <?php if($ls_mes=="10"){ print "selected";} ?>>OCTUBRE</option>
          <option value="11" <?php if($ls_mes=="11"){ print "selected";} ?>>NOVIEMBRE</option>
          <option value="12" <?php if($ls_mes=="12"){ print "selected";} ?>>DICIEMBRE</option>
        </select>
                  <input name="txtano" type="text" id="txtano" size="10" maxlength="4" value="<?php print $ls_ano?>">
                </label></td>
                <td width="142" height="22"><div align="right">Estatus</div></td>
                <td width="190" height="22"><input name="txtestatus" type="text" class="sin-borde2" id="txtestatus" value="<?php print $ls_estatus; ?>" size="20" readonly></td>
              </tr>
              <tr>
                <td height="22" align="right">Comprobante </td>
                <td height="22" align="left"><input name="txtnumcom" type="text" id="txtnumcom" value="<?php print $ls_numcom; ?>" readonly></td>
                <td height="22" align="right">Tipo</td>
                <td height="22" align="right"><div align="left">
                  <label>
                  <select name="cmbtipo" size="1" id="cmbtipo" onChange="javascript: ue_nuevo();">
				  <?php if($ls_modageret=$_SESSION["la_empresa"]["estretiva"]!="B"){?>
                    <option value="0000000001" <?php if($ls_codret=="0000000001"){ print "selected";} ?>>IVA</option>
					<?php }if($ls_modageret=$_SESSION["la_empresa"]["modageret"]!="B"){?>
                    <option value="0000000003" <?php if($ls_codret=="0000000003"){ print "selected";} ?>>Municipal</option>
					<?php }?>
                    <option value="0000000004" <?php if($ls_codret=="0000000004"){ print "selected";} ?>>Aporte Social</option>
                  </select>
                  </label>
                </div></td>
              </tr>
              
              <tr>
                <td height="13">&nbsp;</td>
                <td colspan="3">&nbsp;</td>
              </tr>
              <tr>
                <td height="13" colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="formato-blanco">
                  <tr>
                    <td colspan="4" class="titulo-ventana">Sujeto Retenci&oacute;n</td>
                  </tr>
                  <tr>
                    <td height="22" colspan="4"><?php 
					    if($ls_operacion=="NEW")
                         {
						  print"<div align=center>Proveedor
                                <input name=estprov type=radio value=P>
                                Beneficiario
                                <input name=estprov type=radio value=B>
                                </div>";
						 }
					  ?></td>
                  </tr>
                  <tr>
                    <td width="19%" height="25"><div align="right">Codigo</div></td>
                    <td width="24%"><div align="left">
                      <label>
                      <input name="txtcodigo" type="text" id="txtcodigo" value="<?php print $ls_codigo?>" readonly>
					  <?php 
					    if($ls_operacion=="NEW")
                         {
						  print"<a href=javascript:uf_catalogo_proben('D');><img src=../shared/imagebank/tools15/buscar.gif name=buscar1 width=15 height=15 border=0 id=buscar1 onClick=document.form1.hidrangocodigos.value=1></a>";
						 
						 }
					  ?>
                      </label>
                    </div></td>
                    <td width="25%"><div align="right">Rif</div></td>
                    <td width="32%"><label>
                      <input name="txtrif" type="text" id="txtrif" value="<?php print $ls_rif?>" readonly>
                    </label></td>
                  </tr>
                  <tr>
                    <td height="23"><div align="right">Nombre</div></td>
                    <td colspan="3"><label>
                      <input name="txtnomsujret" type="text" id="txtnomsujret" size="100" maxlength="100" value="<?php print $ls_nomsujret?>" readonly>
                    </label></td>
                  </tr>
                  <tr>
                    <td height="22"><div align="right">Direccion</div></td>
                    <td colspan="3"><label>
                      <input name="txtdirsujret" type="text" id="txtdirsujret" size="100" value="<?php print $ls_dirsujret?>" readonly>
                    </label></td>
                  </tr>
				  <tr>
				    <td height="13" colspan="4" align="center">&nbsp;</td>
			      </tr>
				  <tr>
                   <td height="13" colspan="4" align="center"></td>
                 </tr>
                  
                </table></td>
              </tr>
            </table>
            
            <p align="center">
          <input name="operacion"  type="hidden" id="operacion"  value="<?php print $ls_operacion;?>">
          <input name="existe"     type="hidden" id="existe"     value="<?php print $ls_existe;?>">
          <input name="estapr"     type="hidden" id="estapr"     value="<?php print $li_estaprord;?>">
          <input name="parametros" type="hidden" id="parametros" value="<?php print $ls_parametros;?>">
          <input name="txttipdes"  type="hidden" id="txttipdes"  value="<?php print $ls_tipodestino; ?>">
          <input name="txtfecha"   type="hidden" id="txtfecha"   value="<?php print $ld_fecemisol; ?>">
		  <input name="txtmes"   type="hidden" id="txtmes"   value="<?php print $ls_mes; ?>">
          <input name="totrowrecepciones" type="hidden" id="totrowrecepciones" value="<?php print $li_totrowrecepciones;?>">
          <input name="formato"    type="hidden" id="formato"    value="<?php print $ls_reporte; ?>">
          <input name="txtprobene" type="hidden" id="txtprobene" value="<?php print $ls_probene; ?>">
          <input name="txtindice" type="hidden" id="txtindice" value="<?php print $ls_indice; ?>">
          <input name="hidfilsel" type="hidden" id="hidfilsel">
          <input name="modageret" type="hidden" id="modageret" value="<?php print $_SESSION["la_empresa"]["modageret"]; ?>">
          <input name="conivaret" type="hidden" id="conivaret" value="<?php print $_SESSION["la_empresa"]["estretiva"]; ?>">
          <input name="txtbasdatcmp" type="hidden" id="txtbasdatcmp" value="<?php print $ls_basdatcmp; ?>">
          </p></td>
      </tr>
    </table>
	<br>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
                <td><div id="detalles" align="center"></div></td>
     </tr>
    </table>
</form>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

function ue_nuevo()
{
	f=document.formulario;
	li_modageret=f.modageret.value;
	li_conivaret=f.conivaret.value;
	if((li_modageret!="B")||(li_conivaret!="B"))
	{
		li_incluir=f.incluir.value;
		if(li_incluir==1)
		{	
			f.operacion.value="NEW";
			f.existe.value="FALSE";		
			f.action="sigesp_cxp_p_modcmpret.php";
			f.submit();
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("Los dos comprobantes se manejan por el Módulo de Caja y Banco");
	}
}

function ue_guardar()
{
	f=document.formulario;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	ls_basdatcmp=f.txtbasdatcmp.value;
	if(((lb_existe=="TRUE")&&(li_cambiar==1))||(lb_existe=="FALSE")&&(li_incluir==1))
	{
		valido=true;
		if((ls_basdatcmp!="")&&(li_cambiar==1))
		{
			alert("Los comprobantes solo pueden ser modificados desde la Base de Datos integradora");
		}
		else
		{
			// Obtenemos el total de filas de los Conceptos
			total=ue_calcular_total_fila_local("txtnumope");
			f.totrowrecepciones.value=total;
			numcom=ue_validarvacio(f.txtnumcom.value);
			if(valido)
			{
				valido=ue_validarcampo(f.txtnumcom.value,"Debe seleccionar un Comprobante.",f.txtnumsol);
			}
			if(valido)
			{
				valido=ue_validarcampo(f.txtcodigo.value,"Debe seleccionar un Proveedor / Beneficiario.",f.txtnumsol);
			}
			if(valido)
			{
				rowrecepciones=f.totrowrecepciones.value;
				for(row=1;row<rowrecepciones;row++)
				{
					numfac= eval("f.txtnumfac"+row+".value");
					if(numfac=="")
					{
						alert("No se permite numeros de factura en Blanco.");
						valido=false;
						break;
					}
				}		
				if(rowrecepciones<=1)
				{
					alert("El comprobante debe tener al menos un detalle.");
					valido=false;
				}
			}
			if(valido)
			{
				f.operacion.value="GUARDAR";
				f.action="sigesp_cxp_p_modcmpret.php";
				f.submit();		
			}
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación.");
   	}
}

function ue_eliminar()
{
    f=document.formulario;
	li_eliminar=f.eliminar.value;
	lb_existe=f.existe.value;
	ls_basdatcmp=f.txtbasdatcmp.value;
	if(li_eliminar==1)
	{
		valido=true;
		if(ls_basdatcmp!="")
		{
			alert("Los comprobantes solo pueden ser modificados desde la Base de Datos integradora");
		}
		else
		{
			// Obtenemos el total de filas de los Conceptos
			total=ue_calcular_total_fila_local("txtnumope");
			f.totrowrecepciones.value=total;
			numcom=ue_validarvacio(f.txtnumcom.value);
			if(valido)
			{
				valido=ue_validarcampo(numcom,"Debe seleccionar un Comprobante.",f.txtnumsol);
			}
			if(valido)
			{
				rowrecepciones=f.totrowrecepciones.value;
				if(rowrecepciones<=1)
				{
					alert("El comprobante debe tener al menos un detalle.");
					valido=false;
				}
			}
			if(valido)
			{
				if(confirm("¿Realmente desea inutilizar este registro?"))
				 {
				   f.operacion.value="ELIMINAR";
				   f.action="sigesp_cxp_p_modcmpret.php";
				   f.submit();		
				 }
				 else
				 {
				   alert("Operación Cancelada!!!");	  
				 }  
			}
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operación.");
   	}
}

function ue_buscar()
{
	f=document.formulario;
	li_leer=f.leer.value;
	li_modageret=f.modageret.value;
	li_conivaret=f.conivaret.value;
	if((li_modageret!="B")||(li_conivaret!="B"))
	{
		if (li_leer==1)
		{
			window.open("sigesp_cxp_cat_cmpretiva.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else
		{
			alert("No tiene permiso para realizar esta operacion");
		}
	}
	else
	{
		alert("Los dos comprobantes se manejan por el Módulo de Caja y Banco");
	}
}


function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_delete_detalle(fila)
{
	f=document.formulario;
	if(confirm("¿Desea eliminar el Registro actual?"))
		{
			valido=true;
			parametros="";
			total=ue_calcular_total_fila_local("txtnumope");
			f.totrowrecepciones.value=total;
			rowrecepciones=f.totrowrecepciones.value;
			li_i=1;
			
			for(j=1;(j<rowrecepciones)&&(valido);j++)
			{
				if(j!=fila)
				{
					
					ls_codret=eval("document.formulario.txtcodret"+j+".value");
			        ls_numope=eval("document.formulario.txtnumope"+j+".value");
			        ls_fecfac=eval("document.formulario.txtfecfac"+j+".value");
			        ls_numfac=eval("document.formulario.txtnumfac"+j+".value");
			        ls_numcon=eval("document.formulario.txtnumcon"+j+".value");
			        ls_numnd=eval("document.formulario.txtnumnd"+j+".value");
			        ls_numnc=eval("document.formulario.txtnumnc"+j+".value");
			        ls_tiptrans=eval("document.formulario.txttiptrans"+j+".value");
			        ls_tot_cmp_sin_iva=eval("document.formulario.txttotsiniva"+j+".value");
			        ls_tot_cmp_con_iva=eval("document.formulario.txttotconiva"+j+".value");
			        ls_basimp=eval("document.formulario.txtbasimp"+j+".value");
			        ls_porimp=eval("document.formulario.txtporimp"+j+".value");
			        ls_porret=eval("document.formulario.txtporret"+j+".value");
			        ls_totimp=eval("document.formulario.txttotimp"+j+".value");
			        ls_ivaret=eval("document.formulario.txtivaret"+j+".value");
			        ls_numsop=eval("document.formulario.txtnumsop"+j+".value");
			        ls_numdoc=eval("document.formulario.txtnumdoc"+j+".value");
		            
					parametros=parametros+"&txtcodret"+li_i+"="+ls_codret+"&txtnumope"+li_i+"="+ls_numope+"&txtfecfac"+li_i+"="+ls_fecfac+
							   "&txtnumfac"+li_i+"="+ls_numfac+"&txtnumcon"+li_i+"="+ls_numcon+"&txtnumnd"+li_i+"="+ls_numnd+
							   "&txtnumnc"+li_i+"="+ls_numnc+"&txttiptrans"+li_i+"="+ls_tiptrans+"&txttotsiniva"+li_i+"="+ls_tot_cmp_sin_iva+
							   "&txttotconiva"+li_i+"="+ls_tot_cmp_con_iva+"&txtbasimp"+li_i+"="+ls_basimp+"&txtporimp"+li_i+"="+ls_porimp+
							   "&txttotimp"+li_i+"="+ls_totimp+"&txtivaret"+li_i+"="+ls_ivaret+"&txtnumsop"+li_i+"="+ls_numsop+
							   "&txtnumdoc"+li_i+"="+ls_numdoc+"&txtporret"+li_i+"="+ls_porret;
					li_i=li_i+1;	
				}
			}
			totalrecepciones=eval(li_i);
			f.totrowrecepciones.value=totalrecepciones;
			parametros=parametros+"&totrowrecepciones="+totalrecepciones;	
			
			
			if((parametros!="")&&(valido))
			{
				divgrid = document.getElementById("detalles");
				ajax=objetoAjax();
				ajax.open("POST","class_folder/sigesp_cxp_c_modcmpret_ajax.php",true);
				ajax.onreadystatechange=function() {
					if (ajax.readyState==4) {
						divgrid.innerHTML = ajax.responseText
					}
				}
				ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				ajax.send("proceso=AGREGARCMPRET"+parametros);
			}
		}
	}

function ue_insert_row()
{
	f=document.formulario;
	parametros="";
	total=ue_calcular_total_fila_local("txtnumope");
	f.totrowrecepciones.value=total;
	rowrecepciones=f.totrowrecepciones.value;
	for (j=1;(j<rowrecepciones);j++)
	{
		  ls_codret			 = eval("document.formulario.txtcodret"+j+".value");
		  ls_numope			 = eval("document.formulario.txtnumope"+j+".value");
		  ls_fecfac			 = eval("document.formulario.txtfecfac"+j+".value");
		  ls_numfac			 = eval("document.formulario.txtnumfac"+j+".value");
		  ls_numcon			 = eval("document.formulario.txtnumcon"+j+".value");
		  ls_numnd			 = eval("document.formulario.txtnumnd"+j+".value");
		  ls_numnc			 = eval("document.formulario.txtnumnc"+j+".value");
		  ls_tiptrans		 = eval("document.formulario.txttiptrans"+j+".value");
		  ls_tot_cmp_sin_iva = eval("document.formulario.txttotsiniva"+j+".value");
		  ls_tot_cmp_con_iva = eval("document.formulario.txttotconiva"+j+".value");
		  ls_basimp			 = eval("document.formulario.txtbasimp"+j+".value");
		  ls_porimp			 = eval("document.formulario.txtporimp"+j+".value");
		  ls_porret			 = eval("document.formulario.txtporret"+j+".value");
		  ls_totimp			 = eval("document.formulario.txttotimp"+j+".value");
		  ls_ivaret			 = eval("document.formulario.txtivaret"+j+".value");
		  ls_numsop			 = eval("document.formulario.txtnumsop"+j+".value");
		  ls_numdoc			 = eval("document.formulario.txtnumdoc"+j+".value");
		  parametros= parametros+"&txtcodret"+j+"="+ls_codret+"&txtnumope"+j+"="+ls_numope+"&txtfecfac"+j+"="+ls_fecfac+
		  			  "&txtnumfac"+j+"="+ls_numfac+"&txtnumcon"+j+"="+ls_numcon+"&txtnumnd"+j+"="+ls_numnd+
					  "&txtnumnc"+j+"="+ls_numnc+"&txttiptrans"+j+"="+ls_tiptrans+"&txttotsiniva"+j+"="+ls_tot_cmp_sin_iva+
					  "&txttotconiva"+j+"="+ls_tot_cmp_con_iva+"&txtbasimp"+j+"="+ls_basimp+"&txtporimp"+j+"="+ls_porimp+
					  "&txttotimp"+j+"="+ls_totimp+"&txtivaret"+j+"="+ls_ivaret+"&txtnumsop"+j+"="+ls_numsop+
					  "&txtnumdoc"+j+"="+ls_numdoc+"&txtporret"+j+"="+ls_porret;
	}
	j++;	
	totalrecepciones=eval(j);
	f.totrowrecepciones.value=totalrecepciones;
	parametros=parametros+"&totrowrecepciones="+totalrecepciones;	
	if (parametros!="")
	   {
	     divgrid = document.getElementById("detalles");
		 ajax    = objetoAjax();
		 ajax.open("POST","class_folder/sigesp_cxp_c_modcmpret_ajax.php",true);
	 	 ajax.onreadystatechange=function() {
		 if (ajax.readyState==4) {
	 		divgrid.innerHTML = ajax.responseText
		    }
	     }
		 ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		 ajax.send("proceso=AGREGARCMPRETINS"+parametros);
       }
}

function ue_reload()
{
	f=document.formulario;
	parametros=f.parametros.value;
	proceso="AGREGARCMPRET";
	if(parametros!="")
	{
		divgrid = document.getElementById("detalles");
		ajax=objetoAjax();
		ajax.open("POST","class_folder/sigesp_cxp_c_modcmpret_ajax.php",true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				divgrid.innerHTML = ajax.responseText
			}
		}
		ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		ajax.send("proceso="+proceso+""+parametros);
	}
}

function ue_cargardatos(ls_numcom,ls_anofiscal,ls_mesfiscal,ls_codsujret,ls_nomsujret,ls_dirsujret,ls_rifsujret,ls_codret,
						ls_probene,ls_estcmpret)
{
 f=document.formulario;
 f.txtnumcom.value=ls_numcom;
 f.txtano.value=ls_anofiscal;
 f.cmbmes.value=ls_mesfiscal;
 f.cmbtipo.value=ls_codret;
 f.txtcodigo.value=ls_codsujret;
 f.txtnomsujret.value=ls_nomsujret;
 f.txtdirsujret.value=ls_dirsujret;
 f.txtrif.value=ls_rifsujret;
 f.txtprobene.value=ls_probene;
 if(ls_estcmpret==1)
 {
 	f.txtestatus.value="EMITIDO";
 }
 else
 {
 	f.txtestatus.value="ANULADO";
 }
 f.existe.value="TRUE";
}
function uf_catalogo_proben()
{
	fop=document.formulario;
    if (fop.estprov[0].checked==true)
	   {
	     ls_tipo="MODCMPRET";
		 f.txtprobene.value="P";
		 pagina="sigesp_cxp_cat_proveedor.php?tipo="+ls_tipo;
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=560,height=400,resizable=yes,location=no");
	   }
	else
	   {
			if (fop.estprov[1].checked==true)
			{
				ls_tipo="MODCMPRET";
				f.txtprobene.value="B";
				pagina="sigesp_cxp_cat_beneficiario.php?tipo="+ls_tipo;
				window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=560,height=400,resizable=yes,location=no");
		    }
	   }
}
	
function cargarcodpro(codpro,nompro,rifpro,dirprov)
{
  f=document.formulario;
  f.txtcodigo.value=codpro;
  f.txtnomsujret.value=nompro;
  f.txtdirsujret.value=dirprov;
  f.txtrif.value=rifpro;
}

function ue_cat_solicitud(li_fila)
{
  f=document.formulario;
  f.txtindice.value=li_fila;
  ls_catalogo="sigesp_cxp_cat_solicitudpago.php?tipo=MODCMPRET";
  window.open(ls_catalogo,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,left=50,top=50,location=no,resizable=yes");
}

function uf_iva(li_row)
{
  f=document.formulario;
  ld_basimp = eval("f.txtbasimp"+li_row+".value");
  if ((ld_basimp!="0,00") && (ld_basimp!=""))	  
	 {	 
	   f.hidfilsel.value = li_row;
	   pagina="sigesp_cxp_pdt_otroscreditos.php?tipo=CMPRET";
	   window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=600,resizable=yes,location=no");	  
  	 }
  else
	 {
	   alert("Para que pueda seleccionar un cargo la Base Imponible debe ser distinta de cero !!!");
	 }
}

function uf_retenciones(li_row)
{
  f         = document.formulario;
  f.hidfilsel.value = li_row;
  ls_tipret = f.cmbtipo.value;
  switch (ls_tipret)
  {
  	case "0000000001":
	   ld_porcar = eval("f.txtporimp"+li_row+".value");
	   if ((ld_porcar!="0,00") && (ld_porcar!=""))
		  {
		    pagina="sigesp_cxp_pdt_deducciones.php?tipo=CMPRETIVA";
		    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=600,resizable=yes,location=no");	  
		  }  
	   else
		  {
		    alert("Para que pueda seleccionar un deducción el porcentaje debe ser distinto a cero !!!");
		  }
	break;
  	case "0000000003":
	   ld_basimp = eval("f.txtbasimp"+li_row+".value");
	   if ((ld_basimp!="0,00") && (ld_basimp!=""))
		  {
		    pagina="sigesp_cxp_pdt_deducciones.php?tipo=CMPRETMUN";
		    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=600,resizable=yes,location=no");	  
		  }  
	   else
		  {
		    alert("Para que pueda seleccionar un deducción el Monto de la Base Imponible debe ser Mayor a Cero !!!");
		  }
	break;
  	case "0000000004":
	   ld_basimp = eval("f.txtbasimp"+li_row+".value");
	   if ((ld_basimp!="0,00") && (ld_basimp!=""))
		  {
		    pagina="sigesp_cxp_pdt_deducciones.php?tipo=CMPRETAPO";
		    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=600,resizable=yes,location=no");	  
		  }  
	   else
		  {
		    alert("Para que pueda seleccionar un deducción el Monto de la Base Imponible debe ser Mayor a Cero !!!");
		  }
	break;
  }
/*  if (ls_tipret=='0000000001')
     {
	   numnd=eval("document.formulario.txtnumnd"+li_row+".value");
	   numnc=eval("document.formulario.txtnumnc"+li_row+".value");
	   if((numnd=="")&&(numnc==""))
	   {
		   ld_porcar = eval("f.txtporimp"+li_row+".value");
		   if ((ld_porcar!="0,00") && (ld_porcar!=""))
			  {
				pagina="sigesp_cxp_pdt_deducciones.php?tipo=CMPRETIVA";
				window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=600,resizable=yes,location=no");	  
			  }  
		   else
			  {
				alert("Para que pueda seleccionar un deducción el porcentaje debe ser distinto a cero !!!");
			  }
		}
		else
		{
			alert("Las Notas de Debito/Credito no tienen retenciones");
		}
	 }
  else
     {
	   ld_basimp = eval("f.txtbasimp"+li_row+".value");
	   if ((ld_basimp!="0,00") && (ld_basimp!=""))
		  {
		    pagina="sigesp_cxp_pdt_deducciones.php?tipo=CMPRETMUN";
		    window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=600,resizable=yes,location=no");	  
		  }  
	   else
		  {
		    alert("Para que pueda seleccionar un deducción el Monto de la Base Imponible debe ser Mayor a Cero !!!");
		  }
	 }
*/  }  
  
function ue_numeronegativo(valor,li_row)
{
	f= document.formulario;
	if(eval("document.formulario.txtnumnc"+li_row+".value"))
	{
		auxiliar=valor.value;
		if((auxiliar.indexOf("-")==-1)&&(auxilar!=""))
		{
			auxiliar="-"+auxiliar;
			valor.value = auxiliar;
		}
	}
}
function ue_validarnota(valor,li_row)
{
	f= document.formulario;
	if(valor=="NC")
	{
		eval("document.formulario.txtnumnd"+li_row+".value=''");
	}
	else
	{
		eval("document.formulario.txtnumnc"+li_row+".value=''");
	}
	eval("document.formulario.txtbasimp"+li_row+".value='0,00'");
	eval("document.formulario.txttotconiva"+li_row+".value='0,00'");
	eval("document.formulario.txtporimp"+li_row+".value='0,00'");
	eval("document.formulario.txttotimp"+li_row+".value='0,00'");
}
function currency_Format(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789-'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Enter 
	if (whichCode == 127) return true; // Enter 	
	if (whichCode == 9) return true; // Enter 	
    key = String.fromCharCode(whichCode); // Get key value from key code 
    if (strCheck.indexOf(key) == -1) return false; // Not a valid key 
    len = fld.value.length; 
    for(i = 0; i < len; i++) 
     if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break; 
    aux = ''; 
    for(; i < len; i++) 
     if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i); 
    aux += key; 
    len = aux.length; 
    if (len == 0) fld.value = ''; 
    if (len == 1) fld.value = '0'+ decSep + '0' + aux; 
    if (len == 2) fld.value = '0'+ decSep + aux; 
    if (len > 2) { 
     aux2 = ''; 
     for (j = 0, i = len - 3; i >= 0; i--) { 
      if(aux.charAt(i)=='-')
	  {
		  if (j == 4) { 
		   aux2 += milSep; 
		   j = 0; 
		  }
	  }
	  else
	  {
		  if (j == 3) { 
		   aux2 += milSep; 
		   j = 0; 
		  }
	  } 
      aux2 += aux.charAt(i); 
      j++; 
     } 
     fld.value = ''; 
     len2 = aux2.length; 
     for (i = len2 - 1; i >= 0; i--) 
      fld.value += aux2.charAt(i); 
     fld.value += decSep + aux.substr(len - 2, len); 
    } 
    return false; 
   }

</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<?php
if(($ls_operacion=="GUARDAR"))
{
	print "<script language=JavaScript>";
	print "   ue_reload();";
	print "</script>";
}
if($ls_operacion=="NEW")
{
	print "<script language=JavaScript>";
	print "   ue_insert_row();";
	print "</script>";
}
?>		  
</html>