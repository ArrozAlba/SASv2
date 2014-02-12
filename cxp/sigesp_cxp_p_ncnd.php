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
	$io_fun_cxp->uf_load_seguridad("CXP","sigesp_cxp_p_ncnd.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
  //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_limpiarvariables
		//		   Access: private
		//	  Description: Funcin que limpia todas las variables necesarias en la pgina
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creacin: 17/03/2007								Fecha ltima Modificacin : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_estatus,$ld_fecregnota,$ls_numncnd,$ls_nrocontrol,$ls_numord,$ls_codproben,$ls_nomproben,$ls_tipdoc,$ls_dentipdoc,
			   $ls_numrecdoc,$ls_connota,$ls_operacion,$ld_fecregsol,$ls_ctaprov,$lb_checknc,$lb_checknd,$ls_denctascg,$ls_rifproben,
			   $io_fun_cxp,$rb_chkprov,$rb_chkben,$ls_estsol,$li_estapro,$li_rowspre,$li_rowscon,$li_estcon,$li_estpre,$as_parametros,
			   $ls_codtipsol,$li_rowsprerecepcion,$li_rowsconrecepcion;
	    $as_parametros="";
		$li_estcon    =0;
		$li_estpre    =0;			   
		$ls_estatus   ="REGISTRO";
		$ls_estsol    ="R";
		$li_estapro   =0;
		$ld_fecregsol =date("d/m/Y");
		$ls_numncnd   ="";
		$ls_nrocontrol="";
		$ls_numord    ="";
		$ls_dentipdoc ="";
		$ls_tipdoc    ="";
		$ls_numrecdoc ="";
		$ls_codproben =$ls_rifproben="";
		$ls_nomproben ="";
		$ls_connota   ="";
		$ls_operacion ="";
		$lb_checknd   ="";
		$lb_checknc   ='checked';
		$ls_ctaprov   ="";
		$ls_denctascg ="";
		$rb_chkprov   ="";
		$rb_chkben    ="";
		$li_rowspre   =0;
		$li_rowscon   =0;
		$ls_operacion =$io_fun_cxp->uf_obteneroperacion();
		$ls_codtipsol = "";
		$li_rowsprerecepcion = $li_rowsconrecepcion = 0;
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_load_variables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Funcin que carga todas las variables necesarias en la pgina
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creacin: 17/03/2007								Fecha ltima Modificacin : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_estatus,$ld_fecregnota,$ls_numncnd,$ls_nrocontrol,$ls_numord,$ls_codproben,$ls_nomproben,$ls_tipdoc,$ls_dentipdoc,
			   $ls_numrecdoc,$ls_connota,$ls_operacion,$ld_fecregsol,$ls_ctaprov,$lb_checknc,$lb_checknd,$ls_denctascg,$ls_rifproben,
			   $io_fun_cxp,$ls_estsol,$rb_chkprov,$rb_chkben,$ls_estsol,$li_estapro,$li_rowspre,$li_rowscon,$li_estcon,$li_estpre,$as_parametros;
	    $as_parametros="";
		$li_estcon=$_POST["txtestcontipdoc"];
		$li_estpre=$_POST["txtestpretipdoc"];
		$ls_rifproben = $_POST["txtrifproben"];
		$ls_estsol   = $io_fun_cxp->uf_obtenervalor("txtestsol","");
		$li_estapro  = $io_fun_cxp->uf_obtenervalor("txtestapro","");
		$ld_fecregsol= $io_fun_cxp->uf_obtenervalor("txtfecregsol",date("d/m/Y"));
		$ls_numncnd  = trim($io_fun_cxp->uf_obtenervalor("txtnumncnd",""));
		$ls_numord   = $io_fun_cxp->uf_obtenervalor("txtnumord","");
		$ls_dentipdoc= $io_fun_cxp->uf_obtenervalor("txtdentipdoc","");
		$ls_tipdoc   = $io_fun_cxp->uf_obtenervalor("txttipdoc","");
		$ls_numrecdoc= $io_fun_cxp->uf_obtenervalor("txtnumrecdoc","");
		$ls_codproben= $io_fun_cxp->uf_obtenervalor("txtcodproben","");
		$ls_nomproben= $io_fun_cxp->uf_obtenervalor("txtnomproben","");
		$ls_connota  = $io_fun_cxp->uf_obtenervalor("txtconnota","");
		$li_rowspre  = $io_fun_cxp->uf_obtenervalor("rowspre","");
		$li_rowscon  = $io_fun_cxp->uf_obtenervalor("rowscon","");
		$ls_nrocontrol=$io_fun_cxp->uf_obtenervalor("txtnrocontrol","");
		if(array_key_exists("tiponota",$_POST))
		{
			$ls_tipo  = $io_fun_cxp->uf_obtenervalor("tiponota","");
			if($ls_tipo=='NC')
			{
				$lb_checknd  = "";
				$lb_checknc  = 'checked';
			}
			else
			{
				$lb_checknd  = "checked";
				$lb_checknc  = '';				
			}
		}
		else
		{
				$lb_checknd  = "";
				$lb_checknc  = 'checked';
		}
		$ls_ctaprov  = $io_fun_cxp->uf_obtenervalor("txtcuentaprov","");
		$ls_denctascg= $io_fun_cxp->uf_obtenervalor("txtdenctascg","");
		if(array_key_exists("tipproben",$_POST))
		{
			$ls_tipo  = $io_fun_cxp->uf_obtenervalor("tipproben","");
			if($ls_tipo=='P')
			{
				$rb_chkprov="checked";
				$rb_chkben ="";
			}
			else
			{
				$rb_chkprov="";
				$rb_chkben ="checked";				
			}
		}
		else
		{
			$rb_chkprov="checked";
			$rb_chkben="";
		}
		$ls_operacion= $io_fun_cxp->uf_obteneroperacion();
   }
   //--------------------------------------------------------------  
   
 //--------------------------------------------------------------
   function uf_load_data(&$as_parametros)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_load_variables
		//		   Access: private
		//	  Description: Funcin que carga todas las variables necesarias en la pgina
		//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
		// Fecha Creacin: 17/03/2007								Fecha ltima Modificacin : 
		//////////////////////////////////////////////////////////////////////////////
   		global $li_rowspre,$li_rowscon,$li_estcon,$li_estpre;

		if(($li_estcon==1)&&(($li_estpre==3)||($li_estpre==4)))
		{			
			for($li_i=1;($li_i<=$li_rowscon);$li_i++)
			{
				$ls_cuenta=$_POST["txtscgcuentancnd".$li_i];
				$ls_dencuenta=$_POST["txtdencuentascgncnd".$li_i];
				$ldec_mondeb=$_POST["txtdebencnd".$li_i];
				$ldec_monhab=$_POST["txthaberncnd".$li_i];
		
				$as_parametros=$as_parametros."&txtscgcuenta".$li_i."=".$ls_cuenta."&txtdencuenta".$li_i."=".$ls_dencuenta."".
											  "&txtmondeb".$li_i."=".$ldec_mondeb."&txtmonhab".$li_i."=".$ldec_monhab;									  
			}
			$as_parametros=$as_parametros."&selected=".$li_rowscon."";								  
		}
		else
		{
			for ($li_i=1;$li_i<=$li_rowspre;$li_i++)
			    {
				  $as_parametros   = $as_parametros."&selectedpre=".$li_rowspre;
				  $ls_cuentaspg	   = $_POST["txtcuentaspgncnd".$li_i];
				  $ls_cuentascg	   = $_POST["txtscgcuentadt".$li_i];
				  $ls_dencuentascg = $_POST["txtdenscgcuentadt".$li_i];
				  $ls_estcargo	   = $_POST["txtestcargo".$li_i];
				  $ls_codestpro	   = $_POST["txtcodestproncnd".$li_i];
				  $ls_codpro	   = $_POST["txtcodpro".$li_i];
				  $ls_estcla	   = $_POST["txtestclancnd".$li_i];
				  $ls_dencuentaspg = $_POST["txtdencuentancnd".$li_i];
				  $ldec_monto	   = $_POST["txtmontoncnd".$li_i];
				  $as_parametros   = $as_parametros."&txtcuentaspgncnd".$li_i."=".$ls_cuentaspg."&txtcodestpro".$li_i."=".$ls_codestpro."".
				                                    "&txtestclancnd".$li_i."=".$ls_estcla."&txtcodpro".$li_i."=".$ls_codpro."".
											        "&txtdencuentancnd".$li_i."=".$ls_dencuentaspg."&txtmontoncnd".$li_i."=".$ldec_monto."".
											        "&txtscgcuentadt".$li_i."=".$ls_cuentascg."&txtdenscgcuentadt".$li_i."=".$ls_dencuentascg."".
											        "&txtestcargo".$li_i."=".$ls_estcargo;
			    }
			for ($li_i=1;$li_i<=$li_rowscon;$li_i++)
			    {
				  $ls_ctascgnot    = trim($_POST["txtscgcuentancnd".$li_i]);
				  $ls_denctascgnot = $_POST["txtdencuentascgncnd".$li_i];
			      $ld_debctascgnot = $_POST["txtdebencnd".$li_i];
			      $ld_habctascgnot = $_POST["txthaberncnd".$li_i];				  
				  $as_parametros=$as_parametros."&selectedcon=".$li_rowscon."&txtscgcuentancnd".$li_i."=".$ls_ctascgnot."".
				  								"&txtdencuentascgncnd".$li_i."=".$ls_denctascgnot."".
												"&txtdebencnd".$li_i."=".$ld_debctascgnot."".
												"&txthaberncnd".$li_i."=".$ld_habctascgnot;
				}			
		}			
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<title >Registro de Notas de D&eacute;bito/Notas de Cr&eacute;dito</title>
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_sep.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_cxp.js"></script>
<link href="css/cxp.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:9px;
	top:151px;
	width:214px;
	height:28px;
	z-index:1;
}
.Estilo1 {font-size: 10px}
-->
</style>
</head>
<body>
<?php 
	require_once("class_folder/sigesp_cxp_c_ncnd.php");
	$io_cxp=new sigesp_cxp_c_ncnd("../");
	uf_limpiarvariables();
	switch ($ls_operacion) 
	{
		case "GUARDAR":
			uf_load_variables();
			$lb_valido=$io_cxp->uf_guardar($la_seguridad);
			uf_load_data($as_parametros);		
			switch($ls_estsol)
			{
				case "R": 
					$ls_estatus="REGISTRO";
					break;
				case "C": 
					$ls_estatus="CONTABILIZADA";
					break;
			}
			if($lb_valido)
			{
				$ls_existe="TRUE";
			}
			else
			{
				$ls_existe="FALSE";
			}
			break;			
		case "ELIMINAR":
			uf_load_variables();
			$lb_valido=$io_cxp->uf_delete_nota($la_seguridad);
			if(!$lb_valido)
			{
				uf_load_data(&$as_parametros);	
				switch($ls_estsol)
				{
					case "R": 
						$ls_estatus="REGISTRO";
						break;
					case "C": 
						$ls_estatus="CONTABILIZADA";
						break;
				}
				$ls_existe="TRUE";
			}
			else
			{
				uf_limpiarvariables();
				$ls_existe="FALSE";
			}
			break;
		case "NUEVO":
			uf_limpiarvariables();
			$ls_existe="FALSE";
			if(array_key_exists("la_crenotas",$_SESSION))
			{
				unset($_SESSION["la_crenotas"]);
			}
			break;
	}
?>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="803" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			
          <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Cuentas por Pagar</td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("d/m/Y")." - ".date("h:i a");?></b></span></div></td>
	  	  <tr>
	  	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	    <td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
    </table>    </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="26" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0" title="Nuevo"></a></div></td>
    <td class="toolbar" width="26"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0" title="Guardar"></a></div></td>
    <td class="toolbar" width="26"><div align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0" title="Buscar"></a></div></td>
    <td class="toolbar" width="26"><div align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0" title="Eliminar"></a></div></td>
    <td class="toolbar" width="25"><a href="javascript: ue_imprimir();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0" title="Imprimir"></a></td>
    <td class="toolbar" width="27"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0" title="Salir"></a></div></td>
    <td class="toolbar" width="26"><div align="center"><a href="javascript: ue_ayuda();"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20" border="0" title="Ayuda"></a></div></td>
    <td class="toolbar" width="26">&nbsp;</td>
    <td class="toolbar" width="26">&nbsp;</td>
    <td class="toolbar" width="26">&nbsp;</td>
    <td class="toolbar" width="543">&nbsp;</td>
  </tr>
</table>

<p>&nbsp;</p>
<form action="" method="post" name="formulario" id="formulario">
  <?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_cxp->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_cxp);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
    <td width="760" height="136"><p>&nbsp;</p>
      <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr> 
            <td colspan="4" class="titulo-ventana">Registro de Notas de D&eacute;bito/Notas de Cr&eacute;dito </td>
          </tr>
          <tr style="visibility:hidden">
            <td height="22">Reporte en
              <select name="cmbbsf" id="cmbbsf">
                <option value="0" selected>Bs.</option>
                <option value="1">Bs.F.</option>
              </select></td>
            <td>&nbsp;</td>
            <td><label></label></td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td width="203" height="22"><div align="right">Estatus</div></td>
            <td width="354">
                <input name="txtestatus" type="text" class="sin-borde2" id="txtestatus" value="<?php print $ls_estatus; ?>" size="35" readonly>            </td>
            <td width="89"><div align="right">Fecha</div></td>
            <td width="202"><input name="txtfecregsol" type="text" id="txtfecregsol" style="text-align:center" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);" value="<?php print $ld_fecregsol;?>" size="15"  datepicker="true"></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Nota Nro. </div></td>
            <td height="22"><input name="txtnumncnd" style="text-align:center" type="text" id="txtnumncnd" value="<?php print $ls_numncnd;?>" size="18" maxlength="15"  ></td>
            <td height="22"><div align="right">Nro Control </div></td>
            <td height="22"><input name="txtnrocontrol" type="text" id="txtnrocontrol" value="<?php print $ls_nrocontrol;?>" size="15"></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Orden de Pago Nro. </div></td>
            <td height="22" colspan="3"><label>
              <input name="txtnumord" type="text" id="txtnumord" style="text-align:center" value="<?php print $ls_numord;?>" size="18" maxlength="15" readonly>
              <a href="javascript:ue_catalogo_ordenes();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catálogo de Ordenes de Pago" width="15" height="15" border="0"></a></label></td>
          </tr>
          <tr>
            <td height="22" style="text-align:right">Proveedor/Beneficiario</td>
            <td height="22" colspan="3"><input name="tipproben" type="radio" class="sin-borde" onClick="return false;" value="P" "<?php print $rb_chkprov;?>"> 
              Proveedor 
              <input name="tipproben" type="radio" class="sin-borde" onClick="return false;" value="B" "<?php print $rb_chkben;?>"> 
              Beneficiario              </td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td height="22" colspan="3"><input name="txtcodproben" type="text" id="txtcodproben" style="text-align:center" value="<?php print $ls_codproben;?>" size="18" maxlength="10" readonly>
            <input name="txtnomproben" type="text" class="sin-borde" id="txtnomproben" value="<?php print $ls_nomproben;?>" size="55" readonly>
            <input name="txtcuentaprov" type="hidden" id="txtcuentaprov" value="<?php print $ls_ctaprov;?>">
            <input name="txtdenctascg" type="hidden" id="txtdenctascg" value="<?php print $ls_denctascg;?>"> 
            <strong>RIF: 
            <label>
            <input name="txtrifproben" type="text" class="sin-borde" id="txtrifproben" value="<?php echo $ls_rifproben; ?>" size="17" maxlength="12" style="text-align:left">
            </label>
            </strong></td>
          </tr>
          <tr> 
            <td height="22" style="text-align:right">Recepci&oacute;n Nro.</td>
            <td height="22" colspan="3"><input name="txtnumrecdoc" type="text" id="txtnumrecdoc" value="<?php print $ls_numrecdoc;?>" size="18" style="text-align:center" maxlength="15" readonly>
            <a href="javascript:ue_catalogo_recepciones();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catálogo de Recepciones de Documento" width="15" height="15" border="0"></a></td>
          </tr>
          <tr> 
            <td height="22"><div align="right">Tipo de Documento</div></td>
            <td height="22" colspan="3"><input name="txttipdoc" style="text-align:center" type="text" id="txttipdoc" value="<?php print $ls_tipdoc;?>" size="18" maxlength="10" readonly>
              <input name="txtdentipdoc" type="text" class="sin-borde" id="txtdentipdoc" value="<?php print $ls_dentipdoc;?>" size="80" readonly>
              <input name="txtestcontipdoc" type="hidden" id="txtestcontipdoc" value="<?php print $li_estcon;?>">
              <input name="txtestpretipdoc" type="hidden" id="txtestpretipdoc" value="<?php print $li_estpre;?>"></td>
          </tr>
          <tr> 
            <td height="24"> <div align="right">Descripci&oacute;n Nota </div></td>
            <td height="22" colspan="3" rowspan="2"><textarea name="txtconnota" cols="90" rows="3" id="txtconnota" onKeyUp="ue_validarcomillas(this);" ><?php print $ls_connota;?></textarea></td>
          </tr>
          <tr> 
            <td height="11">&nbsp;</td>
          </tr>
          <tr>
            <td height="13" colspan="4">&nbsp;</td>
          </tr>
        </table>
        <table width="780" border="0" cellpadding="1" cellspacing="1" class="formato-blanco">
          <tr bordercolor="#007F00" class="titulo-catclaro">
            <td height="22"><div align="center" class="Estilo1">Detalles de la Recepci&oacute;n de Documento </div></td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><div id="detallesrecepcion"></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr class="titulo-catclaro">
            <td height="23"><div align="center" class="Estilo1">Detalles de la Nota</div></td>
          </tr>
          <tr>
            <td><label><strong>Tipo de Nota: </strong></label>
              <table width="200">
                <tr>
                  <td><label>	
                    <input name="tiponota" type="radio" value="NC" onChange="javascript:uf_cambio_tiponota();" <?php print $lb_checknc;?>>
                    Nota de Cr&eacute;dito</label></td>
                </tr>
                <tr>
                  <td><label>
                    <input type="radio" name="tiponota" value="ND" onChange="javascript:uf_cambio_tiponota();" <?php print $lb_checknd;?>>
                    Nota de D&eacute;bito</label></td>
                </tr>
              </table>            </td>
          </tr>
          <tr>
            <td><a href="javascript:uf_agregar_dtnota('','','','','');"><img src="../shared/imagebank/mas.gif" width="9" height="17" border="0">Agregar Detalle</a>
              <input name="filadelete" type="hidden" id="filadelete">
            </td>
          </tr>
          <tr>
            <td align="center"><div id="detallesnota"></div></td>
          </tr>
        </table>
        <p> 
          <input name="operacion"       type="hidden" id="operacion"       value="<?php print $ls_operacion;?>">
          <input name="existe"          type="hidden" id="existe"          value="<?php print $ls_existe;?>">         
          <input name="parametros"      type="hidden" id="parametros"      value="<?php print $as_parametros;?>">
          <input name="txtestsol"       type="hidden" id="txtestsol"       value="<?php print $ls_estsol;?>">
          <input name="txtestapro"      type="hidden" id="txtestapro"      value="<?php print $li_estapro; ?>">
          <input name="txttipsol"       type="hidden" id="txttipsol"       value="<?php print $ls_codtipsol; ?>">
          <input name="txtfecha"        type="hidden" id="txtfecha"        value="<?php print $ld_fecregsol; ?>">
          <input name="rowspre"         type="hidden" id="rowspre"         value="<?php print $li_rowspre;?>">
          <input name="rowscon"         type="hidden" id="rowscon"         value="<?php print $li_rowscon;?>">
          <input name="rowsprerecpcion" type="hidden" id="rowsprerecpcion" value="<?php print $li_rowsprerecepcion;?>">
          <input name="rowsconrecpcion" type="hidden" id="rowsconrecpcion" value="<?php print $li_rowsconrecepcion;?>">
        </p></td>
    </tr>
</table>
</form>   
<?php
	$io_cxp->uf_destructor();
	unset($io_cxp);
?>   
<p>&nbsp;</p>
</body>
<script language="javascript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);

function ue_nuevo()
{
	f=document.formulario;
	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{	
		f.operacion.value="NUEVO";
		f.existe.value="FALSE";		
		f.action="sigesp_cxp_p_ncnd.php";
		f.submit();
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_guardar()
{
	f=document.formulario;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_existe=f.existe.value;
	li_estcon=f.txtestcontipdoc.value;
	li_estpre=f.txtestpretipdoc.value;
	ls_estncnd=f.txtestsol.value;
	estapro=f.txtestapro.value;
	connota=f.txtconnota.value;
	if(estapro==0)
	{
		if((li_estcon==1)&&((li_estpre==3)||(li_estpre==4)))
		{
			li_numrowsdt    = uf_chequear_dt('C');
			f.rowscon.value = li_numrowsdt;
		}
		else
		{
			li_numfilcon    = uf_chequear_dt('C');
			f.rowscon.value = li_numfilcon;
			
			li_numrowsdt    = uf_chequear_dt('P');
			f.rowspre.value = li_numrowsdt;				
		}
		if( ( (lb_existe=="TRUE")&&(li_cambiar==1) ) || ( (lb_existe=="FALSE")&&(li_incluir==1) ) )
		{
			valido=true;
			if(li_numrowsdt>0)
			{
				ls_numncnd=f.txtnumncnd.value;
				ls_numord =f.txtnumord.value;
				if(f.tipproben[0].checked)
				{
					ls_tipproben='P';
				}
				else
				{
					ls_tipproben='B';
				}
				ls_codproben=f.txtcodproben.value;
				ls_numrecdoc=f.txtnumrecdoc.value;
		
				if((ls_numncnd!="") && (connota!=""))
				{
					lb_valido=true;		
				}
				else
				{
					lb_valido=false;
					alert("Debe registrar el numero de la Nota y descripción");
				}
				if(ls_numord!="" && lb_valido)
				{
					lb_valido=true;		
				}
				else
				{
					lb_valido=false;
					alert("Debe seleccionar la Orden de Pago");
				}
				if(ls_tipproben!="" && ls_codproben!=""  && lb_valido)
				{
					lb_valido=true;		
				}
				else
				{
					lb_valido=false;
					alert("Verifique los datos del Proveedor o Beneficiario");
				}
				if(ls_numrecdoc!="" && lb_valido)
				{
					lb_valido=true;		
				}
				else
				{
					lb_valido=false;
					alert("Seleccione la Recepción de Documento");
				}			
				if(lb_valido)
				{
					f.operacion.value="GUARDAR";
					f.action="sigesp_cxp_p_ncnd.php";
					f.submit();
				}
			}
			else
			{
				alert("Debe registrar los detalles de la Nota");
			}
		}
		else
		{
			alert("No tiene permiso para realizar esta operación");
		}
	}
	else
	{
		alert("La nota no puede ser Modificada, esta ya fue Aprobada");
	}
}

function ue_eliminar()
{
	f=document.formulario;
	li_eliminar=f.eliminar.value;
	ls_estncnd=f.txtestsol.value;
	if(li_eliminar==1)
	{	
		if(f.existe.value=="TRUE")
		{
			estapro=f.txtestapro.value;
			if(estapro=="1")
			{
				alert("La solicitud esta aprobada no la puede eliminar.");
			}
			else
			{
				if(confirm("Desea eliminar el Registro actual?"))
				{
					f.operacion.value="ELIMINAR";
					f.action="sigesp_cxp_p_ncnd.php";
					f.submit();
				}
			}
		}
		else
		{
			alert("Debe buscar el registro a eliminar.");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}
function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_buscar()
{
	f=document.formulario;
	li_leer=f.leer.value;
	if (li_leer==1)
   	{
		window.open("sigesp_cxp_cat_notas.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,left=50,top=50,location=no,resizable=yes");
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operacion");
   	}
}

function ue_imprimir()
{
	f=document.formulario;
	li_imprimir=f.imprimir.value;
	lb_existe=f.existe.value;
	if(li_imprimir==1)
	{
		if(lb_existe=="TRUE")
		{
			ls_numsol =f.txtnumncnd.value;
			ls_numord =f.txtnumord.value;
			ls_numrecdoc=f.txtnumrecdoc.value;
			ls_codtipdoc=f.txttipdoc.value;
			if(f.tiponota[0].checked)
			{
				ls_tiponota='NC';
			}
			else
			{
				ls_tiponota='ND';
			}
			if(f.tipproben[0].checked)
			{
				ls_tipproben='P';
			}
			else
			{
				ls_tipproben='B';
			}
			ls_tiporeporte= f.cmbbsf.value;
			ls_codproben=f.txtcodproben.value;
			window.open("reportes/sigesp_cxp_rfs_ncnd.php?numnota="+ls_numsol+"&numord="+ls_numord+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben+"&tiponota="+ls_tiponota+"&tiporeporte="+ls_tiporeporte,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,left=0,top=0,location=no,resizable=yes");
		}
		else
		{
			alert("Debe existir un documento a imprimir");
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function uf_chequear_dt(ls_tipodoc)
{
	f=document.formulario;
	li_totalcon=f.numrowsconnota.value;
	li_totalpre=f.numrowsprenota.value;
	
	li_totalconrecep=f.rowsconrecepcion.value;
	li_totalprerecep=f.rowsprerecepcion.value;
	f.rowspre.value=li_totalpre;
	f.rowsprerecpcion.value=li_totalprerecep;
	f.rowsconrecpcion.value=li_totalconrecep;
	f.rowscon.value=li_totalcon;
	if(ls_tipodoc=='C')
	{
		li_selected=0;
		for(j=1;j<=li_totalcon;j++)
		{
			cuenta=eval("document.formulario.txtscgcuentancnd"+j+".value");
			dencuenta=eval("document.formulario.txtdencuentascgncnd"+j+".value");
			mondeb=eval("document.formulario.txtdebencnd"+j+".value");
			monhab=eval("document.formulario.txthaberncnd"+j+".value");
			if( cuenta!="" && dencuenta!="" && mondeb!="" && monhab!="")
			{
				li_selected=li_selected+1;				
			}								
		}		
	}
	else
	{
		li_selected=0;
		for(j=1;j<=li_totalpre;j++)
		{
			cuenta=eval("document.formulario.txtcuentaspgncnd"+j+".value");
			codestpro=eval("document.formulario.txtcodestproncnd"+j+".value");
			dencuenta=eval("document.formulario.txtdencuentancnd"+j+".value");
			monto=eval("document.formulario.txtmontoncnd"+j+".value");
			sc_cuenta=eval("document.formulario.txtscgcuentadt"+j+".value");
			den_sccuenta=eval("document.formulario.txtdenscgcuentadt"+j+".value");
			est_cargo=eval("document.formulario.txtestcargo"+j+".value");
			if(cuenta!="" && codestpro!="" && monto!="")
			{
				li_selected=li_selected+1;				  
			}								
		}		
	}
	return li_selected;		
}


function ue_catalogo_ordenes()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	ls_estncnd=f.txtestsol.value;
	if(ls_estncnd=='R')
	{
		if(estapro=="1")
		{
			alert("La Nota esta en Estatus Aprobada no puede ser Modificada");
		}
		else
		{
			window.open("sigesp_cxp_cat_solicitudpago.php?tipo=NCND","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
		}
	}
	else
	{
		alert("La nota no puede ser Modificada, esta en Estatus Contabilizada");
	}
}

function ue_catalogo_recepciones()
{
	f=document.formulario;
	estapro=f.txtestapro.value;
	ls_numord=f.txtnumord.value;
	if(f.tipproben[0].checked)
	{
	    ls_tipproben='P';
	}
	else
	{
		ls_tipproben='B';
	}
	ls_codproben=f.txtcodproben.value;
	ls_aux="?numord="+ls_numord+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben;
	ls_estncnd=f.txtestsol.value;
	if(ls_estncnd=='R')
	{
		if(estapro=="1")
		{
			alert("La Nota esta en Estatus Aprobada no puede ser Modificada");
		}
		else
		{
			window.open("sigesp_cxp_cat_recepcionesncnd.php"+ls_aux,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no,left=50,top=50");          
		}
	}
	else
	{
		alert("La nota no puede ser Modificada, esta en Estatus Contabilizada");
	}		
}

/////////////////////////////////////////////////////////////////////////////////////////////
// Funcion que carga los grid del detalle de la recepcion de documento y a su vez          //
// hace el llamado del metodo que crea el detalle de la nota 			                   //	
/////////////////////////////////////////////////////////////////////////////////////////////

function uf_cargar_dtrecepcion(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_tipproben,ls_codproben,ls_numncnd,ls_numord,ld_fecha,ls_codope)
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	divgrid = document.getElementById('detallesrecepcion');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
					uf_cargar_dtnota(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_tipproben,ls_codproben,ls_numncnd,ls_numord,ld_fecha,ls_codope);
					//close();
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La página no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
			}
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("funcion=DTRECEPCION&codemp="+ls_codemp+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben);
}

/////////////////////////////////////////////////////////////////////////////////////////////
// Funcion que recarga los grid del detalle de la recepcion de documento y a su vez        //
// hace el llamado del metodo que recarga  el detalle de la nota 			               //	
/////////////////////////////////////////////////////////////////////////////////////////////

function uf_reload_dtrecepcion(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_tipproben,ls_codproben,ls_numncnd,ls_numord,ld_fecha,ls_codope)
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	divgrid = document.getElementById('detallesrecepcion');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
					uf_reload_dtnota();
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La página no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
			}
		}
	}
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("funcion=DTRECEPCION&codemp="+ls_codemp+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben);	
}

function uf_reload_dtnota()
{
  f = document.formulario;
  li_totrowspre = f.rowspre.value;
  li_totrowscon = f.rowscon.value;
  parametros    = f.parametros.value;
  
  divgrid = document.getElementById("detallesnota");
  ajax=objetoAjax();
  ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
  ajax.onreadystatechange=function() {
  if (ajax.readyState==4) {
	   divgrid.innerHTML = ajax.responseText
	 }
  }
  ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  ajax.send("funcion=RELOAD_DTNOTA&rowspre="+li_totrowspre+"&rowscon="+li_totrowscon+parametros);
}

function uf_reload_dtnota_old()
{
	//Metodo que carga el detalle de la nota con los datos que tenia al momento de hacer el submit
	f=document.formulario;	
	li_total=f.rowspre.value;
	li_totalcon=f.rowscon.value;
	li=f.filadelete.value;
	li_selected=0;
	ls_campos="";
	ls_ctaprov=f.txtcuentaprov.value;
	ls_denctascg=f.txtdenctascg.value;
	ls_numrecdoc=f.txtnumrecdoc.value;
	ls_codproben   =f.txtcodproben.value;
	if(f.tipproben[0].checked)
	{
	    ls_tipproben='P';
	}
	else
	{
		ls_tipproben='B';
	}	
	ls_codtipdoc=f.txttipdoc.value;	
	li_estcon=f.txtestcontipdoc.value;
	li_estpre=f.txtestpretipdoc.value;
	ls_estncnd=f.txtestsol.value;
	estapro=f.txtestapro.value;
	ls_numncnd=f.txtnumncnd.value;
	ls_numord =f.txtnumord.value;
	ld_fecha  =f.txtfecregsol.value;
	if(f.tiponota[0].checked)
	{
		ls_codope='NC';
	}
	else
	{
		ls_codope='ND';
	}
	ls_campos=ls_campos+"&totalactual="+li_selected+"&txtctaprov="+ls_ctaprov+"&denctascg="+ls_denctascg+"&tiponota="+ls_codope+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc;		
	// Cargamos las variables para pasarlas al AJAX
	divgrid = document.getElementById('detallesnota');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
	
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La página no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
			}
		}
	}	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	ls_camposaux=f.parametros.value;	
	// Enviar todos los campos a la pagina para que haga el procesamiento
	if((li_estcon==1)&&((li_estpre==3)||(li_estpre==4)))//Chequeo si es contable para pintar el presupuesto igual colocando la variable li en 0 y asignando a la varialbe de eliminacion cntable el valor de la fila a eliminar.
	{
		ajax.send("funcion=AGREGARDTNOTACON"+ls_campos+ls_camposaux);
	}
	else
	{
		ajax.send("funcion=AGREGARDTNOTAPRE"+ls_campos+ls_camposaux);
	}
	f.filadelete.value=0;		
}
/////////////////////////////////////////////////////////////////////////////////////////////
// Funcion que carga los grid de detalle de la nota							               //	
/////////////////////////////////////////////////////////////////////////////////////////////
function uf_cargar_dtnota(ls_codemp,ls_numrecdoc,ls_codtipdoc,ls_tipproben,ls_codproben,ls_numncnd,ls_numord,ld_fecha,ls_codope)
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	divgrid = document.getElementById('detallesnota');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='../shared/imagebank/cargando.gif' width='32' height='32'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
					//close();
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La página no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
			}
		}
	}	
	ls_campos="&codemp="+ls_codemp+"&numrecdoc="+ls_numrecdoc+"&numncnd="+ls_numncnd+"&numord="+ls_numord+"&fecha="+ld_fecha+"&codtipdoc="+ls_codtipdoc+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben+"&tiponota="+ls_codope;
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("funcion=CARGARDTNOTA"+ls_campos);
	
}
/////////////////////////////////////////////////////////////////////////////////////////////
// Funcion que crea los grid del detalle de la recepcion de documento en blanco y a su vez //
// hace el llamado del metodo que crea el detalle de la nota tambien en blanco			   //	
/////////////////////////////////////////////////////////////////////////////////////////////
function uf_cargar_dtrecepcion_blanco()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	divgrid = document.getElementById('detallesrecepcion');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
					uf_cargar_dtnota_blanco();
					//close();
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La página no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
			}
		}
	}	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("funcion=DTRECEPCION&codemp= &numrecdoc= &codtipdoc= &tipo= &codproben= ");
}
/////////////////////////////////////////////////////////////////////////////////////////////
// Funcion que crea los grid del detalle de la nota en blanco 							   //	
/////////////////////////////////////////////////////////////////////////////////////////////
function uf_cargar_dtnota_blanco()
{
	f=document.formulario;
	// Cargamos las variables para pasarlas al AJAX
	divgrid = document.getElementById('detallesnota');
	// Instancia del Objeto AJAX
	ajax=objetoAjax();
	// Pagina donde están los métodos para buscar y pintar los resultados
	ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
	ajax.onreadystatechange=function(){
		if(ajax.readyState==1)
		{
			divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
		}
		else
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{//mostramos los datos dentro del contenedor
					divgrid.innerHTML = ajax.responseText;
					//close();
				}
				else
				{
					if(ajax.status==404)
					{
						divgrid.innerHTML = "La página no existe";
					}
					else
					{//mostramos el posible error     
						divgrid.innerHTML = "Error:".ajax.status;
					}
				}
			}
		}
	}	
	ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	// Enviar todos los campos a la pagina para que haga el procesamiento
	ajax.send("funcion=DTNOTA");	
}
function uf_agregar_dtnota()
{
	f=document.formulario;
	ls_numncnd=f.txtnumncnd.value;
	ls_numord =f.txtnumord.value;
	ls_numrecdoc=f.txtnumrecdoc.value;
	ls_tipproben=f.tipproben.value;
	if(f.tipproben[0].checked)
	{
	    ls_tipproben='P';
	}
	else
	{
		ls_tipproben='B';
	}
	if(f.tiponota[0].checked)
	{
	    ls_tiponota='NC';
	}
	else
	{
		ls_tiponota='ND';
	}
	ls_codproben=f.txtcodproben.value;
	ls_codtipdoc=f.txttipdoc.value;	
	li_estcon=f.txtestcontipdoc.value;
	li_estpre=f.txtestpretipdoc.value;
	ls_estncnd=f.txtestsol.value;
	estapro=f.txtestapro.value;
	if(ls_estncnd=='R')
	{
		if(estapro==0)
		{
			if((ls_numncnd!="")&&(ls_numord!="")&&(ls_numrecdoc!="")&&(ls_codtipdoc!="")&&(ls_codproben!=""))
			{
				ls_aux="?numncnd="+ls_numncnd+"&numord="+ls_numord+"&numrecdoc="+ls_numrecdoc+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben+"&codtipdoc="+ls_codtipdoc+"&tiponota="+ls_tiponota;
				if((li_estcon==1)&&((li_estpre==3)||(li_estpre==4)))
				{
					alert("Recuerde Editar el Monto del detalle Contable antes de Procesar el Registro");
					window.open("sigesp_cxp_cat_dtcontable.php"+ls_aux,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=790,height=400,resizable=yes,location=no,left=50,top=50");
				}
				else
				{
					alert("Recuerde Editar el Monto del detalle Presupuestario antes de Procesar el Registro");
					window.open("sigesp_cxp_cat_dtpresupuestario.php"+ls_aux,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=850,height=400,resizable=yes,location=no,left=50,top=50");
				}
			}
			else
			{
				alert("No puede agregar detalles, Complete los datos!!");
			}
		}
		else
		{
			alert("La nota no puede ser Modificada, esta ya fue Aprobada");
		}
	}
	else
	{
		alert("La nota no puede ser Modificada, esta en Estatus Contabilizada");
	}
}
function uf_agregar_dtcargos()
{
	f=document.formulario;
	ls_numncnd=f.txtnumncnd.value;
	ls_numord =f.txtnumord.value;
	ls_numrecdoc=f.txtnumrecdoc.value;
	ls_tipproben=f.tipproben.value;
	if(f.tipproben[0].checked)
	{
	    ls_tipproben='P';
	}
	else
	{
		ls_tipproben='B';
	}
	if(f.tiponota[0].checked)
	{
	    ls_tiponota='NC';
	}
	else
	{
		ls_tiponota='ND';
	}
	ls_codproben=f.txtcodproben.value;
	ls_codtipdoc=f.txttipdoc.value;	
	li_estcon=f.txtestcontipdoc.value;
	li_estpre=f.txtestpretipdoc.value;
	ldec_monto=f.txtmontosincargo.value;
	ls_estncnd=f.txtestsol.value;
	estapro=f.txtestapro.value;
	ls_aux="";
	if(ls_estncnd=='R')
	{
		if(estapro==0)
		{
			if((ls_numncnd!="")&&(ls_numord!="")&&(ls_numrecdoc!="")&&(ls_codtipdoc!="")&&(ls_codproben!=""))
			{	
				ls_aux="?numncnd="+ls_numncnd+"&numord="+ls_numord+"&numrecdoc="+ls_numrecdoc+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben+"&codtipdoc="+ls_codtipdoc+"&tiponota="+ls_tiponota+"&montodoc="+ldec_monto;
				if((li_estcon==1)&&((li_estpre==3)||(li_estpre==4)))
				{			
					alert("Cargos no aplican a documento tipo Contable!!");
				}
				else
				{
					if(parseFloat(uf_convertir_monto(ldec_monto))>0)
					{
						alert("De ser necesario, Recuerde Editar el Monto del Cargo antes de Procesar el Registro");
						window.open("sigesp_cxp_cat_notadtcargos.php"+ls_aux,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=790,height=400,resizable=yes,location=no,left=50,top=50");
					}
					else
					{
						alert("Debe Registrar algun detalle Presupuestario para obtener la Base imponible");
					}
				}
			}
			else
			{
				alert("No puede agregar detalles, Complete los datos!!");
			}
		}	
		else
		{
			alert("La nota no puede ser Modificada, esta ya fue Aprobada");
		}
	}
	else
	{
		alert("La nota no puede ser Modificada, esta en Estatus Contabilizada");
	}			
}

function uf_delete_dtnota(li)
{
	f=document.formulario;	
	li_total=f.numrowsprenota.value;
	li_totalcon=f.numrowsconnota.value;
	//li=f.filadelete.value;
	li_selected=0;
	ls_campos="";
	ls_ctaprov=f.txtcuentaprov.value;
	ls_denctascg=f.txtdenctascg.value;
	ls_numrecdoc=f.txtnumrecdoc.value;
	ls_codproben   =f.txtcodproben.value;
	if(f.tipproben[0].checked)
	{
	    ls_tipproben='P';
	}
	else
	{
		ls_tipproben='B';
	}	
	ls_codtipdoc=f.txttipdoc.value;	
	li_estcon=f.txtestcontipdoc.value;
	li_estpre=f.txtestpretipdoc.value;
	ls_estncnd=f.txtestsol.value;
	estapro=f.txtestapro.value;
	if(ls_estncnd=='R')
	{
		if(estapro==0)
		{
			if(li>0)
			{
				if(confirm("Seguro desea Eliminar el detalle?,Si presiona Aceptar debe volver a Registrar los Cargos"))
				{
					if(f.tiponota[0].checked)
					{
						ls_tiponota='NC';
					}
					else
					{
						ls_tiponota='ND';
					}
					if((li_estcon==1)&&((li_estpre==3)||(li_estpre==4)))//Chequeo si es contable para pintar el presupuesto igual colocando la variable li en 0 y asignando a la varialbe de eliminacion cntable el valor de la fila a eliminar.
					{
						for(j=1;j<=li_totalcon;j++)
						{
							cuenta=eval("document.formulario.txtscgcuentancnd"+j+".value");
							dencuenta=eval("document.formulario.txtdencuentascgncnd"+j+".value");
							mondeb=eval("document.formulario.txtdebencnd"+j+".value");
							monhab=eval("document.formulario.txthaberncnd"+j+".value");
							if(li!=j && cuenta!=ls_ctaprov)
							{
								li_selected=li_selected+1;
								ls_campos=ls_campos+"&txtscgcuentancnd"+li_selected+"="+cuenta+
										   "&txtdencuentascgncnd"+li_selected+"="+dencuenta+"&txtdebencnd"+li_selected+"="+mondeb+"&txthaberncnd"+li_selected+"="+monhab;
									   
							}								
						}			
					}	
					else
					{		
					
						for(j=1;j<=li_total;j++)
						{
							cuenta=eval("document.formulario.txtcuentaspgncnd"+j+".value");
							codestpro=eval("document.formulario.txtcodestproncnd"+j+".value");
							dencuenta=eval("document.formulario.txtdencuentancnd"+j+".value");
							monto=eval("document.formulario.txtmontoncnd"+j+".value");
							sc_cuenta=eval("document.formulario.txtscgcuentadt"+j+".value");
							den_sccuenta=eval("document.formulario.txtdenscgcuentadt"+j+".value");
							est_cargo=eval("document.formulario.txtestcargo"+j+".value");
							codpro=eval("document.formulario.txtcodpro"+j+".value");
							estclancnd=eval("document.formulario.txtestclancnd"+j+".value");
							if((li!=j)&&(est_cargo!="C"))
							{
								li_selected=li_selected+1;
								ls_campos=ls_campos+"&txtcuentaspgncnd"+li_selected+"="+cuenta+"&txtcodestproncnd"+li_selected+"="+codestpro+
										   "&txtdencuentancnd"+li_selected+"="+dencuenta+"&txtmontoncnd"+li_selected+"="+monto+
										   "&txtscgcuentadt"+li_selected+"="+sc_cuenta+"&txtdenscgcuentadt"+li_selected+"="+den_sccuenta+
										   "&txtestcargo"+li_selected+"="+est_cargo+"&txtcodpro"+li_selected+"="+codpro+"&txtestclancnd"+li_selected+"="+estclancnd;
									   
							}								
						}
					}
					ls_campos=ls_campos+"&totalactual="+li_selected+"&txtctaprov="+ls_ctaprov+"&denctascg="+ls_denctascg+"&tiponota="+ls_tiponota+"&tipproben="+ls_tipproben+"&codproben="+ls_codproben+"&numrecdoc="+ls_numrecdoc+"&codtipdoc="+ls_codtipdoc;		
					// Cargamos las variables para pasarlas al AJAX
					divgrid = document.getElementById('detallesnota');
					// Instancia del Objeto AJAX
					ajax=objetoAjax();
					// Pagina donde están los métodos para buscar y pintar los resultados
					ajax.open("POST","class_folder/sigesp_cxp_c_ncnd_ajax.php",true);
					
					ajax.onreadystatechange=function(){
						if(ajax.readyState==1)
						{
							divgrid.innerHTML = "<img src='imagenes/loading.gif' width='350' height='200'>";//<-- aqui iria la precarga en AJAX 
						}
						else
						{
							if(ajax.readyState==4)
							{
								if(ajax.status==200)
								{//mostramos los datos dentro del contenedor
									divgrid.innerHTML = ajax.responseText;
								}
								else
								{
									if(ajax.status==404)
									{
										divgrid.innerHTML = "La página no existe";
									}
									else
									{//mostramos el posible error     
										divgrid.innerHTML = "Error:".ajax.status;
									}
								}
							}
						}
					}	
					ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
					// Enviar todos los campos a la pagina para que haga el procesamiento
					if((li_estcon==1)&&((li_estpre==3)||(li_estpre==4)))//Chequeo si es contable para pintar el presupuesto igual colocando la variable li en 0 y asignando a la varialbe de eliminacion cntable el valor de la fila a eliminar.
					{
						ajax.send("funcion=DTNOTACON"+ls_campos);
					}
					else
					{
						ajax.send("funcion=DTNOTAPRE"+ls_campos);
					}
					f.filadelete.value=0;		
				}
			}
			else
			{
				alert("No hay detalle Seleccionado, Haga Click sobre la fila que desea eliminar!!");
			}
		}	
		else
		{
			alert("La nota no puede ser Modificada, esta ya fue Aprobada");
		}
	}
	else
	{
		alert("La nota no puede ser Modificada, esta en Estatus Contabilizada");
	}		
}

function ue_reload()
{
	//Metodo de recarga de los datos de la nota al hacer submit y ocurrir un error de eliminacion o si la operacion era guardar
	f=document.formulario;	
	li_total=f.rowspre.value;
	li_totalcon=f.rowscon.value;
	li=f.filadelete.value;
	li_selected=0;
	ls_campos="";                                                                                
	ls_ctaprov=f.txtcuentaprov.value;
	ls_denctascg=f.txtdenctascg.value;
	ls_numrecdoc=f.txtnumrecdoc.value;
	ls_codproben   =f.txtcodproben.value;
	if(f.tipproben[0].checked)
	{
	    ls_tipproben='P';
	}
	else
	{
		ls_tipproben='B';
	}	
	ls_codtipdoc=f.txttipdoc.value;	
	li_estcon=f.txtestcontipdoc.value;
	li_estpre=f.txtestpretipdoc.value;
	ls_estncnd=f.txtestsol.value;
	estapro=f.txtestapro.value;
	ls_numncnd=f.txtnumncnd.value;
	ls_numord =f.txtnumord.value;
	ld_fecha  =f.txtfecregsol.value;
	if(f.tiponota[0].checked)
	{
		ls_codope='NC';
	}
	else
	{
		ls_codope='ND';
	}
	uf_reload_dtrecepcion("<?php print $_SESSION["la_empresa"]["codemp"]?>",ls_numrecdoc,ls_codtipdoc,ls_tipproben,ls_codproben,ls_numncnd,ls_numord,ld_fecha,ls_codope);
}			

function uf_cambio_tiponota()
{
	f=document.formulario;	
	ls_estncnd=f.txtestsol.value;
	estapro=f.txtestapro.value;
	if(ls_estncnd=='R')
	{
		if(estapro==0)
		{
			uf_cargar_dtnota_blanco();	
		}
		else
		{
			return false;
		}
	}	
	else
	{
		return false;
	}
}
function uf_select_filadelete(li)
{
	f=document.formulario;	
	ls_estncnd=f.txtestsol.value;
	estapro=f.txtestapro.value;
	if(ls_estncnd=='R')
	{
		if(estapro==0)
		{
			f.filadelete.value=li;
		}
	}		
}

function uf_mostrar_alerta()
{
	alert("Los Montos deben ser editados al momento de agregar el detalle ");
}
</script> 
<?php
if(($ls_operacion=="GUARDAR")||(($ls_operacion=="ELIMINAR")&&(!$lb_valido)))
{
	print "<script language=JavaScript>";
	print "   ue_reload();";
	print "</script>";
}
if(($ls_operacion=="NUEVO")||($ls_operacion=="ELIMINAR" && $lb_valido))
{
	print "<script language=JavaScript>";
	print "  uf_cargar_dtrecepcion_blanco();";
	print "</script>";
}
?>		  
</html>