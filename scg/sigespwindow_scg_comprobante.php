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
	$ls_ventanas="sigespwindow_scg_comprobante.php";

	$la_seguridad["empresa"]=$ls_empresa;
	$la_seguridad["logusr"]=$ls_logusr;
	$la_seguridad["sistema"]=$ls_sistema;
	$la_seguridad["ventanas"]=$ls_ventanas;
	$ls_codban="---";
	$ls_ctaban="-------------------------";
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
$li_ano=substr($_SESSION["la_empresa"]["periodo"],0,4);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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
</script>
<title>Comprobante Contable.</title>
<meta http-equiv="imagetoolbar" content="no"> 
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #f3f3f3;
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
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scg.js"></script>
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo25 {color: #6699CC}
-->
</style>
</head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/number_format.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/validaciones.js"></script>
<body onUnload="javascript:uf_valida_cuadre();">
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
	function uf_valida_cuadre()
	{
		f=document.form1;
		ldec_diferencia=f.txtdiferencia.value;
		ls_operacion=f.operacion.value;
		ldec_diferencia=uf_convertir_monto(ldec_diferencia);
		if((ls_operacion=="NUEVO")||(ls_operacion=="GUARDAR")||(ls_operacion==""))
		{
			if(ldec_diferencia!=0)
			{
				alert("Comprobante descuadrado");
				f.operacion.value="CARGAR";
				f.action="sigespwindow_scg_comprobante.php";
				f.submit();
			}
		}
	}


</script>
 <table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="780" height="40"></td>
  </tr>
  <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo25">Sistema de Contabilidad Patrimonial</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
    </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="20" colspan="11" align="center" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td width="25" height="20" align="center" class="toolbar"><a href="javascript: ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" alt="Nuevo" title="Nuevo" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_guardar();"><img src="../shared/imagebank/tools20/grabar.gif" alt="Guardar" title="Guardar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" alt="Buscar" title="Buscar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" alt="Eliminar" title="Eliminar" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a></td>
    <td width="25" class="toolbar" align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></td>
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
	$ls_provbene  = $_POST["txtprovbene"];
	$ls_nomproben = $_POST["txtnomproben"];
	$ls_tipo      = $_POST["tipo"];
	$ls_descripcion = $_POST["txtdesccomp"];
	$ls_procede	  = $_POST["txtproccomp"];
	$ls_tipo      = $_POST["tipo"];
	$li_fila	 = 0;
}
else
{
	$ls_operacion="";
	require_once("../shared/class_folder/class_funciones.php");
	$io_funcion=new class_funciones();
	$io_funcion->uf_limpiar_sesion();
	$_SESSION["ACTUALIZAR"]="NO";
	$_SESSION["ib_new"]	=true;
	$array_fecha=getdate();
	$ls_dia=$array_fecha["mday"];
	$ls_mes=$array_fecha["mon"];
	$ls_ano=$array_fecha["year"];
	$ls_fecha=$fun->uf_cerosizquierda($ls_dia,2)."/".$fun->uf_cerosizquierda($ls_mes,2)."/".$ls_ano;
	$li_fila		 = 0;
	$ls_tipo      = "-";
	$ls_provbene  = "----------";
	$ls_nomproben = "";

}

///// PARA VERIFICAR LA CONFIGURACIÓN DE LA NUMERACIÓN DE LOS COMPROBANTES CONTABLES ///// 
global $ls_config,$ls_estcompscg,$la_mesact;
$ls_logusr   = $_SESSION["la_logusr"];
$ls_estcompscg="";
$lb_config=$int_scg->uf_scg_verificar_configuracion($la_emp["codemp"],$ls_logusr,$ls_estcompscg);
$la_mesact[0]='';
$la_mesact[1]='';
$la_mesact[2]='';
$la_mesact[3]='';
$la_mesact[4]='';
$la_mesact[5]='';
$la_mesact[6]='';
$la_mesact[7]='';
$la_mesact[8]='';
$la_mesact[9]='';
$la_mesact[10]='';
$la_mesact[11]='';


			  
////////////////////////////////////////////////////////////////////////////////////////////////

//Incluyo la clase datastore
require_once("../shared/class_folder/class_datastore.php");
//Instancio la clase datastore
$ds_mov=new class_datastore();
$ds_mov->data=array();
if($ls_operacion=="GUARDAR")
{
 	$lb_valido=true;
	$ls_codemp="";
	$ls_procede="";
	$ls_mesact="";
	$ls_messel="";
	$ls_anoact="";
	$ls_anosel="";
	$ls_anosel="";
	if ($lb_config)
	{
		if ($ls_estcompscg==1)
		{
			$ld_fecact=$_POST["txtfecha"];			
			$ls_mesact=substr($ld_fecact,3,2);
			$ls_anoact=substr($ld_fecact,6,4);
			$ls_messel=$_POST["cmbmes"];
			$ls_anosel=$_POST["txtperiodo"];			
			$ls_comprobante = $int_scg->io_keygen_mensual->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP",$ls_messel,$ls_anosel);
			unset($io_keygen_mensual);
		}
		else
		{
			$ls_comprobante = $int_scg->io_keygen->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP");
			unset($io_keygen);	
		}
		$readonly_comp='readonly';
	}
	else
	{
		$ls_comprobante = "";
		$readonly_comp="";
	}
	$ls_cod_prov="";
	$ls_ced_ben="";
	$ls_descripcion="";
	$ls_tipo="";
	$li_tipo_comp=0;
	$li_row=0;
	$ls_fecha="";
	global $ib_new;
		$ds_mov->data    = $_SESSION["objact"];
		$ls_codemp  = $la_emp["codemp"];
		$ls_procede = $_POST["txtproccomp"];
		$ls_comprobante = $_POST["txtcomprobante"];
		$ls_fecha     = $_POST["txtfecha"];
		if(array_key_exists("cmbmes",$_POST))
		{	
			$ls_mes=$_POST["cmbmes"];
		}
		else
		{
			$ls_mes="";
		}
		switch ($ls_mes)
		{
		  case '01': $la_mesact[0]='selected';
		  break; 
		  case '02': $la_mesact[1]='selected';
		  break;
		  case '03': $la_mesact[2]='selected';
		  break;
		  case '04': $la_mesact[3]='selected';
		  break;
		  case '05': $la_mesact[4]='selected';
		  break;
		  case '06': $la_mesact[5]='selected';
		  break;
		  case '07': $la_mesact[6]='selected';
		  break;	
		  case '08': $la_mesact[7]='selected';
		  break; 
		  case '09': $la_mesact[8]='selected';
		  break;
		  case '10': $la_mesact[9]='selected';
		  break;
		  case '11': $la_mesact[10]='selected';
		  break;
		  case '12': $la_mesact[11]='selected';
		  break;	
		}	

		$ls_cod_prov = $_POST["txtprovbene"];
		$ls_ced_ben  = $_POST["txtprovbene"];
		$ls_provbene  = $_POST["txtprovbene"];
		$ls_procede	  = $_POST["txtproccomp"];
		$ls_descripcion = $_POST["txtdesccomp"];
		$ldec_mondeb=$_POST["txtdebe"];
		$ldec_monhab=$_POST["txthaber"];
		$ldec_diferencia=$_POST["txtdiferencia"];
		$is_tipo  =		$_POST["tipo"];
		$ls_tipo      = $_POST["tipo"];
		
		
		$ii_tipo_comp = 1;
		if($ls_tipo=="P")
		{
			$ls_cod_prov = $_POST["txtprovbene"];
			$ls_ced_ben  = "----------";
		}
		elseif($ls_tipo=="B")
		{
			$ls_ced_ben = $_POST["txtprovbene"];
			$ls_cod_prov  = "----------";
		}
		else
		{
			$ls_ced_ben = "----------";
			$ls_cod_prov= "----------";
		}
		
		if($ldec_diferencia==0)//Valido que el comprobante este cuadrado
		{
			if(!uf_valida_datos_cabezera($ls_comprobante,$ls_tipo,$ls_cod_prov,$ls_ced_ben,$ls_procede,$ls_fecha,$ls_mesact,$ls_messel,$ls_anoact,$ls_anosel))
			{
				$ib_valido = false;
				
			}
			else
			{	$ib_new=$_SESSION["ib_new"];
				if (($ib_new)&&(!$lb_config))
				{
					if($int_scg->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ls_codban,$ls_ctaban,$ls_tipo,$ls_ced_ben,$ls_cod_prov))
					{
						$msg->message("El comprobante que usted ha generado ya existe,favor registre un nuevo ID o número de comprobante"); 
					}
				}		
				if($ib_new)
				{
					  if ($lb_config)
					  {
					  		$lb_valido = $int_scg->uf_sigesp_insert_comprobante_generado($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ii_tipo_comp,$ls_descripcion,$is_tipo,$ls_cod_prov,$ls_ced_ben,$ls_codban,$ls_ctaban,$ls_estcompscg,$ls_messel,$ls_anosel);
					  }
					  else
					  {
					  		$lb_valido = $int_scg->uf_sigesp_insert_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ii_tipo_comp,$ls_descripcion,$is_tipo,$ls_cod_prov,$ls_ced_ben,$ls_codban,$ls_ctaban);
					  }
					  
					   if($lb_valido) 
					   {
						   $lb_valido =	uf_guardar_movimientos($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ii_tipo_comp,$ls_descripcion,$ds_mov,$ls_cod_prov,$ls_ced_ben);
						   if($lb_valido)
						   {
								$msg->message("El comprobante contable fue registrado."); 
								$ds_mov->data    = $_SESSION["objact"];
								$ds_mov->resetds("SC_cuenta");
								if ($lb_config)
								{
									if ($ls_estcompscg==1)
									{
										$ls_mesact==$_POST["cmbmes"];
										$ls_anosel=$_POST["txtperiodo"];
										
										switch ($ls_mesact)
										{
										  case '01': $la_mesact[0]='selected';
										  break; 
										  case '02': $la_mesact[1]='selected';
										  break;
										  case '03': $la_mesact[2]='selected';
										  break;
										  case '04': $la_mesact[3]='selected';
										  break;
										  case '05': $la_mesact[4]='selected';
										  break;
										  case '06': $la_mesact[5]='selected';
										  break;
										  case '07': $la_mesact[6]='selected';
										  break;	
										  case '08': $la_mesact[7]='selected';
										  break; 
										  case '09': $la_mesact[8]='selected';
										  break;
										  case '10': $la_mesact[9]='selected';
										  break;
										  case '11': $la_mesact[10]='selected';
										  break;
										  case '12': $la_mesact[11]='selected';
										  break;	
										}									
										
										$ls_comprobante = $int_scg->io_keygen_mensual->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP",$ls_messel,$ls_anosel);
										unset($io_keygen_mensual);
														
									}
									else
									{
										$ls_comprobante = $int_scg->io_keygen->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP");
										unset($io_keygen);	
									}
									$readonly_comp='readonly';
								}
								else
								{
									$ls_comprobante = "";
									$readonly_comp="";
								}
								$ls_procede	  = "SCGCMP";//Procedencia comprobante
								$ls_fecha     = "";
								$ls_tipo      = "-";
								$ls_provbene  = "----------";
								$ls_nomproben = "";
								$ls_descripcion = "";
								$ls_cuenta="";
								$ls_denominacion="";
								$ls_procdoc="";//Procedencia documento detalle
								$ls_documento="";
								$ls_debhab="";
								$ls_fecha=date("d/m/Y");
								$ls_mes=substr($ls_fecha,3,2);	
								switch ($ls_mes)
								{
								  case '01': $la_mesact[0]='selected';
								  break; 
								  case '02': $la_mesact[1]='selected';
								  break;
								  case '03': $la_mesact[2]='selected';
								  break;
								  case '04': $la_mesact[3]='selected';
								  break;
								  case '05': $la_mesact[4]='selected';
								  break;
								  case '06': $la_mesact[5]='selected';
								  break;
								  case '07': $la_mesact[6]='selected';
								  break;	
								  case '08': $la_mesact[7]='selected';
								  break; 
								  case '09': $la_mesact[8]='selected';
								  break;
								  case '10': $la_mesact[9]='selected';
								  break;
								  case '11': $la_mesact[10]='selected';
								  break;
								  case '12': $la_mesact[11]='selected';
								  break;	
								}	
								
								$ldec_monto="";
								$readonly="";								
								$_SESSION["ib_new"]	=true;
								$_SESSION["ACTUALIZAR"]="NO";
								$li_fila  = 0;	
								
						   }
						   else
						   {
								$int_scg->uf_sql_transaction($lb_valido);
						   }
					   }
					   else		
					   {
							$msg->message("Error al procesar el comprobante contable"); 
						
					   }
					} 
					else
					{
						 $lb_valido = $int_scg->uf_sigesp_update_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ii_tipo_comp,$ls_descripcion,$is_tipo,$ls_cod_prov,$ls_ced_ben,$ls_codban,$ls_ctaban);
						 if($lb_valido)
						 {
							$msg->message("Registro Actualizado"); 
							  //////////////////////////////////         SEGURIDAD               /////////////////////////////		
								$ls_evento="UPDATE";
								$ls_descripcion_evento =" Inserta el comprobante contable,Asociado a la Empresa:".$ls_codemp."  Procede:".$ls_procede."  Comprobante:".$ls_comprobante." y la Fecha:".$ls_fecha."  Descripcion:".$ls_descripcion;
								$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
																$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
																$la_seguridad["ventanas"],$ls_descripcion_evento);
							 /////////////////////////////////         SEGURIDAD               /////////////////////////////	
							$ls_operacion="NUEVO";
						 }
						
					}
				
				
			}
		}
		else
		{
			$msg->message("Monto descuadrado, no se puede procesar el comprobante");
		}
			$ib_valido = $lb_valido;
			$readonly="";	
			if ($lb_config)
			{
				$readonly_comp='readonly';
			}
			else
			{
				$readonly_comp="";
			}		
			$ls_cuenta="";
			$ls_denominacion="";
			$ls_procdoc="";
			$ls_documento="";
			$ls_debhab="";
			$ldec_monto="";
			//$li_fila= 0;
		
}
elseif($ls_operacion=="ELIMINAR")
{
	$ds_mov->data=$_SESSION["objact"];
	$ls_comprobante = $_POST["txtcomprobante"];
	$ls_procede	  = $_POST["txtproccomp"];
	$ls_fecha     = $_POST["txtfecha"];

			$ls_codemp    = $la_emp["codemp"];
			$li_total=$ds_mov->getRowCount("SC_cuenta");
			$lb_valido=true;
			for($li_row=1;$li_row<=$li_total;$li_row++)
			{
				$ls_documento	= $ds_mov->getValue("Documento",$li_row);			
				$ls_procede_doc	= $ds_mov->getValue("Procede_doc",$li_row);			
				$ls_cuenta		= $ds_mov->getValue("SC_cuenta",$li_row);				
				$ls_operacion   = $ds_mov->getValue("DebHab",$li_row);			
				$ldec_monto	=$ds_mov->getValue("Monto",$li_row);					
			//Función que elimina los detalles del comprobante y actualiza los saldos			
			$lb_valido=$int_scg->uf_scg_procesar_delete_movimiento($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ls_cuenta,$ls_procede_doc,$ls_documento,$ls_operacion,$ldec_monto,$ls_codban,$ls_ctaban);
			}
			
			if($lb_valido)
			{		
				//Funcion que elimina los datos de la cabezera del comprobante
				$int_scg->is_codemp=$ls_codemp;
				$int_scg->is_procedencia=$ls_procede;
				$int_scg->is_comprobante=$ls_comprobante;
				$int_scg->id_fecha=$ls_fecha;
				$int_scg->as_codban=$ls_codban;
				$int_scg->as_ctaban=$ls_ctaban;				
			    $lb_valido=$int_scg->uf_sigesp_delete_comprobante();
			
				if($lb_valido)
				{
					$msg->message("Comprobante eliminado satisfactoriamente ");
					  //////////////////////////////////         SEGURIDAD               /////////////////////////////		
						$ls_evento="DELETE";
						$ls_descripcion_evento =" Elimino el comprobante contable,Asociado a la Empresa:".$ls_codemp."  Procede:".$ls_procede."  Comprobante:".$ls_comprobante." y la Fecha:".$ls_fecha;
						$ls_variable= $io_seguridad->uf_sss_insert_eventos_ventana($la_seguridad["empresa"],
														$la_seguridad["sistema"],$ls_evento,$la_seguridad["logusr"],
														$la_seguridad["ventanas"],$ls_descripcion_evento);
					 /////////////////////////////////         SEGURIDAD               /////////////////////////////	
					$int_scg->io_sql->commit();//Realizo el commit o el rollback dependiendo $lb_valido
					$_SESSION["ib_new"]	=true;
					$_SESSION["ACTUALIZAR"]="NO";
					$ls_operacion="NUEVO";
				}
				else
				{
					$msg->message("No se elimino el comprobante ");
					$int_scg->io_sql->rollback();
				}
	
				$ds_mov->resetds("SC_cuenta");
				if ($lb_config)
				{
					if ($ls_estcompscg==1)
					{
						
						$ld_fecact=date("d/m/Y");			
						$ls_mesact=substr($ld_fecact,3,2);
						$ls_anoact=substr($ld_fecact,6,4);
						switch ($ls_mesact)
						{
						  case '01': $la_mesact[0]='selected';
						  break; 
						  case '02': $la_mesact[1]='selected';
						  break;
						  case '03': $la_mesact[2]='selected';
						  break;
						  case '04': $la_mesact[3]='selected';
						  break;
						  case '05': $la_mesact[4]='selected';
						  break;
						  case '06': $la_mesact[5]='selected';
						  break;
						  case '07': $la_mesact[6]='selected';
						  break;	
						  case '08': $la_mesact[7]='selected';
						  break; 
						  case '09': $la_mesact[8]='selected';
						  break;
						  case '10': $la_mesact[9]='selected';
						  break;
						  case '11': $la_mesact[10]='selected';
						  break;
						  case '12': $la_mesact[11]='selected';
						  break;	
						}		
						
						$ls_comprobante = $int_scg->io_keygen_mensual->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP",$ls_mesact,$ls_anoact);
						unset($io_keygen_mensual);
										
					}
					else
					{
						$ls_comprobante = $int_scg->io_keygen->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP");
						unset($io_keygen);	
					}
					$readonly_comp='readonly';
				}
				else
				{
					$ls_comprobante = "";
					$readonly_comp="";
				}
				$ls_fecha     = date("d/m/Y");
				$ls_provbene  = "";
				$ls_nomproben = "";
				$ls_descripcion = "";
				$ls_procede	  = "SCGCMP";
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
				$ds_mov->resetds("SC_cuenta");
				if ($lb_config)
				{
					if ($ls_estcompscg==1)
					{
						$ld_fecact=date("d/m/Y");			
						$ls_mesact=substr($ld_fecact,3,2);
						$ls_anoact=substr($ld_fecact,6,4);
						switch ($ls_mesact)
						{
						  case '01': $la_mesact[0]='selected';
						  break; 
						  case '02': $la_mesact[1]='selected';
						  break;
						  case '03': $la_mesact[2]='selected';
						  break;
						  case '04': $la_mesact[3]='selected';
						  break;
						  case '05': $la_mesact[4]='selected';
						  break;
						  case '06': $la_mesact[5]='selected';
						  break;
						  case '07': $la_mesact[6]='selected';
						  break;	
						  case '08': $la_mesact[7]='selected';
						  break; 
						  case '09': $la_mesact[8]='selected';
						  break;
						  case '10': $la_mesact[9]='selected';
						  break;
						  case '11': $la_mesact[10]='selected';
						  break;
						  case '12': $la_mesact[11]='selected';
						  break;	
						}									
						
						$ls_comprobante = $int_scg->io_keygen_mensual->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP",$ls_mesact,$ls_anoact);
						unset($io_keygen_mensual);
										
					}
					else
					{
						$ls_comprobante = $int_scg->io_keygen->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP");
						unset($io_keygen);	
					}
					$readonly_comp='readonly';
				}
				else
				{
					$ls_comprobante = "";
					$readonly_comp="";
				}
				$ls_fecha     = date("d/m/Y");
				$ls_provbene  = "";
				$ls_nomproben = "";
				$ls_descripcion = "";
				$ls_procede	  = "SCGCMP";
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
	$ds_mov->data    = $_SESSION["objact"];
	$readonly        = "";
	$ls_cuenta       = $_POST["txtcuenta"];
	$ls_denominacion = $_POST["txtdescdoc"];
	$ls_documento    = $_POST["txtdocumento"];
	$ls_debhab       = $_POST["debhab"];
	$ldec_monto      = $_POST["txtmonto"];
	$ldec_monto      = str_replace(".","",$ldec_monto);
	$ldec_monto      = str_replace(",",".",$ldec_monto);
	$ls_comprobante  = $_POST["txtcomprobante"];
	$ls_procede	     = $_POST["txtproccomp"];
	$ls_procdoc      = $ls_procede;
	$ls_fecha        = $_POST["txtfecha"];
	if(array_key_exists("cmbmes",$_POST))
	{	
		$ls_mes=$_POST["cmbmes"];
	}
	else
	{
		$ls_mes="";
	}	
	switch ($ls_mes)
	{
	  case '01': $la_mesact[0]='selected';
	  break; 
	  case '02': $la_mesact[1]='selected';
	  break;
	  case '03': $la_mesact[2]='selected';
	  break;
	  case '04': $la_mesact[3]='selected';
	  break;
	  case '05': $la_mesact[4]='selected';
	  break;
	  case '06': $la_mesact[5]='selected';
	  break;
	  case '07': $la_mesact[6]='selected';
	  break;	
	  case '08': $la_mesact[7]='selected';
	  break; 
	  case '09': $la_mesact[8]='selected';
	  break;
	  case '10': $la_mesact[9]='selected';
	  break;
	  case '11': $la_mesact[10]='selected';
	  break;
	  case '12': $la_mesact[11]='selected';
	  break;	
	}	
	$ls_provbene     = $_POST["txtprovbene"];
	$ls_tipo         = $_POST["tipo"];
	$ls_descripcion  = $_POST["txtdesccomp"];

		if(!$int_scg->uf_valida_procedencia( $ls_procdoc,&$ls_desproc ))	
		{	
			$msg->message("Procedencia ".$ls_procdoc." es invalida");
			//return false;	
		}
		else
		{
			if(($ls_cuenta!="")&&($ls_denominacion!="")&&($ls_procdoc!="")&&($ls_documento!="")&&($ls_debhab!=""))
			{
				$arr["SC_cuenta"]=$ls_cuenta;
				$arr["Procede_doc"]=$ls_procdoc;
				$arr["Documento"]=$ls_documento;
				$arr["DebHab"]=$ls_debhab;				
				
				$find=$ds_mov->findValues($arr,"SC_cuenta");				
				if(($find<0)&&($_SESSION["ACTUALIZAR"]=="NO"))
				{
					$ds_mov->insertRow("SC_cuenta",$ls_cuenta);
					$ds_mov->insertRow("Denominacion",$ls_denominacion);
					$ds_mov->insertRow("Procede_doc",$ls_procdoc);
					$ds_mov->insertRow("Documento",$ls_documento);
					$ds_mov->insertRow("DebHab",$ls_debhab);
					$ds_mov->insertRow("Monto",$ldec_monto);
				}
				elseif(($find<0)&&($_SESSION["ACTUALIZAR"]=="SI"))
				{
					
					$ls_codemp		 = $la_emp["codemp"];
					$ls_comprobante  = $_POST["txtcomprobante"];
					$ls_procede	     = $_POST["txtproccomp"];
					$ls_fecha        = $_POST["txtfecha"];
					if(array_key_exists("cmbmes",$_POST))
					{	
						$ls_mes=$_POST["cmbmes"];
					}
					else
					{
						$ls_mes="";
					}	
					switch ($ls_mes)
					{
					  case '01': $la_mesact[0]='selected';
					  break; 
					  case '02': $la_mesact[1]='selected';
					  break;
					  case '03': $la_mesact[2]='selected';
					  break;
					  case '04': $la_mesact[3]='selected';
					  break;
					  case '05': $la_mesact[4]='selected';
					  break;
					  case '06': $la_mesact[5]='selected';
					  break;
					  case '07': $la_mesact[6]='selected';
					  break;	
					  case '08': $la_mesact[7]='selected';
					  break; 
					  case '09': $la_mesact[8]='selected';
					  break;
					  case '10': $la_mesact[9]='selected';
					  break;
					  case '11': $la_mesact[10]='selected';
					  break;
					  case '12': $la_mesact[11]='selected';
					  break;	
					}	
					$ls_cod_prov	 = $_POST["txtprovbene"];
					$ls_ced_bene	 = $_POST["txtprovbene"];
					$ls_tipo=$_POST["tipo"];
					
			
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
						$ld_fecha=$fun->uf_convertirdatetobd($ls_fecha);										
						$lb_valido = $int_scg->uf_scg_procesar_insert_movimiento($ls_codemp,$ls_procede,$ls_comprobante,$ld_fecha,
															  $ls_tipo,$ls_cod_prov,$ls_ced_bene,$ls_cuenta,
															  $ls_procede_doc,$ls_documento,$ls_debhab,$ls_denominacion,
															  $ldec_monto_anterior,$ldec_monto_actual,$ls_codban,$ls_ctaban);
						$lb_valido=$int_scg->uf_sql_transaction( $lb_valido );
		
						if(!$lb_valido)
						{
							$msg->message("Error al registrar movimiento contable. ".$int_scg->is_msg_error);
						}
						$ds_mov->insertRow("SC_cuenta",$ls_cuenta);
						$ds_mov->insertRow("Denominacion",$ls_denominacion);
						$ds_mov->insertRow("Procede_doc",$ls_procdoc);
						$ds_mov->insertRow("Documento",$ls_documento);
						$ds_mov->insertRow("DebHab",$ls_debhab);
						$ds_mov->insertRow("Monto",$ldec_monto);
						
					}
					
				}
				elseif($find>0)
				{					
					$ls_codemp		 = $la_emp["codemp"];
					$ls_comprobante  = $_POST["txtcomprobante"];
					$ls_procede	     = $_POST["txtproccomp"];
					$ls_fecha        = $_POST["txtfecha"];
					if(array_key_exists("cmbmes",$_POST))
					{	
						$ls_mes=$_POST["cmbmes"];
					}
					else
					{
						$ls_mes="";
					}	
					switch ($ls_mes)
					{
					  case '01': $la_mesact[0]='selected';
					  break; 
					  case '02': $la_mesact[1]='selected';
					  break;
					  case '03': $la_mesact[2]='selected';
					  break;
					  case '04': $la_mesact[3]='selected';
					  break;
					  case '05': $la_mesact[4]='selected';
					  break;
					  case '06': $la_mesact[5]='selected';
					  break;
					  case '07': $la_mesact[6]='selected';
					  break;	
					  case '08': $la_mesact[7]='selected';
					  break; 
					  case '09': $la_mesact[8]='selected';
					  break;
					  case '10': $la_mesact[9]='selected';
					  break;
					  case '11': $la_mesact[10]='selected';
					  break;
					  case '12': $la_mesact[11]='selected';
					  break;	
					}	
					$ls_cod_prov	 = $_POST["txtprovbene"];
					$ls_ced_bene	 = $_POST["txtprovbene"];
					$ls_tipo=$_POST["tipo"];
					$ds_mov->updateRow("Denominacion",$ls_denominacion,$find);
					$ds_mov->updateRow("Monto",$ldec_monto,$find);
					if($_SESSION["ACTUALIZAR"]=="SI")
					{
						$int_scg->uf_scg_select_movimiento($ls_cuenta,$ls_procdoc,$ls_documento,$ls_debhab,&$ldec_monto_anterior,&$li_orden);
						$lb_valido=$int_scg->uf_scg_procesar_update_movimiento($ls_codemp,$ls_procede, $ls_comprobante, $ls_fecha,
                                     	      $ls_tipo,$ls_cod_prov, $ls_ced_bene, $ls_cuenta,
										      $ls_procdoc, $ls_documento,$ls_debhab,$ls_descripcion,
										      $ldec_monto_anterior, $ldec_monto,$ls_codban,$ls_ctaban );
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
			$ls_procdoc="";
			$ls_debhab="";
			$ldec_monto="";
			$ls_documento=$ls_comprobante;
			$li_fila		 = 0;
			if ($lb_config)
			{
				$readonly_comp='readonly';
			}
			else
			{
				$readonly_comp="";
			}
		}
	

}
elseif($ls_operacion=="DELMOV")//Acciones para eliminar en detalle contable del comprobante
{
	$ds_mov->data    = $_SESSION["objact"];
	$ls_cuenta       = $_POST["txtcuenta"];
	$ls_denominacion = $_POST["txtdescdoc"];
	$ls_documento    = $_POST["txtdocumento"];
	$ls_debhab       = $_POST["debhab"];
	$ldec_monto      = $_POST["txtmonto"];
	$ldec_monto      = str_replace(".","",$ldec_monto);
	$ldec_monto      = str_replace(",",".",$ldec_monto);
	$ls_comprobante = $_POST["txtcomprobante"];
	$ls_procede   = $_POST["txtproccomp"];
	$ls_procdoc      = $ls_procede;
	$ls_fecha     = $_POST["txtfecha"];
	if(array_key_exists("cmbmes",$_POST))
	{	
		$ls_mes=$_POST["cmbmes"];
	}
	else
	{
		$ls_mes="";
	}
	switch ($ls_mes)
	{
	  case '01': $la_mesact[0]='selected';
	  break; 
	  case '02': $la_mesact[1]='selected';
	  break;
	  case '03': $la_mesact[2]='selected';
	  break;
	  case '04': $la_mesact[3]='selected';
	  break;
	  case '05': $la_mesact[4]='selected';
	  break;
	  case '06': $la_mesact[5]='selected';
	  break;
	  case '07': $la_mesact[6]='selected';
	  break;	
	  case '08': $la_mesact[7]='selected';
	  break; 
	  case '09': $la_mesact[8]='selected';
	  break;
	  case '10': $la_mesact[9]='selected';
	  break;
	  case '11': $la_mesact[10]='selected';
	  break;
	  case '12': $la_mesact[11]='selected';
	  break;	
	}	
	$ls_provbene  = $_POST["txtprovbene"];
	$ls_tipo      = $_POST["tipo"];
	$ls_descripcion = $_POST["txtdesccomp"];
	$ls_codemp    = $la_emp["codemp"];
	if(!$int_scg->uf_select_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ls_codban,$ls_ctaban))
	{
		$row=$ds_mov->find("SC_cuenta",$ls_cuenta);
		$ds_mov->deleteRow("SC_cuenta",$row);
	}
	else
	{
		 $int_scg->is_codemp=$ls_codemp;
		 $int_scg->is_procedencia=$ls_procede;
		 $int_scg->is_comprobante=$ls_comprobante;
		 $int_scg->id_fecha=$ls_fecha;
		 $int_scg->as_codban=$ls_codban;
		 $int_scg->as_ctaban=$ls_ctaban;
		 if($int_scg->uf_scg_select_movimiento($ls_cuenta,$ls_procdoc,$ls_documento,$ls_debhab, &$ldec_monto2,&$li_orden))
		 {			
			$lb_valido=$int_scg->uf_scg_procesar_delete_movimiento($ls_codemp,$ls_procede,$ls_comprobante,$ls_fecha,$ls_cuenta,$ls_procdoc,$ls_documento,$ls_debhab,$ldec_monto,$ls_codban,$ls_ctaban);
			$lb_valido=$int_scg->uf_sql_transaction($lb_valido);

				$arr["SC_cuenta"]=$ls_cuenta;
				$arr["Procede_doc"]=$ls_procdoc;
				$arr["Documento"]=$ls_documento;
				$arr["DebHab"]=$ls_debhab;							
				$find=$ds_mov->findValues($arr,"SC_cuenta");				
				$ds_mov->deleteRow("SC_cuenta",$find);
		 }
	}	
	
	$li_fila		 = 0;
	$ls_cuenta="";
	$ls_denominacion="";
	$ls_procdoc="";
	$ls_documento="";
	$ls_documento=$ls_comprobante;
	$ls_debhab="";
	$ldec_monto="";
	$readonly="";
	if ($lb_config)
	{
		$readonly_comp='readonly';
	}
	else
	{
		$readonly_comp="";
	}	
	
}
elseif($ls_operacion=="EDITAR")//Accion de seleccion de un elemento de la tabla y mostrarlo en los input bien sea para editarlos o para eliminarlos del datastore
{
	$ds_mov->data    = $_SESSION["objact"];
	$ls_cuenta       = $_POST["txtcuenta"];
	$ls_denominacion = $_POST["txtdescdoc"];
	$ls_documento    = $_POST["txtdocumento"];
	$ls_debhab       = $_POST["debhab"];
	$ldec_monto      = $_POST["txtmonto"];
	$readonly="readonly";
	if ($lb_config)
	{
		$readonly_comp='readonly';
	}
	else
	{
		$readonly_comp="";
	}
	$ls_comprobante = $_POST["txtcomprobante"];
	$ls_procede   = $_POST["txtproccomp"];
	$ls_procdoc      = $ls_procede;
	$ls_fecha     = $_POST["txtfecha"];
	if(array_key_exists("cmbmes",$_POST))
	{	
		$ls_mes=$_POST["cmbmes"];
	}
	else
	{
		$ls_mes="";
	}
	switch ($ls_mes)
	{
	  case '01': $la_mesact[0]='selected';
	  break; 
	  case '02': $la_mesact[1]='selected';
	  break;
	  case '03': $la_mesact[2]='selected';
	  break;
	  case '04': $la_mesact[3]='selected';
	  break;
	  case '05': $la_mesact[4]='selected';
	  break;
	  case '06': $la_mesact[5]='selected';
	  break;
	  case '07': $la_mesact[6]='selected';
	  break;	
	  case '08': $la_mesact[7]='selected';
	  break; 
	  case '09': $la_mesact[8]='selected';
	  break;
	  case '10': $la_mesact[9]='selected';
	  break;
	  case '11': $la_mesact[10]='selected';
	  break;
	  case '12': $la_mesact[11]='selected';
	  break;	
	}	
	$ls_provbene  = $_POST["txtprovbene"];
	$ls_tipo      = $_POST["tipo"];
	$ls_descripcion = $_POST["txtdesccomp"];
	$li_fila		 = $_POST["fila"];
}
elseif($ls_operacion=="VALIDAFECHA")
{
	
	$ds_mov->data    = $_SESSION["objact"];
	$readonly="";	
	if ($lb_config)
	{
		$readonly_comp='readonly';
	}
	else
	{
		$readonly_comp="";
	}
	$ls_cuenta       = $_POST["txtcuenta"];
	$ls_denominacion = $_POST["txtdescdoc"];
	$ls_documento    = $_POST["txtdocumento"];
	$ls_debhab       = $_POST["debhab"];
	$ldec_monto      = $_POST["txtmonto"];
	$ls_comprobante  = $_POST["txtcomprobante"];
	$ls_documento  = $_POST["txtcomprobante"];
	$ls_procede   = $_POST["txtproccomp"];
	$ls_procdoc      = $ls_procede;
	$ls_fecha     = $_POST["txtfecha"];
    if(array_key_exists("cmbmes",$_POST))
	{	
		$ls_mes=$_POST["cmbmes"];
	}
	else
	{
		$ls_mes="";
	}
	switch ($ls_mes)
	{
	  case '01': $la_mesact[0]='selected';
	  break; 
	  case '02': $la_mesact[1]='selected';
	  break;
	  case '03': $la_mesact[2]='selected';
	  break;
	  case '04': $la_mesact[3]='selected';
	  break;
	  case '05': $la_mesact[4]='selected';
	  break;
	  case '06': $la_mesact[5]='selected';
	  break;
	  case '07': $la_mesact[6]='selected';
	  break;	
	  case '08': $la_mesact[7]='selected';
	  break; 
	  case '09': $la_mesact[8]='selected';
	  break;
	  case '10': $la_mesact[9]='selected';
	  break;
	  case '11': $la_mesact[10]='selected';
	  break;
	  case '12': $la_mesact[11]='selected';
	  break;	
	}	
	$ls_tipo      = $_POST["tipo"];
	$ls_provbene  = $_POST["txtprovbene"];
	$ls_descripcion = $_POST["txtdesccomp"];
	if($ls_tipo=="P")
	{
		$ls_cod_prov = $_POST["txtprovbene"];
		$ls_ced_ben  = "----------";
	}
	elseif($ls_tipo=="B")
	{
		$ls_ced_ben = $_POST["txtprovbene"];
		$ls_cod_prov  = "----------";
	}
	else
	{
		$ls_ced_ben = "----------";
		$ls_cod_prov= "----------";
	}
	$ls_codemp=$la_emp["codemp"];
	$lb_valido=$int_fec->uf_valida_fecha_periodo($ls_fecha,$ls_codemp);
	if(!$lb_valido)
	{
		$msg->message($int_fec->is_msg_error);
	}
	$ld_fec=$ls_fecha;
	$ls_tip=$ls_tipo;
	$ls_ced=$ls_ced_ben;
	$ls_codpro=$ls_cod_prov;
	if($int_scg->uf_obtener_comprobante($ls_codemp,$ls_procede,$ls_comprobante,$ld_fec,$ls_codban,$ls_ctaban,$ls_tip,$ls_ced,$ls_codpro))
	{
		$ib_new=$_SESSION["ib_new"];
		if($ib_new)
		{
			
			$msg->message("El numero de comprobante ya existe,favor registre un nuevo ID o número de comprobante"); 
		}
	}
	$li_fila		 = 0;
}
elseif($ls_operacion=="CARGAR")
{

	$ls_codemp=$la_emp["codemp"];
	$_SESSION["ib_new"]	=false;
	$_SESSION["ACTUALIZAR"]="SI";
	if(array_key_exists("txtcomprobante",$_POST))
	{
		$ls_comprobante = $_POST["txtcomprobante"];		
		$ls_fecha     = $_POST["txtfecha"];
		$ls_mes=substr($ls_fecha,3,2);	
		switch ($ls_mes)
		{
		  case '01': $la_mesact[0]='selected';
		  break; 
		  case '02': $la_mesact[1]='selected';
		  break;
		  case '03': $la_mesact[2]='selected';
		  break;
		  case '04': $la_mesact[3]='selected';
		  break;
		  case '05': $la_mesact[4]='selected';
		  break;
		  case '06': $la_mesact[5]='selected';
		  break;
		  case '07': $la_mesact[6]='selected';
		  break;	
		  case '08': $la_mesact[7]='selected';
		  break; 
		  case '09': $la_mesact[8]='selected';
		  break;
		  case '10': $la_mesact[9]='selected';
		  break;
		  case '11': $la_mesact[10]='selected';
		  break;
		  case '12': $la_mesact[11]='selected';
		  break;	
		}	
		
		$ls_procede   = $_POST["txtproccomp"];
		$ls_tipo      = $_POST["tipo"];
		$ls_provbene  = $_POST["txtprovbene"];
		$ls_descripcion = $_POST["txtdesccomp"];
		$ls_codban_c = $_POST["txtcodban"];
		$ls_ctaban_c = $_POST["txtctaban"];
		$ld_fecha=$fun->uf_convertirdatetobd($ls_fecha);
		$rs_dtcmp = $int_scg->uf_scg_cargar_detalle_comprobante( $ls_codemp,$ls_procede,$ls_comprobante,$ld_fecha,&$ds_mov,$ls_codban_c,$ls_ctaban_c);
		$li_num_rows=$int_scg->io_sql->num_rows($rs_dtcmp);
		if($li_num_rows>0)
		{
			while($row=$int_scg->io_sql->fetch_row($rs_dtcmp))
			{
				$ls_cuenta=$row["sc_cuenta"];
				$ls_denominacion=$row["descripcion"];
				$ls_procdoc=$row["procede_doc"];
				$ls_documento=$row["documento"];
				$ls_debhab=$row["debhab"];
				$ldec_monto=$row["monto"];
				$ds_mov->insertRow("SC_cuenta",$ls_cuenta);
				$ds_mov->insertRow("Denominacion",$ls_denominacion);
				$ds_mov->insertRow("Procede_doc",$ls_procdoc);
				$ds_mov->insertRow("Documento",$ls_documento);
				$ds_mov->insertRow("DebHab",$ls_debhab);
				$ds_mov->insertRow("Monto",$ldec_monto);
			}
		}
		else
		{
			$ds_mov->data= $_SESSION["objact"];
		}		
		
	}
	else
	{
		$ls_cuenta="";
		$ls_denominacion="";
		$ls_procdoc="";
		$ls_documento="";
		$ls_debhab="";
		$ldec_monto="";
		$readonly="";		
		if ($lb_config)
		{
			if ($ls_estcompscg==1)
			{
				$ls_comprobante = $int_scg->io_keygen_mensual->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP");
				unset($io_keygen_mensual);
								
			}
			else
			{
				$ls_comprobante = $int_scg->io_keygen->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP");
				unset($io_keygen);	
			}
			$readonly_comp='readonly';
		}
		else
		{
			$ls_comprobante = "";
			$readonly_comp="";
		}
		$ls_procede="SCGCMP";
		$ls_provbene  = "";
		$ls_tipo="";
		$ls_descripcion = "";
		$ib_new=true;
		$array_fecha=getdate();
		$li_fila		 = "";
	}
	$ls_cuenta="";
	$ls_denominacion="";
	$ls_procdoc="";
	$ls_documento="";
	$ls_debhab="";
	$ldec_monto="";
	$readonly="";
	if ($lb_config)
	{
		$readonly_comp='readonly';
	}
	else
	{
		$readonly_comp="";
	}
	
}
elseif($ls_operacion=="GENERAR_NUMERO")//Acciones para un comprobante nuevo
{
	
	$ds_mov->data    = $_SESSION["objact"];
	$ds_mov->resetds("SC_cuenta");
	$ls_comprobante = $_POST["txtcomprobante"];		
	$ls_fecha     = $_POST["txtfecha"];
	$ls_procede   = $_POST["txtproccomp"];
	$ls_tipo      = $_POST["tipo"];
	$ls_provbene  = $_POST["txtprovbene"];
	$ls_descripcion = $_POST["txtdesccomp"];
	$ls_codban_c = $_POST["txtcodban"];
	$ls_ctaban_c = $_POST["txtctaban"];	
	$ls_cuenta="";
	$ls_denominacion="";
	$ls_procdoc="";//Procedencia documento detalle
	$ls_documento="";
	$ls_debhab="";	
	$ldec_monto="";
	$readonly="";
	$_SESSION["ib_new"]	=true;
	$_SESSION["ACTUALIZAR"]="NO";
	$li_fila  = 0;	
	if ($lb_config)
	{		
		if ($ls_estcompscg==1)
		{
						
			$ls_mesact=$_POST["cmbmes"];
			$ls_anoact=$_POST["txtperiodo"];
			switch ($ls_mesact)
			{
			  case '01': $la_mesact[0]='selected';
			  break; 
			  case '02': $la_mesact[1]='selected';
			  break;
			  case '03': $la_mesact[2]='selected';
			  break;
			  case '04': $la_mesact[3]='selected';
			  break;
			  case '05': $la_mesact[4]='selected';
			  break;
			  case '06': $la_mesact[5]='selected';
			  break;
			  case '07': $la_mesact[6]='selected';
			  break;	
			  case '08': $la_mesact[7]='selected';
			  break; 
			  case '09': $la_mesact[8]='selected';
			  break;
			  case '10': $la_mesact[9]='selected';
			  break;
			  case '11': $la_mesact[10]='selected';
			  break;
			  case '12': $la_mesact[11]='selected';
			  break;	
			}									
			
			$ls_comprobante = $int_scg->io_keygen_mensual->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP",$ls_mesact,$ls_anoact);
			unset($io_keygen_mensual);
							
		}
		else
		{
			$ls_comprobante = $int_scg->io_keygen->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP");
			unset($io_keygen);	
		}
		$readonly_comp='readonly';
	}
}
elseif($ls_operacion=="NUEVO")//Acciones para un comprobante nuevo
{
	$ds_mov->data    = $_SESSION["objact"];
	$ds_mov->resetds("SC_cuenta");
	if ($lb_config)
	{		
		if ($ls_estcompscg==1)
		{
			$ld_fecact=date("d/m/Y");			
			$ls_mesact=substr($ld_fecact,3,2);
			$ls_anoact=substr($ld_fecact,6,4);
			switch ($ls_mesact)
			{
			  case '01': $la_mesact[0]='selected';
			  break; 
			  case '02': $la_mesact[1]='selected';
			  break;
			  case '03': $la_mesact[2]='selected';
			  break;
			  case '04': $la_mesact[3]='selected';
			  break;
			  case '05': $la_mesact[4]='selected';
			  break;
			  case '06': $la_mesact[5]='selected';
			  break;
			  case '07': $la_mesact[6]='selected';
			  break;	
			  case '08': $la_mesact[7]='selected';
			  break; 
			  case '09': $la_mesact[8]='selected';
			  break;
			  case '10': $la_mesact[9]='selected';
			  break;
			  case '11': $la_mesact[10]='selected';
			  break;
			  case '12': $la_mesact[11]='selected';
			  break;	
			}									
			
			$ls_comprobante = $int_scg->io_keygen_mensual->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP",$ls_mesact,$ls_anoact);
			unset($io_keygen_mensual);
							
		}
		else
		{
			$ls_comprobante = $int_scg->io_keygen->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP");
			unset($io_keygen);	
		}
		$readonly_comp='readonly';
	}
	else
	{
		$ls_comprobante = "";
		$readonly_comp="";
	}
	$ls_procede	  = "SCGCMP";//Procedencia comprobante
	$ls_fecha     = "";
	$ls_tipo      = "-";
	$ls_provbene  = "----------";
	$ls_nomproben = "";
	$ls_descripcion = "";
	$ls_cuenta="";
	$ls_denominacion="";
	$ls_procdoc="";//Procedencia documento detalle
	$ls_documento="";
	$ls_debhab="";
	$ls_fecha=date("d/m/Y");
	$ldec_monto="";
	$readonly="";
	$_SESSION["ib_new"]	=true;
	$_SESSION["ACTUALIZAR"]="NO";
	$li_fila  = 0;	
}
else//Sin operacion.
{
	$ls_cuenta="";
	$ls_denominacion="";
	$ls_procdoc="";
	$ls_documento="";
	$ls_debhab="";
	$ldec_monto="";
	$readonly="";
	if ($lb_config)
	{
		if ($ls_estcompscg==1)
		{
			
			$ld_fecact=date("d/m/Y");			
			$ls_mesact=substr($ld_fecact,3,2);
			$ls_anoact=substr($ld_fecact,6,4);
			switch ($ls_mesact)
			{
			  case '01': $la_mesact[0]='selected';
			  break; 
			  case '02': $la_mesact[1]='selected';
			  break;
			  case '03': $la_mesact[2]='selected';
			  break;
			  case '04': $la_mesact[3]='selected';
			  break;
			  case '05': $la_mesact[4]='selected';
			  break;
			  case '06': $la_mesact[5]='selected';
			  break;
			  case '07': $la_mesact[6]='selected';
			  break;	
			  case '08': $la_mesact[7]='selected';
			  break; 
			  case '09': $la_mesact[8]='selected';
			  break;
			  case '10': $la_mesact[9]='selected';
			  break;
			  case '11': $la_mesact[10]='selected';
			  break;
			  case '12': $la_mesact[11]='selected';
			  break;	
			}
			
			
			$ls_comprobante = $int_scg->io_keygen_mensual->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP",$ls_mesact,$ls_anoact);
			unset($io_keygen_mensual);
							
		}
		else
		{
			$ls_comprobante = $int_scg->io_keygen->uf_generar_numero_nuevo("SCG","sigesp_cmp","comprobante","SCGCMP",15,"","procede","SCGCMP");
			unset($io_keygen);	
		}
		$readonly_comp='readonly';
	}
	else
	{
		$ls_comprobante = "";
		$readonly_comp="";
	}
	$ls_procede="SCGCMP";
	$ls_descripcion = "";
	$ib_new=true;
	$array_fecha=getdate();
	$li_fila		 = "";
	
}

function uf_valida_datos_cabezera($as_comprobante,$as_tipo,$as_cod_prov,$as_ced_bene,$as_procedencia,$ad_fecha,$as_mesact,$as_messel,$as_anoact,$as_anosel)
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
		if(trim($ad_fecha)=="")
		{
			$msg->message("Debe ingresar una fecha correspondiente al Periodo Contable seleccionado.");
			return false;
		}
		if ($as_mesact!=$as_messel)
		{
			$msg->message("Debe ingresar una fecha correspondiente al Periodo Contable seleccionado.");
			return false;
		}
		
		if ($as_anoact!=$as_anosel)
		{
			$msg->message("Debe ingresar una fecha correspondiente al Periodo Contable seleccionado.");
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
			return false;	
		}
		
		if((trim($as_cod_prov)=="")&&($as_tipo=="P"))
		{
			
			return false;
		}
		
		if((trim($as_cod_prov)!="----------")&&($as_tipo=="B"))
		{
			$as_cod_prov = "----------";
		}
		
		if((trim($as_ced_bene)=="----------")&&($as_tipo=="B"))
		{
			
			return false;	
		}
		
		if((trim($as_ced_bene)=="")&&($as_tipo=="B"))
		{
			
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
		
	function uf_guardar_movimientos($is_codemp,$is_procede,$is_comprobante,$id_fecha,$ii_tipo_comp,$is_descripcion,$ds_mov,$is_cod_prov,$is_ced_bene)
	{
		global $int_scg;
		global $msg;
		global $fun;
		global $la_seguridad;
		global $funciones_scg;
		
		$ls_cuenta="";
		$ls_procede_doc="";
		$ls_documento="";
		$ls_debhab="";
		$ls_descripcion="";
		$ls_fecnew="";
		$lb_valido=true;
		$ldec_monto_anterior=0;
		$ldec_monto_actual=0;
		$li_dia=0;
		$li_mes=0;
		$li_agno=0;
		$la_emp=$_SESSION["la_empresa"];
		$is_codemp  = $la_emp["codemp"];
		$li_numrows=$ds_mov->getRowCount("SC_cuenta");
		for($li_i=1;$li_i<=$li_numrows;$li_i++)
		{		
			
			$is_tipo=$_POST["tipo"];			
			$ii_tipo_comp = 1;				
			$ld_debaux=0;
			$ld_habaux=0;
			$ld_mondeb=0;
			$ld_monhab=0;
			
			$ls_cuenta      = $ds_mov->getValue("SC_cuenta",$li_i);
			$ls_procede_doc = $ds_mov->getValue("Procede_doc",$li_i);
			$ls_denominacion = $ds_mov->getValue("Denominacion",$li_i);
			$ls_documento   = $ds_mov->getValue("Documento",$li_i);
			$ls_debhab      = $ds_mov->getValue("DebHab",$li_i);
			$ldec_monto_actual=$ds_mov->getValue("Monto",$li_i);
			
			if(!$int_scg->uf_valida_procedencia( $ls_procede_doc,&$ls_desproc ))
			{
				$msg->message("Procedencia ".$ls_procede_doc." es invalida");
				return false;	
			}
			$ls_codban="---";
			$ls_ctaban="-------------------------";
			$lb_valido=$int_scg->uf_select_comprobante($is_codemp,$is_procede,$is_comprobante,$id_fecha,$ls_codban,$ls_ctaban);
			if($lb_valido)
			{
				$ld_fecha=$fun->uf_convertirdatetobd($id_fecha);
				$lb_valido = $int_scg->uf_scg_procesar_insert_movimiento($is_codemp,$is_procede,$is_comprobante,$ld_fecha,
															$is_tipo,$is_cod_prov,$is_ced_bene,$ls_cuenta,
															$ls_procede_doc,$ls_documento,$ls_debhab,$ls_denominacion,
															 $ldec_monto_anterior,$ldec_monto_actual,$ls_codban,$ls_ctaban);
				if(!$lb_valido)
				{
					$msg->message("Error al registrar movimiento contable. ".$int_scg->is_msg_error);
					return false;
				}
				
			}
			
		}	
		
		return $lb_valido;
	}	
	
	
	if($ls_tipo=="P")
	{
		$prov_sel="selected";
		$bene_sel="";
		$ning_sel="";
	}
	elseif($ls_tipo=="B")
	{
		$prov_sel="";
		$bene_sel="selected";
		$ning_sel="";
	}
	else
	{
		$prov_sel="";
		$bene_sel="";
		$ning_sel="selected";
	}
$ls_procdoc=$ls_procede;	
if($ls_procdoc!='SCGCMP')
{
	$ro_comprobante="readonly";
	$ro_descripcion="readonly";
	$ro_fecha="readonly";
}
else
{
	$ro_comprobante="";
	$ro_descripcion="";
	$ro_fecha="";
}
if($ds_mov->getRowCount("DebHab")>0)
{
	$ds_mov->sortData("DebHab");
}
?>
<form name="form1" method="post" action=""><div >
    <?php
			//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
			if (($ls_permisos)||($ls_logusr=="PSEGIS"))
			{
				print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
				
			}
			else
			{
				print("<script language=JavaScript>");
				print(" location.href='sigespwindow_blank.php'");
				print("</script>");
			}
			//////////////////////////////////////////////         SEGURIDAD               //////////////////////////////////////////////
        ?>
  <table width="719" height="460" border="0" cellpadding="0" cellspacing="0" class="formato-blanco" align="center">
      <tr class="titulo-nuevo">
        <td height="20" colspan="3">Datos del Comprobante </td>
      </tr>
      <tr>
        <td width="123" height="15">&nbsp;</td>
        <td width="436">&nbsp;</td>
        <td width="158">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Procedencia</td>
        <td height="22"><input name="txtproccomp" type="text" id="txtproccomp" value="<?php print $ls_procede?>" readonly="true" style="text-align:center" ></td>
        <td height="22">Fecha 
          <input name="txtfecha" type="text" id="txtfecha" style="text-align:center" onKeyDown="javascript:ue_formato_fecha(this,'/',patron,true,event);" onBlur="javascript: ue_validar_formatofecha(this);"   value="<?php print $ls_fecha?>" size="18" maxlength="10" datepicker="true" <?php print $ro_fecha;  ?>></td>
      </tr>
	   <tr id="fila1" style="display:none">
        <td height="22" style="text-align:right">Periodo Contable</td>
        <td height="22"><select name="cmbmes" onChange="javascript: uf_cambiar_fecha(); ue_generar_numero();">
            <option value="01" <?php print $la_mesact[0]?>>ENERO</option>
            <option value="02" <?php print $la_mesact[1]?>>FEBRERO</option>
            <option value="03" <?php print $la_mesact[2]?>>MARZO</option>
            <option value="04" <?php print $la_mesact[3]?>>ABRIL</option>
            <option value="05" <?php print $la_mesact[4]?>>MAYO</option>
            <option value="06" <?php print $la_mesact[5]?>>JUNIO</option>
            <option value="07" <?php print $la_mesact[6]?>>JULIO</option>
            <option value="08" <?php print $la_mesact[7]?>>AGOSTO</option>
            <option value="09" <?php print $la_mesact[8]?> >SEPTIEMBRE</option>
            <option value="10" <?php print $la_mesact[9]?>>OCTUBRE</option>
            <option value="11" <?php print $la_mesact[10]?>>NOVIEMBRE</option>
            <option value="12" <?php print $la_mesact[11]?>>DICIEMBRE</option>
          </select>
            <input name="txtperiodo" type="text" id="txtperiodo" value="<?php print $li_ano; ?>" size="6" maxlength="4" style="text-align:center" readonly></td>
        </tr>
<?php	
//////////////// PARA LA CONFIGURACIÓN DE INICIAR NUMERACIÓN DE COMPROBANTES MENSUALMENTE 	////////////////
	if ($lb_config)
	{
		if ($ls_estcompscg)  // Si se reinicializa en cada mes muestro el Combo de los Meses
		{
			?>
			<script language="javascript">
			   fila1=document.getElementById("fila1");
			   fila1.style.display="";
			   fila1.style.display="compact";
			</script>
		<?php
		
		}
	}		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?> 
  
   <tr>
        <td height="22" style="text-align:right">Comprobante</td>
        <td height="22"><input name="txtcomprobante" type="text" id="txtcomprobante"  maxlength="15" style="text-align:center" <?php print $readonly_comp?>  value="<?php print $ls_comprobante ?>" <?php print $ro_comprobante;  ?> onBlur="javascript: rellenar_cad(document.form1.txtcomprobante.value,15,'cmp');"></td>
        <td height="22">&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Descripcion</td>
        <td height="22" colspan="2"><input name="txtdesccomp" type="text" id="txtdesccomp" value="<?php echo $ls_descripcion ?>" size="108"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Tipo</td>
        <td height="22" colspan="2"><select name="tipo" id="tipo" onChange="javascript:uf_cambio_tipo();" style="width:120px">
          <option value="P" <?php print $prov_sel ?>  >Proveedor</option>
          <option value="B" <?php print $bene_sel ?> >Beneficiario</option>
          <option value="-" <?php print $ning_sel ?> >Ninguno</option>
        </select>
        <a href="javascript:catprovbene(document.form1.tipo.value)"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Proveedores/Beneficiarios" width="15" height="15" border="0"></a> <input name="txtprovbene" type="text" class="sin-borde" id="txtprovbene" style="text-align:center" value="<?php print $ls_provbene?>" readonly>
        <input name="txtnomproben" type="text" class="sin-borde" id="txtnomproben" value="<?php print $ls_nomproben;?>" size="55" readonly></td>
      </tr>
      <tr >
        <td height="13" colspan="3">&nbsp;</td>
      </tr>
      <tr class="titulo-nuevo">
        <td height="20" colspan="3" class="titulo">Detalles contables </td>
      </tr>
      <tr>
        <td height="15">&nbsp;</td>
        <td height="17">&nbsp;</td>
        <td height="17"><input name="fila" type="hidden" id="fila" value="<?php print $li_fila?>"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">C&oacute;digo Contable</td>
        <td colspan="2">
          <input name="txtcuenta" type="text" id="txtcuenta" value="<?php print $ls_cuenta?>" style="text-align:center" readonly>
          <a href="javascript:cat()"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a>
          <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" size="82"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Descripci&oacute;n</td>
        <td colspan="2"><input name="txtdescdoc" type="text" id="txtdescdoc" size="111" value="<?php print $ls_denominacion;?>"></td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">N&ordm; Documento</td>
        <td><input name="txtdocumento" type="text" id="txtdocumento" value="<?php print $ls_documento;?>" onBlur="javascript: rellenar_cad(document.form1.txtdocumento.value,15,'doc');" style="text-align:center"></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="22" style="text-align:right">Operaci&oacute;n</td>
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
        <td height="22" style="text-align:right">Monto</td>
        <td>
          <input name="txtmonto" type="text" id="txtmonto" value="<?php print $ldec_monto?>" style="text-align:right" onKeyPress="javascript: return(ue_formatonumero(this,'.',',',event));" onBlur="javascript:uf_format(this);">
		  <a href="javascript: uf_save_mov();"><img src="../shared/imagebank/tools15/aprobado.gif" alt="Agregar" width="15" height="15" border="0">Agregar Detalle</a> <a href="javascript: uf_del_mov(document.form1.txtcuenta.value,document.form1.txtdescdoc.value,'SCGCMP',document.form1.txtdocumento.value,document.form1.debhab.value,document.form1.txtmonto.value);"><img src="../shared/imagebank/tools15/eliminar.gif" alt="Eliminar" width="15" height="15" border="0">Eliminar Detalle </a> </td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="104" colspan="3" valign="top" bordercolor="#FFFFFF">
            <table width="690" border="0" align="center" cellpadding="1" cellspacing="1" class="fondo-tabla">
              <tr bordercolor="#000000" bgcolor="#D5D5D5" class="titulo-celdanew">
                <td align="center" width="87">Cuenta</td>
                <td align="center" width="268">Descripci&oacute;n</td>
                <td align="center" width="45">Procede</td>
                <td align="center" width="89">Documento</td>
                <td align="center" width="54">Operaci&oacute;n</td>
                <td align="center" width="133">Monto</td>
              </tr>
              <?php
			$totrow=$ds_mov->getRowCount("SC_cuenta");
			
			if($totrow==0)
			{
			  ?>
			  <tr class="celdas-blancas">
			    <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
			  <tr class="celdas-blancas">
			    <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
			  <tr class="celdas-blancas">
			    <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <?php	
			}
		for($i=1;$i<=$totrow;$i++)
		{
			$ls_debhab=$ds_mov->getValue("DebHab",$i);
			$ls_denominacion=$ds_mov->getValue("Denominacion",$i);
			$ls_cuenta=$ds_mov->getValue("SC_cuenta",$i);
			$ls_procdoc=$ds_mov->getValue("Procede_doc",$i);
			$ls_documento=$ds_mov->getValue("Documento",$i);
			$ldec_monto=$ds_mov->getValue("Monto",$i);

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
                <td><?php print $ls_denominacion?></td>
                <td align="center"><?php print $ls_procdoc     ?></td>
                <td align="center"><?php print $ls_documento   ?></td>
                <td align="center"><?php print $ls_debhab      ?></td>
                <td align="right"> <?php print number_format($ldec_monto,2,",",".") ?></td>
              </tr>
              <?php	
		}
		
		function uf_calcular_diferencia($ds_mov,$ldec_mondeb,$ldec_monhab)
		{
			$ldec_mondeb=0;
			$ldec_monhab=0;
			$totrow=$ds_mov->getRowCount("SC_cuenta");
			$ldec_dif=0;
			for($i=1;$i<=$totrow;$i++)
			{
				$ls_debhab=$ds_mov->getValue("DebHab",$i);
				$ldec_monto=$ds_mov->getValue("Monto",$i);
				if($ls_debhab=="D")
				{
					$ldec_dif=$ldec_dif + $ldec_monto;
					$ldec_mondeb=$ldec_mondeb + $ldec_monto;
					
				}
				else
				{
					$ldec_dif=$ldec_dif - $ldec_monto;
					$ldec_monhab=$ldec_monhab + $ldec_monto;

				}
			}
			$ldec_mondeb= number_format($ldec_mondeb,2,",",".");
			$ldec_monhab= number_format($ldec_monhab,2,",",".");
			return number_format($ldec_dif,2,",",".");
		}
		
		$ldec_diferencia=uf_calcular_diferencia($ds_mov,&$ldec_mondeb,&$ldec_monhab);
		?>
        </table></td>
      </tr>
	  <br>
      <tr>
        <td height="73" colspan="3" valign="top" bordercolor="#FFFFFF"><table width="705" border="0" cellspacing="0" cellpadding="0" class="celdas-blancas">
            <tr>
              <td height="23">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td align="right"><div align="right">Debe</div></td>
              <td align="left">
                <input name="txtdebe" type="text" id="txtdebe" style="text-align:right" value="<?php print $ldec_mondeb;?>" size="28" readonly>              </td>
            </tr>
            <tr>
              <td height="21">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td align="right"><div align="right">Haber</div></td>
              <td align="left">
                <input name="txthaber" type="text" id="txthaber" style="text-align:right" value="<?php print $ldec_monhab;?>" size="28" readonly>              </td>
            </tr>
            <tr>
              <td width="78" height="21">&nbsp;</td>
              <td width="99">&nbsp;</td>
              <td width="89">&nbsp;</td>
              <td width="219" align="right"> </td>
              <td width="71"  align="left"><div align="right">Diferencia</div></td>
              <td width="149" align="center">
                  <input name="txtdiferencia" type="text" id="txtdiferencia" style="text-align:right" value="<?php print $ldec_diferencia;?>" size="28" readonly>              </td>
            </tr>
        </table></td>
      </tr>
    </table>
  </div>
  <p >
	<?php
	$_SESSION["objact"]=$ds_mov->data;
	
	?>
  </p>
  <p >
    <input name="operacion" type="hidden" id="operacion">
	<input name="txtcodban" type="hidden" id="txtcodban">
	<input name="txtctaban" type="hidden" id="txtctaban">    
  </p>
</form>
</body>
<script language="javascript">
//Funcion de carga de Catalogos
function cat()
{
	f=document.form1;
	ls_procede=f.txtproccomp.value;
	if(ls_procede=='SCGCMP')
	{
		f.txtcuenta.disabled=false;
		window.open("sigesp_cat_scg.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=535,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No puede editar un comprobante que no fue generado por este modulo");
	}
}

function catprovbene(provbene)
{
	f=document.form1;
	ls_procede=f.txtproccomp.value;
	if (ls_procede=='SCGCMP')
	   {
		 if (provbene=="P")
		    {
			  f.txtprovbene.disabled=false;	
			  window.open("sigesp_catdinamic_prov.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		    }
		 else if(provbene=="B")
		    {
			  f.txtprovbene.disabled=false;	
			  window.open("sigesp_catdinamic_bene.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
		    }
	   }
	else
	{
		alert("No puede editar un comprobante que no fue generado por este modulo");
	}
}

//Funciones de operaciones para el detalle del comprobante

function editar(fila,cuenta , deno , procede, documento,debhab,monto)
{
	f=document.form1;
	ls_procede=f.txtproccomp.value;
	if(ls_procede=='SCGCMP')
	{
		f.fila.value=fila;
		f.txtcuenta.disabled=false;
		f.txtcuenta.value=cuenta;
		f.txtdescdoc.value=deno;
		f.txtdocumento.value=documento;
		f.debhab.value=debhab;
		f.txtmonto.value=uf_convertir(monto);
		f.operacion.value ="EDITAR";
		f.action="sigespwindow_scg_comprobante.php";
		f.txtcuenta.focus(true	);
		f.submit();
	}
	else
	{
		alert("No puede editar un comprobante que no fue generado por este modulo");
	}
}

function uf_save_mov()
{
	f=document.form1;
	ls_procede=f.txtproccomp.value;
	if(ls_procede=='SCGCMP')
	{
		f.operacion.value="AGREGAR";
		f.action="sigespwindow_scg_comprobante.php";
		f.submit();
	}
	else
	{
		alert("No puede editar un comprobante que no fue generado por este modulo");
	}
}
function uf_cargar_dt()
{
	f=document.form1;
	f.operacion.value="CARGAR";
	f.action="sigespwindow_scg_comprobante.php";
	f.submit();
}
function uf_del_mov(cuenta,desc,proc,doc,debhab,monto)
{
	f=document.form1;
	ls_procede=f.txtproccomp.value;
	if(ls_procede=='SCGCMP')
	{
		fila=f.fila.value;		
		if(fila>0)
		{
			f.txtcuenta.value=cuenta;
			f.txtdescdoc.value=desc;
			f.txtdocumento.value=doc;
			f.debhab.value=debhab;
			f.txtmonto.value=monto;
			f.operacion.value="DELMOV";
			f.action="sigespwindow_scg_comprobante.php";
			f.submit();
		}
	}
	else
	{
		alert("No puede editar un comprobante que no fue generado por este modulo");
	}
	
}


//Funciones de operaciones sobre el comprobante

function ue_generar_numero()
{
	f=document.form1;
	f.operacion.value="GENERAR_NUMERO";
	f.action="sigespwindow_scg_comprobante.php";
	f.submit();
}

function ue_nuevo()
{
	f=document.form1;
	f.operacion.value="NUEVO";
	f.action="sigespwindow_scg_comprobante.php";
	f.submit();
}
function ue_guardar()
{
	f=document.form1;
	ls_procede=f.txtproccomp.value;
	ls_comprobante=f.txtcomprobante.value;
	
	if((ls_procede=='SCGCMP')&&(ls_comprobante!="")&&(ls_comprobante!='000000000000000'))
	{
		ls_codproben = f.txtprovbene.value;
		ls_tipproben = f.tipo.value;
		if ((ls_tipproben=='P' || ls_tipproben=='B') && (ls_codproben=='' || ls_codproben=='----------'))
		   {
		     alert("Debe especificar Código/Cédula del Proveedor/Beneficiario del Comprobante !!!");
		   }
		else
		   {
			 f.operacion.value="GUARDAR";
			 f.action="sigespwindow_scg_comprobante.php";
			 f.submit();		   
		   }
	}
	else
	{
		alert("No puede editar un comprobante que no fue generado por este modulo");
	}
}
function ue_eliminar()
{
	f=document.form1;
	ls_procede=f.txtproccomp.value;
	if(ls_procede=='SCGCMP')
	{
		f.operacion.value="ELIMINAR";
		f.action="sigespwindow_scg_comprobante.php";
		f.submit();
	}
	else
	{
		alert("No puede editar un comprobante que no fue generado por este modulo");
	}
}
function ue_buscar()
{
	f=document.form1;
	ldec_diferencia=f.txtdiferencia.value;
	ldec_diferencia=uf_convertir_monto(ldec_diferencia);
	if(ldec_diferencia!=0)
	{
		alert("Comprobante descuadrado");
	}
	else
	{
		window.open("sigesp_cat_comprobantes.php","Catalogo","menubar=no,toolbar=no,scrollbars=yes,width=583,height=400,left=50,top=50,location=no,resizable=yes");
	}
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}
function ue_close()
{
close()
}
function valid_cmp(cmp)
{
rellenar_cad(cmp,15,"cmp");
f=document.form1;
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

function currencyFormat(fld, milSep, decSep, e) { 
    var sep = 0; 
    var key = ''; 
    var i = j = 0; 
    var len = len2 = 0; 
    var strCheck = '0123456789'; 
    var aux = aux2 = ''; 
    var whichCode = (window.Event) ? e.which : e.keyCode; 
    if (whichCode == 13) return true; // Enter 
    if (whichCode == 8) return true; // Backspace <-
    if (whichCode == 127) return true; // Suprimir -Del 
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

  function uf_cambio_tipo()
  {
  	f=document.form1;
	ls_tipo=f.tipo.value;
	ls_procede=f.txtproccomp.value;
	if(ls_procede=='SCGCMP')
	{
		if(ls_tipo=='-')
		{
			f.txtprovbene.value="----------";
			f.txtnomproben.value="";
		}
		else
		{
			f.txtprovbene.value="";
			f.txtnomproben.value="";
		}
	}
	else
	{
		alert("No puede editar un comprobante que no fue generado por este modulo");
	}
  }
 function uf_denominacion_dt(obj)
 {
 	f=document.form1;
	f.txtdescdoc.value=obj.value;
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
			//alert(ls_long);


  //  return false; 
   }
   function uf_format(obj)
   {
		//ldec_monto=uf_convertir(obj.value);
		//obj.value=ldec_monto;
   }
   function uf_cambiar_fecha()
   {
   		
		f=document.form1;
		fecha=f.txtfecha.value;
		mesact=fecha.substr(3,2);
		messel=f.cmbmes.value;
		
		if (mesact!=messel)
		{
			alert ('Debe ajustar la fecha de registro al Periodo Contable Seleccionado');
			f.txtfecha.focus();
		}
   }
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);	  

</script> 
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>