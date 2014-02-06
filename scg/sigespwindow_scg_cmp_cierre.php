<?php
session_start();
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "location.href='../sigesp_inicio_sesion.php'";
	print "</script>";		
}
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
    
	$dat=$_SESSION["la_empresa"];
	$ls_empresa=$dat["codemp"];
	$ls_logusr=$_SESSION["la_logusr"];
	$ls_sistema="SCG";
	$ls_ventanas="sigespwindow_scg_cmp_cierre.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;
	
	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos=$_POST["permisos"];
		}
	}
	else
	{
		$ls_permisos=$io_seguridad->uf_sss_select_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas);
	}
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_codban="---";
	$ls_ctaban="-------------------------";

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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<title >Comprobante Contable.</title>
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
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {color: #6699CC}
-->
</style>
</head>

<body onUnload="javascript:uf_valida_cuadre();">
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
	function uf_valida_cuadre()
	{
		f=document.form1;
		ldec_diferencia=f.txtdiferencia.value;
		ldec_diferencia=uf_convertir_monto(ldec_diferencia);
		ls_operacion=f.operacion.value;
		if((ldec_diferencia!=0)&&((ls_operacion=="")||(ls_operacion=="GUARDAR")||(ls_operacion=='NUEVO')))
		{
			alert("Comprobante descuadrado Contablemente");
			f.operacion.value="CARGAR_DT";
			f.action="sigespwindow_scg_cmp_cierre.php";
			f.submit();
		}		
	}
	
	
	
</script>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
   <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Sistema de Contabilidad Patrimonial</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
  <tr>
    <td height="21" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" align="center" class="toolbar">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar" align="center">&nbsp;</td>
    <td class="toolbar">&nbsp;</td>
  </tr>
  <tr>
 <td width="25" height="20" align="center" class="toolbar"><a href="javascript: ue_ejecutar();"><img src="../shared/imagebank/tools20/ejecutar.gif" alt="Procesar" title="Procesar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="25" class="toolbar" align="center">&nbsp;</td>
    <td width="530" class="toolbar">&nbsp;</td>
  </tr>
</table>
<?php
require_once("../shared/class_folder/class_fecha.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_sigesp_int.php");
require_once("../shared/class_folder/class_sigesp_int_scg.php");
require_once("class_funciones_scg.php");
$funciones_scg=new class_funciones_scg();
$fun=new class_funciones();
$int_scg=new class_sigesp_int_scg();
$msg=new class_mensajes();
$int_fec=new class_fecha();
$la_emp=$_SESSION["la_empresa"];
if(array_key_exists("operacion",$_POST))
{
	$ls_operacion=$_POST["operacion"];
	$ls_comprobante = $_POST["txtcomprobante"];
	$ls_fecha     = $_POST["txtfecha"];
	$li_fila		 = 0;
	$ls_codban  = "---";
	$ls_ctaban  = "-------------------------";
}
else
{
	$ls_operacion="NUEVO";
	require_once("../shared/class_folder/class_funciones.php");
	$io_funcion=new class_funciones();
	$io_funcion->uf_limpiar_sesion();
	$_SESSION["ib_new"]	=true;
	$ls_fecha=date("d/m/Y");
	$li_fila		 = 0;
}
//Incluyo la clase datastore
require_once("../shared/class_folder/class_datastore.php");
//Instancio la clase datastore
$ds_mov=new class_datastore();
if($ls_operacion=="NUEVO")//Acciones para un comprobante nuevo
{
	uf_nuevo();
}
if($ls_operacion=="EJECUTAR")//Ejecutar el cierre	
{
	
	$lb_val_cierre = $int_scg->uf_scg_select_estatus_cierre_presupuesto($ls_empresa,$ls_status_spg,$ls_status_spi);
	
	if ($lb_val_cierre)
	{
		if ($ls_status_spg==0)
		{
			$msg->message("No puede Ejecutar el Cierre Contable. Debe Procesar el Cierre Presupuestario. Contacte al Administrador del Sistema !!!");
			uf_nuevo();
		}
	    else if ($ls_status_spi==0)
		{
			$msg->message("No puede Ejecutar el Cierre Contable. Debe Procesar el Cierre Presupuestario. Contacte al Administrador del Sistema !!!");
			uf_nuevo();
		}
	    else
		{
			$lb_valido=$int_scg->uf_existe_comprobante_cierre();
			
			$lb_valido = $int_scg->uf_scg_procesar_cierre();
			$ls_codemp      =  $int_scg->is_codemp;
			$ls_procede     =  $int_scg->is_procedencia;
			$ls_comprobante =  $int_scg->is_comprobante;
			$ls_fecha       =  $int_scg->id_fecha;
			$ls_tipo		=  $int_scg->is_tipo;
			$ls_codban  = "---";
			$ls_ctaban  = "-------------------------";
			$ls_codban  =  $int_scg->as_codban;
			$ls_ctaban  =  $int_scg->as_ctaban;
			
			$ls_provbene	=  "";
			$ls_descripcion	=  $int_scg->is_descripcion;
			$readonly="";
			$ls_cuenta="";
			$ls_denominacion="";
			$ls_procdoc="";
			$ls_documento= "";
			$ls_debhab   = "";
			$ldec_monto  = "";
			$li_fila	 = 0;
			$_SESSION["ACTUALIZAR"]="SI";	
			
			if ($lb_valido)
			{
				$lb_actualizar=$int_scg->uf_scg_update_estciescg($ls_codemp,1);			
			}
			else
			{
				$msg->message("Las Cuentas no poseen Movimientos, No se puede Ejecutar el Cierre Contable.");
			}
			$int_scg->uf_cargar_comprobante_cierre($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha);	
		}
	}
	else
	{
			$msg->message("No se puede Ejecutar el Cierre Contable.");
			uf_nuevo();
	}		
}

function uf_nuevo()
{
	global $ds_mov;
	$li_a=false;
	global $int_scg;
	global $ls_comprobante;
	global $ls_fecha;
	global $ls_descripcion;
	global $ls_tipo;
	global $ls_provbene;
	global $readonly;
	global $ls_cuenta;
	global $ls_denominacion;
	global $ls_procdoc;
	global $ls_documento;
	global $ls_debhab;
	global $ldec_monto;
	global $li_fila;
	global $msg;
	$lb_valido=$int_scg->uf_existe_comprobante_cierre();
	if($lb_valido)
	{
		$msg->message("El cierre fue ejecutado con Anteriodad");
		$ls_codemp      =  $int_scg->is_codemp;
		$ls_procede     =  $int_scg->is_procedencia;
		$ls_comprobante =  $int_scg->is_comprobante;
		$ls_fecha       =  $int_scg->id_fecha;
		$ls_tipo		=  $int_scg->is_tipo;
		$ls_provbene	=  "";
		$ls_descripcion	=  $int_scg->is_descripcion;
		$readonly="";
		$ls_cuenta="";
		$ls_denominacion="";
		$ls_procdoc="";
		$ls_documento= "";
		$ls_debhab   = "";
		$ldec_monto  = "";
		$li_fila	 = 0;
		$_SESSION["ACTUALIZAR"]="SI";
		$int_scg->uf_cargar_comprobante_cierre($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha);
	}
	$_SESSION["ACTUALIZAR"]="SI";
}
if($ls_operacion=="GUARDAR")
{
 	$lb_valido=true;
	$ls_codemp="";$ls_procede="";$ls_comprobante="";$ls_cod_prov="";$ls_ced_ben="";$ls_descripcion="";$ls_tipo="";
	$li_tipo_comp=0;$li_row=0;
	$ls_fecha="";
	global $ib_new;
		$ds_mov->data    = $_SESSION["objact"];
		$ls_codemp  = $la_emp["codemp"];
		$ls_procede = $_POST["txtproccomp"];
		$ls_comprobante = $_POST["txtcomprobante"];
		$ls_fecha     = $_POST["txtfecha"];
		$ls_cod_prov = "----------";
		$ls_ced_ben  = "----------";
		$ls_provbene = "----------";
		$ls_procede	  = $_POST["txtproccomp"];
		$ls_descripcion = $_POST["txtdesccomp"];
		$ldec_mondeb=$_POST["txtdebe"];
		$ldec_monhab=$_POST["txthaber"];
		$ldec_diferencia=$_POST["txtdiferencia"];
		$is_tipo  =	"-";
		$ls_tipo  = "-";
		
		$ii_tipo_comp = 1;
				
		if($ldec_diferencia==0)//Valido que el comprobante este cuadrado
		{
			if(!uf_valida_datos_cabezera($ls_comprobante,$ls_tipo,$ls_cod_prov,$ls_ced_ben,$ls_procede))
			{
				$ib_valido = false;
			}
			else
			{
						
				if($int_scg->uf_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ls_codban,$ls_ctaban))
				{
					   if($lb_valido) 
					   {
						   $lb_valido =	uf_guardar_movimientos($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ii_tipo_comp,$ls_descripcion,$ds_mov,$ls_cod_prov,$ls_ced_ben);
						   
						   if ($lb_valido)
						   {
								$msg->message("El comprobante contable fue registrado."); 
								//////////////////////////////////         SEGURIDAD               /////////////////////////////		
									$ls_evento="INSERT";
									$ls_descripcion =" Inserta el comprobante contable,Asociado a la Empresa:".$ls_codemp."  Procede:".$ls_procede."  Comprobante:".$ls_comprobante." y la Fecha:".$ls_fecha."  Descripcion:".$ls_descripcion;
									$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
																	$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
																	$la_seguridad["ventanas"],$ls_descripcion);
								/////////////////////////////////         SEGURIDAD               /////////////////////////////	
								$ls_procedeaux=$ls_procede;
								$ls_comprobanteaux=$ls_comprobante;
								$fecha=$fun->uf_convertirdatetobd($ls_fecha);
								$ls_comprobante = "";
								$ls_fecha     = "";
								$ls_provbene  = "";
								$ls_descripcion = "";
								$ls_procede	  = "SCGCMP";
								$ls_tipo      = "";
								$ds_mov->resetds("SC_cuenta");
						   }
						   else
						   {
								$int_scg->uf_sql_transaction($lb_valido);
						   }
					   }
					   else		
					   {
							$msg->message("Error al procesar el comprobante contable".$int_scg->is_msg_error); 
					   }
					} 
					$lb_valido=$int_scg->uf_sql_transaction( $lb_valido );
			}
		}
		else
		{
			$msg->message("Monto descuadrado, no se puede procesar el comprobante");
		}
		$ib_valido       = $lb_valido;
		$readonly        = "";
		$ls_cuenta       = "";
		$ls_denominacion = "";
		$ls_procdoc      = "";
		$ls_documento    = "";
		$ls_debhab       = "";
		$ldec_monto      = "";
		$li_fila		 = 0;
		
}
elseif($ls_operacion=="ELIMINAR")
{
	$ls_codemp      = $_SESSION["la_empresa"]["codemp"];
	$ls_comprobante = $_POST["txtcomprobante"];
	$ls_procede	    = $_POST["txtproccomp"];
	$ls_fecha       = $_POST["txtfecha"];
	
			$ls_codemp    = $la_emp["codemp"];
			$int_scg->uf_cargar_comprobante_cierre_delete($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha);
			$li_total     =	$int_scg->lds_cmp_cierre_del->getRowCount("sc_cuenta");
			$lb_valido=true;
			for($li_row=1;$li_row<=$li_total;$li_row++)
			{
				$ls_documento	= 	$int_scg->lds_cmp_cierre_del->getValue("documento",$li_row);
				$ls_procede_doc	= 	$int_scg->lds_cmp_cierre_del->getValue("procede_doc",$li_row);
				$ls_cuenta		= 	$int_scg->lds_cmp_cierre_del->getValue("sc_cuenta",$li_row);
				$ls_operacion   = 	$int_scg->lds_cmp_cierre_del->getValue("debhab",$li_row);
				$ldec_monto	    =	$int_scg->lds_cmp_cierre_del->getValue("monto",$li_row);	
				
			$int_scg->is_codemp=$ls_codemp;
			$int_scg->is_procedencia=$ls_procede;
			$int_scg->is_comprobante=$ls_comprobante;
			$int_scg->id_fecha=$ls_fecha;
			$int_scg->as_codban="---";
			$int_scg->as_ctaban="-------------------------";
			//Función que elimina los detalles del comprobante y actualiza los saldos			
			$lb_valido=$int_scg->uf_scg_procesar_delete_movimiento($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ls_cuenta,
																	$ls_procede_doc,$ls_documento,$ls_operacion,$ldec_monto,
																	$int_scg->as_codban,$int_scg->as_ctaban);
			}
			if($lb_valido)
			{		
				//Funcion que elimina los datos de la cabezera del comprobante
				$lb_valido=$int_scg->uf_sigesp_delete_comprobante($ls_codemp,$ls_comprobante,$ls_procede,$ls_fecha);
				$lb_actualizar=$int_scg->uf_scg_update_estciescg($ls_codemp,0);
				if (($lb_valido)&&($lb_actualizar))
				{
					  //////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="DELETE";
						$ls_descripcion =" Elimino el comprobante contable,Asociado a la Empresa:".$ls_codemp."  Procede:".$ls_procede."  Comprobante:".$ls_comprobante." y la Fecha:".$ls_fecha;
						$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
														$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
														$la_seguridad["ventanas"],$ls_descripcion);
					 /////////////////////////////////         SEGURIDAD               /////////////////////////////	
					$int_scg->io_sql->commit();
					$msg->message("Comprobante eliminado satisfactoriamente !!!");					
				}
				else
				{
					$int_scg->io_sql->rollback();
					$msg->message("No se pudo eliminar comprobante".$int_scg->is_msg_error);
				}
				unset($int_scg->lds_cmp_cierre_del);
				$int_scg->lds_cmp_cierre->resetds("sc_cuenta");
				$ls_comprobante = "";
				$ls_fecha     = "";
				$ls_provbene  = "";
				$ls_descripcion = "";
				$ls_procede	  = "SCGCIE";
				$ls_tipo      = "";
				$readonly="";
				$ls_cuenta="";
				$ls_denominacion="";
				$ls_procdoc="";
				$ls_documento="";
				$ls_debhab="";
				$ldec_monto="";
			}
			else
			{
				$msg->message("".$int_scg->is_msg_error);
				$int_scg->lds_cmp_cierre->resetds("sc_cuenta");
				$ls_comprobante = "";
				$ls_fecha     = "";
				$ls_provbene  = "";
				$ls_descripcion = "";
				$ls_procede	  = "SCGCIE";
				$ls_tipo      = "";
				$readonly="";
				$ls_cuenta="";
				$ls_denominacion="";
				$ls_procdoc="";
				$ls_documento="";
				$ls_debhab="";
				$ldec_monto="";
			}

}
elseif($ls_operacion=="AGREGAR")//Acciones para agregar un detalle contable al comprobante
{
	$int_scg->lds_cmp_cierre->data    = $_SESSION["objact"];
	$readonly="";
	$ls_cuenta       = $_POST["txtcuenta"];
	$ls_denominacion = $_POST["txtdescdoc"];
	$ls_procdoc      = $_POST["txtprocdoc"];
	$ls_documento    = $_POST["txtdocumento"];
	$ls_debhab       = $_POST["debhab"];
	$ldec_monto      = $_POST["txtmonto"];
	$ls_comprobante = $_POST["txtcomprobante"];
	$ls_procede	  = $_POST["txtproccomp"];
	$ls_fecha     = $_POST["txtfecha"];
	$ls_provbene  = "----------";
	$ls_tipo      = "-";
	$ls_descripcion = $_POST["txtdesccomp"];

		if(!$int_scg->uf_valida_procedencia( $ls_procdoc,&$ls_desproc ))	
		{	
			$msg->message("Procedencia ".$ls_procdoc." es invalida");
		}
		else
		{
			if(($ls_cuenta!="")&&($ls_denominacion!="")&&($ls_procdoc!="")&&($ls_documento!="")&&($ls_debhab!="")&&($ldec_monto))
			{
				$arr["sc_cuenta"]=$ls_cuenta;
				$arr["procede_doc"]=$ls_procdoc;
				$arr["documento"]=$ls_documento;
				$arr["debhab"]=$ls_debhab;
								
				$find=$int_scg->lds_cmp_cierre->findValues($arr,"sc_cuenta");
				if(($find<0)&&($_SESSION["ACTUALIZAR"]=="NO"))
				{
					$int_scg->lds_cmp_cierre->insertRow("sc_cuenta",$ls_cuenta);
					$int_scg->lds_cmp_cierre->insertRow("denominacion",$ls_denominacion);
					$int_scg->lds_cmp_cierre->insertRow("procede_doc",$ls_procdoc);
					$int_scg->lds_cmp_cierre->insertRow("documento",$ls_documento);
					$int_scg->lds_cmp_cierre->insertRow("debhab",$ls_debhab);
					$int_scg->lds_cmp_cierre->insertRow("monto",$ldec_monto);
				}
				elseif(($find<0)&&($_SESSION["ACTUALIZAR"]=="SI"))
				{
					
					$ls_codemp		 = $la_emp["codemp"];
					$ls_comprobante  = $_POST["txtcomprobante"];
					$ls_procede	     = $_POST["txtproccomp"];
					$ls_fecha        = $_POST["txtfecha"];
					$ls_cod_prov	 = "----------";
					$ls_ced_bene	 = "----------";
					$ls_tipo="-";
			
					$li_tipo_comp = 1;
					$ld_debaux=0;
					$ld_habaux=0;
					$ld_mondeb=0;
					$ld_monhab=0;
					
					$ls_procede_doc = 	$ls_procdoc;
					$ldec_monto_actual=$ldec_monto;
					$ldec_monto_anterior=0;
					$lb_valido=$int_scg->uf_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ls_codban,$ls_ctaban);
					if($lb_valido)
					{
						$fecha=$fun->uf_convertirdatetobd($ls_fecha);
						$lb_valido = $int_scg->uf_scg_procesar_insert_movimiento($ls_codemp,$ls_procede,$ls_comprobante,$fecha,
															  $ls_tipo,$ls_cod_prov,$ls_ced_bene,$ls_cuenta,
															  $ls_procede_doc,$ls_documento,$ls_debhab,$ls_denominacion,
															  $ldec_monto_anterior,$ldec_monto_actual,$ls_codban,$ls_ctaban);
									
						$lb_valido=$int_scg->uf_sql_transaction( $lb_valido );
		
						if(!$lb_valido)
						{
							$msg->message("Error al registrar movimiento contable. ".$int_scg->is_msg_error);
						}
						$int_scg->lds_cmp_cierre->insertRow("sc_cuenta",$ls_cuenta);
						$int_scg->lds_cmp_cierre->insertRow("denominacion",$ls_denominacion);
						$int_scg->lds_cmp_cierre->insertRow("procede_doc",$ls_procdoc);
						$int_scg->lds_cmp_cierre->insertRow("documento",$ls_documento);
						$int_scg->lds_cmp_cierre->insertRow("debhab",$ls_debhab);
						$int_scg->lds_cmp_cierre->insertRow("monto",$ldec_monto);
					}
					
				}
				else
				{
					$msg->message("No puede repetirse el movimiento");
				}
			}
			else
			{
				$msg->message("Verifique los datos del movimiento");
			}	
			$ls_cuenta="";
			$ls_denominacion="";
			$ls_procdoc="";
			$ls_documento="";
			$ls_debhab="";
			$ldec_monto="";
			$li_fila		 = 0;
		}
}
elseif($ls_operacion=="DELMOV")//Acciones para eliminar en detalle contable del comprobante
{
	$int_scg->lds_cmp_cierre->data    = $_SESSION["objact"];
	$ls_codemp		 = $la_emp["codemp"];
	$ls_procede		 = $_POST["txtproccomp"];
	$ls_comprobante  = $_POST["txtcomprobante"];
	$ls_fecha        = $_POST["txtfecha"];
	$ls_descripcion  = $_POST["txtdesccomp"];
	$ls_cuenta       = $_POST["txtcuenta"];
	$ls_denominacion = $_POST["txtdescdoc"];
	$ls_procdoc      = $_POST["txtprocdoc"];
	$ls_documento    = $_POST["txtdocumento"];
	$ls_debhab       = $_POST["debhab"];
	$ldec_monto      = $_POST["txtmonto"];
	$int_scg->is_codemp      =	$ls_codemp;
	$int_scg->is_procedencia =  $ls_procede;
	$int_scg->is_comprobante =	$ls_comprobante;
	$int_scg->id_fecha       =	$ls_fecha;
	$ls_provbene     = "----------";
	$ls_tipo         = "-";
	
	 if($int_scg->uf_scg_select_movimiento($ls_cuenta,$ls_procdoc,$ls_documento,$ls_debhab, &$ldec_monto2,&$li_orden))
		 {
		 	//print "!or aqui";
			$lb_valido=$int_scg->uf_scg_procesar_delete_movimiento($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ls_cuenta,$ls_procdoc,$ls_documento,$ls_debhab,$ldec_monto);
			$lb_valido=$int_scg->uf_sql_transaction($lb_valido);
			$row=$int_scg->lds_cmp_cierre->find("sc_cuenta",$ls_cuenta);
			$int_scg->lds_cmp_cierre->deleteRow("sc_cuenta",$row);
		 }
	$li_fila		 = 0;
	$ls_cuenta       = "";
	$ls_denominacion = "";
	$ls_procdoc      = "";
	$ls_documento    = "";
	$ls_debhab       = "";
	$ldec_monto      = "";
	$readonly        = "";	
	

}
elseif($ls_operacion=="EDITAR")//Accion de seleccion de un elemento de la tabla y mostrarlo en los input bien sea para editarlos o para eliminarlos del datastore
{
	$int_scg->lds_cmp_cierre->data    = $_SESSION["objact"];
	$ls_cuenta       = $_POST["txtcuenta"];
	$ls_denominacion = $_POST["txtdescdoc"];
	$ls_procdoc      = $_POST["txtprocdoc"];
	$ls_documento    = $_POST["txtdocumento"];
	$ls_debhab       = $_POST["debhab"];
	$ldec_monto      = $_POST["txtmonto"];
	$readonly        = "readonly";
	$ls_comprobante  = $_POST["txtcomprobante"];
	$ls_fecha        = $_POST["txtfecha"];
	$li_fila		 = $_POST["fila"];
	$ls_descripcion  = $_POST["txtdesccomp"];
}
elseif($ls_operacion=="UPDMOV")
{
	if(array_key_exists("ACTUALIZAR",$_SESSION))
	{
		$ls_actualiza=$_SESSION["ACTUALIZAR"];
		if ($ls_actualiza=="SI")
		{
				$int_scg->lds_cmp_cierre->data    = $_SESSION["objact"];

				$ls_codemp	  = $la_emp["codemp"];
				$ls_procede   = $_POST["txtproccomp"];
				$ls_fecha     = $_POST["txtfecha"];
				$ls_provbene  = "----------";
				$ls_tipo      = "-";
				$ls_descripcion = $_POST["txtdesccomp"];
				$ls_cod_prov  = "----------";
				$ls_ced_ben   = "----------";
				if(!$int_scg->uf_scg_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha))
				{
					/*$row=$ds_mov->find("SC_cuenta",$ls_cuenta);
					$ds_mov->deleteRow("SC_cuenta",$row);*/
				}
				else
				{
						
						$li_total=	$int_scg->lds_cmp_cierre->getRowCount("SC_cuenta");
						for($li_i=1;$li_i<=$li_total;$li_i++)
						{
				
							$ls_cuenta       =	$int_scg->lds_cmp_cierre->getValue("sc_cuenta",$li_i);
							$ls_denominacion =	$int_scg->lds_cmp_cierre->getValue("denominacion",$li_i);
							$ls_procdoc      =	$int_scg->lds_cmp_cierre->getValue("procede_doc",$li_i);
							$ls_documento    =	$int_scg->lds_cmp_cierre->getValue("documento",$li_i);
							$ls_debhab       =	$int_scg->lds_cmp_cierre->getValue("debhab",$li_i);
							$ldec_monto      =	$int_scg->lds_cmp_cierre->getValue("monto",$li_i);
							 if($int_scg->uf_scg_select_movimiento($ls_cuenta,$ls_procdoc,$ls_documento,$ls_debhab, &$ldec_monto2,&$li_orden))
							 {
								$lb_valido=$int_scg->uf_scg_procesar_delete_movimiento($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ls_cuenta,$ls_procdoc,$ls_documento,$ls_debhab,$ldec_monto);
								$lb_valido=$int_scg->uf_sql_transaction($lb_valido);
							 }
						}
						$li_fila		 = $_POST["fila"];
						$ls_cuenta       = $_POST["txtcuenta"];
						$ls_denominacion = $_POST["txtdescdoc"];
						$ls_procdoc      = $_POST["txtprocdoc"];
						$ls_documento    = $_POST["txtdocumento"];
						$ls_debhab       = $_POST["debhab"];
						$ldec_monto      = $_POST["txtmonto"];
						$ls_comprobante  = $_POST["txtcomprobante"];					 
				}
					
				$int_scg->lds_cmp_cierre->updateRow("sc_cuenta",$ls_cuenta,$li_fila);
				$int_scg->lds_cmp_cierre->updateRow("denominacion",$ls_denominacion,$li_fila);
				$int_scg->lds_cmp_cierre->updateRow("procede_doc",$ls_procdoc,$li_fila);
				$int_scg->lds_cmp_cierre->updateRow("documento",$ls_documento,$li_fila);
				$int_scg->lds_cmp_cierre->updateRow("debhab",$ls_debhab,$li_fila);
				$int_scg->lds_cmp_cierre->updateRow("monto",$ldec_monto,$li_fila);
				$ii_tipo_comp = 1;
				$lb_valido =	uf_guardar_movimientos($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ii_tipo_comp,$ls_descripcion,$int_scg->lds_cmp_cierre,$ls_cod_prov,$ls_ced_ben);
				$ls_cuenta="";
				$ls_denominacion="";
				$ls_procdoc="";
				$ls_documento="";
				$ls_debhab="";
				$ldec_monto="";
				$readonly="";	
				$_SESSION["ACTUALIZAR"]="NO";
		}
		else
		{
			$int_scg->lds_cmp_cierre->data    = $_SESSION["objact"];
			$li_fila		 = $_POST["fila"];
			$ls_cuenta       = $_POST["txtcuenta"];
			$ls_denominacion = $_POST["txtdescdoc"];
			$ls_procdoc      = $_POST["txtprocdoc"];
			$ls_documento    = $_POST["txtdocumento"];
			$ls_debhab       = $_POST["debhab"];
			$ldec_monto      = $_POST["txtmonto"];
			$ls_cuenta="";
			$ls_denominacion="";
			$ls_procdoc="";
			$ls_documento="";
			$ls_debhab="";
			$ldec_monto="";
			$readonly="";	
		}
	}
	$int_scg->lds_cmp_cierre->data    = $_SESSION["objact"];
	$li_fila		 = $_POST["fila"];
	$ls_cuenta       = $_POST["txtcuenta"];
	$ls_denominacion = $_POST["txtdescdoc"];
	$ls_procdoc      = $_POST["txtprocdoc"];
	$ls_documento    = $_POST["txtdocumento"];
	$ls_debhab       = $_POST["debhab"];
	$ldec_monto      = $_POST["txtmonto"];
	$int_scg->lds_cmp_cierre->updateRow("sc_cuenta",$ls_cuenta,$li_fila);
	$int_scg->lds_cmp_cierre->updateRow("denominacion",$ls_denominacion,$li_fila);
	$int_scg->lds_cmp_cierre->updateRow("procede_doc",$ls_procdoc,$li_fila);
	$int_scg->lds_cmp_cierre->updateRow("documento",$ls_documento,$li_fila);
	$int_scg->lds_cmp_cierre->updateRow("debhab",$ls_debhab,$li_fila);
	$int_scg->lds_cmp_cierre->updateRow("monto",$ldec_monto,$li_fila);
	$ls_cuenta="";
	$ls_denominacion="";
	$ls_procdoc="";
	$ls_documento="";
	$ls_debhab="";
	$ldec_monto="";
	$readonly="";	
}
elseif($ls_operacion=="VALIDAFECHA")
{
	$int_scg->lds_cmp_cierre->data    = $_SESSION["objact"];
	$readonly="";
	$ls_cuenta       = $_POST["txtcuenta"];
	$ls_denominacion = $_POST["txtdescdoc"];
	$ls_procdoc      = $_POST["txtprocdoc"];
	$ls_documento    = $_POST["txtdocumento"];
	$ls_debhab       = $_POST["debhab"];
	$ldec_monto      = $_POST["txtmonto"];
	$ls_comprobante = $_POST["txtcomprobante"];
	$ls_fecha     = $_POST["txtfecha"];
	$ls_tipo      = $_POST["tipo"];
	$ls_provbene  = $_POST["txtprovbene"];
	$ls_descripcion = $_POST["txtdesccomp"];
	$ls_codemp=$la_emp["codemp"];
	$lb_valido=$int_fec->uf_valida_fecha_periodo($ls_fecha,$ls_codemp);
	if(!$lb_valido)
	{
		$msg->message($int_fec->is_msg_error);
	}
	$li_fila		 = 0;
}


	function uf_valida_datos_cabezera($as_comprobante,$as_tipo,$as_cod_prov,$as_ced_bene,$as_procedencia)
	{
		$ls_desproc="" ;
		$int_scg=new class_sigesp_int_scg();
		$msg=new class_mensajes();
		
		if(!$int_scg->uf_valida_procedencia( $as_procedencia,&$ls_desproc ))
		{
			$msg->message("".$as_comprobante.$ls_desproc);
			return false;	
		}
		
		if(trim($as_comprobante)=="")
		{
			$msg->message("Debe registrar el comprobante contable.");
			return false;
		}
		
		if(trim($as_comprobante)=="000000000000000")
		{
			$msg->message("Debe registrar el comprobante contable.");
			return false;
		}
				
		if($as_comprobante=="")
		{
			$msg->message("Debe registrar el comprobante contable.");
			return false;
		}
		
		if((trim($as_cod_prov)=="----------")&&($as_tipo=="P"))
		{
			$msg->message("Debe registrar el codigo del proveedor.");
			return false;	
		}
		
		if((trim($as_cod_prov)=="")&&($as_tipo=="P"))
		{
			$msg->message("Debe registrar el codigo del proveedor.");
			return false;
		}
		
		if((trim($as_cod_prov)!="----------")&&($as_tipo=="B"))
		{
			$as_cod_prov = "----------";
		}
		
		if((trim($as_ced_bene)=="----------")&&($as_tipo=="B"))
		{
			$msg->message("Debe registrar el codigo del beneficiario.");
			return false;	
		}
		
		if((trim($as_ced_bene)=="")&&($as_tipo=="B"))
		{
			$msg->message("Debe registrar el codigo del beneficiario.");
			return false;	
		}
		
		if((trim($as_ced_bene)!="----------")&&($as_tipo=="P"))
		{
			$as_ced_bene="----------";
		}
		
		if($as_tipo=="-")
		{
			$as_ced_bene="----------";
			$as_cod_prov="----------";
		}
		
		$is_cod_prov=$as_cod_prov;
		$is_ced_ben=$as_ced_bene;
		return true;

	}
		$prov_sel="";
		$bene_sel="";
		$ning_sel="selected";
		function uf_guardar_movimientos($is_codemp,$is_procede,$is_comprobante,$id_fecha,$ii_tipo_comp,$is_descripcion,$ds_mov,$is_cod_prov,$is_ced_bene)
	{
		global $int_scg;
		global $msg;
		global $ls_codban;
		global $ls_ctaban;
		global $funciones_scg;
		global $la_seguridad;
		global $fun;
		
		$ls_cuenta="";$ls_procede_doc="";$ls_documento="";$ls_debhab="";$ls_descripcion="";$ls_fecnew="";
		$lb_valido=true;
		$ldec_monto_anterior=0;$ldec_monto_actual=0;
		$li_dia=0;$li_mes=0;$li_agno=0;
		$la_emp=$_SESSION["la_empresa"];
		$is_codemp  = $la_emp["codemp"];
		
		$li_numrows=$ds_mov->getRowCount("sc_cuenta");
					
		for($li_i=1;$li_i<=$li_numrows;$li_i++)
		{		
			
			$is_tipo="-";
			
			$ii_tipo_comp = 1;
				
			$ld_debaux=0;
			$ld_habaux=0;
			$ld_mondeb=0;
			$ld_monhab=0;
			
			$ls_cuenta      = $ds_mov->getValue("sc_cuenta",$li_i);
			$ls_procede_doc = $ds_mov->getValue("procede_doc",$li_i);
			$ls_denominacion = $ds_mov->getValue("denominacion",$li_i);
			$ls_documento   = $ds_mov->getValue("documento",$li_i);
			$ls_debhab      = $ds_mov->getValue("debhab",$li_i);
			$ldec_monto_actual=$ds_mov->getValue("monto",$li_i);
			if(!$int_scg->uf_valida_procedencia( $ls_procede_doc,&$ls_desproc ))
			{
				$msg->message("Procedencia ".$ls_procede_doc." es invalida");
				return false;	
			}
			
			$lb_valido=$int_scg->uf_select_comprobante($is_codemp,$is_procede,$is_comprobante,$id_fecha,$ls_codban,$ls_ctaban);
			if($lb_valido)
			{
				$ld_fecha=$fun->uf_convertirdatetobd($id_fecha);										
				$lb_valido=$int_scg->uf_scg_procesar_update_movimiento($is_codemp,$is_procede, $is_comprobante, $ld_fecha,
									  $is_tipo,$is_cod_prov, $is_ced_bene, $ls_cuenta,
									  $ls_procede_doc, $ls_documento,$ls_debhab,$ls_denominacion,
									  $ldec_monto_anterior, $ldec_monto_actual,$ls_codban,$ls_ctaban );
				
				$lb_valido=$int_scg->uf_sql_transaction( $lb_valido );
	
				if(!$lb_valido)
				{
					$msg->message("Error al registrar movimiento contable. ".$int_scg->is_msg_error);
				}
			}
		}	
		return $lb_valido;
	}	
	
	if($int_scg->lds_cmp_cierre->getRowCount("debhab")>0)
	{
		$int_scg->lds_cmp_cierre->sortData("debhab");
	}
?>
<form name="form1" method="post" action=""><div >
  <p>&nbsp;</p>
  <table width="738" height="532" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-nuevo">
        <td height="20" colspan="3"><input name="operacion" type="hidden" id="operacion">
        Datos del Comprobante </td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="138" height="22">
        <p align="right"> Procedencia</p></td>
        <td width="430">
          <input name="txtproccomp" type="text" id="txtproccomp" value="SCGCIE" readonly="true" style="text-align:center" >
          <label>&nbsp;&nbsp; Cierre del Ejercicio</label></td>
        <td width="168">Fecha
            <input name="txtfecha" type="text" id="txtfecha" size="18" value="<?php print $ls_fecha?>" onBlur="valFecha(document.form1.txtfecha)" style="text-align:center" readonly>        </td>
      </tr>
      <tr>
        <td height="22">
        <p align="right">Comprobante </p></td>
        <td><input name="txtcomprobante" type="text" id="txtcomprobante" maxlength="15" style="text-align:center" value="<?php print $ls_comprobante ?>"></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22">
        <p align="right">Descripci&oacute;n </p></td>
        <td colspan="2"><input name="txtdesccomp" type="text" id="txtdesccomp" size="111" value="<?php print $ls_descripcion?>"></td>
      </tr>
      <tr >
        <td height="15" colspan="3">&nbsp;</td>
      </tr>
      <tr class="titulo-nuevo">
        <td height="20" colspan="3" class="titulo">Detalles contables </td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td height="17">&nbsp;</td>
        <td height="17">&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Codigo Contable</div></td>
        <td>
          <input name="txtcuenta" type="text" id="txtcuenta" value="<?php print $ls_cuenta?>" 	<?php print $readonly?> style="text-align:center" >
        <a href="javascript:cat()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a> <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" size="40"></td>
        <td><input name="fila" type="hidden" id="fila" value="<?php print $li_fila?>"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td colspan="2"><input name="txtdescdoc" type="text" id="txtdescdoc" size="111" value="<?php print $ls_denominacion?>"></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Procede Documento</div></td>
        <td>
          <input name="txtprocdoc" type="text" id="txtprocdoc" value="<?php print $ls_procdoc?>" maxlength="15" style="text-align:center">        </td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">N&ordm; Documento </div></td>
        <td><input name="txtdocumento" type="text" id="txtdocumento" value="<?php print $ls_documento?>" onBlur="javascript: rellenar_cad(document.form1.txtdocumento.value,15,'doc');" style="text-align:center"></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Operaci&oacute;n</div></td>
        <td>
          <p>
            <?php
		  if(($ls_debhab=="D")||($ls_debhab==""))
		  {
		  	$deb="selected";
			$hab="";
		  }
		  else
		  {
		  	$deb="";
			$hab="selected";
		  }

		  ?>
            <select name="debhab" id="debhab">
              <option value="D" <?php  print $deb ?> >Debe</option>
              <option value="H" <?php  print $hab ?>>Haber</option>
            </select>
        </p></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22"><div align="right">Monto</div></td>
        <td>
          <input name="txtmonto" type="text" id="txtmonto" value="<?php print $ldec_monto?>" style="text-align:right">
          <a href="javascript: uf_save_mov();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar" width="15" height="15" border="0"></a><a href="javascript: uf_save_mov();">Agregar Detalle</a> <a href="javascript: uf_del_mov(document.form1.txtcuenta.value,document.form1.txtdescdoc.value,document.form1.txtprocdoc.value,document.form1.txtdocumento.value,document.form1.debhab.value,document.form1.txtmonto.value);"><img src="../shared/imagebank/tools15/eliminar.gif" alt="Eliminar" width="15" height="15" border="0"></a><a href="javascript: uf_del_mov(document.form1.txtcuenta.value,document.form1.txtdescdoc.value,document.form1.txtprocdoc.value,document.form1.txtdocumento.value,document.form1.debhab.value,document.form1.txtmonto.value);">Eliminar Detalle</a> </td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="115" colspan="3" valign="top" bordercolor="#FFFFFF"><p>&nbsp;</p>
            <table width="690" border="0" align="center" cellpadding="1" cellspacing="1" class="fondo-tabla">
              <tr bgcolor="#D5D5D5" class="titulo-celdanew">
                <td align="center" width="87">Cuenta</td>
                <td align="center" width="268">Descripci&oacute;n del Movimiento</td>
                <td align="center" width="45">Procede</td>
                <td align="center" width="89">Documento</td>
                <td align="center" width="54">Operaci&oacute;n</td>
                <td align="center" width="133">Monto</td>
              </tr>
              <?php
			$totrow=$int_scg->lds_cmp_cierre->getRowCount("sc_cuenta");

			for($i=1;$i<=$totrow;$i++)
			{
				$ls_debhab=$int_scg->lds_cmp_cierre->getValue("debhab",$i);
				$ls_denominacion=$int_scg->lds_cmp_cierre->getValue("denominacion",$i);
				$ls_cuenta=$int_scg->lds_cmp_cierre->getValue("sc_cuenta",$i);
				$ls_procdoc=$int_scg->lds_cmp_cierre->getValue("procede_doc",$i);
				$ls_documento=$int_scg->lds_cmp_cierre->getValue("documento",$i);
				$ldec_monto=$int_scg->lds_cmp_cierre->getValue("monto",$i);
				if($ls_debhab=="D")
				{
				?>
				  <tr class="celdas-blancas">
				<?php
				}
				else
				{
				?>
				  <tr class="celdas-azules">
				<?php
				}
			?>
                <td align="center"><?php print "<a href=\"javascript: editar('$i','$ls_cuenta','$ls_denominacion','$ls_procdoc','$ls_documento','$ls_debhab','$ldec_monto');\">".$ls_cuenta."</a>"?></td>
                <td><?php print $ls_denominacion ?></td>
                <td align="center"><?php print $ls_procdoc ?></td>
                <td align="center"><?php print $ls_documento ?></td>
                <td align="center"><?php print $ls_debhab ?></td>
                <td align="right"><?php print number_format($ldec_monto,2,",",".") ?> </td>
              </tr>
              <?php	
		}
		
        function uf_calcular_diferencia($lds_cmp_cierre,$ldec_mondeb,$ldec_monhab)
		{
			$ldec_mondeb=0;
			$ldec_monhab=0;
			$totrow=$lds_cmp_cierre->getRowCount("sc_cuenta");
			$ldec_dif=0;
			for($i=1;$i<=$totrow;$i++)
			{
				$ls_debhab=$lds_cmp_cierre->getValue("debhab",$i);
				$ldec_monto=$lds_cmp_cierre->getValue("monto",$i);
				if($ls_debhab=="D")
				{
					$ldec_mondeb=$ldec_mondeb + $ldec_monto;					
				}
				else
				{
					$ldec_monhab=$ldec_monhab + $ldec_monto;
				}
			}
			$ldec_dif=$ldec_mondeb-$ldec_monhab;
			$ldec_mondeb= " ".number_format($ldec_mondeb,2,",",".");
			$ldec_monhab= " ".number_format($ldec_monhab,2,",",".");		
			return " ".number_format($ldec_dif,2,",",".");
		}		
		$ldec_diferencia=uf_calcular_diferencia($int_scg->lds_cmp_cierre,&$ldec_mondeb,&$ldec_monhab);
	?>
        </table></td>
      </tr>
	  
      <tr>
        <td height="48" colspan="3" valign="top" bordercolor="#FFFFFF"><table width="735" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="23">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td  align="right">Debe</td>
            <td  align="left">
                <input name="txtdebe" type="text" id="txtdebe" style="text-align:right" value="<?php print $ldec_mondeb;?>" size="28" readonly>            </td>
          </tr>
          <tr>
            <td height="21">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td  align="right">Haber</td>
            <td  align="left">
                <input name="txthaber" type="text" id="txthaber" style="text-align:right" value="<?php print $ldec_monhab;?>" size="28" readonly>            </td>
          </tr>
            <tr>
              <td width="79" height="18">&nbsp;</td>
              <td width="101">&nbsp;</td>
              <td width="91">&nbsp;</td>
              <td width="209" align="right"></td>
              <td width="83" align="right">Diferencia</td>
              <td width="172" align="left">                 
              <input name="txtdiferencia" type="text" id="txtdiferencia" style="text-align:right" value="<?php print $ldec_diferencia;?>" size="28" readonly>              </td>
            </tr>
        </table></td>
      </tr>
    </table>
  <?php
	$_SESSION["objact"]=$int_scg->lds_cmp_cierre->data;
	?>
    <p>&nbsp;</p>
</div>
</form>
</body>
<script language="javascript">
f=document.form1;
function cat()
{	
	f.txtcuenta.disabled=false;
	window.open("sigesp_cat_scg.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
}

function editar(fila,cuenta , deno , procede,documento,debhab,monto)
{
	f.fila.value=fila;
	f.txtcuenta.disabled=false;
	f.txtcuenta.value=cuenta;
	f.txtdescdoc.value=deno;
	f.txtprocdoc.value=procede;
	f.txtdocumento.value=documento;
	f.debhab.value=debhab;
	f.txtmonto.value=monto;
	f.operacion.value ="EDITAR";
	f.action="sigespwindow_scg_cmp_cierre.php";
	f.txtcuenta.focus(true	);
	f.submit();

}

function uf_save_mov()
{
	f.operacion.value="AGREGAR";
	f.action="sigespwindow_scg_cmp_cierre.php";
	f.submit();
}

function uf_del_mov(cuenta,desc,proc,doc,debhab,monto)
{
	f.txtcuenta.value=cuenta;
	f.txtdescdoc.value=desc;
	f.txtprocdoc.value=proc;
	f.txtdocumento.value=doc;
	f.debhab.value=debhab;
	f.txtmonto.value=monto;
	f.operacion.value="DELMOV";
	f.action="sigespwindow_scg_cmp_cierre.php";
	f.submit();
}

function uf_upd_mov(fila,cuenta,desc,proc,doc,debhab,monto)
{
	f.fila.value=fila;
	f.txtcuenta.value=cuenta;
	f.txtdescdoc.value=desc;
	f.txtprocdoc.value=proc;
	f.txtdocumento.value=doc;
	f.debhab.value=debhab;
	f.txtmonto.value=monto;
	f.operacion.value="UPDMOV";
	f.action="sigespwindow_scg_cmp_cierre.php";
	f.submit();
}

function ue_ejecutar()
{
	if(confirm("Esta seguro de realizar el Cierre del Ejercicio"))
	{
		f.operacion.value="EJECUTAR";
		f.action="sigespwindow_scg_cmp_cierre.php";	
		f.submit();
	}
}

function ue_guardar()
{
	f.operacion.value="GUARDAR";
	f.action="sigespwindow_scg_cmp_cierre.php";
	f.submit();
}

function ue_eliminar()
{
  ls_numcmpscg = f.txtcomprobante.value;
  if (ls_numcmpscg!='')
     {
	   f.operacion.value="ELIMINAR";
	   f.action="sigespwindow_scg_cmp_cierre.php";
	   f.submit();	 
	 }
  else
     {
	   alert("Debe seleccionar un Comprobante para su Eliminación !!!");
	 }
}

function valid_cmp(cmp)
{
  rellenar_cad(cmp,15,"cmp");
  f.operacion.value="VALIDAFECHA";
  f.action="sigespwindow_scg_comprobante.php";
  f.submit();
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

  function valSep(oTxt){ 
    var bOk = false; 
    var sep1 = oTxt.value.charAt(2); 
    var sep2 = oTxt.value.charAt(5); 
    bOk = bOk || ((sep1 == "-") && (sep2 == "-")); 
    bOk = bOk || ((sep1 == "/") && (sep2 == "/")); 
    return bOk; 
   } 

   function finMes(oTxt){ 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    var nAno = parseInt(oTxt.value.substr(6), 10); 
    var nRes = 0; 
    switch (nMes){ 
     case 1: nRes = 31; break; 
     case 2: nRes = 28; break; 
     case 3: nRes = 31; break; 
     case 4: nRes = 30; break; 
     case 5: nRes = 31; break; 
     case 6: nRes = 30; break; 
     case 7: nRes = 31; break; 
     case 8: nRes = 31; break; 
     case 9: nRes = 30; break; 
     case 10: nRes = 31; break; 
     case 11: nRes = 30; break; 
     case 12: nRes = 31; break; 
    } 
    return nRes + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
   } 

   function valDia(oTxt){ 
    var bOk = false; 
    var nDia = parseInt(oTxt.value.substr(0, 2), 10); 
    bOk = bOk || ((nDia >= 1) && (nDia <= finMes(oTxt))); 
    return bOk; 
   } 

   function valMes(oTxt){ 
    var bOk = false; 
    var nMes = parseInt(oTxt.value.substr(3, 2), 10); 
    bOk = bOk || ((nMes >= 1) && (nMes <= 12)); 
    return bOk; 
   } 

   function valAno(oTxt){ 
    var bOk = true; 
    var nAno = oTxt.value.substr(6); 
    bOk = bOk && ((nAno.length == 2) || (nAno.length == 4)); 
    if (bOk){ 
     for (var i = 0; i < nAno.length; i++){ 
      bOk = bOk && esDigito(nAno.charAt(i)); 
     } 
    } 
    return bOk; 
   } 

   function valFecha(oTxt){ 
    var bOk = true; 
	
		if (oTxt.value != ""){ 
		 bOk = bOk && (valAno(oTxt)); 
		 bOk = bOk && (valMes(oTxt)); 
		 bOk = bOk && (valDia(oTxt)); 
		 bOk = bOk && (valSep(oTxt)); 
		 if (!bOk){ 
		  alert("Fecha inválida ,verifique el formato(Ejemplo: 10/10/2005) \n o introduzca una fecha correcta."); 
		  oTxt.value = "01/01/1900"; 
		  oTxt.focus(); 
		 } 
		}
	 
   }

  function esDigito(sChr){ 
    var sCod = sChr.charCodeAt(0); 
    return ((sCod > 47) && (sCod < 58)); 
   }

</script> 
</html>