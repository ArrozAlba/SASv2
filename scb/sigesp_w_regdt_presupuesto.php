<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "close();";
	 print "opener.document.form1.submit();";
	 print "</script>";		
   }
$la_empresa		  = $_SESSION["la_empresa"];
$li_estmodest     = $la_empresa["estmodest"];
$li_loncodestpro1 = $la_empresa["loncodestpro1"];
$li_loncodestpro2 = $la_empresa["loncodestpro2"];
$li_loncodestpro3 = $la_empresa["loncodestpro3"];
$li_loncodestpro4 = $la_empresa["loncodestpro4"];
$li_loncodestpro5 = $la_empresa["loncodestpro5"];

$li_size1 = $li_loncodestpro1+10;
$li_size2 = $li_loncodestpro2+10;
$li_size3 = $li_loncodestpro3+10;
$li_size4 = $li_loncodestpro4+10;
$li_size5 = $li_loncodestpro5+10;

require_once("../shared/class_folder/sigesp_include.php");	
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");	

$io_include = new sigesp_include();
$ls_connect = $io_include->uf_conectar();
$io_funcion = new class_funciones(); 
$io_msg     = new class_mensajes();
$io_sql     = new class_sql($ls_connect);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Entrada de Comprobante de Gastos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scb.js"></script>
<style type="text/css">
<!--
.style2 {font-size: 11px}
-->
</style>
</head>
<body>
<?php
if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion = $_POST["operacion"];
     $ls_estcla    = $_POST["hidtipestpro"];
	 $ls_estpro1=$_POST["codestpro1"];
	 $ls_estpro2=$_POST["codestpro2"];
	 $ls_estpro3=$_POST["codestpro3"];
	 if ($li_estmodest==2)
	    {
		 $ls_estpro4=$_POST["codestpro4"];
		 $ls_estpro5=$_POST["codestpro5"];
	    }
	 $ls_cuentaplan      = $_POST["txtcuenta"];
	 $ls_denominacion    = $_POST["txtdenominacion"];
	 $ls_procedencia     = $_POST["txtprocedencia"];
	 $ls_descripcion     = $_POST["txtdescripcion"];
	 $ls_comprobante     = $_POST["comprobante"];
	 $ls_proccomp        = $_POST["procede"];
	 $ls_desccomp        = $_POST["descripcion"];
	 $ld_fecha	         = $_POST["fecha"];
	 $ls_tipo            = $_POST["tipo"];
	 $ls_provbene        = $_POST["provbene"];
	 $ls_mov_document    = $_POST["mov_document"];
	 $ls_mov_procede     = $_POST["procede"];
	 $ld_fecha           = $_POST["fecha"];
	 $ls_provbene        = $_POST["provbene"];
	 $ls_tipo            = $_POST["tipo"];
	 $ls_mov_descripcion = $_POST["descripcion"];
	 $ls_codban          = $_POST["codban"];
	 $ls_ctaban          = $_POST["ctaban"];
	 $ls_cuenta_scg      = $_POST["cuenta_scg"];
	 $ls_codope          = $_POST["mov_operacion"];
	 $ldec_monto_mov     = $_POST["monto"];
	 $ldec_objret        = $_POST["objret"];
	 $ldec_retenido      = $_POST["retenido"];
	 $ls_chevau          = $_POST["chevau"];
	 $li_estint          = $_POST["estint"];
	 $li_estcob          = $_POST["estcob"];
	 $li_cobrapaga       = $_POST["cobrapaga"];
	 $ls_estbpd          = $_POST["estbpd"];
	 $ls_nomproben       = $_POST["txtnomproben"];
	 $ls_estmov          = $_POST["estmov"];
	 $ls_codconmov       = $_POST["codconmov"];
	 $ls_estreglib       = $_POST["tip_mov"];
	 $ls_opener          = $_POST["opener"];
	 $ls_estdoc          = $_POST["estdoc"];
	 $ls_afectacion      = $_POST["txtafectacion"];
	 $ls_codfuefin       = $_POST["codfuefin"];
	 $ls_codtipfon 		 = $_POST["hidcodtipfon"];
	 $ls_numordpagmin 	 = $_POST["hidnumordpagmin"];
   }
else
   {
	 $ls_operacion="";
     $ls_estcla = "";
	 $ls_estpro1="";
	 $ls_estpro2="";
	 $ls_estpro3="";
	 $ls_estpro4="";
	 $ls_estpro5="";
	 $ls_cuentaplan="";
	 $ls_denominacion="";
	 $ls_procedencia="SCBMOV";
	 $ls_mov_document=$_GET["mov_document"];
	 $ls_mov_procede=$_GET["procede"];
	 $ld_fecha=$_GET["fecha"];
	 $ls_provbene=$_GET["provbene"];
	 $ls_tipo=$_GET["tipo"];
	 $ls_mov_descripcion=$_GET["descripcion"];
	 $ls_descripcion=$ls_mov_descripcion;
	 $ls_codban=$_GET["codban"];
	 $ls_ctaban=$_GET["ctaban"];
	 $ls_cuenta_scg=$_GET["cuenta_scg"];
	 $ls_codope=$_GET["mov_operacion"];
	 $ldec_monto_mov=$_GET["monto"];
	 $ldec_objret=$_GET["objret"];
	 $ldec_retenido=$_GET["retenido"];
	 $ls_chevau     =$_GET["chevau"];
	 $li_estint     =$_GET["estint"];
	 $li_estcob     =$_GET["estcob"];
	 $li_cobrapaga  =$_GET["cobrapaga"];
	 $ls_estbpd     =$_GET["estbpd"];
	 $ls_nomproben  =$_GET["txtnomproben"];
	 $ls_estmov     =$_GET["estmov"];
	 $ls_codconmov  =$_GET["codconmov"];
	 $ls_estreglib  =$_GET["tip_mov"];
	 $ls_opener     =$_GET["opener"];
	 $ls_estdoc     =$_GET["estdoc"];
	 $ls_afectacion =$_GET["afectacion"];
	 $ls_codfuefin     = $_GET["codfuefin"];
	 $ls_codtipfon     = $_GET["codtipfon"];
     $ls_numordpagmin  = $_GET["numordpagmin"];
  }
if($ls_codfuefin=="")
{
	$ls_codfuefin="--";
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
require_once("sigesp_scb_c_movbanco.php");
$io_fun_banco = new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB",$ls_opener,$ls_permisos,&$la_seguridad,$la_permisos);
$in_classmovbanco=new sigesp_scb_c_movbanco($la_seguridad);

if ($ls_operacion=="GUARDARPRE")
   {
	  $ldec_monto = $_POST["txtmonto"];
	  $ls_estmov  = "N";
	  if ($ls_tipo=="P")
		 {
		   $ls_codpro  = $ls_provbene;
		   $ls_cedbene = "----------";
		 }
	  else
		 {
		   $ls_cedbene=$ls_provbene;
		   $ls_codpro ="----------";
		 }
	  $in_classmovbanco->io_sql->begin_transaction();
	  $lb_valido				  = $in_classmovbanco->uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ld_fecha,$ls_mov_descripcion,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto_mov,$ldec_objret,$ldec_retenido,$ls_chevau,$ls_estmov,$li_estint,$li_cobrapaga,$ls_estbpd,$ls_mov_procede,$ls_estreglib,$ls_estdoc,$ls_tipo,$ls_codfuefin,$ls_numordpagmin,$ls_codtipfon,$li_estcob);
	  $arr_movbco["codban"]		  =	$ls_codban;
	  $arr_movbco["ctaban"]		  = $ls_ctaban;
	  $arr_movbco["mov_document"] = $ls_mov_document;
	  $ld_fecdb=$io_funcion->uf_convertirdatetobd($ld_fecha);
	  $arr_movbco["codope"]=$ls_codope;
	  $arr_movbco["fecha"]=$ld_fecha;
	  $arr_movbco["codpro"]=$ls_codpro;
	  $arr_movbco["cedbene"]=$ls_cedbene;
	  $arr_movbco["monto_mov"]=$ldec_monto_mov;
	  $arr_movbco["objret"]   =$ldec_objret;
	  $arr_movbco["retenido"] =$ldec_retenido;
	  $arr_movbco["estmov"]=$ls_estmov;
	  if ($lb_valido)
		 {
		   if (($ls_codope=="ND")||($ls_codope=="RE")||($ls_codope=="CH"))
			  {
				$ls_operacioncon="H";
			  }
		   else
			  { 
				$ls_operacioncon="D";
			  }
		   $lb_valido		= $in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta_scg,$ls_procedencia,$ls_mov_descripcion,$ls_mov_document,$ls_operacioncon,$ldec_monto_mov,$ldec_objret,true,'00000');
		   $ls_cuenta       = $_POST["txtcuentascg"];
		   $ls_denominacion = $_POST["txtdescripcion"];
		   $ls_operacioncon = "D";
		   $ld_monto        = $_POST["txtmonto"];
		   $ldec_monto      = str_replace(".","",$ld_monto);
		   $ldec_monto      = str_replace(",",".",$ldec_monto);
		   if ($lb_valido)
			  {
				$lb_valido    = $in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta,$ls_procedencia,$ls_descripcion,$ls_mov_document,$ls_operacioncon,$ldec_monto,$ldec_objret,false,'00000');
				$ls_spgcuenta = $_POST["txtcuenta"];
				$ls_est1      = str_pad($_POST["codestpro1"],25,0,0);
				$ls_est2      = str_pad($_POST["codestpro2"],25,0,0);
				$ls_est3      = str_pad($_POST["codestpro3"],25,0,0);
				if ($li_estmodest=='2')
				   {
					 $ls_est4 = str_pad($_POST["codestpro4"],25,0,0);
					 $ls_est5 = str_pad($_POST["codestpro5"],25,0,0);
				   }
				else
				   {
					 $ls_est4 = str_pad("",25,0,0);
					 $ls_est5 = str_pad("",25,0,0);
				   }
				$ls_programa  =	$ls_est1.$ls_est2.$ls_est3.$ls_est4.$ls_est5;
				$ls_desmov    = $_POST["txtdescripcion"];
				$ls_operacion = $_POST["txtafectacion"];
				$ldec_monto   = $_POST["txtmonto"];
				$ldec_monto   = str_replace(".","",$ldec_monto);
				$ldec_monto   = str_replace(",",".",$ldec_monto);
			
				$lb_valido=$in_classmovbanco->uf_procesar_dt_gasto($ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ls_estmov,$ls_programa,$ls_spgcuenta,$ls_mov_document,$ls_desmov,$ls_procedencia,$ldec_monto,$ls_operacion,$ls_estcla);
				if ($lb_valido)
				   {
					 $in_classmovbanco->io_sql->commit();
					 $ls_estdoc='C';
					 ?>
					 <script language="javascript">
						f=opener.document.form1;
						f.operacion.value="CARGAR_DT";
						f.status_doc.value='C';//Cambio estatus a actualizable
						f.action="<?php print $ls_opener;?>";
						f.submit();
					 </script>	
					 <?php
				   }
				else
				   {
					 $in_classmovbanco->io_sql->rollback();
					 $io_msg->message($in_classmovbanco->is_msg_error);
				   }
			  }
		   else
			  {
				$io_msg->message($in_classmovbanco->is_msg_error);
				$in_classmovbanco->io_sql->rollback();
			  }				
		 } 	
	  else
		 {
		   $io_msg->message($in_classmovbanco->is_msg_error);
		   $in_classmovbanco->io_sql->rollback();
		   ?>
		   <script language="javascript">
			close();
		   </script>	
		   <?php
		 }
   }

$ls_apertura="";
$ls_aumento="";
$ls_disminucion="";
$ls_precompromiso="";	   
$ls_compromiso="";
$ls_compromisogastocausado="";
$ls_gastocausado="";
$ls_causadopago="";
$ls_pago="";
$ls_compromisocausasopago="";	   	   	   

switch ($ls_operacion) {
   case 'AAP':
       $ls_apertura="selected";
       break;
   case 'AU':
       $ls_aumento="selected";
       break;
   case 'DI':
       $ls_disminucion="selected";
       break;
	case 'PC':
       $ls_precompromiso="selected";	   
	   break;
	case 'CS':   
       $ls_compromiso="selected";
	   break;
	case 'CG': 
	   $ls_compromisogastocausado="selected";
	   break;
	case 'GC':
	   $ls_gastocausado="selected";
	   break;   
	case 'CP':
       $ls_causadopago="selected";
	   break;
	case 'PG':
       $ls_pago="selected";
	   break;
	case 'CCP':
       $ls_compromisocausasopago="selected";	   	   	   
	   break;
    default:
	   $ls_compromisogastocausado="selected";
	   break;
}
 ?>
<form method="post" name="form1" action=""> 
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="583" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
   <td height="22" colspan="2" class="titulo-celda">Entrada de Comprobante de Gastos 
     <input name="hidcodtipfon" type="hidden" id="hidcodtipfon" value="<?php echo $ls_codtipfon; ?>">
     <input name="hidnumordpagmin" type="hidden" id="hidnumordpagmin" value="<?php echo $ls_numordpagmin; ?>"></td>
  </tr>
  <tr>
    <td height="13">&nbsp;</td>
    <td height="13">&nbsp;</td>
  </tr>
  <tr>
    <td width="119" height="22" align="right">Documento</td>
    <td width="450" height="22"><input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="javascript:valid_cmp(this);" size="22" maxlength="15" value="<?php print $ls_mov_document;?>" readonly></td>
  </tr>
  <tr>
    <td height="22" align="right">Descripci&oacute;n</td>
    <td height="22"><input name="txtdescripcion" type="text" id="txtdescripcion" size="80" maxlength="100" style="text-align:left" value="<?php print $ls_descripcion;?>"></td>
  </tr>
  <tr>
    <td height="22" align="right">Procedencia</td>
    <td height="22"><input name="txtprocedencia" type="text" id="txtprocedencia" size="22" maxlength="6" style="text-align:center" value="<?php print $ls_procedencia;?>" readonly></td>
  </tr>
   <tr>
    <td height="22"><div align="right"><?php print $la_empresa["nomestpro1"];  ?></div></td>
    <td height="22">
      <input name="codestpro1" type="text" id="codestpro1" size="<?php echo $li_size1 ?>" maxlength="<?php echo $li_loncodestpro1 ?>" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 1"></a>      <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="50" readonly>     
      <input name="hidtipestpro" type="hidden" id="hidtipestpro">
      <div align="left">      </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $la_empresa["nomestpro2"] ; ?></div>      </td>
    <td height="22"><input name="codestpro2" type="text" id="codestpro2" size="<?php echo $li_size2 ?>" maxlength="<?php echo $li_loncodestpro2 ?>" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 2"></a>
      <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="50" readonly></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $la_empresa["nomestpro3"] ; ?></div></td>
    <td height="22">      <div align="left">
      <input name="codestpro3" type="text" id="codestpro3" size="<?php echo $li_size3 ?>" maxlength="<?php echo $li_loncodestpro3 ?>" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 3"></a>
      <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="50" readonly>
      </div></td>
  </tr>
  <?php 
  if($li_estmodest==2)
  {
  ?>
  <tr>
    <td height="22"> <div align="right"><?php print $la_empresa["nomestpro4"];  ?></div></td>
    <td height="22"><input name="codestpro4" type="text" id="codestpro4" size="<?php echo $li_size4 ?>" maxlength="<?php echo $li_loncodestpro4 ?>" style="text-align:center">
      <a href="javascript:catalogo_estpro4();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 4">      </a><input name="denestpro4" type="text" class="sin-borde" id="denestpro4" size="50" readonly>      </td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $la_empresa["nomestpro5"];  ?></div></td>
    <td height="22"><input name="codestpro5" type="text" id="codestpro5" size="<?php echo $li_size5 ?>" maxlength="<?php echo $li_loncodestpro5 ?>" style="text-align:center">
      <a href="javascript:catalogo_estpro5();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Estructura Programatica 5">      </a><input name="denestpro5" type="text" class="sin-borde" id="denestpro5" size="50" readonly>      </td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td height="22"><div align="right">Cuenta</div></td>
    <td height="22"><input name="txtcuenta" type="text" id="txtcuenta" size="22" style="text-align:center" readonly> 
    <a href="javascript:catalogo_cuentasSPG();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Cuentas de Gastos"></a>	 <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion3" style="text-align:left" size="53" maxlength="254" readonly></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Operaci&oacute;n</div></td>
    <td height="22"><div align="left">
      <input name="txtafectacion" type="text" id="txtafectacion" value="<?php print $ls_afectacion?>" size="8" style="text-align:center" readonly>
</div></td>
  </tr>
  <tr>
    <td height="22" align="right">Monto</td>
    <td height="22"><input name="txtmonto" type="text" id="txtmonto" style="text-align:right" size="22" onKeyPress="return(currencyFormat(this,'.',',',event))"  > 
      <a href="javascript:aceptar_presupuestario();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar Detalle Presupuestario" width="15" height="15" border="0"></a> <a href="javascript: close();"><img src="../shared/imagebank/tools15/eliminar.gif" alt="Cancelar Registro de Detalle Presupuestario" width="15" height="15" border="0"></a></td>
  </tr>
  <tr>
    <td height="22" colspan="2"><input name="txtcuentascg" type="hidden" id="txtcuentascg">
      <input name="comprobante" type="hidden" id="comprobante" value="<?php print $ls_comprobante;?>">
      <input name="procede" type="hidden" id="procede" value="<?php print $ls_mov_procede;?>">
	  <input name="fecha" type="hidden" id="fecha" value="<?php print $ld_fecha;?>">
      <input name="provbene" type="hidden" id="provbene" value="<?php print $ls_provbene;?>">
      <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo;?>">
      <input name="descripcion" type="hidden" id="descripcion" value="<?php print $ls_mov_descripcion;?>">
      <input name="operacion" type="hidden" id="operacion">
      <input name="mov_document" type="hidden" id="mov_document" value="<?php print $ls_mov_document;?>">
      <input name="codban" type="hidden" id="codban" value="<?php print $ls_codban;?>">
      <input name="ctaban" type="hidden" id="ctaban" value="<?php print $ls_ctaban;?>">
      <input name="cuenta_scg" type="hidden" id="cuenta_scg" value="<?php print $ls_cuenta_scg;?>">
      <input name="mov_operacion" type="hidden" id="mov_operacion" value="<?php print $ls_codope;?>">
      <input name="txtnomproben" type="hidden" id="txtnomproben" value="<?php print $ls_nomproben;?>">
      <input name="monto" type="hidden" id="monto" value="<?php print $ldec_monto_mov;?>">
      <input name="objret" type="hidden" id="objret" value="<?php print $ldec_objret;?>">
      <input name="retenido" type="hidden" id="retenido" value="<?php print $ldec_retenido;?>">
      <input name="chevau" type="hidden" id="chevau" value="<?php print $ls_chevau;?>">
      <input name="estint" type="hidden" id="estint" value="<?php print  $li_estint;?>">
	  <input name="estcob" type="hidden" id="estcob" value="<?php print  $li_estcob;?>">
      <input name="cobrapaga" type="hidden" id="cobrapaga" value="<?php print $li_cobrapaga;?>">
      <input name="estbpd" type="hidden" id="estbpd" value="<?php print $ls_estbpd;?>">
      <input name="estmov" type="hidden" id="estmov" value="<?php print $ls_estmov;?>">
      <input name="codconmov" type="hidden" id="codconmov" value="<?php print $ls_codconmov;?>">
      <input name="tip_mov" type="hidden" id="tip_mov" value="<?php print $ls_estreglib;?>">
      <input name="opener" type="hidden" id="opener" value="<?php print $ls_opener;?>">
      <input name="estdoc" type="hidden" id="estdoc" value="<?php print $ls_estdoc;?>">
      <input name="codfuefin" type="hidden" id="codfuefin" value="<?php print $ls_codfuefin;?>"></td>
    </tr>
</table>
</form>
</body>
<script language="JavaScript">
fop = opener.document.form1;
function aceptar_presupuestario()
{
  f = document.form1;
  lb_valido 	  = true;
  ldec_monto   	  = f.txtmonto.value;
  ls_numordpagmin = fop.txtnumordpagmin.value;
  ls_codtipfon    = fop.hidcodtipfon.value;
  if (lb_valido)
     {
	   ls_numdoc    = f.txtdocumento.value;
	   ls_procede   = f.txtprocedencia.value;
	   ls_codest1   = f.codestpro1.value;
	   ls_codest2   = f.codestpro2.value;
	   ls_codest3   = f.codestpro3.value;
	   ls_cuenta    = f.txtcuenta.value;
	   ls_operacion = f.txtafectacion.value;	   
	   if ((ls_numdoc!="")&&(ls_procede!="")&&(ls_codest1!="")&&(ls_codest2!="")&&(ls_codest3!="")&&(ls_cuenta!="")&&(ls_operacion!="")&&(ldec_monto!=""))
		  {
		    if (ls_numordpagmin!='' && ls_codtipfon!='')
			   {
			     lb_valido = uf_validar_monto(ldec_monto);
			   }
            if (lb_valido)
		       {
			     f.operacion.value="GUARDARPRE";
				 f.action="sigesp_w_regdt_presupuesto.php";
				 f.submit();			   
			   }
		  }
	   else
		  {
		    alert("Complete todos los datos !!!");
		  }
	 }
}

function uf_validar_monto(ad_mondet)
{
  ld_sumdet    = parseFloat(0);
  li_totrowspg = fop.totpre.value;
  ld_monmaxmov = fop.hidmonmaxmov.value;
  for (li_z=1;li_z<=li_totrowspg;li_z++)
      {
	    ls_spgcta = eval("fop.txtcuenta"+li_z+".value");
		if (ls_spgcta!='')
		   {
		     ld_mondet = eval("fop.txtmonto"+li_z+".value");
			 ld_mondet = ue_formato_calculo(ld_mondet);
			 ld_sumdet = eval(ld_sumdet+"+"+ld_mondet);
		   }	  
	  }
  ad_mondet    = ue_formato_calculo(ad_mondet);
  ld_sumdetmov = eval(ld_sumdet+"+"+ad_mondet);//Monto Temporal del Documento, que equivale a la Suma de los Detalles Presupuestarios del Grid más el Detalle que viene.
  if (ld_sumdetmov>ld_monmaxmov)
     {
	   alert("El Monto de los Detalles supera al Monto Disponible de la Orden de Pago Ministerio !!!");
	   return false;
	 }
  else
     {
	   return true;
	 }  
}
  
  function uf_close()
  {
	  close()
  }
	
function agregar_scg(ls_cuenta,ls_descripcion,ls_documento,ldec_monto,ls_procede,ls_debhab)
{
	f=document.form1;
	fop=opener.document.form1;
	li_total =fop.totcon.value;
	li_last  =fop.lastscg.value;	
  	li_newrow= parseInt(li_last,10)+1;
	ls_cuenta=f.txtcuentascg.value;
	lb_valido=false;
	for(li_i=1;li_i<=li_total&&lb_valido!=true;li_i++)
	{
		
		ls_cuenta_opener=eval("fop.txtcontable"+li_i+".value");
		if(ls_cuenta==ls_cuenta_opener)
		{
			ldec_monto_actual=eval("fop.txtmontocont"+li_i+".value");	
			while(ldec_monto_actual.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto_actual=ldec_monto_actual.replace(".","");
			}
			ldec_monto_actual=ldec_monto_actual.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
			ldec_monto_nuevo=parseFloat(parseFloat(ldec_monto) + parseFloat(ldec_monto_actual));
			ldec_monto_nuevo=uf_convertir(ldec_monto_nuevo);
			eval("fop.txtmontocont"+li_i+".value='"+ldec_monto_nuevo+"'");	
			lb_valido=true;
		}
	}
	if((li_newrow<=li_total))
	{
		if(!lb_valido)
		{
		eval("fop.txtcontable"+li_newrow+".value='"+ls_cuenta+"'");
		eval("fop.txtdesdoc"+li_newrow+".value='"+ls_descripcion+"'");
		eval("fop.txtdocscg"+li_newrow+".value='"+ls_documento+"'");
		eval("fop.txtmontocont"+li_newrow+".value='"+ldec_monto+"'");
		eval("fop.txtdebhab"+li_newrow+".value='"+ls_debhab+"'");
		eval("fop.txtprocdoc"+li_newrow+".value='"+ls_procede+"'");
		fop.lastscg.value=li_newrow;
		}
		uf_calcular_montoscg();
	}
	else
	{
		alert("Debe agregar mas filas a la tabla");
	}
 }	

function valid_cmp(cmp)
{
	if((cmp.value==0)||(cmp.value==""))
	{
		alert("Introduzca un numero comprobante valido");
		cmp.focus();
	}
	else
	{
		rellenar_cad(cmp.value,15,"doc");
	}
}

//Funciones de validacion de fecha.
function rellenar_cad(cadena,longitud,campo)
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
	else
	{
		document.form1.txtcomprobante.value=cadena;
	}

}

function catalogo_cuentasSPG()
{
   f=document.form1;
   codest1=f.codestpro1.value;
   codest2=f.codestpro2.value;
   codest3=f.codestpro3.value;
   ls_denestpro1 = f.denestpro1.value;
   ls_denestpro2 = f.denestpro2.value;
   ls_denestpro3 = f.denestpro3.value;
   ls_estcla     = f.hidtipestpro.value;
   ls_fecmov     = f.fecha.value;
	 if(<?php print $li_estmodest?>==2)
   {
   	   codest4=f.codestpro4.value;   		
	   codest5=f.codestpro5.value;
	   ls_denestpro4 = f.denestpro4.value;
       ls_denestpro5 = f.denestpro5.value;
	   if((codest1!="")&&(codest2!="")&&(codest3!="")&&(codest4!="")&&(codest5!=""))
	   {
		   pagina="sigesp_cat_ctaspg.php?codestpro1="+codest1+"&hicodest2="+codest2+"&hicodest3="+codest3+"&hicodest4="+codest4+"&hicodest5="+codest5+"&txtdenestpro1="+ls_denestpro1+"&txtdenestpro2="+ls_denestpro2+"&txtdenestpro3="+ls_denestpro3+"&txtdenestpro4="+ls_denestpro4+"&txtdenestpro5="+ls_denestpro5+"&hidestcla="+ls_estcla+"&fecmov="+ls_fecmov;
		   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
	   }
	   else
	   {  alert("Debe completar la programatica");  }
   }
   else
   {
       if((codest1!="")&&(codest2!="")&&(codest3!=""))
	   {
		   pagina="sigesp_cat_ctaspg.php?codestpro1="+codest1+"&hicodest2="+codest2+"&hicodest3="+codest3+"&txtdenestpro1="+ls_denestpro1+"&txtdenestpro2="+ls_denestpro2+"&txtdenestpro3="+ls_denestpro3+"&hidestcla="+ls_estcla+"&fecmov="+ls_fecmov;
		   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600=yes,location=no");
	   }
	   else
	   {  alert("Debe completar la programatica !!!");   }   	
   }

 }
 
function catalogo_estpro1()
{
  pagina="sigesp_cat_public_estpro1.php";
  window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
}

function catalogo_estpro2()
{
	f=document.form1;
	codestpro1=f.codestpro1.value;
	denestpro1=f.denestpro1.value;
	ls_estcla = f.hidtipestpro.value;
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&hidestcla="+ls_estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no");
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
	codestpro3=f.codestpro3.value;
	denestpro2=f.denestpro2.value;
	ls_estcla = f.hidtipestpro.value;
	if(<?php print $li_estmodest?>==1)
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(codestpro3=="")&&(denestpro2!=""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&hidestcla="+ls_estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=730,height=400,resizable=yes,location=no");
		}
		else
		{
			pagina="sigesp_cat_public_estpro.php";
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=730,height=400,resizable=yes,location=no");
		}
	}
	else
	{
		if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
		{
			pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&hidestcla="+ls_estcla;
			window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=700,height=400,resizable=yes,location=no");
		}
		else
		{
			alert("Seleccione la Estructura nivel 2 !!!");
		}
	}		
}

function catalogo_estpro4()
{
	f=document.form1;
	codestpro1 = f.codestpro1.value;
	denestpro1 = f.denestpro1.value;
	codestpro2 = f.codestpro2.value;
	denestpro2 = f.denestpro2.value;
	codestpro3 = f.codestpro3.value;
	denestpro3 = f.denestpro3.value;
	ls_estcla  = f.hidtipestpro.value;
	if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(denestpro1!="")&&(denestpro2!="")&&(denestpro3!=""))
	{
		pagina="sigesp_cat_public_estpro4.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&hidestcla="+ls_estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura nivel 3");
	}
}
function catalogo_estpro5()
{
	f=document.form1;
	codestpro1 = f.codestpro1.value;
	denestpro1 = f.denestpro1.value;
	codestpro2 = f.codestpro2.value;
	denestpro2 = f.denestpro2.value;
	codestpro3 = f.codestpro3.value;
	denestpro3 = f.denestpro3.value;
	codestpro4 = f.codestpro4.value;
	denestpro4 = f.denestpro4.value;
	codestpro5 = f.codestpro5.value;
	ls_estcla  = f.hidtipestpro.value;
	if((codestpro1!="")&&(codestpro2!="")&&(codestpro3!="")&&(denestpro1!="")&&(denestpro2!="")&&(denestpro3!="")&&(codestpro4!="")&&(denestpro4!="")&&(codestpro5==""))
	{
		pagina="sigesp_cat_public_estpro5.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2+"&codestpro3="+codestpro3+"&denestpro3="+denestpro3+"&codestpro4="+codestpro4+"&denestpro4="+denestpro4+"&hidestcla="+ls_estcla;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=750,height=400,resizable=yes,location=no");
	}
	else
	{
		pagina="sigesp_cat_public_estprograma.php";
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=710,height=400,resizable=yes,location=no");
	}
}

   function  uf_cambiar()
	{
		f=document.form1;
		fop=opener.document.form1;
		li_newtotal=f.totalpre.value;
		fop.totpre.value=li_newtotal;
		fop.operacion.value="RECARGAR"
		fop.submit();
		
	}
function uf_calcular_montoscg()
  {
  	f=document.form1;
	ldec_mondeb=0;
	ldec_monhab=0;
	li_total=fop.lastscg.value;
	for(li_i=1;li_i<=li_total;li_i++)
	{
			ls_debhab=eval("fop.txtdebhab"+li_i+".value");
			ldec_monto=eval("fop.txtmontocont"+li_i+".value");	
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
			if(ls_debhab=="D")
			{
				ldec_mondeb=parseFloat(ldec_mondeb)+parseFloat(ldec_monto);
				
			}
			else
			{
				ldec_monhab=parseFloat(ldec_monhab) + parseFloat(ldec_monto);
				
			}
			
	}
	ldec_diferencia=parseFloat(ldec_mondeb)-parseFloat(ldec_monhab);
	ldec_mondeb=uf_convertir(ldec_mondeb);
	fop.txtdebe.value=ldec_mondeb;	
	ldec_monhab=uf_convertir(ldec_monhab);
	fop.txthaber.value=ldec_monhab;	
	ldec_diferencia=uf_convertir(ldec_diferencia);
	fop.txtdiferencia.value=ldec_diferencia;	
  }
  
  function uf_calcular_montospg()
  {
  	f=document.form1;
	ldec_monspg=0;
	li_total=fop.lastspg.value;
	for(li_i=1;li_i<=li_total;li_i++)
	{
			ldec_monto=eval("fop.txtmonto"+li_i+".value");	
			while(ldec_monto.indexOf('.')>0)
			{//Elimino todos los puntos o separadores de miles
				ldec_monto=ldec_monto.replace(".","");
			}
			ldec_monto=ldec_monto.replace(",",".");//Cambio la coma de separacion de decimales por un punto para poder realizar la operacion
			ldec_monspg=parseFloat(ldec_monspg)+parseFloat(ldec_monto);
	}
	ldec_monspg=uf_convertir(ldec_monspg);
	fop.totspg.value=ldec_monspg;		
  }
  
   function uf_format(obj)
   {
		ldec_monto=uf_convertir(obj.value);
		obj.value=ldec_monto;
   }
   
   function uf_validar_cantidad()
   {
		f=opener.document.form1;
		f2=document.form1;
		ldec_monto_mov=parseFloat(uf_convertir_monto(f.txtmonto.value));
		ldec_totspg=parseFloat(uf_convertir_monto(f.totspg.value));
		ldec_monto_guardar=parseFloat(uf_convertir_monto(f2.txtmonto.value));		
		if((ldec_monto_guardar + ldec_totspg) > ldec_monto_mov)
		{
			alert("El monto total del movimiento presupuestario supera el monto total");
			f2.txtmonto.value="0,00";
		}
   }
</script>
</html>