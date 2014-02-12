<?Php
/*************************************************************************/
/**************  INICIO DE LA PAGINA E INICIO DE SESION   ****************/
/*************************************************************************/

session_start();
if (!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_conexion.php'";
	print "</script>";
}
$la_datemp=$_SESSION["la_empresa"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Contabilizaci&oacute;n de Dep&oacute;sito de Efectivo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1"><script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css"><script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
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
</script><style type="text/css">
<!--
.Estilo3 {color: #6699CC}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="2" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td width="500" height="20" class="cd-menu"><span class="descripcion_sistema Estilo1 Estilo3">Sistema de Facturaci&oacute;n</span></td>
    <td width="278" class="cd-menu"><div align="right"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></div></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">&nbsp;</td>
    <td height="20" class="cd-menu"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
  </tr>
  <tr>
    <td height="20" colspan="2" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script><script type="text/javascript" language="JavaScript1.2" src="js/validaciones.js"></script>	</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" colspan="2" bgcolor="#FFFFFF" class="toolbar"><a href="javascript: ue_ver();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Buscar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" width="20" height="20" border="0"></a><!--img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20"--><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?Php

//********************************************         SEGURIDAD       ****************************************************
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SFC";
	$ls_ventanas="sigesp_sfc_d_cierrecaja.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{
			$ls_permisos                         =  $_POST["permisos"];
			$la_permisos["leer"]           =  $_POST["leer"];
			$la_permisos["incluir"]       =  $_POST["incluir"];
			$la_permisos["cambiar"]  =  $_POST["cambiar"];
			$la_permisos["eliminar"]   =  $_POST["eliminar"];
			$la_permisos["imprimir"]   =  $_POST["imprimir"];
			$la_permisos["anular"]      =  $_POST["anular"];
			$la_permisos["ejecutar"]  = $_POST["ejecutar"];
		
	}
	else
	{
		$la_permisos["leer"]="";
		$la_permisos["incluir"]="";
		$la_permisos["cambiar"]="";
		$la_permisos["eliminar"]="";
		$la_permisos["imprimir"]="";
		$la_permisos["anular"]="";
		$la_permisos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_permisos);
	}
//*****************         SEGURIDAD    ****************************************/
/********************************************************/
/**************   LIBRERIAS  ****************************/
/********************************************************/
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("class_folder/sigesp_sfc_c_cierre.php");

$io_cierre=new sigesp_sfc_c_cierre();
$io_include=new sigesp_include();
$io_connect=$io_include->uf_conectar();
$io_sql=new class_sql($io_connect);
$io_data=new class_datastore();
$io_data_scg=new class_datastore();
$io_msg=new class_mensajes();


$io_msg=new class_mensajes();

if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_codusu="%".$_POST["txtcodusu"]."%";
	$ls_codusuV=$_POST["txtcodusu"];
	$ls_nomusuV=$_POST["txtnomusu"];
	$ls_codcie=$_POST["hidcodcie"];
	$ld_fecdep=$_POST["txtfeccie"];
	//print $ld_fecdep;
	$ls_hidstatus=$_POST["hidstatus"];
	if(array_key_exists("chkprecie",$_POST)){
       $ls_precie="V";
    }
	else{
	   $ls_precie="F";
	}
	$ls_codcaj=$_POST["txtcodcaj"];
	$ls_nomcaj=$_POST["txtnomcaj"];
	$ldec_total=number_format($_POST["txttotal"],2,",",".");
	$ls_numdep=$_POST["txtdeposito"];	
	$ls_codban=$_POST["txtcodban"];
	$ls_nomban=$_POST["txtnomban"];
	//print $ls_codban;
	$ls_ctaban=$_POST["txtctaban"];
	$ls_descta=$_POST["txtdescta"];
	$ls_ctabancoscg=$_POST["txtctascg"];	
	$ldec_total_caja=$_POST["txtcaja"];	
	$ldec_total_adelantos=$_POST["txtadelanto"];	
	$ldec_diferencia=$_POST["txttotal"];
	$ls_concepto=$_POST["txtconcepto"];	
}
else
{
	$ls_operacion="";
	$ls_codusu="";
	$ls_codusuV="";
	$ls_nomusuV="";
	$ls_nomusu="";
	$ls_codcie="";
	$ls_precie="";
	$ld_fecdep='';
	$ls_hidstatus="";
	$ls_codcaj="";
	$ls_nomcaj="";
	$ldec_total_caja=number_format(0,2,",",".");		
	$ldec_total_adelantos=number_format(0,2,",",".");		
	$ldec_diferencia=number_format(0,2,",",".");		
	$ldec_total=number_format(0,2,",",".");	
	$ls_numdep="";
	$ls_codban="";
	$ls_ctaban="";
	$ls_ctabancoscg="";
	$ls_concepto='';
	$ls_nomban='';
	$ls_descta='';
}
if ($ls_operacion=='ue_calcular')
{
$ls_fecprueba=substr($ld_fecdep,6,4)."-".substr($ld_fecdep,3,2)."-".substr($ld_fecdep,0,2);
$lb_validocierre=$io_cierre->uf_select_cierre($ld_fecdep);
if (!$lb_validocierre)
{
$io_msg->message ("No ha sido realizado el cierre para ese día!!!");
}
else
{
require_once("class_folder/sigesp_sfc_c_contabilizar.php");  
$io_contabiliza=new sigesp_sfc_c_contabilizar($ls_fecprueba);
require_once("class_folder/sigesp_sfc_c_int_data.php");
$io_intdat=new sigesp_sfc_c_int_data();
//Datos del comprobante de contabilizacion diaria

$ls_procedencia="SFCCIE";
$ls_fecha=$ls_fecprueba;
$ls_codban='---';
$ls_ctaban='-------------------------';
$lb_existe_contabilizacion=false;
$lb_existe_contabilizacion=$io_intdat->uf_select_comprobante($ls_empresa,$ls_procedencia,$ls_fecha,$ls_codban,$ls_ctaban);
//print $lb_existe_contabilizacion;
if ($lb_existe_contabilizacion)
{
$io_contabiliza->is_conpag=1;//Facturas al contado
$ldec_mondeb=0;
$ldec_monhab=0;
$io_contabiliza->uf_buscar_total_monto_contado_efectivo($ldec_total_caja);
$io_contabiliza->uf_buscar_total_monto_adelantos_efectivo($ldec_total_adelantos);
$io_contabiliza->is_conpag=2;//Facturas a credito
$ldec_mondeb=0;
$ldec_monhab=0;
$io_contabiliza->uf_buscar_total_monto_contado_efectivo($ldec_total_caja1);
$io_contabiliza->uf_buscar_total_monto_adelantos_efectivo($ldec_total_adelantos1);
$io_contabiliza->is_conpag=3;//Facturas parciales
$ldec_mondeb=0;
$ldec_monhab=0;
$io_contabiliza->uf_buscar_total_monto_contado_efectivo($ldec_total_caja2);
$io_contabiliza->uf_buscar_total_monto_adelantos_efectivo($ldec_total_adelantos2);
$io_contabiliza->is_conpag=4;//Facturas con Carta Orden
$ldec_mondeb=0;
$ldec_monhab=0;
$io_contabiliza->uf_buscar_total_monto_contado_efectivo($ldec_total_caja3);
$io_contabiliza->uf_buscar_total_monto_adelantos_efectivo($ldec_total_adelantos3);				
$ldec_total_caja=$ldec_total_caja+$ldec_total_caja1+$ldec_total_caja2+$ldec_total_caja3;
$ldec_total_adelantos=$ldec_total_adelantos+$ldec_total_adelantos1+$ldec_total_adelantos2+$ldec_total_adelantos3;
$ldec_diferencia=$ldec_total_caja+$ldec_total_adelantos;
//print $ldec_diferencia;
$ldec_diferencia=number_format($ldec_diferencia,2,",",".");
}
else
{
$io_msg->message ("No ha sido realizado la contabilización diaria para la fecha seleccionada ".$ld_fecdep."!!!");
}
}
}
if($ls_operacion=="PROCESAR")
{
			require_once("class_folder/sigesp_sfc_c_int_data.php");
			$io_intdat=new sigesp_sfc_c_int_data();
			require_once("class_folder/sigesp_sfc_c_contabilizar.php");  
			$io_contabiliza=new sigesp_sfc_c_contabilizar($ls_fecprueba);	
			$io_contabiliza->ls_spicuenta    =$_SESSION["ls_spicuenta"];
			//$io_contabiliza->ls_cuentascg   =$io_intdat->uf_buscar_contrapartida($io_contabiliza->ls_spicuenta);
			$lb_valido=$io_intdat->uf_buscar_contrapartida($io_contabiliza->ls_spicuenta,&$io_contabiliza->ls_cuentascg);
			//Busqueda y asignacion de las cuentas contables
			$lb_valido=$io_intdat->uf_buscar_cuentacontable('111010101',&$io_contabiliza->ls_cuentacaja);
			if ($lb_valido)
			{
			$io_contabiliza->ls_cuentabanco  =$ls_ctabancoscg;
			$lb_valido=$io_intdat->uf_buscar_cuentacontable('219090201',&$io_contabiliza->ls_cuentaadelanto);
			}			
			//Establecimiento de los parametros del comprobante de deposito
			$io_contabiliza->io_int_int->is_codemp=$_SESSION["la_empresa"]["codemp"];
			$io_contabiliza->io_int_int->is_procedencia="SCBBDP";
			$io_contabiliza->io_int_int->is_comprobante=$ls_numdep;
			$io_contabiliza->io_int_int->id_fecha=$ld_fecdep;
			$_SESSION["fechacomprobante"]=$ld_fecdep;
			$io_contabiliza->io_int_int->ii_tipo_comp=1;
			$io_contabiliza->io_int_int->is_descripcion="DEPOSITO POR VENTAS DE LA TIENDA ".$_SESSION["ls_nomtienda"]." DEL DIA ".$ld_fecdep ;
			$io_contabiliza->io_int_int->is_tipo='-';
			$io_contabiliza->io_int_int->is_cod_prov='----------';
			$io_contabiliza->io_int_int->is_ced_ben='----------';
			$io_contabiliza->io_int_int->as_codban=$ls_codban;
			$io_contabiliza->io_int_int->as_ctaban=$ls_ctaban; 
			$io_contabiliza->io_int_int->io_int_scg->as_codban=$ls_codban;
			$io_contabiliza->io_int_int->io_int_scg->as_ctaban=$ls_ctaban; 																		 
			$io_contabiliza->io_int_int->is_modo='C'; 
			$lb_valido=$io_contabiliza->uf_procesar_contabilizacion_despositos_efectivo($ldec_total_caja,$ldec_total_adelantos,$ldec_diferencia,$ls_ctabancoscg,$la_seguridad);			
			if($lb_valido)
			{
					$io_msg->message("Proceso Ejecutado Satisfactoriamente.");	
			}
			else
			{
					$io_msg->message("Ocurrio un error en el Proceso.");	
			}
}
?>
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=leer value='$la_permisos[leer]'>");
	print("<input type=hidden name=incluir  id=incluir value='$la_permisos[incluir]'>");
	print("<input type=hidden name=cambiar  id=cambiar value='$la_permisos[cambiar]'>");
	print("<input type=hidden name=eliminar id=eliminar value='$la_permisos[eliminar]'>");
	print("<input type=hidden name=imprimir id=imprimir value='$la_permisos[imprimir]'>");
	print("<input type=hidden name=anular   id=anular value='$la_permisos[anular]'>");
	print("<input type=hidden name=ejecutar id=ejecutar value='$la_permisos[ejecutar]'>");
}
else
{

	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>

    <table width="791" height="443" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="789" height="195"><div align="center">
          <table width="770"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td colspan="3" class="titulo-ventana">
                    Contabilizaci&oacute;n de Dep&oacute;sito de Efectivo </td>
            </tr>
            <tr>
              <td >
			  <input name="operacion" type="hidden" id="operacion"  value="<? print $ls_operacion?>">
			  <input name="hidstatus" type="hidden" id="hidstatus" value="<? print $ls_hidstatus?>">
			  <input name="hidcodcie" type="hidden" id="hidcodcie" value="<?php print $ls_codcie ?>"></td>
              <td colspan="2" >&nbsp;</td>
            </tr>

			<tr>
			  <td height="22" align="right">&nbsp;</td>
			  <td width="280" ><div align="right">Fecha</div></td>
		      <td width="342" ><input name="txtfeccie" type="text" id="txtfeccie"  style="text-align:left" value="<?php print $ld_fecdep?>" size="11" maxlength="10"  datepicker="true"  readonly="true"></td>
			</tr>

			<tr>
              <td width="146" height="22" align="right">Cajero </td>
              <td colspan="2" ><input name="txtcodusu" type="text" id="txtcodusu" style="text-align:center " value="<?php print $ls_codusuV?>" size="15" maxlength="15"  readonly="true">
			  <!-- javascript:ue_catusuario(); -->
              <a href="javascript:ue_catusuario();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
              <input name="txtnomusu" type="text" id="txtnomusu" class="sin-borde" size="40" readonly="true" value="<?php print $ls_nomusuV?>" ></td>
            </tr>
            <tr>
              <td width="146" height="22" align="right">Caja </td>
              <td colspan="2" ><input name="txtcodcaj" type="text" id="txtcodcaj" style="text-align:center " value="<?php print $ls_codcaj?>" size="15" maxlength="15"  readonly="true">
			  <!-- javascript:ue_catusuario(); -->
              <a href="javascript:ue_catcaja();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de tipos de unidades"></a>
              <input name="txtnomcaj" type="text" id="txtnomcaj" class="sin-borde" size="40" readonly="true" value="<?php print $ls_nomcaj?>" ></td>
            </tr>
		    <tr>
		      <td height="22" align="right">N&ordm; Deposito </td>
		      <td colspan="2" ><label>
		        <input name="txtdeposito" type="text" class="letras-negrita" id="txtdeposito"  style="text-align:center; font-size:14px;" value="<?php print $ls_numdep;?>" size="15" maxlength="15">
		      </label></td>
	        </tr>
			<?php
			if ($ls_operacion=='ue_calcular' || $ls_operacion=='PROCESAR')
			{
			?>
		    <tr>
		      <td height="29" align="right">Banco</td>
		      <td colspan="2" ><input name="txtcodban" type="text" class="letras-negrita" id="txtcodban" value="<?php print $ls_codban?>" size="15" maxlength="15">
	          <a href="javascript:ue_catbanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Bancos"></a>
	          <input name="txtnomban" type="text" id="txtnomban" class="sin-borde" size="70" readonly="true" value="<?php print $ls_nomban?>" >
	          </td>
	        </tr>
		    <tr>
		      <td height="28" align="right">Cuenta</td>
		      <td colspan="2" ><input name="txtctaban" type="text" class="letras-negrita" id="txtctaban" size="15" maxlength="15"value="<?php print $ls_ctaban?>" >
		        <a href="javascript:ue_catctabanco();"><img src="../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Cat&aacute;logo de Cuentas Bancarias"></a>
                <input name="txtdescta" type="text" id="txtdescta" class="sin-borde" size="70" readonly="true" value="<?php print $ls_descta?>" >
              <input name="txtctascg" type="hidden" id="txtctascg" value="<?php print $ls_ctabancoscg?>">
			  </td>
	        </tr>
			<?php
			}
			?>			
		    <tr>
			<td height="37" align="right">Monto Efectivo (Cobranzas)</td>
			<td colspan="2" ><label>
			  <input name="txtcaja" type="text" class="texto-azul" id="txtcaja" style="text-align:right; font-size:18px;"  value="<?php print  number_format($ldec_total_caja,2,",",".");?>" size="15" readonly>
			</label></td>
		</tr>
		<tr>
			<td height="36" align="right">Monto Efectivo (Adelantos)</td>
			<td colspan="2" ><label>
			  <input name="txtadelanto" type="text" class="texto-azul" id="txtadelanto" style="text-align:right; font-size:18px;" value="<?php print  number_format($ldec_total_adelantos,2,",",".");?>" size="15" readonly>
			</label></td>
		</tr>
		<tr>
          <td height="36" align="right">Total Deposito </td>
		  <td height="36" colspan="2" ><label>
            <input name="txttotal" type="text" class="letras-negrita" id="txttotal" style="text-align:right; font-size:18px;"   value="<?php print $ldec_diferencia;?>" size="15" readonly>
          </label>		    <label></label></td>
		  </tr>
		<tr>
          <td height="22" align="right">Concepto</td>
		  <td colspan="2" ><label>
            <input name="txtconcepto" type="text" id="txtconcepto" style="text-align:left" size="90" value="<?php print $ls_concepto;?>">
          </label></td>
		  </tr>
            <tr>
              <td width="146" height="22" align="right">&nbsp;</td>
              <td colspan="2" ><div align="left">
                <p>&nbsp;</p>
				<?php
				if ($ld_fecdep=='')
				{
				?>
				<p><a href="javascript:ue_calcular();">Calcular<img src="Imagenes/ejecutar.gif" width="20" height="20" border="0"></a></p>
				<?php
				}
				else
				{
				?>
                <p><a href="javascript:ue_procesar();">Procesar<img src="Imagenes/ejecutar.gif" width="20" height="20" border="0"></a></p>
				<?php
				}
				?>
              </div></td>
            </tr>
            <tr>
              <td height="8" colspan="3">
              <p>&nbsp;</p></td>
            </tr>
          </table>
          <p>&nbsp;</p>
        </div></td>
      </tr>
  </table>
</form>
</body><script language="JavaScript">
/*******************************************************************************************************************************/
	function ue_procesar()
	{
		  f=document.form1;
		
		 /* if(f.txtcodcaj.value=="" || f.txtcodusu.value==""){
			alert("Seleccione el cajero y la caja de la cual desea hacer el cierre");
		  }
		  else{
				li_incluir=f.incluir.value;
				li_cambiar=f.cambiar.value;
				lb_status=f.hidstatus.value;
				if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
				{
					if (lb_status!="C")
					{
						f.hidstatus.value="C";
					}*/
			 if (f.txtcodban.value=='---')
			 {
				 alert('Debe seleccionar un Banco Valido');
				  txtcodban.focus();
				  suiche=false;
			 }
			 else if (f.txtctaban.value=='-------------------------')
			 {
			 	alert('Debe seleccionar una Cta. Valida');
				  txtctaban.focus();
				  suiche=false;
			 }
			 else if (ue_valida_null(txtconcepto,"Concepto")==false)
			 {
				  txtconcepto.focus();
				  suiche=false;
			 }
			 else
			 {
			 		f.operacion.value="PROCESAR";
					//alert (f.operacion.value);
					f.action="sigesp_sfc_d_contabilizar_deposito.php";
					f.submit();
			 }
		/*		}else{
					alert("No tiene permiso para realizar esta operacion");
			}*/
	  //}
}

function ue_ver()
{
  f=document.form1;
  f.operacion.value="VER";
  f.action="sigesp_sfc_d_cierre_diario.php";
  f.submit();
}

function ue_nuevo()
{
	f=document.form1;

	li_incluir=f.incluir.value;
	if(li_incluir==1)
	{
		f.operacion.value="ue_nuevo";
		f.txtcodusu.value="";
		f.txtnomusu.value="";
		f.txtcodtie.value="";
		f.txtnomtie.value="";
		f.action="sigesp_sfc_d_cajero.php";
		f.submit();
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function ue_guardar()
{
	f=document.form1;

	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.hidstatus.value;
	if(((lb_status=="C")&&(li_cambiar==1))||(lb_status!="C")&&(li_incluir==1))
	{
		if (lb_status!="C")
		{
			f.hidstatus.value="C";
		}

		with(f)
		{
			if (ue_valida_null(txtcodusu,"Usuario")==false)
			{
				txtcodusu.focus();
			}
			else
			{
				if (ue_valida_null(txtcodtie,"Tienda")==false)
				{
				  txtcodtie.focus();
				}
				else
				{
					f.operacion.value="ue_guardar";
					f.action="sigesp_sfc_d_cajero.php";
					f.submit();
				}
			}
		}
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_eliminar()
{
	f=document.form1;

	li_eliminar=f.eliminar.value;
	//li_cambiar=f.cambiar.value;
	//lb_status=f.hidstatus.value;
	if( li_eliminar==1)
	{
		with(f)
		{
			if (ue_valida_null(txtcodusu,"Usuario")==false)
			{
				txtcodusu.focus();
			}
			else
			{
				if (confirm("ï¿½ Esta seguro de eliminar este registro ?"))
				{
					f.operacion.value="ue_eliminar";
					f.action="sigesp_sfc_d_cierrecaja.php";
					f.submit();
				}
				else
				{
					f=document.form1;
					f.action="sigesp_sfc_d_cierrecaja.php";
					alert("Eliminaciï¿½n Cancelada !!!");
					f.txtcodusu.value="";
					f.txtnomusu.value="";
					f.operacion.value="";
					f.submit();
				}
			}
		}
    }
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_buscar()
{
	f=document.form1;

	li_leer=f.leer.value;
	if(li_leer==1)
	{
		f.operacion.value="";
		pagina="sigesp_cat_cierre.php";
		popupWin(pagina,"catalogo",650,300);
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}

function ue_cargarcaja(codcaja,desccaja,precot,prefac,predev,preped,sercot,serfac,serdev,serped,sernot,sercon,formalibre,precob,sercob)
{
	f=document.form1;
	f.txtcodcaj.value = codcaja;
	f.txtnomcaj.value = desccaja;

}

function ue_catcaja(){
	pagina="sigesp_cat_caja.php";
	popupWin(pagina,"catalogo",650,300);
}

/***********************************************************************************************************************************/

		function ue_catusuario()
		{
            f=document.form1;
			f.operacion.value="";
		    pagina="../sss/sigesp_sss_cat_usuarios.php?destino=Reporte";
	    	popupWin(pagina,"catalogo",520,200);
		}
		
		function ue_catbanco()
		{
            f=document.form1;
			f.operacion.value="";
		    pagina="sigesp_cat_banco2.php";
	    	popupWin(pagina,"catalogo",520,200);
		}
		
		function ue_catctabanco()
		{
            f=document.form1;
			ls_codban=f.txtcodban.value;
			f.operacion.value="";
		    pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban;
	    	popupWin(pagina,"catalogo",520,200);
		}		

		function ue_cattienda()
		{
            f=document.form1;
			f.operacion.value="";
		    pagina="sigesp_cat_tienda.php";
	    	popupWin(pagina,"catalogo",520,200);
		}

		function ue_cargartienda(codtie,nomtie,dirtie,teltie,riftie,codpai,codest,codmun,codpar)
		{
 	   	    f=document.form1;
			f.txtcodtie.value=codtie;
        	f.txtnomtie.value=nomtie;

		}

		function ue_cargarcajero(codusu,nomusu,codtie,nomtie)
		{
		    f=document.form1;
			f.txtcodusu.value=codusu;
			f.txtnomusu.value=nomusu;
			/*f.txtcodtie.value=codtie;*/
			/*f.txtnomtie.value=nomtie;*/
       	}

		function ue_cargarcierre(codcie,codusu,feccie,nomusu)
		{
		    f=document.form1;
			f.hidcodcie.value=codcie;
			f.txtfeccie.value=feccie;
			f.txtcodusu.value=codusu;
			f.txtnomusu.value=nomusu;
			f.hidstatus.value="C";
		}

		function ue_calcular_diferencia()
		{
			f=document.form1;
			ldec_caja=parseFloat(uf_convertir_monto(f.txtcaja.value));
			ldec_adelanto=parseFloat(uf_convertir_monto(f.txtadelanto.value));
			ldec_total=f.txttotal.value;
			if(ldec_total=="")			
			{
					ldec_total=0;
					f.txttotal.value=uf_convertir(ldec_total);
			}
			ldec_total=parseFloat(uf_convertir_monto(ldec_total));
			ldec_diferencia=(ldec_caja+ldec_adelanto)-ldec_total;
			f.txtdiferencia.value=uf_convertir(ldec_diferencia);
		}
	function ue_calcular()
	{
	f=document.form1;
	with(f)
		{
		    if (ue_valida_null(txtfeccie,"Fecha")==false)
			 {
				  txtfeccie.focus();
				  suiche=false;
			 }		
			 else if (ue_valida_null(txtcodusu,"Cajero")==false)
			 {
				  txtcodusu.focus();
				  suiche=false;
			 }
			 else if (ue_valida_null(txtcodcaj,"Caja")==false)
			 {
				  txtcodcaj.focus();
				  suiche=false;
			 }
			else if (ue_valida_null(txtdeposito,"Nro. Deposito")==false)
			 {
				  txtdeposito.focus();
				  suiche=false;
			 }
			else{
			 	f.action="sigesp_sfc_d_contabilizar_deposito.php";
				f.operacion.value="ue_calcular";
				f.submit();
			 }
			 
		}
	
	}
		
/***********************************************************************************************************************************/

</script><script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>
