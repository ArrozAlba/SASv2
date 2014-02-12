<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
$ls_logusr=$_SESSION["la_logusr"];
require_once("class_funciones_banco.php");
$io_fun_banco= new class_funciones_banco();
$io_fun_banco->uf_load_seguridad("SCB","sigesp_scb_p_movcol.php",$ls_permisos,$la_seguridad,$la_permisos);
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
$msg=new class_mensajes();
$siginc=new sigesp_include();
$con=$siginc->uf_conectar();
require_once("../shared/class_folder/class_sql.php");
$fun=new class_funciones();
$SQL=new class_sql($con);

require_once("sigesp_scb_c_movcol.php");
$in_classmovcol=new sigesp_scb_c_movcol($la_seguridad);

if (array_key_exists("operacion",$_POST))
{
	 $ls_operacion= $_POST["operacion"];
	 $ls_codope   = $_POST["txtoperacion"];
	 $ls_codope   = $_POST["mov_operacion"];	
	 $ls_opener   = $_POST["opener"];
	 $ls_mov_colocacion=$_POST["txtdoccol"];
	 $ls_numcol=$_POST["numcol"];
	 $ls_mov_procede=$_POST["procede"];
	 $ld_fecha=$_POST["fecha"];
	 $ls_mov_descripcion=$_POST["descripcion"];	 
	 $ls_codban=$_POST["codban"];
	 $ls_ctaban=$_POST["ctaban"];
	 $ls_cuenta_scg=$_POST["cuenta_scg"];
	 $ls_codope=$_POST["mov_operacion"];
	 $ldec_monto_mov=$_POST["monto"];
	 $ls_chevau=$_POST["chevau"];
	 $li_estint=$_POST["estint"];
	 $li_cobrapaga=$_POST["cobrapaga"];
	 $ls_estbpd=$_POST["estbpd"];
	 $ls_estmov=$_POST["estmov"];
	 $ls_codconmov=$_POST["codconmov"];
	 $ldec_tasa=$_POST["tasa"];
	 $ls_opener   =$_POST["opener"];
     $ls_cuentaplan = "";
  	 $ls_denominacion="";
}
else
{
	$ls_operacion="";
  	$ls_cuentaplan = "";
	$ls_documento  = "";
	$ls_procedencia= $_GET["txtprocedencia"];
	$ls_descripcion= $_GET["descripcion"];
	$ls_denominacion="";
	$ld_fecha	   = "";
	$ldec_monto	   = "";
	$ls_mov_colocacion=$_GET["txtdoccol"];
	$ls_numcol=$_GET["numcol"];
	$ls_mov_procede=$_GET["procede"];
	$ld_fecha=$_GET["fecha"];
	$ls_mov_descripcion=$_GET["descripcion"];
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_cuenta_scg=$_GET["cuenta_scg"];
	$ls_codope=$_GET["mov_operacion"];
	$ldec_monto_mov=$_GET["monto"];
	$ls_chevau=$_GET["chevau"];
	$li_estint=$_GET["estint"];
	$li_cobrapaga=$_GET["cobrapaga"];
	$ls_estbpd=$_GET["estbpd"];
	$ls_estmov=$_GET["estmov"];
	$ls_codconmov=$_GET["codconmov"];
	$ldec_tasa=$_GET["tasa"];
	$ls_opener   =$_GET["opener"];
}

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

if($ls_operacion=="GUARDAR")
{
	$ls_codban      = $_POST["codban"];
	$ls_ctaban      = $_POST["ctaban"];
	$ls_numcol      = $_POST["numcol"];
	$ls_mov_colocacion=$_POST["txtdoccol"];
	$ldec_monto     = $_POST["monto"];
	$ls_codope      = $_POST["mov_operacion"];	
	$ld_fecha       = $_POST["fecha"];
	$ls_descripcion   = $_POST["descripcion"];
	$ldec_tasa        = $_POST["tasa"];
	$li_cobrapaga     = $_POST["cobrapaga"];
	
	$arr_movbco["codban"]=$ls_codban;
	$arr_movbco["ctaban"]=$ls_ctaban;
    $arr_movbco["mov_colocacion"]=$ls_mov_colocacion;
	$arr_movbco["numcol"]= $ls_numcol ;
	$arr_movbco["codope"]= $ls_codope; 
	$arr_movbco["estcol"]= 'N'; 
	
	
	$ls_cuenta_scg_col= $_POST["cuenta_scg"];
	$ls_mov_procede   = $_POST["procede"];
	$ls_codded        = '00000';

	$ls_esttransf     = 0;
	
	$lb_valido=$in_classmovcol->uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_mov_colocacion,$ls_numcol,$ls_codope,$ld_fecha,$ls_descripcion,$ldec_monto,$ldec_tasa,'N',$li_cobrapaga,$ls_esttransf,$la_seguridad);
	
	if($lb_valido)
	{
		$lb_valido=$in_classmovcol->uf_procesar_dt_contable($arr_movbco,$ls_cuenta_scg_col,$ls_operacioncon,'00000',$ls_descripcion,$ldec_monto,true,$la_seguridad);	
	}	
	
	$ldec_monto   = $_POST["txtmonto"];
	$ldec_monto=str_replace(".","",$ldec_monto);
	$ldec_monto=str_replace(",",".",$ldec_monto);
	$ls_sc_cuenta     = $_POST["txtcuenta"];
	$ls_debhab        = $_POST["txtoperacion"];
	
	if($lb_valido)
	{
		$lb_valido=$in_classmovcol->uf_procesar_dt_contable($arr_movbco,$ls_sc_cuenta,$ls_debhab,'00000',$ls_descripcion,$ldec_monto,false,$la_seguridad);	
				
		if(!$lb_valido)
		{
			$in_classmovcol->SQL->rollback();
			$msg->message($in_classmovcol->is_msg_error);
		}
		else
		{
			$in_classmovcol->SQL->commit();
			?>
			<script language="javascript">
				f=opener.document.form1;
				f.operacion.value="CARGAR_DT";
				f.action="<?php print $ls_opener;?>" ;
				f.submit();
			</script>	
			<?php			
		}	
	}
	else
	{
		$in_classmovcol->SQL->rollback();
		$msg->message($in_classmovcol->is_msg_error);
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
   <td colspan="2" class="titulo-celda">Entrada de Movimientos Contables </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="119" height="22" align="right">Documento</td>
    <td width="446"><input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="javascript:valid_cmp(this);" value="<?php print $ls_mov_colocacion?>"  size="20" maxlength="15"></td>
  </tr>
  <tr>
    <td height="22" align="right">Descripci&oacute;n</td>
    <td><input name="txtdescripcion" type="text" id="txtdescripcion" size="80" maxlength="100" style="text-align:left" value="<?php print $ls_descripcion;?>"></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Cuenta Contable</div></td>
    <td><input name="txtcuenta" type="text" id="txtcuenta" value="<?php print $ls_cuentaplan ?>" size="20" style="text-align:center"> 
    <a href="javascript:catalogo_cuentasSCG();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Cuentas de Gastos"></a>	 <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_denominacion ?>" size="50" maxlength="254"></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Operaci&oacute;n</div></td>
    <td><div align="left"> 
	
     <select name="txtoperacion" id="txtoperacion">
        <option value="D" <?php print $lb_seldeb;?>>Debe</option>
        <option value="H" <?php print $lb_selhab;?>>Haber</option>
     </select>
    </div></td>
  </tr>
  <tr>
    <td height="22" align="right">Monto</td>
    <td><input name="txtmonto" type="text" id="txtmonto" style="text-align:right" size="20" onBlur="javascript:uf_format(this);"> 
      <a href="javascript:aceptar_presupuestario();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar Detalle Contable" width="15" height="15" border="0"></a> <a href="javascript:uf_close();"><img src="../shared/imagebank/tools15/eliminar.gif" alt="Cancelar Registro  Contable" width="15" height="15" border="0"></a>      </td>
  </tr>
  <tr>
    <td><div align="right"></div></td>
    <td>
		<input name="operacion"      type="hidden"  id="operacion">
        <input name="txtdoccol"      type="hidden"  id="txtdoccol"      value="<?php print $ls_mov_colocacion;?>">
		<input name="numcol"         type="hidden"  id="numcol"         value="<?php print $ls_numcol;?>">
        <input name="procede"        type="hidden"  id="procede"        value="<?php print $ls_mov_procede;?>">
        <input name="fecha"          type="hidden"  id="fecha"          value="<?php print $ld_fecha;?>">
        <input name="descripcion"    type="hidden"  id="descripcion"    value="<?php print $ls_mov_descripcion;?>"> 
        <input name="codban"         type="hidden"  id="codban"         value="<?php print $ls_codban;?>">      
        <input name="ctaban"         type="hidden"  id="ctaban"         value="<?php print $ls_ctaban;?>">
        <input name="cuenta_scg"     type="hidden"  id="cuenta_scg"     value="<?php print $ls_cuenta_scg;?>">
        <input name="mov_operacion"  type="hidden"  id="mov_operacion"  value="<?php print $ls_codope?>">
        <input name="monto"          type="hidden"  id="monto"          value="<?php print $ldec_monto_mov;?>">
        <input name="chevau"         type="hidden"  id="chevau"         value="<?php print $ls_chevau;?>">
        <input name="estint"         type="hidden"  id="estint"         value="<?php print $li_estint;?>">
        <input name="cobrapaga"      type="hidden"  id="cobrapaga"      value="<?php print $li_cobrapaga;?>">
        <input name="estbpd"         type="hidden"  id="estbpd"         value="<?php print $ls_estbpd;?>">
        <input name="estmov"         type="hidden"  id="estmov"         value="<?php print $ls_estmov;?>">
        <input name="codconmov"      type="hidden"  id="codconmov"      value="<?php print $ls_codconmov;?>">
        <input name="tip_mov"        type="hidden"  id="tip_mov"        value="<?php print $ls_estreglib;?>">
	    <input name="opener"         type="hidden"  id="opener"         value="<?php print $ls_opener;?>">
        <input name="tasa"           type="hidden"  id="tasa"           value="<?php print $ldec_tasa;?>">
	</td>
  </tr>
</table>
</form>
</body>
<script language="JavaScript">
  function aceptar_presupuestario()
  {
	f=document.form1;
	fop=opener.document.form1;
	if((f.txtdescripcion.value!="") && (f.txtcuenta.value!="") && (f.txtmonto.value!="") && (f.txtmonto.value!="0") && (f.txtmonto.value!=",00") && (f.txtmonto.value!="0,00"))
	{
		f.operacion.value="GUARDAR";
		f.action="sigesp_w_regdt_col_contable.php";
		f.submit();	
	}
	else
	{
		alert("Complete los datos");
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
</script>
</html>