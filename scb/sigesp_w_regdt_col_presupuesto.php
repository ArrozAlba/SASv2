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
require_once("../shared/class_folder/sigesp_c_seguridad.php");
require_once("sigesp_scb_c_movcol.php");
$msg			= new class_mensajes();
$siginc			= new sigesp_include();
$con			= $siginc->uf_conectar();
$fun			= new class_funciones();
$SQL			= new class_sql($con);
$io_seguridad	= new sigesp_c_seguridad();
$in_classmovcol = new sigesp_scb_c_movcol($la_seguridad);

if (array_key_exists("operacion",$_POST))
{
   $ls_operacion=$_POST["operacion"];
   $ls_codope= $_POST["mov_operacion"];	
   $ls_cuenta_scg=$_POST["cuenta_scg"];
   $ls_codban=$_POST["codban"];
   $ls_ctaban=$_POST["ctaban"];
   $ld_fecha=$_POST["fecha"];
   $ls_mov_colocacion=$_POST["numdoc"];
   $ls_numcol=$_POST["txtdoccol"];
   $ls_descripcion=$_POST["txtdescripcion"];
   $ldec_tasa=$_POST["tasa"];
   $li_cobrapaga= $_POST["cobrapaga"];
   $ls_opener   =$_POST["opener"];
   $ls_mov_colocacion=$_POST["numdoc"];
   $ls_mov_procede=$_POST["txtprocedencia"];
   $ls_mov_descripcion=$_POST["descripcion"];
   $ldec_monto_mov=$_POST["monto"];
}
else
{
	$ls_operacion="";
  	$ls_cuentaplan = "";
	$ls_documento  = $_GET["numdoc"];
	$ls_procedencia= $_GET["txtprocedencia"];
	$ls_descripcion= $_GET["descripcion"];
	$ls_denominacion="";
	$ld_fecha	   = "";
	$ldec_monto	   = "";
	$ls_mov_colocacion=$_GET["numdoc"];
	$li_cobrapaga=$_GET["cobrapaga"];
	$ls_numcol=$_GET["txtdoccol"];
	$ls_mov_procede=$_GET["procede"];
	$ld_fecha=$_GET["fecha"];
	$ls_mov_descripcion=$_GET["descripcion"];
	$ls_codban=$_GET["codban"];
	$ls_ctaban=$_GET["ctaban"];
	$ls_cuenta_scg=$_GET["cuenta_scg"];
	$ls_codope=$_GET["mov_operacion"];
	$ldec_monto_mov=$_GET["monto"];
	$li_cobrapaga=$_GET["cobrapaga"];
	$ls_codconmov=$_GET["codconmov"];
	$ldec_tasa=$_GET["tasa"];
	$ls_opener   =$_GET["opener"];
}


if($ls_operacion=="GUARDARPRE")
{
	$ldec_monto=$_POST["txtmonto"];
	$ldec_monto_mov=$_POST["monto"];
	$ls_estcol="N";
	$ls_esttransf     = 0;
	$in_classmovcol->SQL->begin_transaction();
	
	$lb_valido=$in_classmovcol->uf_guardar_automatico($ls_codban,$ls_ctaban,$ls_mov_colocacion,$ls_numcol,$ls_codope,$ld_fecha,$ls_descripcion,$ldec_monto_mov,$ldec_tasa,'N',$li_cobrapaga,$ls_esttransf);

	 $arr_col["codban"]=$ls_codban;
	 $arr_col["ctaban"]=$ls_ctaban;
	 $arr_col["estcol"]=$ls_estcol;
	 $arr_col["mov_colocacion"]=$ls_mov_colocacion;
	 $arr_col["numcol"]=$ls_numcol;
	 $arr_col["codope"]=$ls_codope;
	 $arr_col["fecmovcol"]=$ld_fecha;	
	 $arr_col["conmov"]=$ls_descripcion;
	 $arr_col["monmovcol"]=$ldec_monto_mov;
	 $arr_col["tasmovcol"]=$ldec_tasa;	
		
	if($lb_valido)
	{
		
		if(($ls_codope=="ND"))
		{
			$ls_operacioncon="H";
		}
		else
		{
			$ls_operacioncon="D";
		}
		
		$lb_valido=$in_classmovcol->uf_procesar_dt_contable($arr_col,   $ls_cuenta_scg,$ls_operacioncon,'00000'  ,$ls_descripcion,  $ldec_monto_mov,true);	
														   
		$ls_cuenta      = $_POST["txtcuentascg"];
		$ls_documento   = $_POST["txtdocumento"];
		$ls_denominacion= $_POST["txtdescripcion"];
		$ls_procede     = $_POST["txtprocedencia"];
		$ls_operacioncon= "D";
		$ld_monto       = $_POST["txtmonto"];
		$ldec_monto=str_replace(".","",$ld_monto);
		$ldec_monto=str_replace(",",".",$ldec_monto);
		if($lb_valido)
		{
			$lb_valido=$in_classmovcol->uf_procesar_dt_contable($arr_col, $ls_cuenta,$ls_operacioncon,'00000',    $ls_denominacion,      $ldec_monto,false);	
																
			$ls_spgcuenta = $_POST["txtcuenta"];
			$ls_est1      = $_POST["codestpro1"];
			$ls_est2      = $_POST["codestpro2"];
			$ls_est3      = $_POST["codestpro3"];
			$ls_programa  =	$ls_est1.$ls_est2.$ls_est3."0000";
			$ls_documento    = $_POST["txtdocumento"];
			$ls_desmov    = $_POST["txtdescripcion"];
			$ls_procededoc= $_POST["txtprocedencia"];
			$ls_operacion    = $_POST["txtoperacion"];
			$ldec_monto   = $_POST["txtmonto"];
			$ldec_monto=str_replace(".","",$ldec_monto);
			$ldec_monto=str_replace(",",".",$ldec_monto);
									
			$lb_valido=$in_classmovcol-> uf_procesar_dt_gasto($ls_codban,$ls_ctaban,$ls_mov_colocacion,$ls_codope,$ls_numcol,$ls_programa,$ls_spgcuenta,$ls_desmov,$ldec_monto,$ls_operacion,$ls_estcol);

			if($lb_valido)
			{
				$in_classmovcol->SQL->commit();
				?>
				<script language="javascript">
					f=opener.document.form1;
					f.operacion.value="CARGAR_DT";
					f.action="<?php print $ls_opener;?>";
					f.submit();
				</script>	
				<?php
				$ls_procedencia="";
				$ls_cuenta="";
				$ls_denominacion="";
				$ls_cuentaplan ="";
			}
			else
			{
				$in_classmovcol->SQL->rollback();
				$msg->message($in_classmovcol->is_msg_error);
			}
		}
		else
		{
			$msg->message($in_classmovcol->is_msg_error);
			$in_classmovcol->SQL->rollback();
		}				
	} 	
	else
	{
		$msg->message($in_classmovcol->is_msg_error);
		$in_classmovcol->SQL->rollback();
	}			
	
}
switch ($ls_operacion) {
   case 'AAP':
       $ls_apertura="selected";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
       break;
   case 'AU':
       $ls_apertura="";
       $ls_aumento="selected";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
       break;
   case 'DI':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="selected";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado=""; 
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
       break;
	case 'PC':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="selected";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
	   $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'CS':   
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="selected";
	   $ls_compromisogastocausado="";	   
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'CG': 
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
	   $ls_compromisogastocausado="selected";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'GC':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
	   $ls_gastocausado="selected";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;   
	case 'CP':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
       $ls_gastocausado="";
       $ls_causadopago="selected";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'PG':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
       $ls_compromisogastocausado="";
	   $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="selected";
       $ls_compromisocausasopago="";	   	   	   
	   break;
	case 'CCP':
       $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
	   $ls_compromisogastocausado="";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="selected";	   	   	   
	   break;
    default:
	   $ls_apertura="";
       $ls_aumento="";
       $ls_disminucion="";
       $ls_precompromiso="";	   
       $ls_compromiso="";
	   $ls_compromisogastocausado="selected";
       $ls_gastocausado="";
       $ls_causadopago="";
       $ls_pago="";
       $ls_compromisocausasopago="";	   	   	   
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
   <td colspan="2" class="titulo-celda">Entrada de detalle Gastos </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="119" height="22" align="right">Documento</td>
    <td width="450"><input name="txtdocumento" type="text" id="txtdocumento" style="text-align:center" onBlur="javascript:valid_cmp(this);" size="22" maxlength="15" value="<?php print $ls_documento;?>"></td>
  </tr>
  <tr>
    <td height="22" align="right">Descripci&oacute;n</td>
    <td><input name="txtdescripcion" type="text" id="txtdescripcion" size="80" maxlength="100" style="text-align:left" value="<?php print $ls_descripcion;?>"></td>
  </tr>
  <tr>
    <td height="22" align="right">Procedencia</td>
    <td><input name="txtprocedencia" type="text" id="txtprocedencia" size="22" maxlength="6" style="text-align:center" value="<?php print $ls_procedencia;?>" readonly></td>
  </tr>
   <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro1"];  ?></div></td>
    <td>
      <input name="codestpro1" type="text" id="codestpro1" size="22" maxlength="20" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro1();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 1"></a>      <input name="denestpro1" type="text" class="sin-borde" id="denestpro1" size="53" readonly>     
      <div align="left">      </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro2"]; ?></div>      </td>
    <td><input name="codestpro2" type="text" id="codestpro2" size="22" maxlength="6" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro2();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 2"></a>
      <input name="denestpro2" type="text" class="sin-borde" id="denestpro2" size="53" readonly></td>
  </tr>
  <tr>
    <td height="22"><div align="right"><?php print $dat["nomestpro3"]; ?></div></td>
    <td>      <div align="left">
      <input name="codestpro3" type="text" id="codestpro3" size="22" maxlength="3" style="text-align:center" readonly>
      <a href="javascript:catalogo_estpro3();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Estructura Programatica 3"></a>
      <input name="denestpro3" type="text" class="sin-borde" id="denestpro3" size="53" readonly>
      </div></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Cuenta</div></td>
    <td><input name="txtcuenta" type="text" id="txtcuenta" value="<?php print $ls_cuentaplan ?>" size="22" style="text-align:center"> 
    <a href="javascript:catalogo_cuentasSPG();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Cuentas de Gastos"></a>	 <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion3" style="text-align:left" value="<?php print $ls_denominacion ?>" size="50" maxlength="254"></td>
  </tr>
  <tr>
    <td height="22"><div align="right">Operaci&oacute;n</div></td>
    <td><div align="left">
      <select name="txtoperacion" id="txtoperacion" style="width:200px">
        <option value="AAP" <?php print $ls_apertura;?>    		  >ASIENTO DE APERTURA		  </option>
        <option value="AU"  <?php print $ls_aumento;?>     		  >AUMENTO DE PARTIDA		  </option>
        <option value="DI"  <?php print $ls_disminucion;?> 		  >DISMINUCION DE PARTIDA	  </option>
        <option value="PC"  <?php print $ls_precompromiso;?>		  >PRE-COMPROMISO			  </option>
        <option value="CS"  <?php print $ls_compromiso;?>   		  >COMPROMISO SIMPLE		  </option>
        <option value="CG"  <?php print $ls_compromisogastocausado;?>>COMPROMISO Y GASTO CAUSADO </option>
        <option value="GC"  <?php print $ls_gastocausado;?> 		  >GASTO CAUSADO			  </option>
        <option value="CP"  <?php print $ls_causadopago;?>  		  >GASTO CAUSADO Y PAGO		  </option>
        <option value="PG"  <?php print $ls_pago;?>         		  >PAGO						  </option>
        <option value="CCP" <?php print $ls_compromisocausasopago;?> >COMPROMISO,CAUSADO Y PAGADO</option>
      </select>
    </div></td>
  </tr>
  <tr>
    <td height="22" align="right">Monto</td>
    <td><input name="txtmonto" type="text" id="txtmonto" style="text-align:right" size="22" onKeyPress="return(currencyFormat(this,'.',',',event))"> 
      <a href="javascript:aceptar_presupuestario();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar Detalle Presupuestario" width="15" height="15" border="0"></a> <a href="javascript: close();"><img src="../shared/imagebank/tools15/eliminar.gif" alt="Cancelar Registro de Detalle Presupuestario" width="15" height="15" border="0"></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
      <!-- Valores y operaciones propias del registro de presupuesto-->
	  <input name="txtcuentascg" type="hidden" id="txtcuentascg">
      <input name="cuenta_scg" type="hidden" id="cuenta_scg" value="<?php print $ls_cuenta_scg;?>">
      <input name="comprobante" type="hidden" id="comprobante" value="<?php print $ls_comprobante;?>">
      <input name="descripcion" type="hidden" id="descripcion" value="<?php print $ls_mov_descripcion;?>">
      <input name="operacion" type="hidden" id="operacion">
      <!-- Datos del movimiento-->
	  <input name="codban" type="hidden" id="codban" value="<?php print $ls_codban;?>">
      <input name="ctaban" type="hidden" id="ctaban" value="<?php print $ls_ctaban;?>">
      <input name="txtdoccol" type="hidden" id="txtdoccol" value="<?php print $ls_numcol;?>">
      <input name="numdoc" type="hidden" id="numdoc" value="<?php print $ls_mov_colocacion;?>">
      <input name="mov_operacion" type="hidden" id="mov_operacion" value="<?php print $ls_codope;?>">
	  <input name="fecha" type="hidden" id="fecha" value="<?php print $ld_fecha;?>">
   	  <input name="conmov" type="hidden" id="conmov" value="<?php print $ls_conmov;?>">
	  <input name="monto" type="hidden" id="monto" value="<?php print $ldec_monto_mov;?>">
      <input name="tasa" type="hidden" id="tasa" value="<?php print $ldec_tasa;?>">
	  <input name="cobrapaga"      type="hidden"  id="cobrapaga"      value="<?php print $li_cobrapaga;?>">
	  <!-- Opener del documento-->
	  <input name="opener" type="hidden" id="opener" value="<?php print $ls_opener;?>">
	</td>
  </tr>
</table>
</form>
</body>
<script language="JavaScript">
  function aceptar_presupuestario()
  {
  	f=document.form1;
	ls_numdoc=f.txtdocumento.value;
	ls_procede=f.txtprocedencia.value;
	ls_codest1=f.codestpro1.value;
	ls_codest2=f.codestpro2.value;
	ls_codest3=f.codestpro3.value;
	ls_cuenta=f.txtcuenta.value;
	ls_operacion=f.txtoperacion.value;
	ldec_monto=f.txtmonto.value;
	if((ls_numdoc!="")&&(ls_procede!="")&&(ls_codest1!="")&&(ls_codest2!="")&&(ls_codest3!="")&&(ls_cuenta!="")&&(ls_operacion!="")&&(ldec_monto!=""))
	{
		f.operacion.value="GUARDARPRE";
		f.action="sigesp_w_regdt_col_presupuesto.php";
		f.submit();
  	}
	else
	{
		alert("Complete todos los datos");
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
	   if((codest1!="")&&(codest2!="")&&(codest3!=""))
	   {
	   pagina="sigesp_cat_ctaspg.php?codestpro1="+codest1+"&hicodest2="+codest2+"&hicodest3="+codest3;
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=618,height=400,resizable=yes,location=no");
	   }
	   else
	   {
	   alert("Debe completar la programatica");
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
	if((codestpro1!="")&&(denestpro1!=""))
	{
		pagina="sigesp_cat_public_estpro2.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
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
	denestpro2=f.denestpro2.value;
	if((codestpro1!="")&&(denestpro1!="")&&(codestpro2!="")&&(denestpro2!=""))
	{
    	pagina="sigesp_cat_public_estpro3.php?codestpro1="+codestpro1+"&denestpro1="+denestpro1+"&codestpro2="+codestpro2+"&denestpro2="+denestpro2;
		window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=568,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("Seleccione la Estructura de nivel Anterior");
	}
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
</script>
</html>
