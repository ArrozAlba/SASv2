<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
$dat=$_SESSION["la_empresa"];
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_carta_orden.php",$ls_permisos,$la_seguridad,$la_permisos);
$ls_ruta  = "txt/disco_banco/";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Carta Orden &Uacute;nica Nota de D&eacute;bito</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	background-color: #CCCCCC;
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
.Estilo5 {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.Estilo6 {
	color: #006699;
	font-size: 12px;
}
.Estilo8 {font-size: 10px; font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; }
.Estilo10 {font-size: 10px}
.Estilo11 {font-family: Verdana, Arial, Helvetica, sans-serif}
.Estilo13 {font-size: 12px}
.Estilo14 {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; }
-->
</style>
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>

</head>
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

<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="25" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Grabar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_descargar('<?PHP print $ls_ruta;?>');"><img src="../shared/imagebank/tools20/download.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="25">&nbsp;</td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
<?php
	require_once("../shared/class_folder/class_funciones.php");
	require_once("../shared/class_folder/class_sql.php");
    require_once("../shared/class_folder/sigesp_include.php");
	require_once("../shared/class_folder/ddlb_conceptos.php");
	require_once("../shared/class_folder/grid_param.php");
	require_once("../shared/class_folder/class_sql.php");
	require_once("sigesp_scb_c_carta_orden.php");

	
	$io_function 	= new class_funciones();	
	$lb_guardar  	= true;
    $sig_inc     	= new sigesp_include();
    $con         	= $sig_inc->uf_conectar();
	$msg         	= new class_mensajes();	
 	$obj_con     	= new ddlb_conceptos($con);
	$io_grid	 	= new grid_param();
	$in_class_carta = new sigesp_scb_c_carta_orden();
	$io_sql         = new class_sql($con);
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];

	require_once("sigesp_scb_c_movbanco.php");
	$in_classmovbanco=new sigesp_scb_c_movbanco($la_seguridad);
	require_once("sigesp_scb_c_config.php");
	$in_classconfig=new sigesp_scb_c_config($la_seguridad);

	if (array_key_exists("chktipvia",$_POST))
	   {
	     $li_tipvia = $_POST["chktipvia"];
	   }
    else
	   {
	     $li_tipvia = 0;
	   }
	$ls_checked = "";
	if ($li_tipvia=='1')
	   {
	     $ls_checked = 'checked';
         $ls_style  = 'style="visibility:visible"';
	   }
    else
	   {
         $ls_style  = 'style="visibility:hidden"';
	   }
	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_mov_operacion="ND";
		$ls_documento=$_POST["txtdocumento"];
		$ls_codban=$_POST["txtcodban"];
		$ls_denban=$_POST["txtdenban"];
		$ls_cuenta_banco=$_POST["txtcuenta"];
		$ls_dencuenta_banco=$_POST["txtdenominacion"];
		$ls_provbene="";
		$ls_desproben="";
		$ls_selnin = '-';
		$ls_tipo=$_POST["cmbtipdes"];
        $ls_disabled = 'disabled="disabled"';
		if ($ls_tipo=='P')
		   {
             $ls_style  = 'style="visibility:hidden"';
			 $ls_selpro = 'selected'; 
		     $ls_selben = '';
		   }
		elseif($ls_tipo=='B')
		   {
		     $ls_disabled = '';
			 if ($li_tipvia=='1')
			    {
			      $ls_style = 'style="visibility:visible"';
				}
			 else
			    {
			      $ls_style = 'style="visibility:hidden"';
				}
			 $ls_selben   = 'selected'; 
		     $ls_selpro   = '';
		   }
		else
		   {
		      $ls_selpro = "";
		      $ls_selben = "";
		   }
		$ls_chevau="";
		$ldec_montomov= $_POST["totalchq"];
		$ldec_monobjret=$_POST["txtmonobjret"];
		$ldec_montoret= $_POST["txtretenido"];
		$ldec_montomov= str_replace(".","",$ldec_montomov);
		$ldec_montomov= str_replace(",",".",$ldec_montomov);
		$ldec_monobjret=str_replace(".","",$ldec_monobjret);
		$ldec_monobjret=str_replace(",",".",$ldec_monobjret);
		$ldec_montoret= str_replace(".","",$ldec_montoret);
		$ldec_montoret= str_replace(",",".",$ldec_montoret);
		$ls_estmov    = $_POST["estmov"];		
		$ls_codconmov = $_POST["codconmov"];
		$ls_desmov    = $_POST["txtconcepto"];
		$ls_cuenta_scg= $_POST["txtcuenta_scg"];
		$ldec_disponible=$_POST["txtdisponible"];	
		$ld_fecha     = $_POST["txtfecha"];
	    $ls_metban    = $_POST["txtmetban"];
	    $ls_nommetban = $_POST["txtnommetban"];
		$ls_codbene   = $_POST["txtcodbene"];
		$ls_nombene   = $_POST["txtnombene"];
	}
	else
	{
		$ls_operacion= "NUEVO" ;	
		$ls_estmov="N";		
	    $ls_metban = "";
	    $ls_nommetban = "";
	    $ls_tipo = "-";
	}	
	$li_row=0;
	$li_rows_spg=0;
	$li_rows_ret=0;
	$li_rows_spi=0;

function uf_load_datos_recepcion($as_codemp,$as_numsol,&$ab_valido)
{
  global $io_sql,$msg,$io_function;
  
  $ab_valido  = true;
  $ls_procede = "";
  $ls_sql = " SELECT a.procede as procedencia	   ".
            "   FROM cxp_rd a,cxp_dt_solicitudes b ".
			"  WHERE b.codemp='".$as_codemp."'     ".
			"    AND b.numsol='".$as_numsol."'     ".
			"    AND a.numrecdoc=b.numrecdoc	   ".
			"    AND a.codtipdoc=b.codtipdoc       ".
			"    AND a.ced_bene=b.ced_bene         ".
			"    AND a.cod_pro=b.cod_pro           "; 
  $rs_data = $io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $ab_valido = false;
	   $msg->message("PROCESO->sigesp_scb_p_carta_orden.php;Metodo:uf_load_datos_recepcion;Error en consulta, ".$io_function->uf_convertirmsg($io_sql->message));
	 }
  else
     {
	   if ($row=$io_sql->fetch_row($rs_data))
	      {
		    $ls_procede = $row["procedencia"];
		  }
	 }
  return $ls_procede;
}

function uf_load_datos_beneficiario($as_codemp,$as_cedbene,$as_nombene,$as_apebene,&$ab_valido,$ls_procede)
{
  global $io_sql,$msg,$io_function;
  
  $lb_valido  = true;
  
  if ($ls_procede=='SCVSOV')
  {
	  $ls_sql = " SELECT distinct a.codcueban ,b.nomper as nombene,b.apeper as apebene,b.nacper as nacben
  				  FROM sno_personalnomina a , sno_personal b
				  WHERE a.codemp='".$as_codemp."' AND b.cedper='$as_cedbene'  AND a.codemp=b.codemp AND a.codper=b.codper "; 
  }
  else
    {
	  $ls_sql = " SELECT ctaban as codcueban,nombene,apebene,nacben ".
           		"   FROM rpc_beneficiario ".
				"  WHERE codemp='".$as_codemp."'     ".
				"    AND ced_bene='".$as_cedbene."'     ";  
  }
  $rs_data = $io_sql->select($ls_sql);

  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $msg->message("PROCESO->sigesp_scb_p_carta_orden.php;Metodo:uf_load_datos_recepcion;Error en consulta, ".$io_function->uf_convertirmsg($io_sql->message));
	 }
  else
     {
	   if ($row=$io_sql->fetch_row($rs_data))
	      {
		    $rs_datos["nombene"] = $row["nombene"];
			$rs_datos["ctabene"] = $row["codcueban"];
			$rs_datos["apebene"] = $row["apebene"];
			$rs_datos["nacper"]  = $row["nacben"];
		  }
	 }
  return $rs_datos;
}	
	
	if($ls_operacion=="CARGAR_DT")
	{
		$in_class_carta->uf_cargar_programaciones($ls_tipo,$ls_provbene,$ls_codban,$ls_cuenta_banco,&$object,&$li_rows,$li_tipvia);
	}	
	
	
	function uf_nuevo()
	{
		global $ls_mov_operacion;
		global $la_seguridad;
		$ls_mov_operacion="ND";
	    global $ls_opepre;
		$ls_opepre="";
		global $ls_documento;
		$ls_documento="";
		global $ls_codban;
		$ls_codban="";
		global $ls_denban;
		$ls_denban="";
		global $ls_estmov;
		$ls_estmov="N";
		global $ls_cuenta_banco;
		$ls_cuenta_banco="";
		global $ls_dencuenta_banco;
		$ls_dencuenta_banco="";	
		global $ls_provbene;
		$ls_provbene="----------";
		global $ls_desproben;
		$ls_desproben="Ninguno";
		global $ls_tipo;
		$ls_tipo="-";
		global $ls_chevau;
		require_once("sigesp_scb_c_movbanco.php");
		$in_classmovbanco=new sigesp_scb_c_movbanco($la_seguridad);
		global $ls_empresa;
		global $ldec_disponible;	
		$ldec_disponible="";	
		$ls_chevau = $in_classmovbanco->uf_generar_voucher($ls_empresa);
		global $ld_fecha;
		global $ldec_montomov;
		$ldec_montomov="";
		global $ldec_monobjret;
		$ldec_monobjret="";
		global $ldec_montoret;
		$ldec_montoret="";
		global $ls_codconmov;
		$ls_codconmov='---';
		global $ls_desmov;
		$ls_desmov="";
		global $ls_cuenta_scg;
		$ls_cuenta_scg="";
		global $li_rows;
		global $li_temp;
		global $object;
		global $ld_fecha;
		global $ls_metban;
		global $ls_nommetban;
		$ls_metban = "";
		$ls_nommetban = "";
		global $ls_style,$ls_disabled;
        $ls_style  = 'style="visibility:hidden"';
		$ls_disabled = 'disabled="disabled"';
		global $ls_selnin,$ls_selpro,$ls_selben;
		$ls_selnin = '-';
		$ls_selpro = "";
		$ls_selben = "";
		global $ls_codbene;
		global $ls_nombene;
		$ls_codbene=$_SESSION["la_empresa"]["cedben"];
		$ls_nombene=$_SESSION["la_empresa"]["nomben"];
		if(array_key_exists("la_deducciones",$_SESSION))
		{
			unset($_SESSION["la_deducciones"]);
		}
		$li_temp=1;
		$li_rows=$li_temp;
		$ld_fecha=date("d/m/Y");
		$object[$li_temp][1]  = "<input name=chk".$li_temp." type=checkbox 			      id=chk".$li_temp." 				value=1   class=sin-borde onClick=javascript:uf_selected('".$li_temp."');><input type=hidden  name=txtcodban".$li_temp."  id=txtcodban".$li_temp." value='' readonly>";
		$object[$li_temp][2]  = "<input type=text 	  name=txtnumsol".$li_temp." 		  id=txtnumsol".$li_temp."  		value=''  class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
		$object[$li_temp][3]  = "<input type=text 	  name=txtconsol".$li_temp." 		  id=txtconsol".$li_temp."			value=''  class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
		$object[$li_temp][4]  = "<input type=hidden   name=txtcodproben".$li_temp."  	  id=txtcodproben".$li_temp."		value=''  class=sin-borde readonly style=text-align:left size=20 maxlength=20><input type=text name=txtnomproben".$li_temp." id=txtnomproben".$li_temp."  value=''  class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
		$object[$li_temp][5]  = "<input type=text 	  name=txtmonsol".$li_temp."          id=txtmonsol".$li_temp."			value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=16 maxlength=6>";
		$object[$li_temp][6]  = "<input type=text	  name=txtmontopendiente".$li_temp."  id=txtmontopendiente".$li_temp."  value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=16 maxlength=3>";				
		$object[$li_temp][7]  = "<input type=text     name=txtmonto".$li_temp."           id=txtmonto".$li_temp."			value='".number_format(0,2,",",".")."' class=sin-borde onBlur=javascript:uf_actualizar_monto(".$li_temp."); style=text-align:right size=16 maxlength=20>";							
		$object[$li_temp][8]  = "<input type=hidden   name=txtmonobjret".$li_temp."       id=txtmonobjret".$li_temp."		value='".number_format(0,2,",",".")."'  class=sin-borde readonly style=text-align:right size=15 maxlength=3><input type=text name=txtmonret".$li_temp."  value='".number_format(0,2,",",".")."'  class=sin-borde readonly style=text-align:right size=15 maxlength=25>";
		$object[$li_temp][9]  = "<input type=text     name=txtnomban".$li_temp."  	      id=txtnomban".$li_temp."          value=''  class=sin-borde  readonly style=text-align:left size=30 maxlength=254>";
		$object[$li_temp][10] = "<input type=text     name=txtctaban".$li_temp."  	      id=txtctaban".$li_temp."          value=''  class=sin-borde  readonly style=text-align:left size=25 maxlength=25><input type=hidden  name=txtdenctaban".$li_temp."  id=txtdenctaban".$li_temp."  value=''><input type=hidden  name=txtdenctaban".$li_temp."  id=txtdenctaban".$li_temp."  value=''><input type=hidden  name=txtdenctaban".$li_temp."  id=txtdenctaban".$li_temp."  value=''><input type=hidden  name=txtcodtipcta".$li_temp."  id=txtcodtipcta".$li_temp."  value=''><input type=hidden  name=txtnomtipcta".$li_temp."  id=txtnomtipcta".$li_temp."  value=''><input type=hidden  name=txtscgcuenta".$li_temp."  id=txtscgcuenta".$li_temp."  value=''><input type=hidden  name=txtdisponible".$li_temp."  id=txtdisponible".$li_temp."  value='0,00'>";				
		
	}

	$title[1]="";   $title[2]="Solicitud";    $title[3]="Concepto"; $title[4]="Proveedor/Beneficiario";  $title[5]="Monto"; $title[6]="Monto Pendiente";  $title[7]="Monto a Pagar"; $title[8]="Retenido"; $title[9]="Banco"; $title[10]="Cuenta";
	$grid="grid";	
 	
	if($ls_operacion == "NUEVO")
	{
		$ls_operacion= "" ;
		uf_nuevo();
	}

 if ($ls_operacion=="GUARDAR")
	{		
		require_once("../shared/class_folder/class_datastore.php");
		$ds_sol_cancel=new class_datastore();
		$ds_banco_nomina=new class_datastore();
		require_once("../sno/sigesp_sno_c_metodo_banco.php");
	    $_SESSION["la_nomina"]["codnom"]='00000';
	    $_SESSION["la_nomina"]["peractnom"]='';
		$io_metodobanco=new  sigesp_sno_c_metodo_banco();
		$ls_codemp=$dat["codemp"];
		
		if($ls_tipo=='P')
		{
			$ls_codpro=$ls_provbene;
			$ls_cedbene="----------";
		}
		else
		{
			$ls_codpro="----------";
			$ls_cedbene=$ls_provbene;
		}
		
		
		$li_totalRows = $_POST["totalrows"];
		$arr_movbco["codban"]   = $ls_codban;
		$arr_movbco["ctaban"]   = $ls_cuenta_banco;
		$ld_fecdb=$io_function->uf_convertirdatetobd($ld_fecha);
		$arr_movbco["codope"]   = 'ND';
		$arr_movbco["fecha"]    = $ld_fecha;
		$arr_movbco["estmov"]   = $ls_estmov;
		$ls_numcarord  = $ls_documento;
		$ls_probentemp = "";
		$li_aux=0;
		$li_cont = 0;
		$in_classmovbanco->io_sql->begin_transaction();
		for($li_i=1;$li_i<=$li_totalRows;$li_i++)				
		{
			if(array_key_exists("chk".$li_i,$_POST))
			{
				$li_aux++;
				$ls_numsol   = $_POST["txtnumsol".$li_i];
				$ldec_monsol = $_POST["txtmonsol".$li_i];
				$ls_codproben= $_POST["txtcodproben".$li_i];
				$ls_ctaban   = $_POST["txtctaban".$li_i];
				$ldec_monsol = str_replace(".","",$ldec_monsol);
				$ldec_monsol = str_replace(",",".",$ldec_monsol);
				$ldec_montopendiente=$_POST["txtmontopendiente".$li_i];
				$ldec_montopendiente=str_replace(".","",$ldec_montopendiente);
				$ldec_montopendiente=str_replace(",",".",$ldec_montopendiente);
				$ldec_monto = $_POST["txtmonto".$li_i];
				$ldec_monto = str_replace(".","",$ldec_monto);
				$ldec_monto = str_replace(",",".",$ldec_monto);
				$ldec_monobjret = $_POST["txtmonobjret".$li_i];
				$ldec_monobjret = str_replace(".","",$ldec_monobjret);
				$ldec_monobjret = str_replace(",",".",$ldec_monobjret);
				$ls_desproben=$_POST["txtnomproben".$li_i];
				$ls_consol=$_POST["txtconsol".$li_i];
				$ls_tipocta=$_POST["txtcodtipcta".$li_i];
				$ls_codbanbene=$_POST["txtcodban".$li_i];
			    $ls_codfuefin=$_POST["txtcodfuefin".$li_i];
				if($ls_tipo=='P')
				{
					$ls_codpro  = $ls_codproben;
					$ls_cedbene = "----------";
				}
				else
				{
					$ls_codpro  = "----------";
					$ls_cedbene = $ls_codproben;
				}
				
				$ls_procede = uf_load_datos_recepcion($ls_codemp,$ls_numsol,$lb_valido);//Encontrar la procedencia de la Recepcion de Documentos asociadas a la Solicitud de Pago.

				$rs_datosbene=uf_load_datos_beneficiario($ls_codemp,$ls_cedbene,&$ls_nombene,&$ls_apebene,&$lb_valido,$ls_procede);				
				$ls_ctaban=$rs_datosbene["ctabene"];

				if($ls_codban==$ls_codbanbene)
				{			
					if($ls_ctaban!="")
					{
						if ($lb_valido)
						   {
							 if ($ls_procede=='SCVSOV')
								{
								  $li_cont++;
								  $aa_seguridad["empresa"]=$ls_codemp;
								  $aa_seguridad["sistema"]="SNO";
								  $aa_seguridad["logusr"]=$_SESSION["la_logusr"];
								  $aa_seguridad["ventanas"]="sigesp_sno_r_listadobanco.php";
								  $ds_banco["codper"][$li_cont]= $io_function->uf_cerosizquierda($ls_cedbene,10);
								  $ds_banco["cedper"][$li_cont]= $ls_cedbene; 
								  $ds_banco["nomper"][$li_cont]= $rs_datosbene["nombene"];
								  $ds_banco["apeper"][$li_cont]= $rs_datosbene["apebene"]; 
								  $ds_banco["nacper"][$li_cont]= $rs_datosbene["nacper"];
								  $ds_banco["codcueban"][$li_cont]=$rs_datosbene["ctabene"];
								  $ds_banco["tipcuebanper"][$li_cont]=$ls_tipocta;
								  $ds_banco["monnetres"][$li_cont]=$ldec_monto;
								}
						   }
						if($li_aux==1)
						{
							$arr_movbco["mov_document"]   = $ls_numcarord;
							$arr_movbco["objret"]   = $ldec_monobjret;
							$lb_valido=$in_class_carta->uf_procesar_movbanco($ls_codban,$ls_cuenta_banco,$ls_numcarord,$ls_mov_operacion,$ld_fecha,$ls_desmov,$ls_codconmov,$ls_codpro,$ls_codbene,$ls_nombene,$ldec_montomov,$ldec_monobjret,$ldec_montoret,$ls_chevau,$ls_estmov,0,0,'T','SCBCOR','',$ls_tipo,$ls_numcarord);
						}	
					   $lb_valido=$in_class_carta->uf_insert_fuentefinancimiento($ls_codemp,$ls_codban,$ls_cuenta_banco,$ls_numcarord,'ND',$ls_estmov,$ls_codfuefin);
						///////////////// Guardo el detalle de las solicitudes de pago cancelades en esta carta orden ///////////////////
						$lb_valido=$in_class_carta->uf_procesar_dtmov($ls_codemp, $ls_codban, $ls_cuenta_banco, $ls_numcarord, $ls_mov_operacion,'N', $ls_codpro, $ls_cedbene, $ls_numsol, $ldec_monto,$rs_datosbene["ctabene"]);
						/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						
						if($ldec_montopendiente==$ldec_monto)
						{
							$ls_estsol='C';	//Cancelado							
						}
						else
						{
							$ls_estsol='P';//Programado
						}
						$lb_valido=$in_class_carta->uf_procesar_carta_orden($ls_codban,$ls_cuenta_banco,$ls_numcarord,$ls_mov_operacion,$ls_numsol,$ls_estmov,$ldec_monto,$ls_estsol);
						if($lb_valido)//Segundo
						{
							$ldec_montotot=$ldec_montomov-$ldec_montoret;
							$lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta_scg,'SCBCOR',$ls_desmov,$ls_numcarord,'H',$ldec_monto,$ldec_monobjret,false,'00000');
							if($lb_valido)//Tercer if
							{
								$ls_ctaprovbene=$in_class_carta->uf_select_ctaprovbene($ls_tipo,$ls_codproben,&$as_codban,&$as_ctaban);
								//Reemplazo los valores de banco y cuenta banco por los del proveedor.
								$lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_ctaprovbene,'CXPSOP',$ls_desmov,$ls_numsol,'D',$ldec_monto,$ldec_monobjret,false,'00000');
								if($lb_valido)//Cuarto if
								{
										$ldec_monto_spg=0;
										$in_class_carta->uf_buscar_dt_cxpspg($ls_numsol);
										if(array_key_exists("codestpro1",$in_class_carta->ds_sol->data))
										{
											$li_total_rows=$in_class_carta->ds_sol->getRowCount("codestpro1");
											for($li_x=1;$li_x<=$li_total_rows;$li_x++)
											{
												$ldec_monto_aux=$in_class_carta->ds_sol->getValue("monto",$li_x);
												$ldec_monto_spg=$ldec_monto_spg + $ldec_monto_aux;
											}
											
											$ldec_montospg2=0;
											for($li_y=1;$li_y<=$li_total_rows;$li_y++)
											{
												
												$ldec_monto_aux=$in_class_carta->ds_sol->getValue("monto",$li_y);							
												
												if($lb_valido)
												{
								
													if($ls_estsol!="C")
													{
														  $ldec_MontoSpgDet = round(round($ldec_monto_aux , 2 ) *($ldec_monto  / $ldec_monto_spg),2);
														  $ldec_montospg2= $ldec_montospg2 + $ldec_MontoSpgDet;
													}
													else
													{
														$ldec_MontoSpgDet =round($ldec_monto_aux,2);
														$ldec_montospg2 = $ldec_montospg2 + $ldec_MontoSpgDet;
													}
												
													if( ($ldec_MontoSpgDet > $ldec_monto)&&($ls_estsol!="C"))
													{												
													   $ldec_MontoSpgDet = $ldec_monto;
													   $ldec_montospg2    = $ldec_MontoSpgDet;
													}
													if( ($ldec_montospg2 > $ldec_monto)&&($ls_estsol!="C"))
													{
													   $ldec_MontoSpgDet = $ldec_MontoSpgDet - ($ldec_montospg2 - $ldec_monto);
													}
													if(($ldec_montospg2 < $ldec_monto)&&($li_y==$li_total_rows)&&($ldec_montospg2!=$ldec_monto_spg))
													{
													   $ldec_MontoSpgDet = $ldec_MontoSpgDet + ($ldec_monto - $ldec_montospg2);
													}												
													$ls_estcla     = $in_class_carta->ds_sol->getValue("estcla",$li_y);
													$ls_codestpro1 = $in_class_carta->ds_sol->getValue("codestpro1",$li_y);
													$ls_codestpro2 = $in_class_carta->ds_sol->getValue("codestpro2",$li_y);
													$ls_codestpro3 = $in_class_carta->ds_sol->getValue("codestpro3",$li_y);
													$ls_codestpro4 = $in_class_carta->ds_sol->getValue("codestpro4",$li_y);
													$ls_codestpro5 = $in_class_carta->ds_sol->getValue("codestpro5",$li_y);			
													$ls_programa   = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
													$ls_cuentaspg  = $in_class_carta->ds_sol->getValue("spg_cuenta",$li_y);										
													$lb_valido     = $in_classmovbanco->uf_procesar_dt_gasto($ls_codban,$ls_cuenta_banco,$ls_numcarord,'ND',$ls_estmov,$ls_programa,$ls_cuentaspg,$ls_numsol,$ls_desmov,'CXPSOP',$ldec_MontoSpgDet,'PG',$ls_estcla);
												}//End if								
											}//End for							
										}							
								}//End cuarto if
							}//End tercer if
						}//End segundo if	
					$ls_probentemp=$ls_codproben;
					}
					else
					{
						$msg->message("El proveedor o Beneficiario ".$ls_desproben." no tiene cuenta bancaria asociada");	
					}
				}
				else
				{
					$msg->message("El proveedor o Beneficiario ".$ls_desproben." tiene asociado un banco distinto al del movimiento");
				}
			}							
		}//End for	
					
	  $ds_banco_nomina->data=$ds_banco;
	  $ls_ruta = "txt/disco_banco/".$ls_numcarord;
	  @mkdir($ls_ruta,0755);
	  $lb_valido = $io_metodobanco->uf_metodo_banco($ls_ruta,$ls_nommetban,'','','',$ld_fecha,$ldec_montotot,$ls_ctaban,$ds_banco_nomina,$ls_metban,$ls_consol,$la_seguridad);
	
		if($lb_valido)
		{
			$in_classmovbanco->io_sql->commit();
			$msg->message("Movimiento registrado");
			
			$ls_procede = "";
		    $ls_codigo=$in_classconfig->uf_buscar_seleccionado();
			if($ls_codigo!="000")//distinto de chequevoucher
				$ls_pagina="reportes/sigesp_scb_rpp_cartaorden_pdf.php?codigo=$ls_codigo&codban=$ls_codban&ctaban=$ls_cuenta_banco&numdoc=$ls_numcarord&chevau=&codope=ND&tipproben=$ls_tipo";
			else
				$ls_pagina="reportes/sigesp_scb_rpp_voucher_pdf.php?codban=$ls_codban&ctaban=$ls_cuenta_banco&numdoc=$ls_numcarord&chevau=&codope=ND";			
			?>
			<script language="javascript">						
				window.open('<?php print $ls_pagina; ?>',"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
			</script>
			<?php 
		}
		else
		{
			$in_classmovbanco->io_sql->rollback();
			$msg->message("No pudo registrarse el movimiento".$in_class_carta->is_msg_error."  ".$in_classmovbanco->is_msg_error);
		}		
		uf_nuevo();			
	}
	
	if($ls_tipo=='-')
	{
		$rb_n="checked";
		$rb_p="";
		$rb_b="";			
	}
	if($ls_tipo=='P')
	{
		$rb_n="";
		$rb_p="checked";
		$rb_b="";			
	}
	if($ls_tipo=='B')
	{
		$rb_n="";
		$rb_p="";
		$rb_b="checked";			
	}

?>
  <form action="" method="post" name="form1">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <br>
  <table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="5">Carta Orden </td>
    </tr>
    <tr>
      <td height="13" colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">N&ordm; Carta Orden</div></td>
      <td height="22"><input name="txtdocumento" type="text" id="txtdocumento" value="<?php print $ls_documento;?>" size="24" maxlength="15" onBlur="javascript:rellenar_cad(this.value,15,'doc');" style="text-align:center">
      <input name="estmovld" type="hidden" id="estmovld" value="<?php print $ls_estmov;?>"></td>
      <td height="22" colspan="2" style="text-align:right">&nbsp;</td>
      <td height="22">Fecha
      <input name="txtfecha" type="text" id="txtfecha" value="<?php print $ld_fecha;?>" size="15" maxlength="10" style="text-align:left" datepicker="true"></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Concepto</div></td>
      <td height="22" colspan="4"><div align="left">
          <?php $obj_con->uf_cargar_conceptos($ls_mov_operacion,$ls_codconmov);	?>
          <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Concepto Mov. </div></td>
      <td height="22" colspan="4"><input name="txtconcepto" type="text" id="txtconcepto" value="<?php print $ls_desmov;?>" size="120" onKeyPress="return keyRestrict(event,'0123456789'+'abcdefghijklmnopqrstuvwxyz&ntilde; .,*/-()$%&!&ordm;&ordf;&aacute;&eacute;&iacute;&oacute;&uacute;[]{}<>')">      </td>
    </tr>
    
    <tr>
      <td height="22"><div align="right">Tipo Destino </div></td>
      <td height="22"><label>
        <select name="cmbtipdes" id="cmbtipdes" onChange="uf_cambio();">
          <option value="-" <?php print $ls_selnin ?>>---seleccione---</option>
          <option value="P" <?php print $ls_selpro ?>>Proveedor</option>
          <option value="B" <?php print $ls_selben ?>>Beneficiario</option>
        </select>
      &nbsp;&nbsp;&nbsp;&nbsp;</label></td>
      <td height="22" colspan="2" style="text-align:right">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22"><div align="right">Tipo Vi&aacute;tico </div></td>
      <td height="22"><label>
        <input name="chktipvia" type="checkbox" class="sin-borde" id="chktipvia" value="1" <?php print $ls_disabled ?> onClick="javascript:uf_check_boton();" <?php print $ls_checked ?>>
      </label></td>
      <td height="22" colspan="3"><label></label>
        M&eacute;todo a Banco
        <label>
        <input name="txtmetban" type="text" id="txtmetban" value="<?php print $ls_metban ?>" size="6" maxlength="4" readonly style="text-align:center">
      <img src="../shared/imagebank/tools15/buscar.gif" name="buscarmetban" width="15" height="15" id="buscarmetban" <?php print $ls_style ?> onClick="javascript:uf_load_metodos_banco();"> 
      <input name="txtnommetban" type="text" class="sin-borde" id="txtnommetban" style="text-align:left" value="<?php print $ls_nommetban ?>" size="55" maxlength="55" readonly>
      </label></td>
    </tr>
    <tr>
      <td width="95" height="22"><div align="right">Banco</div></td>
      <td height="22" colspan="4"><div align="left">
          <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
          <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
          <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="105" class="sin-borde" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Cuenta</div></td>
      <td height="22" colspan="4"><div align="left">
          <input name="txtcuenta"        type="text"   id="txtcuenta"    style="text-align:center" value="<?php print $ls_cuenta_banco; ?>" size="30" maxlength="25" readonly>
          <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
          <input name="txtdenominacion"  type="text"   class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="85" maxlength="254" readonly>
          <input name="txttipocuenta"    type="hidden" id="txttipocuenta">
          <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Cuenta Contable </div></td>
      <td width="216" height="22"><div align="left">
          <input name="txtcuenta_scg" type="text" id="txtcuenta_scg" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly>
      </div></td>
      <td height="22" colspan="2"><div align="right">Disponible</div></td>
      <td width="146" height="22"><div align="left">
          <input name="txtdisponible" type="text" id="txtdisponible" style="text-align:right" value="<?php print $ldec_disponible;?>" size="22" readonly>
      </div></td>
    </tr>
    <tr>
      <td height="22"><div align="right">Total</div></td>
      <td height="22"><input name="totalchq" type="text" id="totalchq" style="text-align:right" value="<?php print number_format($ldec_montomov,2,",",".");?>" size="24" readonly></td>
      <td width="220" height="22">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;M. O. R
      <input name="txtmonobjret" type="text" id="txtmonobjret" style="text-align:right" value="<?php print  number_format($ldec_monobjret,2,",",".");?>" size="22" readonly>      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
      <td width="83">
      Monto Retenido</td>
      <td height="22"><label>
        <input name="txtretenido" type="text" id="txtretenido" value="<?php print number_format($ldec_montoret,2,",",".");?>" size="22" maxlength="22" style="text-align:right" readonly>
      </label></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13" colspan="2"><input name="txtcodbene" type="hidden" id="txtcodbene" value="<?php print $ls_codbene;?>">
      <input name="txtnombene" type="hidden" id="txtnombene" value="<?php print $ls_nombene;?>"></td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="5"><div align="center"><?php $io_grid->make_gridScroll($li_rows,$title,$object,760,'Solicitudes Programadas',$grid,145);?>
        <input name="fila_selected" type="hidden" id="fila_selected">
        <input name="totalrows" type="hidden" id="totalrows" value="<?php print $li_rows;?>">
        <input name="operacion" type="hidden" id="operacion">
        <input name="estmov" type="hidden" id="estmov" value="<?php print $ls_estmov;?>">
        <input name="txtcodbene" type="hidden" id="txtcodbene" value="<?php print $ls_codbene;?>">
        <input name="txtnombene" type="hidden" id="txtnombene" value="<?php print $ls_nombene;?>">
      </div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
<script language="javascript">
	function ue_nuevo()
	{
	f=document.form1;
	f.operacion.value ="NUEVO";
	f.action="sigesp_scb_p_carta_orden.php";
	f.submit();
	}
	
	
	function ue_guardar()
	{
		f			= document.form1;
		ls_numdoc	= f.txtdocumento.value;
		ls_concepto = f.txtconcepto.value;
		ldec_monto	= f.totalchq.value;
		ldec_monto	= uf_convertir_monto(ldec_monto);
		ls_fecha	= f.txtfecha.value;
		ls_codban	= f.txtcodban.value;
		ls_cuenta	= f.txtcuenta.value;
		ls_tipdes   = f.cmbtipdes.value;
		ls_metban   = f.txtmetban.value;
		li_tipvia   = f.chktipvia.value;
		li_totrows  = f.totalrows.value;
		if((ls_numdoc!="")&&(ls_concepto!="")&&(ldec_monto>0) && (ls_fecha!="") && (ls_codban!="") && (ls_cuenta!="") && (li_totrows>0))
		{
			if (f.chktipvia.checked && ls_metban=="")
			   {
                 alert("Debe seleccionar em Método a Banco !!!");
			   }
			else
			   {
    		     f.operacion.value ="GUARDAR";
			     f.action="sigesp_scb_p_carta_orden.php";
			     f.submit();		
			   }
		}
		else
		{
			alert("Complete todos los datos para poder registrar la Carta Orden !!!");
		}	
	}

	function uf_cargar_dt()
	{
		f=document.form1;
	    f.txtcodban.value 	    = "";
	    f.txtdenban.value 	    = "";
	    f.txtcuenta.value 	    = "";
	    f.txtdenominacion.value = "";
	    f.totalchq.value        = "0,00";
	    f.txtmonobjret.value    = "0,00";
	    f.txtretenido.value     = "0,00";
	    f.txttipocuenta.value   = ""; 
	    f.txtdentipocuenta.value= ""; 
	    f.txtcuenta_scg.value   = ""; 
	    f.txtdisponible.value   = "0,00"; 
		f.operacion.value       = "CARGAR_DT";
		f.action				= "sigesp_scb_p_carta_orden.php";
		f.submit();		
	}
	
	function uf_cambio()
	{
	  f=document.form1;
	  ls_tipdes = f.cmbtipdes.value;
	  if (ls_tipdes=='B')
	     {
	       f.chktipvia.disabled = false;
		 } 
	  else
	     {
	       f.chktipvia.checked = false;
		   f.chktipvia.disabled = true;
	       eval("document.images['buscarmetban'].style.visibility='hidden'");
		   f.txtnommetban.value = "";
		   f.txtmetban.value = "";
		 }
	   uf_cargar_dt();
	} 

	function ue_cerrar()
	{
		f=document.form1;
		f.action="sigespwindow_blank.php";
		f.submit();
	}

    //Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,campo)
	{
		if(cadena!="")
		{
			var mystring=new String(cadena);
			cadena_ceros="";
			lencad=mystring.length;
		
			total=longitud-lencad;
			for(i=1;i<=total;i++)
			{
				cadena_ceros=cadena_ceros+"0";
			}
			cadena=cadena_ceros+cadena;
			if(campo=="doc")
			{
				document.form1.txtdocumento.value=cadena;
			}
			if(campo=="cmp")
			{
				document.form1.txtcomprobante.value=cadena;
			}
			if(campo=="cod")
			{
				document.form1.txtcodigo.value=cadena;
			}
			if(campo=="chequera")
			{
				document.form1.txtchequera.value=cadena;
			}
			if(campo=="numcheque")
			{
				document.form1.txtnumcheque.value=cadena;
			}
			if(campo=="desde")
			{
				document.form1.txtdesde.value=cadena;
			}
			if(campo=="hasta")
			{
				document.form1.txthasta.value=cadena;
			}
		}	
	}
	
	//Catalogo de cuentas contables
	function catalogo_cuentabanco()
	 {
	   f=document.form1;
	   ls_codban=f.txtcodban.value;
	   ls_nomban=f.txtdenban.value;
	  	   if((ls_codban!=""))
		   {
			   pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_nomban;
			   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
		   }
		   else
		   {
				alert("Seleccione el Banco");   
		   }
	  
	 }
	 
	 function catalogo_cuentascg()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_filt_scg.php?filtro="+'11102'+"&opener=sigesp_scb_d_colocacion.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	 	 
	 function cat_bancos()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_bancos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }

	function cat_conceptos()
	{
	   f=document.form1;
	   pagina="sigesp_cat_conceptos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	}
	
  function currencyDate(date)
  { 
	ls_date=date.value;
	li_long=ls_date.length;
	f=document.form1;
			 
		if(li_long==2)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(0,2);
			li_string=parseInt(ls_string,10);

			if((li_string>=1)&&(li_string<=31))
			{
				date.value=ls_date;
			}
			else
			{
				date.value="";
			}
			
		}
		if(li_long==5)
		{
			ls_date=ls_date+"/";
			ls_string=ls_date.substr(3,2);
			li_string=parseInt(ls_string,10);
			if((li_string>=1)&&(li_string<=12))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,3);
			}
		}
		if(li_long==10)
		{
			ls_string=ls_date.substr(6,4);
			li_string=parseInt(ls_string,10);
			if((li_string>=1900)&&(li_string<=2090))
			{
				date.value=ls_date;
			}
			else
			{
				date.value=ls_date.substr(0,6);
			}
		}
   }
      
   

   
	function catprovbene()
	{
		f=document.form1;
		if(f.rb_provbene[0].checked==true)
		{
			f.txtprovbene.disabled=false;	
			window.open("sigesp_cat_prog_proveedores.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=565,height=400,left=50,top=50,location=no,resizable=yes");
		}
		else if(f.rb_provbene[1].checked==true)
		{
			f.txtprovbene.disabled=false;	
			window.open("sigesp_cat_prog_beneficiario.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=565,height=400,left=50,top=50,location=no,resizable=yes");
		}
	}   

	function uf_verificar_provbene(lb_checked,obj)
	{
		f=document.form1;
	
		if((f.rb_provbene[0].checked)&&(obj!='P'))
		{
			f.tipo.value='P';		
		}
		if((f.rb_provbene[1].checked)&&(obj!='B'))
		{
			f.tipo.value='B';
		}
		
	}

    function uf_format(obj)
   {
		ldec_monto=uf_convertir(obj.value);
		obj.value=ldec_monto;
   }

	function uf_selected(li_i)
	{
		f		   = document.form1;
		li_totrows = f.totalrows.value;
		ls_banco   = f.txtcodban.value;
		ls_cuenta  = f.txtcuenta.value;
		li_totsel  = 0;
		for (i=1;i<=li_totrows;i++)
		    {
			  if (eval("f.chk"+i+".checked"))
			     {
			       li_totsel++;
				   ls_codban 	 = eval("f.txtcodban"+i+".value");
			 	   ls_nomban 	 = eval("f.txtnomban"+i+".value");
				   ls_ctaban 	 = eval("f.txtctaban"+i+".value");
				   ls_denctaban  = eval("f.txtdenctaban"+i+".value");
				   ls_codtipcta  = eval("f.txtcodtipcta"+i+".value");
				   ls_nomtipcta  = eval("f.txtnomtipcta"+i+".value");
				   ls_sccuenta   = eval("f.txtscgcuenta"+i+".value");
				   ld_disponible = eval("f.txtdisponible"+i+".value");
				   if (li_totsel==1 && ls_cuenta=="" && ls_banco=="")
				      {
						f.txtcodban.value       = ls_codban;
						f.txtdenban.value       = ls_nomban;
						f.txtcuenta.value       = ls_ctaban;
					    f.txtdenominacion.value = ls_denctaban;
						f.txttipocuenta.value   = ls_codtipcta; 
						f.txtdentipocuenta.value= ls_nomtipcta; 
						f.txtcuenta_scg.value   = ls_sccuenta; 
						f.txtdisponible.value   = ld_disponible; 
						f.fila_selected.value   = li_i;
						uf_actualizar_monto(li_i);
					  }	 
				   else
				      {
					    if (ls_banco!=ls_codban && ls_cuenta!=ls_ctaban)
						   {
						     alert("El Banco o la Cuenta Bancaria son distintos !!!");
						     eval("f.chk"+li_i+".checked=false");
						   }
					  } 
				 }
			}
		if (li_totsel==0)
		   {
			 f.txtcodban.value 	     = "";
			 f.txtdenban.value 	     = "";
			 f.txtcuenta.value 	     = "";
		     f.txtdenominacion.value = "";
		     f.totalchq.value        = "0,00";
		     f.txtmonobjret.value    = "0,00";
		     f.txtretenido.value     = "0,00";
			 f.txttipocuenta.value   = ""; 
			 f.txtdentipocuenta.value= ""; 
			 f.txtcuenta_scg.value   = ""; 
			 f.txtdisponible.value   = "0,00"; 
		   }
		   uf_calcular();
	}
	
	function uf_actualizar_monto(li_i)
	{
		f=document.form1;
		ldec_monto= eval("f.txtmonto"+li_i+".value");
		ldec_monret=eval("f.txtmonret"+li_i+".value");
		ldec_montopendiente= eval("f.txtmontopendiente"+li_i+".value");
		ldec_temp1=ldec_monto;
		ldec_temp2=ldec_montopendiente;
		while(ldec_temp1.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_temp1=ldec_temp1.replace(".","");
		}
		ldec_temp1=ldec_temp1.replace(",",".");
		while(ldec_temp2.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_temp2=ldec_temp2.replace(".","");
		}
		ldec_temp2=ldec_temp2.replace(",",".");
	
		if(parseFloat(ldec_temp1)<=parseFloat(ldec_temp2))
		{
			eval("f.txtmonto"+li_i+".value='"+uf_convertir(ldec_monto)+"'");
		}
		else
		{
			alert("Monto a cancelar no puede ser mayor al monto pendiente");
			eval("f.txtmonto"+li_i+".value='"+ldec_montopendiente+"'");	
			eval("f.txtmonto"+li_i+".focus()");
		}
		uf_calcular();
	}
	
	function uf_calcular()
	{
	    f=document.form1;
		li_total=f.totalrows.value;
		ldec_total=0;
		ldec_totalret=0;
		for(i=1;i<=li_total;i++)
		{
			if(eval("f.chk"+i+".checked"))
			{
				ldec_monto=eval("f.txtmonto"+i+".value");
				ldec_montoret=eval("f.txtmonret"+i+".value");
				while(ldec_monto.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					ldec_monto=ldec_monto.replace(".","");
				}
				ldec_monto=ldec_monto.replace(",",".");
				while(ldec_montoret.indexOf('.')>0)
				{//Elimino todos los puntos o separadores de miles
					ldec_montoret=ldec_montoret.replace(".","");
				}
				ldec_montoret=ldec_montoret.replace(",",".");
				ldec_total=parseFloat(ldec_total)+parseFloat(ldec_monto);
				f.totalchq.value=uf_convertir(ldec_total);
			}
		}
		
	}
   
   function uf_cat_deducciones() 
   {
   	   f=document.form1;
	   ls_documento=f.txtdocumento.value;
	   ldec_monto=f.totalchq.value;
	   ldec_monobjret=f.txtmonobjret.value;	   
	   pagina="sigesp_cat_deducciones.php?monto="+ldec_monto+"&objret="+ldec_monobjret+"&txtdocumento="+ls_documento;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
   }
   
	function cat_cheque()
  	{
	   f=document.form1;
	   ls_codban=f.txtcodban.value;
	   ls_ctaban=f.txtcuenta.value;	   
	   if((ls_codban!="")&&(ls_ctaban!=""))
	   {
	   	   pagina="sigesp_cat_cheques.php?codban="+ls_codban+"&ctaban="+ls_ctaban;
	   	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	   }
	}
	
	function uf_validar_monobjret(txtmonobjret)
	{
		f=document.form1;
		ldec_monobjret=txtmonobjret.value;
		ldec_monto=f.totalchq.value;
		while(ldec_monto.indexOf('.')>0)
		{//Elimino todos los puntos o separadores de miles
			ldec_monto=ldec_monto.replace(".","");
		}
		ldec_monto=ldec_monto.replace(",",".");
		if(ldec_monto>=ldec_monobjret)
		{
			txtmonobjret.value=uf_convertir(ldec_monobjret);
		}
		else
		{
			txtmonobjret.value=uf_convertir(ldec_monto);
			alert("Monto Objeto a Retención no puede ser mayor al monto total del Cheque.");
			txtmonobjret.focus();
		}	
	}

function uf_load_metodos_banco()
{
   pagina="sigesp_scb_cat_metodobanco.php";
   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function uf_check_boton()
{
  f = document.form1;
  if (f.chktipvia.checked==true)
     {
       eval("document.images['buscarmetban'].style.visibility='visible'");
	 }
  else
     {
	   eval("document.images['buscarmetban'].style.visibility='hidden'");
	 }
  uf_cargar_dt();
}

function ue_descargar(ruta)
{
  window.open("sigesp_scb_cat_directorio.php?ruta="+ruta+"","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}	
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>