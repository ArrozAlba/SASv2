<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
     print "<script language=JavaScript>";
  	 print "location.href='../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Entrada de Movimientos Contables</title>
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
<style type="text/css">
<!--
.style2 {font-size: 11px}
-->
</style>
</head>
<body>
<?php
$dat=$_SESSION["la_empresa"];
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_sql.php");

$io_msg    	= new class_mensajes();
$siginc 	= new sigesp_include();
$con		= $siginc->uf_conectar();
$fun		= new class_funciones();
$io_sql		= new class_sql($con);
$arre		= $_SESSION["la_empresa"];
$ls_empresa = $arre["codemp"];

if (array_key_exists("operacion",$_POST))
{
    $ls_operacion  = $_POST["operacion"];
   	$ls_cuentaplan = $_POST["txtcuenta"];
	$ls_procedencia= $_POST["txtprocedencia"];
	$ls_descripcion= $_POST["txtdescripcion"];
	$ldec_monto	   = $_POST["txtmonto"];
	$ls_denominacion=$_POST["txtdenominacion"];
	$ls_mov_document=$_POST["mov_document"];
	$ls_mov_procede=$_POST["procede"];
	$ld_fecha=$_POST["fecha"];
	$ls_provbene=$_POST["provbene"];
	$ls_tipo=$_POST["tipo"];
	$ls_mov_descripcion=$_POST["descripcion"];
	$ls_codban=$_POST["codban"];
	$ls_ctaban=$_POST["ctaban"];
	$ls_cuenta_scg=$_POST["cuenta_scg"];
	$ls_codope=$_POST["mov_operacion"];
	$ldec_monto_mov=$_POST["monto"];
	$ldec_objret=$_POST["objret"];
	$ldec_retenido=$_POST["retenido"];
	$ls_chevau=$_POST["chevau"];
	$li_estint=$_POST["estint"];
	$li_estcob=$_POST["estcob"];
	$li_cobrapaga=$_POST["cobrapaga"];
	$ls_estbpd=$_POST["estbpd"];
	$ls_nomproben=$_POST["txtnomproben"];
	$ls_estmov=$_POST["estmov"];
	$ls_codconmov=$_POST["codconmov"];
	$ls_estreglib=$_POST["tip_mov"];
	$ls_opener   =$_POST["opener"];
	$ls_estdoc   =$_POST["estdoc"];
	$ls_codfuefin =$_POST["codfuefin"];
	$ls_anticipo= $_POST["anticipo"];
	$ls_codtipfon 	 = $_POST["hidcodtipfon"];
	$ls_numordpagmin = $_POST["hidnumordpagmin"]; 
}
else
{
	$ls_operacion="";
  	$ls_cuentaplan = "";
	$ls_procedencia= $_GET["txtprocedencia"];
	$ls_denominacion="";
	$ld_fecha	   = "";
	$ldec_monto	   = "";
	$ls_mov_document=$_GET["mov_document"];
	$ls_mov_procede=$_GET["procede"];
	$ld_fecha=$_GET["fecha"];
	$ls_provbene=$_GET["provbene"];
	$ls_tipo=$_GET["tipo"];
	$ls_mov_descripcion=$_GET["descripcion"];
	$ls_descripcion= $ls_mov_descripcion;
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_cuenta_scg=$_GET["cuenta_scg"];
	$ls_codope=$_GET["mov_operacion"];
	$ldec_monto_mov=$_GET["monto"];
	$ldec_objret=$_GET["objret"];
	$ldec_retenido=$_GET["retenido"];
	$ls_chevau=$_GET["chevau"];
	$li_estint=$_GET["estint"];
	$li_estcob=$_GET["estcob"];
	$li_cobrapaga=$_GET["cobrapaga"];
	$ls_estbpd=$_GET["estbpd"];
	$ls_nomproben=$_GET["txtnomproben"];
	$ls_estmov=$_GET["estmov"];
	$ls_codconmov=$_GET["codconmov"];
	$ls_estreglib=$_GET["tip_mov"];
	$ls_opener   =$_GET["opener"];
	$ls_estdoc   =$_GET["estdoc"];
	$ls_codfuefin =$_GET["codfuefin"];
	$ls_anticipo= $_GET["anticipo"]; 
	$ls_codtipfon     = $_GET["codtipfon"];
    $ls_numordpagmin  = $_GET["numordpagmin"];
}
if($ls_codfuefin=="")
{
	$ls_codfuefin="--";
}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB",$ls_opener,$ls_permisos,&$la_seguridad,$la_permisos);

require_once("sigesp_scb_c_movbanco.php");
$in_classmovbanco=new sigesp_scb_c_movbanco($la_seguridad);

if(($ls_codope=="ND")||($ls_codope=="RE")||($ls_codope=="CH"))
{
	$ls_operacioncon="H";
	$lb_seldeb="selected";
	$lb_selhab="";
}
else
{
	$ls_operacioncon="D";
	$lb_seldeb="";
	$lb_selhab="selected";
}
if ($ls_operacion=="GUARDAR")
   {
	  $ldec_monto = $_POST["txtmonto"];
	  if ($ls_tipo=="P")
		 {
		   $ls_codpro  = $ls_provbene;
		   $ls_cedbene = "----------";
		 }
	  else
		 {
		   $ls_cedbene = $ls_provbene;
		   $ls_codpro  = "----------";
		 }
	  $ldec_objret  = str_replace(".","",$ldec_objret);
	  $ldec_objret  = str_replace(",",".",$ldec_objret);
	  $ls_nomproben = $_POST["txtnomproben"];
	  $ls_anticipo  = $_POST["anticipo"];
	  if ($ls_anticipo=='1')
		 {	
		   $ls_docant = '---------------';
		   $ls_monamo = 0;											  
		   $lb_valido = $in_classmovbanco->uf_guardar_automatico2($ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ld_fecha,$ls_mov_descripcion,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto_mov,$ldec_objret, $ldec_retenido,$ls_chevau,$ls_estmov,$li_estint,$li_cobrapaga,$ls_estbpd,$ls_mov_procede,' ',$ls_estdoc,$ls_tipo,$ls_codfuefin, $ls_anticipo,$ls_docant,
		   $ls_monamo,$li_estcob);
		 }
	  else
		 {
		   $lb_valido=$in_classmovbanco->uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_mov_document,$ls_codope,$ld_fecha,$ls_mov_descripcion,$ls_codconmov,$ls_codpro,$ls_cedbene,$ls_nomproben,$ldec_monto_mov,$ldec_objret, $ldec_retenido,$ls_chevau,$ls_estmov,$li_estint,$li_cobrapaga,$ls_estbpd,$ls_mov_procede,' ',$ls_estdoc,$ls_tipo,$ls_codfuefin,$ls_numordpagmin,$ls_codtipfon,$li_estcob);
		 }			 
	  $arr_movbco["codban"] = $ls_codban;
	  $arr_movbco["ctaban"] = $ls_ctaban;
	  $arr_movbco["mov_document"] = $ls_mov_document;
	  $ld_fecdb				   = $fun->uf_convertirdatetobd($ld_fecha);
	  $arr_movbco["codope"]	   = $ls_codope;
	  $arr_movbco["fecha"]	   = $ld_fecha;
	  $arr_movbco["codpro"]	   = $ls_codpro;
	  $arr_movbco["cedbene"]   = $ls_cedbene;
	  $arr_movbco["monto_mov"] = $ldec_monto_mov;
	  $arr_movbco["objret"]    = $ldec_objret;
	  $arr_movbco["retenido"]  = $ldec_retenido;
	  $arr_movbco["estmov"]	   = $ls_estmov;
	  $ls_codded     		   = "00000";
	  if ($lb_valido)
		 {
		   $lb_valido       = $in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta_scg,$ls_procedencia,$ls_mov_descripcion,$ls_mov_document,$ls_operacioncon,$ldec_monto_mov,$ldec_objret,true,$ls_codded);
		   $ls_cuenta       = $_POST["txtcuenta"];
		   $ls_denominacion = $_POST["txtdescripcion"];
		   $ls_operacioncon = $_POST["txtoperacion"];
		   $ld_monto        = $_POST["txtmonto"];
		   $ldec_monto      = str_replace(".","",$ld_monto);
		   $ldec_monto		= str_replace(",",".",$ldec_monto);
		   if ($lb_valido)
			  {
				$lb_valido = $in_classmovbanco->uf_procesar_dt_contable($arr_movbco,$ls_cuenta,$ls_procedencia,$ls_descripcion,$ls_mov_document,$ls_operacioncon,$ldec_monto,$ldec_objret,false,$ls_codded);
				///------------------------------CUANDO ES UN ANTICIPO--------------------------------------------------------
				if (($ls_anticipo=='1')&&($ls_operacioncon=="D"))
				   {
					 $lb_valido=$in_classmovbanco->uf_procesar_anticipo($arr_movbco,$ls_cuenta,$ls_procedencia,$ls_descripcion,$ls_mov_document,$ls_operacioncon,$ldec_monto,$ldec_objret);
				   }
				//------------------------------------------------------------------------------------------------------------
				if (!$lb_valido)
				   {
					 $in_classmovbanco->io_sql->rollback();
					 $io_msg->message($in_classmovbanco->is_msg_error);
				   }
				else
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
			  }
		   else
			  {
				$in_classmovbanco->io_sql->rollback();
				$io_msg->message($in_classmovbanco->is_msg_error);
			  }
		 }
	  else
		 {
		   $in_classmovbanco->io_sql->rollback();
		   $io_msg->message($in_classmovbanco->is_msg_error);
		 }		
   }
 ?>
<form method="post" name="form1" action=""> 
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_banco->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_banco);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="567" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
   <td height="22" colspan="2" class="titulo-celda"><input name="hidcodtipfon" type="hidden" id="hidcodtipfon" value="<?php echo $ls_codtipfon; ?>">
     Entrada de Movimientos Contables 
      <input name="hidnumordpagmin" type="hidden" id="hidnumordpagmin" value="<?php echo $ls_numordpagmin; ?>"></td>
  </tr>
  <tr>
    <td height="13">&nbsp;</td>
    <td height="13">&nbsp;</td>
  </tr>
  <tr>
    <td width="119" height="22" align="right">Documento</td>
    <td width="446" height="22"><input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="javascript:valid_cmp(this);" value="<?php print $ls_mov_document;?>"  size="20" maxlength="15" readonly></td>
  </tr>
  <tr>
    <td height="22" align="right">Descripci&oacute;n</td>
    <td height="22"><input name="txtdescripcion" type="text" id="txtdescripcion" size="80" maxlength="100" style="text-align:left" value="<?php print $ls_descripcion;?>"></td>
  </tr>
  <tr>
    <td height="22" align="right">Procedencia</td>
    <td height="22"><input name="txtprocedencia" type="text" id="txtprocedencia" size="20" maxlength="6" style="text-align:center" value="<?php print $ls_procedencia;?>" readonly></td>
  </tr>
  <tr>
    <td height="22" style="text-align:right">Cuenta Contable</td>
    <td height="22"><input name="txtcuenta" type="text" id="txtcuenta" value="<?php print $ls_cuentaplan ?>" size="20" style="text-align:center"> 
    <a href="javascript:catalogo_cuentasSCG();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Cuentas de Gastos"></a>	 <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_denominacion ?>" size="50" maxlength="254" readonly></td>
  </tr>
  <tr>
    <td height="22" style="text-align:right">Operaci&oacute;n</td>
    <td height="22">	
     <select name="txtoperacion" id="txtoperacion">
        <option value="D" <?php print $lb_seldeb;?>>Debe</option>
        <option value="H" <?php print $lb_selhab;?>>Haber</option>
      </select>
    </td>
  </tr>
  <tr>
    <td height="22" align="right">Monto</td>
    <td height="22"><input name="txtmonto" type="text" id="txtmonto" style="text-align:right" size="20"  onKeyPress="return(currencyFormat(this,'.',',',event))"> 
      <a href="javascript:aceptar_presupuestario();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar Detalle Contable" width="15" height="15" border="0"></a> <a href="javascript:uf_close();"><img src="../shared/imagebank/tools15/eliminar.gif" alt="Cancelar Registro  Contable" width="15" height="15" border="0"></a>      </td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td height="22"><input name="operacion" type="hidden" id="operacion">
      <input name="mov_document" type="hidden" id="mov_document" value="<?php print $ls_mov_document;?>">
      <input name="procede" type="hidden" id="procede" value="<?php print $ls_mov_procede;?>">
      <input name="fecha" type="text" id="fecha" value="<?php print $ld_fecha;?>">
      <input name="provbene" type="hidden" id="provbene" value="<?php print $ls_provbene;?>">
      <input name="tipo" type="hidden" id="tipo" value="<?php print $ls_tipo;?>">
      <input name="descripcion" type="hidden" id="descripcion" value="<?php print $ls_mov_descripcion;?>">
      <input name="codban" type="hidden" id="codban" value="<?php print $ls_codban;?>">      
      <input name="ctaban" type="hidden" id="ctaban" value="<?php print $ls_ctaban;?>">
      <input name="cuenta_scg" type="hidden" id="cuenta_scg" value="<?php print $ls_cuenta_scg;?>">
      <input name="mov_operacion" type="hidden" id="mov_operacion" value="<?php print $ls_mov_operacion?>">
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
	   <input name="codfuefin" type="hidden" id="codfuefin" value="<?php print $ls_codfuefin;?>">
	   <input name="anticipo" type="hidden" id="anticipo" value="<?php print $ls_anticipo;?>"></td>
  </tr>
</table>
</form>
</body>
<script language="JavaScript">
  function aceptar_presupuestario()
  {
	f=document.form1;
	fop=opener.document.form1;
	f.codban.value=fop.txtcodban.value;
	f.ctaban.value=fop.txtcuenta.value;
	f.mov_document.value=fop.txtdocumento.value;
	f.fecha.value=fop.txtfecha.value;
	f.cuenta_scg.value=fop.txtcuenta_scg.value;
	f.mov_operacion.value=fop.cmboperacion.value; 
	if (f.mov_operacion.value=="CHEQUE")
	{
		f.mov_operacion.value="CH";
	}	
	f.descripcion.value=fop.txtconcepto.value;
	f.provbene.value=fop.txtprovbene.value;
	f.txtnomproben.value=fop.txtdesproben.value;
	ls_documento=f.txtdocumento.value;
	ls_descripcion=f.txtdescripcion.value;
	ls_cuenta=f.txtcuenta.value;
	ls_operacion=f.txtoperacion.value;
	ldec_monto=f.txtmonto.value;
	if((ls_documento!="")&&(ls_descripcion!="")&&(ls_cuenta!="")&&(ls_operacion!="")&&(ldec_monto!=""))
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_w_regdt_contable.php";
		f.submit();	
	}
	else
	{
		alert("Complete todos los datos");
	}
  }
  
  function uf_calcular_montos()
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
  
  function uf_close()
  {
	  close()
  }
 
  function catalogo_cuentasSCG()
 {
   f=document.form1;
   pagina="sigesp_cat_ctasscg.php";
   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
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
function currencyFormat(fld, milSep, decSep, e)
{ 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
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
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
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
function currencyFormat(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
	if (whichCode == 8) return true; // Enter 
	if (whichCode == 127) return true; // Enter 	
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
      if (j == 3) { 
       aux2 += milSep; 
       j = 0; 
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
  

	function  uf_cambiar()
	{
		f=document.form1;
		fop=opener.document.form1;
		li_newtotal=f.totalcon.value;
		fop.totcon.value=li_newtotal;
		fop.operacion.value="RECARGAR"
		fop.submit();		
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
		if(f2.txtoperacion.value=="D")
			ldec_totscg=parseFloat(uf_convertir_monto(f.txtdebe.value));
		else
			ldec_totscg=parseFloat(uf_convertir_monto(f.txthaber.value));
		ldec_monto_guardar=parseFloat(uf_convertir_monto(f2.txtmonto.value));
		if((ldec_monto_guardar + ldec_totscg) > ldec_monto_mov)
		{
			alert("El monto total del movimiento contable supera el monto total");
			f2.txtmonto.value="0,00";
		}
		
   }
</script>
</html>