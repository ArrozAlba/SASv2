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
$ls_report = $io_fun_banco->uf_select_config("SCB","REPORTE","CARTA_ORDEN","sigesp_scb_rpp_cartaorden_pdf.php","C");
$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Carta Orden M&uacute;ltiples Notas de D&eacute;bito</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../shared/css/general.css"  rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css"   rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/valida_fecha.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<style type="text/css">
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
.Estilo1 {color: #6699CC}
-->
</style>
</head>
<body>
<span class="toolbar"><a name="00"></a></span>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
  <td height="20" colspan="12" bgcolor="#E7E7E7">
    <table width="778" border="0" align="center" cellpadding="0" cellspacing="0">			
      <td width="430" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Caja y Banco</td>
	  <td width="350" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
	  <tr>
	    <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	<td bgcolor="#E7E7E7"><div align="right" class="letras-pequenas"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" width="22" class="toolbar"><div align="center"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo"   title="Nuevo"   width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif"          alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="22"><div align="center"><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif"            alt="Salir"   title="Salir"   width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="694">&nbsp;</td>
  </tr>
</table>
<?php
require_once("sigesp_scb_c_carta_orden.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/grid_param.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/ddlb_conceptos.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("class_folder/sigesp_scb_c_disponibilidad_financiera.php");
	
$io_function = new class_funciones();	
$io_include  = new sigesp_include();
$ls_conect   = $io_include->uf_conectar();
$io_msg      = new class_mensajes();	
$obj_con     = new ddlb_conceptos($ls_conect);
$io_grid	 = new grid_param();
$io_carord   = new sigesp_scb_c_carta_orden();
$io_sql      = new class_sql($ls_conect);
$ls_codemp   = $_SESSION["la_empresa"]["codemp"];
$io_disfin    = new sigesp_scb_c_disponibilidad_financiera("../");
$ls_tipvaldis = $io_disfin->uf_load_tipo_validacion();
$li_estciespg = $io_fun_banco->uf_load_estatus_cierre($li_estciespi,$li_estciescg);

require_once("sigesp_scb_c_movbanco.php");
require_once("sigesp_scb_c_config.php");
$in_classmovbanco=new sigesp_scb_c_movbanco($la_seguridad);
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
 if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_mov_operacion="ND";
		$ls_documento=$_POST["txtdocumento"];
		$ls_codban=$_POST["txtcodban"];
		$ls_denban=$_POST["txtdenban"];
		$ls_cuenta_banco= trim($_POST["txtcuenta"]);
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
		$ldec_montomov=$_POST["totalchq"];
		$ldec_monobjret=$_POST["txtmonobjret"];
		$ldec_montoret=$_POST["txtretenido"];
		$ldec_montomov=str_replace(".","",$ldec_montomov);
		$ldec_montomov=str_replace(",",".",$ldec_montomov);
		$ldec_monobjret=str_replace(".","",$ldec_monobjret);
		$ldec_monobjret=str_replace(",",".",$ldec_monobjret);
		$ldec_montoret=str_replace(".","",$ldec_montoret);
		$ldec_montoret=str_replace(",",".",$ldec_montoret);
		$ls_estmov=$_POST["estmov"];		
		$ls_codconmov=$_POST["codconmov"];
		$ls_desmov=$_POST["txtconcepto"];
		$ls_cuenta_scg=$_POST["txtcuenta_scg"];
		$ldec_disponible=$_POST["txtdisponible"];	
		$ld_fecha=$_POST["txtfecha"];
	    $ls_metban = $_POST["txtmetban"];
	    $ls_nommetban = $_POST["txtnommetban"];
		$ls_numordpagmin = $_POST["txtnumordpagmin"];
		$ls_codtipfon    = $_POST["hidcodtipfon"];
		$ld_monmaxmov    = $_POST["hidmonmaxmov"];
	}
 else
	{
	  $ls_operacion= "NUEVO" ;	
	  $ls_estmov="N";		
	  $ls_metban = "";
	  $ls_nommetban = "";
	}	

$ls_disable = "";
if ($li_estciescg==1)
   {
	 $ls_disable = "disabled";
   }
elseif(($li_estciespg==1 || $li_estciespi==1) && $li_estciescg==0 && $ls_operacion=="NUEVO")
   {
	 $io_msg->message("Ya fué procesado el Cierre Presupuestario, sólo serán cargadas Programaciones de Pago asociadas a Recepciones de Documentos netamente Contables !!!");	   
   }

$li_row=0;
$li_rows_spg=0;
$li_rows_ret=0;
$li_rows_spi=0;

function uf_load_datos_recepcion($as_codemp,$as_numsol,&$ab_valido)
{
  global $io_sql,$io_msg,$io_function;
  $lb_valido  = true;
  $ls_procede = "";
  $ls_sql = "SELECT cxp_rd.procede as procedencia ". 
			"	FROM cxp_rd ,cxp_dt_solicitudes ".
			"	WHERE cxp_dt_solicitudes.codemp='".$as_codemp."' ".
			"	AND cxp_dt_solicitudes.numsol='".$as_numsol."' ".
			"	AND cxp_rd.numrecdoc=cxp_dt_solicitudes.numrecdoc ". 
			"	AND cxp_rd.codtipdoc=cxp_dt_solicitudes.codtipdoc ".
			"	AND cxp_rd.ced_bene=cxp_dt_solicitudes.ced_bene ".
			"	AND cxp_rd.cod_pro=cxp_dt_solicitudes.cod_pro "; 
  
  $rs_data = $io_sql->select($ls_sql);
  if ($rs_data===false)
     {
	   $lb_valido = false;
	   $io_msg->message("PROCESO->sigesp_scb_p_carta_orden_mnd.php;Metodo:uf_load_datos_recepcion;Error en consulta, ".$io_function->uf_convertirmsg($io_sql->message));
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

function uf_load_datos_beneficiario($as_codemp,$as_cedbene,$as_nombene,$as_apebene,&$ab_valido,$ls_procede,$adec_monto)
{
  global $io_sql,$io_msg,$io_function;
  $lb_valido  = true;
  if ($ls_procede=='SCVSOV')
     {
       $ls_sql = "SELECT distinct a.codcueban,a.tipcuebanper as tipocta,b.nomper as nombene,b.apeper as apebene,
	                     b.nacper as nacben
	                FROM sno_personalnomina a , sno_personal b, sno_nomina c
                   WHERE b.codemp='".$as_codemp."' 
				     AND b.cedper='".$as_cedbene."'
					 AND c.espnom = 0
					 AND b.codemp=a.codemp 
					 AND b.codper=a.codper 
					 AND a.codnom=c.codnom";
     }
  else
     {
   	   $ls_sql = "SELECT ctaban as codcueban,tipcuebanben as tipocta, nombene,apebene,nacben ".
		  		 "  FROM rpc_beneficiario ".
 	             " WHERE codemp='".$as_codemp."'".
	             "   AND ced_bene='".$as_cedbene."'";
     }
  $rs_data = $io_sql->select($ls_sql);
  if ($rs_data===false)
     {
       $lb_valido = false;
       $io_msg->message("PROCESO->sigesp_scb_p_carta_orden_mnd.php;Metodo:uf_load_datos_beneficiario;Error en consulta, ".$io_function->uf_convertirmsg($io_sql->message));
     }
  else
     {
       if ($row=$io_sql->fetch_row($rs_data))
          {
	        $rs_datos["codcueban"] = trim($row["codcueban"]);
			$rs_datos["monnetres"] = $adec_monto;
			$rs_datos["cedper"] = $as_cedbene;
			$rs_datos["codper"] = $io_function->uf_cerosizquierda($as_cedbene,10);
			$rs_datos["nomper"] = $row["nombene"];
			$rs_datos["apeper"] = $row["apebene"];
	        $rs_datos["nacper"]  = $row["nacben"];
	        $rs_datos["tipcuebanper"] = $row["tipocta"];
		  }
     }
  return $rs_datos;
}
	
if ($ls_operacion=="CARGAR_DT")
   {
     $io_carord->uf_cargar_programaciones($ls_tipo,$ls_provbene,$ls_codban,$ls_cuenta_banco,&$object,&$li_rows,$li_tipvia,$ls_numordpagmin,$ls_codtipfon);
   }	
	
function uf_nuevo()
{
	global $ls_mov_operacion,$ls_numordpagmin,$ls_codtipfon,$ld_monmaxmov;
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
	global $ls_style,$ls_disabled,$ls_disable;
	$ls_style  = 'style="visibility:hidden"';
	$ls_disabled = 'disabled="disabled"';
	global $ls_selnin,$ls_selpro,$ls_selben;
	$ls_selnin = '-';
	$ls_selpro = "";
	$ls_selben = $ls_numordpagmin = $ls_codtipfon = "";
	$ld_monmaxmov = 0;
	if(array_key_exists("la_deducciones",$_SESSION))
	{
		unset($_SESSION["la_deducciones"]);
	}
	$li_temp=1;
	$li_rows=$li_temp;
	$ld_fecha=date("d/m/Y");
	$object[$li_temp][1] = "<input name=chk".$li_temp." type=checkbox 			      id=chk".$li_temp." 				value=1   class=sin-borde onClick=javascript:uf_selected('".$li_temp."'); $ls_disable><input type=hidden  name=txtcodban".$li_temp."  id=txtcodban".$li_temp." value='' readonly>";
	$object[$li_temp][2] = "<input type=text 	  name=txtnumsol".$li_temp." 		  id=txtnumsol".$li_temp."  		value=''  class=sin-borde readonly style=text-align:center size=15 maxlength=15>";
	$object[$li_temp][3] = "<input type=text 	  name=txtconsol".$li_temp." 		  id=txtconsol".$li_temp."			value=''  class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
	$object[$li_temp][4] = "<input type=hidden   name=txtcodproben".$li_temp."  	  id=txtcodproben".$li_temp."		value=''  class=sin-borde readonly style=text-align:left size=20 maxlength=20><input type=text name=txtnomproben".$li_temp." id=txtnomproben".$li_temp."  value=''  class=sin-borde readonly style=text-align:left size=30 maxlength=254>";
	$object[$li_temp][5] = "<input type=text 	  name=txtmonsol".$li_temp."          id=txtmonsol".$li_temp."			value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=16 maxlength=6>";
	$object[$li_temp][6] = "<input type=text	  name=txtmontopendiente".$li_temp."  id=txtmontopendiente".$li_temp."  value='".number_format(0,2,",",".")."' class=sin-borde readonly style=text-align:right size=16 maxlength=3>";				
	$object[$li_temp][7] = "<input type=text     name=txtmonto".$li_temp."           id=txtmonto".$li_temp."			value='".number_format(0,2,",",".")."' class=sin-borde onBlur=javascript:uf_actualizar_monto(".$li_temp."); style=text-align:right size=16 maxlength=20>";							
	$object[$li_temp][8] = "<input type=text     name=txtnomban".$li_temp."  	      id=txtnomban".$li_temp."          value=''  class=sin-borde  readonly style=text-align:left size=30 maxlength=254>";
	$object[$li_temp][9] = "<input type=text     name=txtctaban".$li_temp."  	      id=txtctaban".$li_temp."          value=''  class=sin-borde  readonly style=text-align:left size=25 maxlength=25><input type=hidden  name=txtdenctaban".$li_temp."  id=txtdenctaban".$li_temp."  value=''><input type=hidden  name=txtdenctaban".$li_temp."  id=txtdenctaban".$li_temp."  value=''><input type=hidden  name=txtdenctaban".$li_temp."  id=txtdenctaban".$li_temp."  value=''><input type=hidden  name=txtcodtipcta".$li_temp."  id=txtcodtipcta".$li_temp."  value=''><input type=hidden  name=txtnomtipcta".$li_temp."  id=txtnomtipcta".$li_temp."  value=''><input type=hidden  name=txtscgcuenta".$li_temp."  id=txtscgcuenta".$li_temp."  value=''><input type=hidden  name=txtdisponible".$li_temp."  id=txtdisponible".$li_temp."  value='0,00'>";				
}

$title[1]="";
$title[2]="Solicitud";
$title[3]="Concepto";
$title[4]="Proveedor/Beneficiario";
$title[5]="Monto";
$title[6]="Monto Pendiente";
$title[7]="Monto a Pagar";
$title[8]="Banco";
$title[9]="Cuenta";
$grid="grid";	
 	
if ($ls_operacion == "NUEVO")
   {
     $ls_operacion= "" ;
	 uf_nuevo();
   }

 if ($ls_operacion=="GUARDAR")
	{		
		$li_cont = 0;
		require_once("../shared/class_folder/class_datastore.php");
		$ds_sol_cancel=new class_datastore();
		$rs_datosbene=new class_datastore();
		$ls_clactacon = $_SESSION["la_empresa"]["clactacon"];		
		$li_totalRows = $_POST["totalrows"];
		$arr_movbco["codban"]   = $ls_codban;
		$arr_movbco["ctaban"]   = $ls_cuenta_banco;
		$ld_fecdb=$io_function->uf_convertirdatetobd($ld_fecha);
		$arr_movbco["codope"]   = 'ND';
		$arr_movbco["fecha"]    = $ld_fecha;
		$arr_movbco["estmov"]   = $ls_estmov;
		$ls_numcarord  = $ls_documento;
		$ls_probentemp = "";
		for ($li_i=1;$li_i<=$li_totalRows;$li_i++)				
		    {
			  if (array_key_exists("chk".$li_i,$_POST))
			     { 
				   $li_cont++;
			  	   $ls_numsol   		= $_POST["txtnumsol".$li_i];
				   $ldec_monsol 		= $_POST["txtmonsol".$li_i];
				   $ls_codproben		= $_POST["txtcodproben".$li_i];
				   $ldec_monsol 		= str_replace(".","",$ldec_monsol);
				   $ldec_monsol 		= str_replace(",",".",$ldec_monsol);
				   $ldec_montopendiente = $_POST["txtmontopendiente".$li_i];
				   $ldec_montopendiente = str_replace(".","",$ldec_montopendiente);
				   $ldec_montopendiente = str_replace(",",".",$ldec_montopendiente);
				   $ldec_monto 		    = $_POST["txtmonto".$li_i];
				   $ldec_monto 		    = str_replace(".","",$ldec_monto);
				   $ldec_monto 		    = str_replace(",",".",$ldec_monto);
				   $ls_desproben	    = $_POST["txtnomproben".$li_i];
				   $ls_codfuefin	    = $_POST["txtcodfuefin".$li_i];
				   if($ls_codfuefin=="")
				   {
						$ls_codfuefin="--";
				   }
				   if ($ls_tipo=='P')
				      {
					    $ls_codpro  = $ls_codproben;
					    $ls_cedbene = "----------";
				      }
				   else
				      {
					    $ls_codpro  = "----------";
					    $ls_cedbene = $ls_codproben;
				      }
				   $lb_valido  = true;
				   $ls_procede = uf_load_datos_recepcion($ls_codemp,$ls_numsol,$lb_valido);//Encontrar la procedencia de la Recepcion de Documentos asociadas a la Solicitud de Pago.
				   if ($ls_procede=='SCVSOV')
					  {
		  			    $rs_datosbene 			     = uf_load_datos_beneficiario($ls_codemp,$ls_cedbene,&$ls_nombene,&$ls_apebene,&$lb_valido,$ls_procede,$ldec_monto);
						$aa_seguridad["empresa"]	 = $ls_codemp;
						$aa_seguridad["sistema"]	 = "SCB";
						$aa_seguridad["logusr"]	     = $_SESSION["la_logusr"];
						$aa_seguridad["ventanas"]	 = "sigesp_scb_p_carta_orden_mnd.php";
						/*$ls_nombene				     = $rs_datosbene["nombene"];
						$ls_apebene 				 = $rs_datosbene["apebene"];
						$ds_banco["codper"][1]	     = $io_function->uf_cerosizquierda($ls_cedbene,10);
						$ds_banco["cedper"][1]	     = $ls_cedbene; 
						$ds_banco["nomper"][1]	     = $ls_nombene;
						$ds_banco["apeper"][1]	     = $ls_apebene;
						$ds_banco["nacper"][1]	     = $rs_datosbene["nacper"];
						$ds_banco["codcueban"][1]	 = $rs_datosbene["ctabene"];
						$ds_banco["tipcuebanper"][1] = $ls_tipocta;
						$ds_banco["monnetres"][1]	 = $ldec_monto;*/
				      }
				   if ($ls_codproben!=$ls_probentemp)
				      {
				        $ls_numdoc=$io_carord->uf_generar_num_documento($ls_codemp,$ls_mov_operacion);
 					    $arr_movbco["mov_document"] = $ls_numdoc;
					    $arr_movbco["objret"]   	= $ldec_monobjret;
					    $lb_valido=$io_carord->uf_procesar_movbanco($ls_codban,$ls_cuenta_banco,$ls_numdoc,$ls_mov_operacion,$ld_fecha,$ls_desmov,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_desproben,$ldec_monto,$ldec_monobjret,$ldec_montoret,$ls_chevau,$ls_estmov,0,0,'T','SCBCOR','',$ls_tipo,$ls_numcarord,$ls_codfuefin,$ls_numordpagmin,$ls_codtipfon);
				      }	
				   else
				      {
					    $io_carord->uf_select_monto_actual($ls_codemp,$ls_codban,$ls_cuenta_banco,$ls_numdoc,'ND',$ld_fecha,&$ldec_montoactual,&$ldec_monobjret_actual,&$ldec_monret_actual);
					    $ldec_montomov     = ($ldec_montoactual+$ldec_monto);
					    $ldec_monobjretmov = ($ldec_monobjret_actual+$ldec_monobjret);
					    $ldec_monretmov    = $ldec_monret_actual;	
					    $lb_valido         = $io_carord->uf_update_monto_movimiento($ls_codemp,$ls_codban,$ls_cuenta_banco,$ls_numdoc,'ND',$ld_fecha,$ldec_montomov,$ldec_monobjretmov,$ldec_monretmov);
				      }
				  $lb_valido=$io_carord->uf_insert_fuentefinancimiento($ls_codemp,$ls_codban,$ls_cuenta_banco,$ls_numdoc,'ND',$ls_estmov,$ls_codfuefin);
				   if ($ldec_montopendiente==$ldec_monto)
				      {
					    $ls_estsol='C';	//Cancelado							
				      }
				   else
				      {
					    $ls_estsol='P';//Programado
				      }
				   $lb_valido=$io_carord->uf_procesar_carta_orden($ls_codban,$ls_cuenta_banco,$ls_numdoc,$ls_mov_operacion,$ls_numsol,$ls_estmov,$ldec_monto,$ls_estsol);
				   if ($lb_valido)
				      {
//--------------------------------PARA EL CASO QUE LAS RETENCIONES SE APLIQUE DESDE CXP Y SE REFLEJAN EN BANCO------------					 
						   if ($lb_valido)
					       {
						     require_once("sigesp_scb_c_emision_chq.php");
							 $io_emiche  = new sigesp_scb_c_emision_chq();
						     $ls_estretiva = $_SESSION["la_empresa"]["estretiva"];
					         $ls_ctaprovbene=$io_carord->uf_select_ctaprovbene($ls_tipo,$ls_codproben,&$as_codban,&$as_ctaban);
						     if ($ls_estretiva=='B')//Retenciones aplicadas desde el Módulo de Cuentas Por Pagar y reflejadas en el Módulo Banco.
							    {
							      $ls_procede_doc = "CXPSOP";
								  $la_deducciones = $io_emiche->uf_load_retenciones_iva_cxp($ls_codemp,$ls_numsol);
								}
							 elseif($ls_estretiva=='C')//Retenciones aplicadas desde el Módulo de Cuentas Por Pagar.
							    {
								  $ls_procede_doc = "SCBBCH";
								  if (array_key_exists("la_deducciones",$_SESSION))
								     {
									   $la_deducciones=$_SESSION["la_deducciones"];
								     }										
								}
							 $li_total = 0;
							 $ld_montotret = 0;
							 if (!empty($la_deducciones))
							    {
								  $li_total = count($la_deducciones["codded"]);
							    }
							 for ($i=1;$i<=$li_total;$i++)
								 {
								   if (array_key_exists("$i",$la_deducciones["codded"]))
									  {
									    $ls_ctascg	    = trim($la_deducciones["sc_cuenta"][$i]);
									    $ls_dended	    = $la_deducciones["dended"][$i];
										$ls_codded	    = $la_deducciones["codded"][$i];
										$ldec_objret   = $la_deducciones["monobjret"][$i];
										$ldec_montoret = $la_deducciones["monret"][$i];
										$ld_montotret += $ldec_montoret; 
										if ($ls_codded!="")
										   {
										     $lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_ctascg,
											                                                       $ls_procede_doc,$ls_dended,
																								   $ls_numsol,'H',$ldec_montoret,
																								   $ldec_objret,true,$ls_codded);
										   }//FIN DEL IF
									  }//FIN DEL IF
								 }// FIN DEL FOR
							 if ($ls_estretiva=='B')
							    {
								  $ldec_montotot=$ldec_montomov;
							    }
							 elseif($ls_estretiva=='C')
								{
								  $ldec_montotot=($ldec_montomov-$ldec_montoret);
								}
							 unset($la_deducciones);
					       }//FIN DEL IF
///-----------------------------------------------------------------------------------------------------------------------------------					  
					    //$ldec_montotot = ($ldec_montomov-$ldec_montoret);
					    $lb_valido     = $in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta_scg,'SCBCOR',$ls_desmov,$ls_numdoc,'H',$ldec_monto,$ldec_monobjret,false,'00000');
					    if ($lb_valido)
					       {
						    if ($ls_clactacon==1)
							{
								$ls_ctaprovbene = $io_carord->uf_select_ctacxpclasificador($ls_numsol,$ls_tipo,$ls_codproben);
							}
							else
							{
								$ls_ctaprovbene=$io_carord->uf_select_ctaprovbene($ls_tipo,$ls_codproben,&$as_codban,&$as_ctaban);
						    }
							//Reemplazo los valores de banco y cuenta banco por los del proveedor.
						    /*$lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_ctaprovbene,'CXPSOP',$ls_desmov,$ls_numsol,'D',$ldec_monto,$ldec_monobjret,false,'00000');*/
							$lb_valido=$in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_ctaprovbene,'CXPSOP',$ls_desmov,$ls_numsol,'D',$ldec_monto+$ld_montotret,$ldec_monobjret,false,'00000');
							if ($lb_valido)
						       {
							     //Sustiyuyo nuevamente las del movimiento.
							     if ($lb_valido)
							        {
									  $ldec_monto_spg=0;
									  $io_carord->uf_buscar_dt_cxpspg($ls_numsol);
									  if (array_key_exists("codestpro1",$io_carord->ds_sol->data))
									     {
									       $li_total_rows=$io_carord->ds_sol->getRowCount("codestpro1");
									       for ($li_x=1;$li_x<=$li_total_rows;$li_x++)
									           {
												 $ldec_monto_aux=$io_carord->ds_sol->getValue("monto",$li_x);
												 $ldec_monto_spg=$ldec_monto_spg + $ldec_monto_aux;
									           }
										   $ldec_montospg2=0;
									       for ($li_y=1;$li_y<=$li_total_rows;$li_y++)
									           {
											     $ldec_monto_aux = $io_carord->ds_sol->getValue("monto",$li_y);
										         if ($lb_valido)
													{
													  if ($ls_estsol!="C")
											             {
												           $ldec_MontoSpgDet = round(round($ldec_monto_aux , 2 ) *($ldec_monto  / $ldec_monto_spg),2);
												           $ldec_montospg2= $ldec_montospg2 + $ldec_MontoSpgDet;
											             }
											          else
											             {
											         	   $ldec_MontoSpgDet =round($ldec_monto_aux,2);
												           $ldec_montospg2 = $ldec_montospg2 + $ldec_MontoSpgDet;
											             }
													  if (($ldec_MontoSpgDet > $ldec_monto)&&($ls_estsol!="C"))
														 {												
											   			   $ldec_MontoSpgDet = $ldec_monto;
											   			   $ldec_montospg2   = $ldec_MontoSpgDet;
														 }
											          if (($ldec_montospg2>$ldec_monto)&&($ls_estsol!="C"))
											             {
											               $ldec_MontoSpgDet = $ldec_MontoSpgDet-($ldec_montospg2-$ldec_monto);
											             }
											          if (($ldec_montospg2 < $ldec_monto)&&($li_y==$li_total_rows)&&($ldec_montospg2!=$ldec_monto_spg))
														 {
														   $ldec_MontoSpgDet = $ldec_MontoSpgDet + ($ldec_monto - $ldec_montospg2);
														 }
												 	  $ls_estcla     = $io_carord->ds_sol->getValue("estcla",$li_y);
													  $ls_codestpro1 = $io_carord->ds_sol->getValue("codestpro1",$li_y);
													  $ls_codestpro2 = $io_carord->ds_sol->getValue("codestpro2",$li_y);
													  $ls_codestpro3 = $io_carord->ds_sol->getValue("codestpro3",$li_y);
												   	  $ls_codestpro4 = $io_carord->ds_sol->getValue("codestpro4",$li_y);
												  	  $ls_codestpro5 = $io_carord->ds_sol->getValue("codestpro5",$li_y);			
													  $ls_programa   = $ls_codestpro1.$ls_codestpro2.$ls_codestpro3.$ls_codestpro4.$ls_codestpro5;
													  $ls_cuentaspg  = $io_carord->ds_sol->getValue("spg_cuenta",$li_y);										
													  $lb_valido     = $in_classmovbanco->uf_procesar_dt_gasto($ls_codban,$ls_cuenta_banco,$ls_numdoc,'ND',$ls_estmov,$ls_programa,$ls_cuentaspg,$ls_numsol,$ls_desmov,'CXPSOP',$ldec_MontoSpgDet,'PG',$ls_estcla);
										            }						
									           }
								         }
							        }
						       }
					       }
				      }
			        $ls_probentemp=$ls_codproben;
			     }							
		    }

   	 if ($lb_valido)
		{
			$in_classmovbanco->io_sql->commit();
			$io_msg->message("Movimiento registrado !!!");
		    if ($lb_valido)
			   {
				      //$ds_banco_nomina->data = $ds_banco;
					  //$li_numrows = $ds_banco_nomina->getRowCount("codper");
					  $li_numrows=$rs_datosbene->getRowCount('codper');
					  if ($li_numrows>0)
						 { 
						   $ls_ruta	 = "txt/disco_banco/".$ls_cedbene;
						   @mkdir($ls_ruta,0755);
						   require_once("../sno/sigesp_sno_c_metodo_banco.php");
						   $io_metodobanco=new sigesp_sno_c_metodo_banco();
						   $lb_valido = $io_metodobanco->uf_metodo_banco($ls_ruta,$ls_nommetban,''      ,''        ,''        ,$ld_fecha,$ldec_montomov,$ls_ctaban   ,$rs_datosbene,$ls_metban   ,$ls_consol,$la_seguridad);
						 } 								 //uf_metodo_banco($as_ruta,$as_metodo,$ac_codperi,$ad_fdesde,$ad_fhasta,$ad_fecproc,$adec_montot,$as_codcueban,&$rs_data    ,$as_codmetban,$as_desope,$as_quincena,$as_ref,$aa_seguridad)
					
			   }
			$ls_codigo=$in_classconfig->uf_buscar_seleccionado();
			if($ls_codigo!="000")//distinto de chequevoucher
				$ls_pagina="reportes/".$ls_report."?codigo=$ls_codigo&codban=$ls_codban&ctaban=$ls_cuenta_banco&numdoc=$ls_numcarord&chevau=&codope=ND&tipproben=$ls_tipo";
			else
				$ls_pagina="reportes/sigesp_scb_rpp_voucher_pdf.php?codban=$ls_codban&ctaban=$ls_cuenta_banco&numdoc=$ls_numdoc&chevau=&codope=ND";			
			?>
			<script language="javascript">						
			window.open('<?php print $ls_pagina; ?>',"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
			</script>
			<?php 
		}
		else
		{
			$in_classmovbanco->io_sql->rollback();
			$io_msg->message("No pudo registrarse el movimiento".$io_carord->is_msg_error."  ".$in_classmovbanco->is_msg_error);
		}		
		uf_nuevo();			
	}
	
 if ($ls_tipo=='-')
	{
	  $rb_n="checked";
	  $rb_p="";
	  $rb_b="";			
	}
 if ($ls_tipo=='P')
	{
	  $rb_n="";
	  $rb_p="checked";
	  $rb_b="";			
	}
 if ($ls_tipo=='B')
	{
	  $rb_n="";
	  $rb_p="";
	  $rb_b="checked";			
	}
?>
  <form action="" method="post" name="form1" id="sigesp_scb_p_carta_orden_mnd.php">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
  <br>
  <table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr class="titulo-ventana">
      <td height="22" colspan="4"><input name="hidcodtipfon" type="hidden" id="hidcodtipfon" value="<?php echo $ls_codtipfon; ?>">
        <input name="hiddentipfon" type="hidden" id="hiddentipfon">
        <input name="hidmonmaxmov" type="hidden" id="hidmonmaxmov" value="<?php echo $ld_monmaxmov; ?>">
        Carta Orden 
      <input name="hidestciescg" type="hidden" id="hidestciescg" value="<?php echo $li_estciescg; ?>">
      <input name="hidmesabi" type="hidden" id="hidmesabi" value="true"></td>
    </tr>
    <tr>
      <td height="13" colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">N&uacute;mero</td>
      <td height="22"><input name="txtdocumento" type="text" id="txtdocumento" value="<?php print $ls_documento;?>" size="24" maxlength="15" onBlur="javascript:rellenar_cad(this.value,15,'doc');" style="text-align:center" <?php echo $ls_disable; ?>>
      <input name="estmovld" type="hidden" id="estmovld" value="<?php print $ls_estmov;?>"></td>
      <td height="22" style="text-align:right"><div align="center"><span style="text-align:left">No. Orden Pago Ministerio
        <input name="txtnumordpagmin" type="text" id="txtnumordpagmin" onKeyPress="return keyRestrict(event,'0123456789'); " value="<?php echo $ls_numordpagmin; ?>" size="20" maxlength="15" style="text-align:center" readonly>
  &nbsp;<a href="javascript:uf_catalogo_ordenes();"><img src="../shared/imagebank/tools15/buscar.gif" alt="Buscar Ordenes de Pago Ministerio..." width="15" height="15" border="0" title="Buscar Ordenes de Pago Ministerio..."></a></span></div></td>
      <td height="22">Fecha
      <input name="txtfecha" type="text" id="txtfecha" value="<?php print $ld_fecha;?>" size="15" maxlength="10" style="text-align:left" datepicker="true" <?php echo $ls_disable; ?> onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);uf_validar_estatus_mes();"></td>
    </tr>
      <script language="javascript">uf_validar_estatus_mes();</script>
	<tr>
      <td height="22" style="text-align:right">Tipo Concepto</td>
      <td height="22" colspan="3"><?php $obj_con->uf_cargar_conceptos($ls_mov_operacion,$ls_codconmov);	?>
          <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>">
      </td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Concepto</td>
      <td height="22" colspan="3"><input name="txtconcepto" type="text" id="txtconcepto" value="<?php print $ls_desmov;?>" size="127" onKeyPress="return keyRestrict(event,'0123456789'+'abcdefghijklmnopqrstuvwxyz&ntilde; .,*/-()$%&!&ordm;&ordf;&aacute;&eacute;&iacute;&oacute;&uacute;[]{}<>')" <?php echo $ls_disable; ?>></td>
    </tr>
    
    <tr>
      <td height="22" style="text-align:right">Tipo Destino</td>
      <td height="22">
        <select name="cmbtipdes" id="cmbtipdes" onChange="uf_cambio();" <?php echo $ls_disable; ?>>
          <option value="-" <?php print $ls_selnin ?>>---seleccione---</option>
          <option value="P" <?php print $ls_selpro ?>>Proveedor</option>
          <option value="B" <?php print $ls_selben ?>>Beneficiario</option>
        </select>
      &nbsp;&nbsp;&nbsp;&nbsp;</td>
      <td height="22" style="text-align:right">&nbsp;</td>
      <td height="22">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Tipo Vi&aacute;tico</td>
      <td height="22"><input name="chktipvia" type="checkbox" class="sin-borde" id="chktipvia" value="1" <?php print $ls_disabled; ?> onClick="javascript:uf_check_boton();" <?php print $ls_checked;echo $ls_disable; ?>></td>
      <td height="22" colspan="2">
        M&eacute;todo a Banco
        <label>
        <input name="txtmetban" type="text" id="txtmetban" value="<?php print $ls_metban ?>" size="6" maxlength="4" readonly style="text-align:center">
      <img src="../shared/imagebank/tools15/buscar.gif" name="buscarmetban" width="15" height="15" id="buscarmetban" <?php print $ls_style ?> onClick="javascript:uf_load_metodos_banco();"> 
      <input name="txtnommetban" type="text" class="sin-borde" id="txtnommetban" style="text-align:left" value="<?php print $ls_nommetban ?>" size="60" maxlength="60" readonly>
      </label></td>
    </tr>
    <tr>
      <td width="94" height="22" style="text-align:right">Banco</td>
      <td height="22" colspan="3" style="text-align:left"><input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
          <a href="javascript:cat_bancos();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
          <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="105" class="sin-borde" readonly>
      </td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Cuenta</td>
      <td height="22" colspan="3">
          <input name="txtcuenta"        type="text"   id="txtcuenta"    style="text-align:center" value="<?php print $ls_cuenta_banco; ?>" size="30" maxlength="25" readonly>
          <a href="javascript:catalogo_cuentabanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
          <input name="txtdenominacion"  type="text"   class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_dencuenta_banco; ?>" size="85" maxlength="254" readonly>
          <input name="txttipocuenta"    type="hidden" id="txttipocuenta">
          <input name="txtdentipocuenta" type="hidden" id="txtdentipocuenta">
      </td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Cuenta Contable</td>
      <td width="165" height="22" style="text-align:left"><input name="txtcuenta_scg" type="text" id="txtcuenta_scg" style="text-align:center" value="<?php print $ls_cuenta_scg;?>" size="24" readonly></td>
      <td width="345" height="22" style="text-align:right">Disponible</td>
      <td width="156" height="22" style="text-align:left"><input name="txtdisponible" type="text" id="txtdisponible" style="text-align:right" value="<?php print $ldec_disponible;?>" size="22" readonly></td>
    </tr>
    <tr>
      <td height="22" style="text-align:right">Total</td>
      <td height="22"><input name="totalchq" type="text" id="totalchq" style="text-align:right" value="<?php print number_format($ldec_montomov,2,",",".");?>" size="24" readonly></td>
      <td height="22" style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;M.O.R 
          <input name="txtmonobjret" type="text" id="txtmonobjret" style="text-align:right" value="<?php print  number_format($ldec_monobjret,2,",",".");?>" size="22" readonly>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      Monto Retenido</td>
      <td height="22"><input name="txtretenido" type="text" id="txtretenido" value="<?php print number_format($ldec_montoret,2,",",".");?>" size="22" maxlength="22" style="text-align:right" readonly></td>
    </tr>
    <tr>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
      <td height="13">&nbsp;</td>
    </tr>
    <tr>
      <td height="22" colspan="4"><div align="center"><?php $io_grid->make_gridScroll($li_rows,$title,$object,760,'Solicitudes Programadas',$grid,145);?>
        <input name="fila_selected" type="hidden" id="fila_selected">
        <input name="totalrows" type="hidden" id="totalrows" value="<?php print $li_rows;?>">
        <input name="operacion" type="hidden" id="operacion">
        <input name="estmov" type="hidden" id="estmov" value="<?php print $ls_estmov;?>">
      </div></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
<script language="javascript">
f=document.form1;
var patron = new Array(2,2,4);
function ue_nuevo()
{
  if (uf_evaluate_cierre())
     {	
	   f.operacion.value ="NUEVO";
	   f.action="sigesp_scb_p_carta_orden_mnd.php";
	   f.submit();
	}
}	
	
function ue_guardar()
{
  lb_mesabi = f.hidmesabi.value;
  if (lb_mesabi=='true')
     {
	  if (uf_evaluate_cierre('SCG'))
		 {
		   ls_numdoc   = f.txtdocumento.value;
		   ls_concepto = f.txtconcepto.value;
		   ldec_monto  = f.totalchq.value;
		   ldec_monto  = uf_convertir_monto(ldec_monto);
		   ls_fecha	   = f.txtfecha.value;
		   ls_codban   = f.txtcodban.value;
		   ls_cuenta   = f.txtcuenta.value;
		   ls_tipdes   = f.cmbtipdes.value;
		   ls_metban   = f.txtmetban.value;
		   if (f.chktipvia.checked==true)
			  {
				li_tipvia = '1';
			  }
		   else
			  {
				li_tipvia ='0';
			  }
		   li_totrows  = f.totalrows.value;
		   if ((ls_numdoc!="")&&(ls_concepto!="")&&(ldec_monto>0) && (ls_fecha!="") && (ls_codban!="") && (ls_cuenta!="") && (li_totrows>0))
			  {
				if (li_tipvia=='1' && ls_metban=="")
				   {
					 alert("Debe seleccionar em Método a Banco !!!");
				   }
				else
				   {
					 ld_totmondis = f.txtdisponible.value;
					 ls_tipvaldis = "<?php echo $ls_tipvaldis; ?>";
					 lb_valido    = uf_validar_disponible("ND",ls_tipvaldis,ld_totmondis,f.totalchq.value);
					 if (lb_valido)
						{
						  f.operacion.value ="GUARDAR";
						  f.action="sigesp_scb_p_carta_orden_mnd.php";
						  f.submit();
						}
				   }
			  }
		   else
			  {
				alert("Complete todos los datos para poder registrar la Carta Orden !!!");
			  }	
		 }
	 }
  else
     {
	   alert("Operación No puede ser procesada, El Més está Cerrado !!!");
	 }
}

function uf_cargar_dt()
{
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
  f.action				= "sigesp_scb_p_carta_orden_mnd.php";
  f.submit();		
}
	
function uf_cambio()
{
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

function rellenar_cad(cadena,longitud,campo)
{
  if (cadena!="")
	 {
	   var mystring=new String(cadena);
		   cadena_ceros="";
		   lencad=mystring.length;
	   
	   total=longitud-lencad;
	   for (i=1;i<=total;i++)
		   {
		     cadena_ceros=cadena_ceros+"0";
		   }
	   cadena=cadena_ceros+cadena;
	   if (campo=="doc")
		  {
		    document.form1.txtdocumento.value=cadena;
		  }
	 }	
}
	
function catalogo_cuentabanco()
{
  if (uf_evaluate_cierre('SCG'))
     {
	   ls_codban=f.txtcodban.value;
	   ls_nomban=f.txtdenban.value;
	   if (ls_codban!="")
		  {
		    pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&hidnomban="+ls_nomban;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
		  }
	   else
		  {
		    alert("Seleccione el Banco !!!");
		  }
     }
}
	 
function catalogo_cuentascg()
{
 if (uf_evaluate_cierre('SCG'))
    {
	  pagina="sigesp_cat_filt_scg.php?filtro="+'11102'+"&opener=sigesp_scb_d_colocacion.php";
	  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	}
}
	 	 
function cat_bancos()
{
 if (uf_evaluate_cierre('SCG'))
    {
      pagina="sigesp_cat_bancos.php";
      window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
    }
}
   
function catprovbene()
{
  if (uf_evaluate_cierre('SCG'))
     {
	   if (f.rb_provbene[0].checked==true)
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
}   

function uf_verificar_provbene(lb_checked,obj)
{
  if ((f.rb_provbene[0].checked)&&(obj!='P'))
	 {
	   f.tipo.value='P';		
	 }
  if ((f.rb_provbene[1].checked)&&(obj!='B'))
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
    uf_validar_estatus_mes();
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
	ldec_monto			= eval("f.txtmonto"+li_i+".value");
	ldec_montopendiente = eval("f.txtmontopendiente"+li_i+".value");
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
		li_total=f.totalrows.value;
		ldec_total=0;
		for (i=1;i<=li_total;i++)
		    {
			  if (eval("f.chk"+i+".checked"))
			     {
				   ldec_monto=eval("f.txtmonto"+i+".value");
				   while(ldec_monto.indexOf('.')>0)
				        {
					      ldec_monto=ldec_monto.replace(".","");
				        }
				   ldec_monto = ldec_monto.replace(",",".");
				   ldec_total = parseFloat(ldec_total)+parseFloat(ldec_monto);
				   f.totalchq.value=uf_convertir(ldec_total);
			     }
		    }
	}
   
function uf_cat_deducciones() 
{
  if (uf_evaluate_cierre('SCG'))
     {
	   ls_documento	  =	f.txtdocumento.value;
	   ldec_monto	  = f.totalchq.value;
	   ldec_monobjret = f.txtmonobjret.value;	   
	   pagina		  = "sigesp_cat_deducciones.php?monto="+ldec_monto+"&objret="+ldec_monobjret+"&txtdocumento="+ls_documento;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	 }
}
   
function uf_validar_monobjret(txtmonobjret)
{
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

function uf_evaluate_cierre(as_tipafe)
{
  lb_valido = true;
  if (as_tipafe=='SPG' || as_tipafe=='SPI')
     {
       li_estciespg = f.hidestciespg.value;
       li_estciespi = f.hidestciespi.value;
	   if (li_estciespg==1 || li_estciespi==1)
		  {
		    lb_valido = false;
		    alert("Ya fué procesado el Cierre Presupuestario, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
		  }	   
	 }
  else
     {
	   if (as_tipafe=='SCG')
	      {
  		    li_estciescg = f.hidestciescg.value;
			if (li_estciescg==1)
			   {
			     lb_valido = false;
			     alert("Ya fué procesado el Cierre Contable, No pueden efectuarse movimientos, Contacte al Administrador del Sistema !!!");
			   }
		  }
	 }
  return lb_valido
}

function uf_catalogo_ordenes()
{
  pagina="sigesp_scb_cat_ordenes_pago_ministerio.php?origen=CO";
  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=450,resizable=yes,location=no,dependent=yes");
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>