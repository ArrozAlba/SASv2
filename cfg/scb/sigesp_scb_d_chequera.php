<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definici&oacute;n de Cheques</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
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
-->
</style></head>

<body>

<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" class="toolbar">&nbsp;</td>
  </tr>
  <tr>
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Guardar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div>      <div align="center"></div>      <div align="center"></div>      <div align="center"></div>      <div align="center"></div></td>
  </tr>
</table>
<?php
	require_once("../../shared/class_folder/class_mensajes.php");
	$msg=new class_mensajes();	
	$lb_guardar=true;
	//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	//Instancia de la clase de manejo de Grid dinamico
	require_once("../../shared/class_folder/grid_param.php");
	require_once("../../shared/class_folder/class_funciones.php");
	$io_fun=new class_funciones();
	$io_grid=new grid_param();
	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	$ls_confi_ch=$arre["confi_ch"]; 
	if(array_key_exists("la_logusr",$_SESSION))
	{
		$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
		$ls_logusr="";
	}
	$ls_sistema="CFG";
	$ls_ventanas="sigesp_scb_d_chequera.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
		}
		else
		{
			$ls_permisos=             $_POST["permisos"];
			$la_accesos["leer"]=     $_POST["leer"];
			$la_accesos["incluir"]=  $_POST["incluir"];
			$la_accesos["cambiar"]=  $_POST["cambiar"];
			$la_accesos["eliminar"]= $_POST["eliminar"];
			$la_accesos["imprimir"]= $_POST["imprimir"];
			$la_accesos["anular"]=   $_POST["anular"];
			$la_accesos["ejecutar"]= $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]="";
		$la_accesos["incluir"]="";
		$la_accesos["cambiar"]="";
		$la_accesos["eliminar"]="";
		$la_accesos["imprimir"]="";
		$la_accesos["anular"]="";
		$la_accesos["ejecutar"]="";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
	//Inclusión de la clase de seguridad.
	
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
 require_once("sigesp_scb_c_chequera.php");
 $io_chequera  = new sigesp_scb_c_chequera($la_security);

	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion= $_POST["operacion"];
		$ls_chequera   = $_POST["txtchequera"];
		$ls_tipcta  = $_POST["txttipocuenta"];
		$ls_dentipcta  = $_POST["txtdentipocuenta"];
		$ls_codban   = $_POST["txtcodban"];
		$ls_denban   = $_POST["txtdenban"];
	    $ls_ctaban    = $_POST["txtcuenta"];
	    $ls_denctaban = $_POST["txtdenominacion"];
		$ls_desde= $_POST["txtdesde"];
		$ls_hasta= $_POST["txthasta"];
		$ls_status=$_POST["status"];
		$li_totrowche = $_POST["total"];
		$li_totrowusu = $_POST["totrows"];
		$li_lastrow   = $_POST["lastrow"];
		$ls_config_ch=$_POST["txtcfg_ch"];
		$readonly    = "";
		if(array_key_exists("status",$_POST))
		{
			if($_POST["status"]==1)
			{
				$checked   = "checked" ;	
				$li_status = 1;
			}
			else
			{
				$li_status = 0;
				$checked="";
			}
		}
		else
		{
				$li_status = 0;
				$checked="";
		}
	}
	else
	{
		$ls_operacion= "NUEVO" ;
		$ls_chequera = "" ;
		$ls_tipcta  = "" ;
		$ls_dentipcta= "" ;
		$ls_codban   = "" ;
		$ls_denban   = "" ;
		$ls_ctaban    = "";
	    $ls_denctaban = "";
		$ls_numcheque= "" ;
		$ls_desde= "";
		$ls_hasta= "";
		$readonly    = "" ;
		$li_status = 0;
		$checked="";
		$li_lastrow   = 0;
	    $li_totrowusu = 1;
	    $li_totrowche = 1;
		$ls_config_ch="";
	}
	$title  = array('1'=>'Nº Cheque','2'=>'Usuario','3'=>'Emitido','4'=>'');
	$grid1="grid_ch";
	
////////////////////////////////////////////////////////////////////////////////
   // $title2=array('1'=>'Usuario','2'=>'Nombre','3'=>'Asignado','4'=>'Edición');
	//$grid2="grid_ch";

///////////////////////////////////////////////////////////////////////////////	

    $title2 = array('1'=>'Usuario','2'=>'Nombre','3'=>'Apellido','4'=>'');
    $grid2  = "grid_usu";
	
function uf_obtenervalor($as_valor,$as_valordefecto)
{
	//////////////////////////////////////////////////////////////////////////////
	//	     Function: uf_obtenervalor
	//		   Access: public
	//	    Arguments: as_valor  // Variable que deseamos obtener
	//				   as_valordefecto  // Valor por defecto de la variable
	//	      Returns: valor contenido de la variable
	//	  Description: Función que obtiene el valor de una variable que viene de un submit y si no trae valor coloca el
	//				   por defecto 
	//	   Creado Por: Ing. Yesenia Moreno/ Ing. Luis Lang
	// Fecha Creación: 01/02/2007 								Fecha Última Modificación : 
	//////////////////////////////////////////////////////////////////////////////
	$valor="";
	if(array_key_exists($as_valor,$_POST))
	{
		$valor=$_POST[$as_valor];
	}
	if(trim($valor)=="")
	{
		$valor=$as_valordefecto;
	}
	return $valor; 
}// end function uf_obtenervalor
	
	if($ls_operacion=="RANGO")
	{
		$li_desde=intval($ls_desde);
		$li_hasta=intval($ls_hasta);	
		$li_x=0;
		for($li_i=$li_desde;$li_i<=$li_hasta;$li_i++)
		{
			$li_x=$li_x+1;
			$ls_numcheque=$io_fun->uf_cerosizquierda(strval($li_i),15);
		   //Object que contiene los objetos y valores	iniciales del grid.	
		   $object[$li_x][1] = "<input type=text name=txtnumrefche".$li_x."   value='".$ls_numcheque."' id=txtnumrefche".$li_x."   class=sin-borde style=text-align:center size=30 maxlength=15 onKeyUp=javascript:ue_validarnumero(this); onBlur=javascript:rellenar_cad(this.value,15,this);>";
	       $object[$li_x][2] = "<input type=text name=txtcodusuche".$li_x."   value=''                  id=txtcodusuche".$li_x."   class=sin-borde style=text-align:left   size=35 maxlength=30 readonly>";
		   $object[$li_x][3] = "<input name=chk".$li_x." type=checkbox id=chk".$li_x." value=1 class=sin-borde  onClick='return false;'>";
		   $object[$li_x][4] = "<a href=javascript:uf_delete_dt('".$li_x."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Cheque width=15 height=15 border=0></a>";
			
		}
		$li_totrowche = $li_x; 
		
		 $object_usu[1][1] = "<input type=text name=txtcodusu1 value='' id=txtcodusu1  class=sin-borde style=text-align:left size=35 maxlength=30  readonly>";		
		 $object_usu[1][2] = "<input type=text name=txtnomusu1 value='' id=txtnomusu1  class=sin-borde style=text-align:left size=30 maxlength=100 readonly>";
		 $object_usu[1][3] = "<input type=text name=txtapeusu1 value='' id=txtapeusu1  class=sin-borde style=text-align:left size=26 maxlength=50  readonly>";
		 $object_usu[1][4] = "<a href=javascript:uf_delete_dt_usu('1');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Usuario width=15 height=15 border=0></a>";
		 $li_totrowusu = 1;
	
	}
	
		
	if($ls_operacion == "GUARDAR")
	{
		$li_totrowche = $_POST["total"];
			 $lb_existe    = $io_chequera->uf_select_chequera($ls_empresa,$ls_codban,$ls_ctaban,$ls_chequera);
			 if ($lb_existe)
				{ 
				  if ($ls_status=="N")//Nuevo
					 {
					   $msg->message("Este Número de Chequera ya está Registrado para este Banco y Cuenta Bancaria !!!");  
					   $lb_valido = false;
					   $ls_operacion = "PINTAR";
					 }
				  elseif($ls_status=="G")//Grabado
					 { 
					   $lb_valido = true;
					   for ($li_i=1;($li_i<=$li_totrowche) && $lb_valido;$li_i++)
						   {
							 $ls_numche = $_POST["txtnumrefche".$li_i];
							 $ls_codusu = $_POST["txtcodusuche".$li_i];	
							 $lb_valido = $io_chequera->uf_update_chequera($ls_empresa,$ls_codban,$ls_ctaban,$ls_chequera,$ls_numche,$ls_codusu,$li_i);
						   }
						if ($lb_valido)
						   {
							 $io_chequera->io_sql->commit();
							 $msg->message("Registro Actualizado !!!");
							 $ls_status    = "N";
							 $ls_operacion = "NUEVO";
						   }
						else
						   {
							 $io_chequera->io_sql->rollback();
							 $msg->message("Error en Actualización !!!");
							 $ls_operacion = "PINTAR";
						   }
					 }  
				}
			 else
				{
				  $lb_existe = true;
				  for ($li_i=1;$li_i<=$li_totrowche;$li_i++)
					  {
						$ls_numche = $_POST["txtnumrefche".$li_i];
						$lb_existe = $io_chequera->uf_validar_cheque($ls_empresa,$ls_codban,$ls_ctaban,$ls_numche);
						if ($lb_existe)
						   {
							 break;
						   }
					  }
				  if (!$lb_existe)
					 {
						$lb_valido = true;
						for ($li_x=1;($li_x<=$li_totrowche) && $lb_valido;$li_x++)
							{
							  $ls_numche = $_POST["txtnumrefche".$li_x];
							  $ls_codusu = $_POST["txtcodusuche".$li_x];
							  $lb_valido = $io_chequera->uf_guardar_cheques($ls_empresa,$ls_codban,$ls_ctaban,$ls_tipcta,$ls_chequera,$ls_numche,$ls_codusu,0,$li_x);
							}
						if ($lb_valido)
						   {
							 $io_chequera->io_sql->commit();
							 $msg->message("Registro Incluido !!!");
							 $ls_status    = "N";
							 $ls_operacion = "NUEVO";
						   }
						else
						   {
							 $io_chequera->io_sql->rollback();
							 $msg->message("Error en Inclusión !!!");
							 $ls_operacion = "PINTAR";
						   }
					 }
				  else
					 {
					   $ls_operacion = "PINTAR";
					   $msg->message("Existen Números de Cheques Repetidos para esta Cuenta Bancaria, Por Favor Verifique !!!");
					 }
				}	
	}
	
	if($ls_operacion=="DELETE_DT")
	{
		require_once("sigesp_scb_c_chequera.php");
		$in_classchequera=new sigesp_scb_c_chequera($la_security);
		$li_fila_delete=$_POST["fila_delete"];
		$ls_cheque=$_POST["txtnumrefche".$li_fila_delete];

		if(array_key_exists("chk".$li_fila_delete,$_POST))
		{
			$msg->message("No puede eliminar el cheque, ya esta asociado a un pago");
		}
		else
		{
			$lb_valido=$in_classchequera->uf_delete_cheques($ls_chequera,$ls_codban,$ls_ctaban,$ls_cheque);
			if($lb_valido)
			{
				$msg->message("Cheque eliminado satisfactoriamente");
			}
			else
			{
				$msg->message("No puede eliminar el cheque,".$in_classchequera->is_msg_error);
			}
		}
		$ls_operacion='CARGAR';
	}
	
	if($ls_operacion=="DELETE_ALL")
	{
		require_once("sigesp_scb_c_chequera.php");
		$in_classchequera=new sigesp_scb_c_chequera($la_security);
		$li_total=$_POST["total"];
		for($li_x=1;$li_x<=$li_total;$li_x++)
		{
			$ls_cheque=$_POST["txtnumrefche".$li_x];
			if(array_key_exists("chk".$li_x,$_POST))
			{
				$msg->message("No puede eliminar el cheque ".$ls_cheque.", ya esta asociado a un pago");
			}
			else
			{
				$lb_valido = $in_classchequera->uf_delete_cheques($ls_chequera,$ls_codban,$ls_ctaban,$ls_cheque);
				 if (!$lb_valido)
				   {
					 break;
				   }
			}
		}
		$ls_operacion="NUEVO";
		
	}
	if($ls_operacion == "AGREGAR_CTAS")
	{
		$total=1;
		$ls_status="N";
		$i=1;
		$li_totrowche = $_POST["total"];
	    for ($li_i=1;$li_i<=$li_totrowche;$li_i++)
			 {
			   $ls_numrefche = $_POST["txtnumrefche".$li_i];
			   $ls_estche    = uf_obtenervalor("chk".$li_i,0);
			   $ls_codusuche = $_POST["txtcodusuche".$li_i];
			   //Object que contiene los objetos y valores	iniciales del grid.	
			   $object[$li_i][1] = "<input type=text name=txtnumrefche".$li_i."   value='".$ls_numrefche."' id=txtnumrefche".$li_i."  class=sin-borde style=text-align:center size=30 maxlength=15 onKeyUp=javascript:ue_validarnumero(this); onBlur=javascript:rellenar_cad(this.value,15,this);>";
			   $object[$li_i][2] = "<input type=text name=txtcodusuche".$li_i."   value='".$ls_codusuche."' id=txtcodusuche".$li_i."  class=sin-borde style=text-align:left   size=35 maxlength=30 readonly>";
			   if ($ls_estche=='0')
				  {
					$object[$li_i][3] = "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=1 class=sin-borde  onClick='return false;'>";				 
				  }
			   elseif($ls_estche=='1')
				  {
					$object[$li_i][3] = "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=1 class=sin-borde  onClick='return false;' checked>";
				  }elseif($ls_estche=='on')
				  {
					$object[$li_i][3] = "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=1 class=sin-borde  onClick='return false;' checked>";
				  }
 				  $object[$li_i][4] = "<a href=javascript:uf_delete_dt('".$li_i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Cheque width=15 height=15 border=0></a>";
			 }
		   $object[$li_i][1] = "<input type=text     name=txtnumrefche".$li_i." value='' id=txtnumrefche".$li_i."  class=sin-borde style=text-align:center size=30 maxlength=15 onKeyUp=javascript:ue_validarnumero(this); onBlur=javascript:rellenar_cad(this.value,15,this);>";
		   $object[$li_i][2] = "<input type=text     name=txtcodusuche".$li_i." value='' id=txtcodusuche".$li_i."  class=sin-borde style=text-align:left   size=35 maxlength=30 readonly>";
		   $object[$li_i][3] = "<input type=checkbox name=chk".$li_i."          value=1  id=chk".$li_i."           class=sin-borde onClick='return false;'>";
		   $object[$li_i][4] = "<a href=javascript:uf_delete_dt('".$li_i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Cheque width=15 height=15 border=0></a>";
		   $li_totrowche++;
		
	    $li_totrowusu = $_POST["totrows"];
        for ($li_i=1;$li_i<$li_totrowusu;$li_i++)
           {
             $ls_codusu 		   = trim($_POST["txtcodusu".$li_i]);
			 $ls_nomusu 		   = $_POST["txtnomusu".$li_i];
			 $ls_apeusu 		   = $_POST["txtapeusu".$li_i];    
			 $object_usu[$li_i][1] = "<input type=text name=txtcodusu".$li_i." value='".$ls_codusu."' id=txtcodusu".$li_i." class=sin-borde style=text-align:left size=35 maxlength=30  readonly>";		
		     $object_usu[$li_i][2] = "<input type=text name=txtnomusu".$li_i." value='".$ls_nomusu."' id=txtnomusu".$li_i." class=sin-borde style=text-align:left size=30 maxlength=100 readonly>";
		     $object_usu[$li_i][3] = "<input type=text name=txtapeusu".$li_i." value='".$ls_apeusu."' id=txtapeusu".$li_i." class=sin-borde style=text-align:left size=26 maxlength=50  readonly>";
		     $object_usu[$li_i][4] = "<a href=javascript:uf_delete_dt_usu('".$li_i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Usuario width=15 height=15 border=0></a>";
		   }		  
    	$object_usu[$li_totrowusu][1] = "<input type=text name=txtcodusu".$li_totrowusu." value='' id=txtcodusu".$li_totrowusu."  class=sin-borde style=text-align:left size=35 maxlength=30  readonly>";		
		$object_usu[$li_totrowusu][2] = "<input type=text name=txtnomusu".$li_totrowusu." value='' id=txtnomusu".$li_totrowusu."  class=sin-borde style=text-align:left size=30 maxlength=100 readonly>";
		$object_usu[$li_totrowusu][3] = "<input type=text name=txtapeusu".$li_totrowusu." value='' id=txtapeusu".$li_totrowusu."  class=sin-borde style=text-align:left size=26 maxlength=50  readonly>";
		$object_usu[$li_totrowusu][4] = "<a href=javascript:uf_delete_dt_usu('".$li_totrowusu."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Usuario width=15 height=15 border=0></a>";

	}	
	
	if($ls_operacion == "NUEVO")
	{
		$ls_chequera   = "";
		$ls_tipcta  = "";
		$ls_dentipcta  = "";
		$ls_codban   = "";
		$ls_denban   = "";
		$ls_ctaban = "";
		$ls_dencuenta_banco="";
		$ls_ctaban = "";
		$ls_denctaban="";
		$ls_numcheque= "";
		$ls_desde= "";
		$ls_hasta= "";
		$li_status = 0;
		$checked="";
		$readonly="";
		$li_totrowche = 1;
		$ls_status="N";
		$li_lastrow=0;
		$li_totrowusu = 1;
		$i=1;
		//Object que contiene los objetos y valores	iniciales del grid.	
		$object[1][1] = "<input type=text name=txtnumrefche1  value='' id=txtnumrefche1  class=sin-borde style=text-align:center size=30 maxlength=15 onKeyUp=javascript:ue_validarnumero(this); onBlur=javascript:rellenar_cad(this.value,15,this);>";
	    $object[1][2] = "<input type=text name=txtcodusuche1  value='' id=txtcodusuche1  class=sin-borde style=text-align:left   size=35 maxlength=30 readonly>";
		$object[1][3] = "<input name=chk1 type=checkbox       value=1  id=chk1           class=sin-borde onClick='return false;'>";
		$object[1][4] = "<a href=javascript:uf_delete_dt('1');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Cheque width=15 height=15 border=0></a>";
		
	    $object_usu[1][1] = "<input type=text name=txtcodusu1 value='' id=txtcodusu1  class=sin-borde style=text-align:left size=35 maxlength=30  readonly>";		
		$object_usu[1][2] = "<input type=text name=txtnomusu1 value='' id=txtnomusu1  class=sin-borde style=text-align:left size=30 maxlength=30  readonly>";
		$object_usu[1][3] = "<input type=text name=txtapeusu1 value='' id=txtapeusu1  class=sin-borde style=text-align:left size=26 maxlength=35  readonly>";
		$object_usu[1][4] = "<a href=javascript:uf_delete_dt_usu('1');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Usuario width=15 height=15 border=0></a>";

		
	}
	if($ls_operacion=="CARGAR")
	{
		require_once("sigesp_scb_c_chequera.php");
		$in_classchequera=new sigesp_scb_c_chequera($la_security);
		$in_classchequera->uf_cargar_cheques($ls_codban,$ls_ctaban,$ls_chequera,$ls_empresa,$li_totrowche,$object,$li_totrowusu,$object_usu);
	    if ($li_totrowche>=1 && $li_totrowusu>1)
	    {
		  $ls_status = 'G';//Para simular que la carga de los Cheques proviene del Catálogo.
		}
	}
	
	if ($ls_operacion=="DELETE_DT_USU")
   {             
     $li_totrowche = $_POST["total"];
	 for ($li_i=1;$li_i<=$li_totrowche;$li_i++)
		 { 
		   $ls_numrefche 	 = $_POST["txtnumrefche".$li_i];
		   $ls_codusuche     = $_POST["txtcodusuche".$li_i];
		   $ls_estche    	 = uf_obtenervalor("chk".$li_i,0);
		   $object[$li_i][1] = "<input type=text name=txtnumrefche".$li_i."   value='".$ls_numrefche."' id=txtnumrefche".$li_i."   class=sin-borde style=text-align:center size=50 maxlength=15 onKeyUp=javascript:ue_validarnumero(this); onBlur=javascript:rellenar_cad(this.value,15,this);>";
	       $object[$li_i][2] = "<input type=text name=txtcodusuche".$li_i."   value='".$ls_codusuche."' id=txtcodusuche".$li_i."   class=sin-borde style=text-align:left   size=35 maxlength=30 readonly>";
		   if ($ls_estche=='0')
			  {
			    $object[$li_i][3] = "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=1 class=sin-borde  onClick='return false;'>";				 
			  }
		   elseif($ls_estche=='1')
			  {
			    $object[$li_i][3] = "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=1 class=sin-borde  onClick='return false;' checked>";
			  }elseif($ls_estche=='on')
			  {
				$object[$li_i][3] = "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=1 class=sin-borde  onClick='return false;' checked>";
			  }
		   $object[$li_i][4] = "<a href=javascript:uf_delete_dt('".$li_i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Cheque width=15 height=15 border=0></a>";
		 }
 
	 $li_totrowusu = $_POST["totrows"]-1;
     $li_rowdel    = $_POST["filadel"];
     $li_temp      = 0;                
     for ($li_i=1;$li_i<=$li_totrowusu;$li_i++)
         {
	       if ($li_i!=$li_rowdel)
	          {		
		        $li_temp++;
			    $ls_codusu = $_POST["txtcodusu".$li_i];
	            $ls_nomusu = $_POST["txtnomusu".$li_i];      
                $ls_apeusu = $_POST["txtapeusu".$li_i];      
			    
				$object_usu[$li_temp][1] = "<input type=text name=txtcodusu".$li_temp." value='".$ls_codusu."' id=txtcodusu".$li_temp."  class=sin-borde style=text-align:left size=35 maxlength=30  readonly>";		
		        $object_usu[$li_temp][2] = "<input type=text name=txtnomusu".$li_temp." value='".$ls_nomusu."' id=txtnomusu".$li_temp."  class=sin-borde style=text-align:left size=30 maxlength=100 readonly>";
		        $object_usu[$li_temp][3] = "<input type=text name=txtapeusu".$li_temp." value='".$ls_apeusu."' id=txtapeusu".$li_temp."  class=sin-borde style=text-align:left size=26 maxlength=50  readonly>";
		        $object_usu[$li_temp][4] = "<a href=javascript:uf_delete_dt_usu('".$li_temp."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Usuario width=15 height=15 border=0></a>";
			  }
	       else
	          {	
		        $li_rowdelete=0;
	          }
         }

	    $li_temp++;
		$object_usu[$li_temp][1]="<input type=text name=txtcodusu".$li_temp." value='' id=txtcodusu".$li_temp." class=sin-borde style=text-align:left size=35 maxlength=30  readonly>";		
		$object_usu[$li_temp][2]="<input type=text name=txtnomusu".$li_temp." value='' id=txtnomusu".$li_temp." class=sin-borde style=text-align:left size=30 maxlength=100 readonly>";
		$object_usu[$li_temp][3]="<input type=text name=txtapeusu".$li_temp." value='' id=txtapeusu".$li_temp." class=sin-borde style=text-align:left size=26 maxlength=50  readonly>";
		$object_usu[$li_temp][4] ="<a href=javascript:uf_delete_dt_usu('".$li_temp."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Usuario width=15 height=15 border=0></a>";
    }

if ($ls_operacion=="PINTAR")
   { 	
		$li_totrowche = $_POST["total"];
		for ($li_i=1;$li_i<=$li_totrowche;$li_i++)
		    {
			  $ls_numrefche = $_POST["txtnumrefche".$li_i];
			  $ls_estche    = uf_obtenervalor("chk".$li_i,0);
			  $ls_codusuche = $_POST["txtcodusuche".$li_i];
			  //Object que contiene los objetos y valores	iniciales del grid.	
			  $object[$li_i][1] = "<input type=text name=txtnumrefche".$li_i."   value='".$ls_numrefche."' id=txtnumrefche".$li_i."  class=sin-borde style=text-align:center size=30 maxlength=15 onKeyUp=javascript:ue_validarnumero(this); onBlur=javascript:rellenar_cad(this.value,15,this);>";
	          $object[$li_i][2] = "<input type=text name=txtcodusuche".$li_i."   value='".$ls_codusuche."' id=txtcodusuche".$li_i."  class=sin-borde style=text-align:left   size=35 maxlength=30 readonly>";
			  if ($ls_estche=='0')
			     {
				   $object[$li_i][3] = "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=1 class=sin-borde  onClick='return false;'>";				 
				 }
			  elseif($ls_estche=='1')
			     {
				   $object[$li_i][3] = "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=1 class=sin-borde  onClick='return false;' checked>";
				 }
			  elseif($ls_estche=='on')
			     {
				   $object[$li_i][3] = "<input name=chk".$li_i." type=checkbox id=chk".$li_i." value=1 class=sin-borde  onClick='return false;' checked>";
				 }
				 
			  $object[$li_i][4] = "<a href=javascript:uf_delete_dt('".$li_i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Cheque width=15 height=15 border=0></a>";
		    }

	   $li_totrowusu = $_POST["totrows"];
       for ($li_i=1;$li_i<$li_totrowusu;$li_i++)
           {
             $ls_codusu 		   = trim($_POST["txtcodusu".$li_i]);
			 $ls_nomusu 		   = $_POST["txtnomusu".$li_i];
			 $ls_apeusu 		   = $_POST["txtapeusu".$li_i];    
			 $object_usu[$li_i][1] = "<input type=text name=txtcodusu".$li_i." value='".$ls_codusu."' id=txtcodusu".$li_i." class=sin-borde style=text-align:left size=35 maxlength=30  readonly>";		
		     $object_usu[$li_i][2] = "<input type=text name=txtnomusu".$li_i." value='".$ls_nomusu."' id=txtnomusu".$li_i." class=sin-borde style=text-align:left size=30 maxlength=100 readonly>";
		     $object_usu[$li_i][3] = "<input type=text name=txtapeusu".$li_i." value='".$ls_apeusu."' id=txtapeusu".$li_i." class=sin-borde style=text-align:left size=26 maxlength=50  readonly>";
		     $object_usu[$li_i][4] = "<a href=javascript:uf_delete_dt_usu('".$li_i."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Usuario width=15 height=15 border=0></a>";
		   }		  
    	$object_usu[$li_totrowusu][1] = "<input type=text name=txtcodusu".$li_totrowusu." value='' id=txtcodusu".$li_totrowusu."  class=sin-borde style=text-align:left size=35 maxlength=30  readonly>";		
		$object_usu[$li_totrowusu][2] = "<input type=text name=txtnomusu".$li_totrowusu." value='' id=txtnomusu".$li_totrowusu."  class=sin-borde style=text-align:left size=30 maxlength=100 readonly>";
		$object_usu[$li_totrowusu][3] = "<input type=text name=txtapeusu".$li_totrowusu." value='' id=txtapeusu".$li_totrowusu."  class=sin-borde style=text-align:left size=26 maxlength=50  readonly>";
		$object_usu[$li_totrowusu][4] = "<a href=javascript:uf_delete_dt_usu('".$li_totrowusu."');><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar Usuario width=15 height=15 border=0></a>";
	}
	
	
?>
<p>&nbsp;</p>
<div align="center">
  <table width="601" height="223" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="601" height="221" valign="top"><form name="form1" method="post" action="">
		<?php 
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
if (($ls_permisos)||($ls_logusr=="PSEGIS"))
{
	print("<input type=hidden name=permisos id=permisos value='$ls_permisos'>");
	print("<input type=hidden name=leer     id=permisos value='$la_accesos[leer]'>");
	print("<input type=hidden name=incluir  id=permisos value='$la_accesos[incluir]'>");
	print("<input type=hidden name=cambiar  id=permisos value='$la_accesos[cambiar]'>");
	print("<input type=hidden name=eliminar id=permisos value='$la_accesos[eliminar]'>");
	print("<input type=hidden name=imprimir id=permisos value='$la_accesos[imprimir]'>");
	print("<input type=hidden name=anular   id=permisos value='$la_accesos[anular]'>");
	print("<input type=hidden name=ejecutar id=permisos value='$la_accesos[ejecutar]'>");
}
else
{
	
	print("<script language=JavaScript>");
	print(" location.href='sigespwindow_blank.php'");
	print("</script>");
}
		//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
        ?>
          <p>&nbsp;</p>
          <table width="574" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="4">Chequera</td>
              </tr>
              <tr class="formato-blanco">
                <td height="18">&nbsp;</td>
                <td colspan="3">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td width="80" height="22"><div align="right" >
                    <p>Numero Chequera </p>
                </div></td>
                <td colspan="3"><div align="left" >
                    <input name="txtchequera" type="text" id="txtchequera" style="text-align:center " value="<?php print $ls_chequera?>" size="22" maxlength="10" onBlur="javascript:rellenar_cad(this.value,10,this)" <?php print $readonly ?> >
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Banco</div></td>
                <td colspan="3"><div align="left">
                  <input name="txtcodban" type="text" id="txtcodban"  style="text-align:center" value="<?php print $ls_codban;?>" size="10" readonly>
                  <a href="javascript:cat_bancos();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Bancos"></a>
                  <input name="txtdenban" type="text" id="txtdenban" value="<?php print $ls_denban?>" size="51" class="sin-borde" readonly>

                </div></td>
              </tr>
			  
              <tr class="formato-blanco">
                <td height="22"><div align="right">Cuenta Bancaria </div></td>
                <td height="18" colspan="3" align="left"><div align="left">
                  <input name="txtcuenta" type="text" id="txtcuenta" style="text-align:center" value="<?php print $ls_ctaban; ?>" size="30" maxlength="25" readonly>
                  <a href="javascript:catalogo_cuentabanco();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0" alt="Catálogo de Cuentas Bancarias"></a>
                  <input name="txtdenominacion" type="text" class="sin-borde" id="txtdenominacion" style="text-align:left" value="<?php print $ls_denctaban; ?>" size="50" maxlength="254" readonly>

                  <input name="txtcuenta_scg" type="hidden" id="txtcuenta_scg">
                  <input name="txtdisponible" type="hidden" id="txtdisponible">
				  <input name="txtcfg_ch" type="hidden" id="txtcfg_ch" value="<?php print $_SESSION["la_empresa"]["confi_ch"];?>">
</div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Tipo Cuenta</div></td>
                <td height="18" colspan="3" align="left"><div align="left">
                  <input name="txttipocuenta" type="text" id="txttipocuenta2" style="text-align:center" value="<?php print $ls_tipcta;?>" size="10" readonly>
                  <input name="txtdentipocuenta" type="text" id="txtdentipocuenta2" value="<?php print $ls_dentipcta;?>" class="sin-borde" size="51" readonly>

                </div></td>
              </tr>
            <tr class="formato-blanco">
              <td height="22">&nbsp;</td>
              <td colspan="2">&nbsp;</td>
              <td width="210">&nbsp;</td>
            </tr>
            <tr class="titulo-ventana">
              <td height="13" colspan="4" class="titulo-ventana">Cheques asociados a la Chequera </td>
            </tr>
            <tr class="formato-blanco">
              <td height="20">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="20"><div align="right">Desde</div></td>
              <td width="224"><div align="left">
                <input name="txtdesde" type="text" id="txtdesde" onBlur="javascript:rellenar_cad(this.value,15,this)">
              </div></td>
              <td width="58"><div align="right">Hasta</div></td>
              <td><div align="left">
                <input name="txthasta" type="text" id="txthasta" onBlur="javascript:rellenar_cad(this.value,15,this)">
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="20">&nbsp;</td>
              <td height="20" colspan="3"><div align="left"><a href="javascript: uf_agregar_cheques();"><img src="../../shared/imagebank/tools20/nuevo.gif" width="20" height="20" border="0">Agregar Cheques</a>  <a href="javascript: uf_generar_cheques();"><img src="../../shared/imagebank/tools20/ejecutar.gif" width="20" height="20" border="0">Generar Cheques</a><a href="javascript:uf_delete_all();"><img src="../../shared/imagebank/tools20/deshacer.gif" alt="Borrar todas las cuentas" width="20" height="20" border="0"></a><a href="javascript:uf_delete_all();">Borrar Todas</a> </div></td>
            </tr>
            
            <tr class="formato-blanco">
              <td height="20" colspan="4">
                <div align="center">
                  <?php //$io_grid->makegrid($total,$title,$object,580,'Cheques',$grid1);?>
				  <?php $io_grid->makegrid($li_totrowche,$title,$object,580,'Cheques',$grid1);?>
                  <input name="total" type="hidden" id="total" value="<?php print $li_totrowche?>">
                  <input name="status" type="hidden" id="status" value="<?php print $ls_status;?>">
                  <input name="fila_delete" type="hidden" id="fila_delete">
                </div></td>
            </tr>
            
            <tr class="formato-blanco">
              <td height="20">&nbsp;</td>
              <td colspan="3">&nbsp;</td>
            </tr>
            <tr class="titulo-ventana">
              <td height="13" colspan="4" class="titulo-ventana">Usuarios asociados a la Chequera </td>
            </tr>
            <tr>
              <td height="20">&nbsp;</td>
              <td colspan="3"><a href="javascript: catalogo_usuarios();"><img src="../../shared/imagebank/tools20/nuevo.gif" alt="Agregar Usuarios" width="20" height="20" border="0">Agregar Usuarios</a>
                  <input name="totrows" type="hidden" id="totrows" value="<?php print $li_totrowusu ?>">
                  <input name="lastrow"  type="hidden"   id="lastrow" value="<?php print $li_lastrow;?>">
                  <input   name="filadel"  type="hidden"   id="filadel">
                <a href="javascript:uf_asignar_cheques();"><img src="../../shared/imagebank/tools20/presupuestaria.gif" alt="Asignar Cheques..." width="20" height="20" border="0">Asignar Cheques</a></td>
            <tr>
              <td height="20" colspan="4"><div align="center">
                  <?php $io_grid->makegrid($li_totrowusu,$title2,$object_usu,100,'Usuarios',$grid2);?>
                </div>
                  <div align="center"></div>
                <div align="center"></div>
                <div align="center"></div></td>
            </tr>
            <tr>
              <td height="20">&nbsp;</td>
              <td colspan="3">&nbsp;</td>
            </tr>
            <tr class="formato-blanco">
              <td height="20">&nbsp;</td>
              <td colspan="3">&nbsp;</td>
            </tr>
          </table>
            <p align="center">
            <input name="operacion" type="hidden" id="operacion">
</p>
        </form></td>
      </tr>
  </table>
</div>
</body>
<script language="javascript">

function ue_nuevo()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	if (li_incluir==1)
	   {	
         f.operacion.value ="NUEVO";
         f.action="sigesp_scb_d_chequera.php";
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
	li_incluir = f.incluir.value;
	li_cambiar = f.cambiar.value;
	lb_status  = f.status.value;
	if (((lb_status=="G")&&(li_cambiar==1))||(lb_status=="N")&&(li_incluir==1))
	   {
		  with (document.form1)
		       {
		         if (valida_null(txtchequera,"El Número de la Chequera está vacío !!!")==false)
		            {
			          txtchequera.focus();
		            }
		         else
		            {
		              if (valida_null(txtcodban,"El Código del Banco está vacío !!!")==false)
		                 {
			               txtcodban.focus();
		                 }
					  else
					     {
		   		           if (valida_null(txtcuenta,"La Cuenta Bancaria está vacía !!!")==false)
		                      {
			          		    txtcuenta.focus();
		                      }
						   else
						      {
							    li_totrowche = f.total.value;
								if (li_totrowche>1)
								   {
								     li_totrowusu = f.totrows.value;
									 ls_config_ch=txtcfg_ch.value;
								     if (li_totrowusu>1)
									    {
										  if (ls_config_ch==0)
										  {
										    lb_valido=true;
										  }
										  else
										  {
										    lb_valido = uf_evaluar_asignacion();
										  }
										  if (lb_valido)
										     {
											   f.operacion.value ="GUARDAR";
											   f.action="sigesp_scb_d_chequera.php";
											   f.submit();
											 }
										  else
										     {
											   alert("Existen Usuarios sin asignación de Cheques !!!");
											 }
										}
								     else
									    {
										  alert("Debe existir al menos un Usuario asociado a la Chequera !!!");
										}
								   }
								else
								   {
								     alert("Debe generar al menos un Cheque !!!");
								   }
							  }
						 }
					}
		       } 
       }
    else
	   {
 	     alert("No tiene permiso para realizar esta operación !!!");
	   }
}

function ue_eliminar()
{
f=document.form1;
li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     if (confirm("¿ Está seguro de eliminar este registro ?"))
		{
	      ls_chequera=f.txtchequera.value;
	      ls_codban=f.txtcodban.value;
	      ls_ctaban=f.txtcuenta.value;
 	      if ((ls_chequera!="")&&(ls_codban!="")&&(ls_ctaban!=""))
	         {
		       f.operacion.value ="DELETE_ALL";
	 	       f.action="sigesp_scb_d_chequera.php";
		       f.submit();
	         }
	      else
	         {
		       alert("No ha completado los datos");
	         }
        }
     else
	    {
	      alert("Eliminación Cancelada !!!");
	    }
   }  
 else
   {
     alert("No tiene permiso para realizar esta operación");
   }
}

function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
       {
	     window.open("sigesp_scb_cat_cheques.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=620,height=400,left=50,top=50,location=no,resizable=yes");
       }
	else
	   {
		 alert("No tiene permiso para realizar esta operacion");
	   }   
}

function ue_cerrar()
{
	f=document.form1;
	f.action="sigespwindow_blank.php";
	f.submit();
}

    //Funciones de validacion de fecha.
	function rellenar_cad(cadena,longitud,obj)
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
		obj.value=cadena;
	}
	
	//Catalogo de cuentas contables
	function catalogo_cuentabanco()
	 {
	   f=document.form1;
	   ls_codban=f.txtcodban.value;
	   ls_denban=f.txtdenban.value;
	   if((!f.txtchequera.readOnly))
	   {
		   pagina="sigesp_cat_ctabanco.php?codigo="+ls_codban+"&denban="+ls_denban;
		   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	   }
	   else
	   {
	   		alert("No puede Editar la Cuenta, forma parte de la clave primaria ");
	   }	   
	 }
	 
	 function cat_tipo_cuenta()
	 {
	   f=document.form1;
	   pagina="sigesp_cat_tipoctas.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	 }
	 
	 function cat_bancos()
	 {
	   f=document.form1;
	    if((!f.txtchequera.readOnly))
	   {
	   pagina="sigesp_cat_bancos.php";
	   window.open(pagina,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=516,height=400,resizable=yes,location=no");
	   }
	   else
	   {
	   	alert("No puede editar el banco, forma parte de la clave primaria ");
	   
	   }
	 }  
	 
	function  uf_generar_cheques()
	{
	    f=document.form1;
		ls_desde	  = f.txtdesde.value;
		ls_hasta	  = f.txthasta.value;
		li_desde	  = parseInt(ls_desde,10);
		li_hasta	  = parseInt(ls_hasta,10);
		li_diferencia = li_hasta-li_desde;
		ls_status	  = f.status.value;
		ls_chequera   = f.txtchequera.value;
		if (ls_chequera!='')
		   {
			 ls_codban = f.txtcodban.value;
			 if (ls_codban!='')
				{
				  ls_ctaban = f.txtcuenta.value;
				  if (ls_ctaban!='')
					 {
					   if (ls_status=='N')
						  {
							if ((li_diferencia>0)&&(li_diferencia<=49))
							   {
								 if ((ls_desde=!"")&&(ls_hasta!=""))
									{
									  f.operacion.value="RANGO";
									  f.action="sigesp_scb_d_chequera.php";
									  f.submit();							      
									  window.open("sigesp_cat_usuarios.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
									}
							   }
							else
							   {
								 alert("No se pueden generar cheques en el rango seleccionado !!!");
							   }
						  }
					   else
						  {
							alert("Seleccione la opción \"Agregar Cheques\" para poder realizar esta operación");
						  }
					 }
				  else
					 { 
					   alert("La Cuenta Bancaria está vacía !!!");
					 }
				}
			 else
				{
				  alert("El Código del Banco está vacío !!!");
				}
		   }
		else
		   {
			 alert("El Número de la Chequera está vacío !!!");
		   } 
	}

	function valida_null(field,mensaje)
	{
	  with (field) 
	  {
		if (value==null||value=="")
		  {
			alert(mensaje);
			return false;
		  }
		else
		  {
			return true;
		  }
	  }
	}

	function uf_agregar_cheques()
	{
		f=document.form1;
		f.operacion.value='AGREGAR_CTAS';
		f.status.value='N';
		f.actio='sigesp_scb_d_chequera.php';
		f.submit();	
	}	
////////////////////////////////////////////////
function uf_agregar_usuarios()
	{
		f=document.form1;
		f.operacion.value='AGREGAR_USU';
		f.status.value='N';
		f.actio='sigesp_scb_d_chequera.php';
		f.submit();	
	}	

/////////////////////////////////////////////////
	  
	function uf_delete_dt(li_i)  
	{
		f=document.form1;
		f.fila_delete.value=li_i;
		f.operacion.value="DELETE_DT";
		f.action="sigesp_scb_d_chequera.php";
		f.submit();
	}
	function uf_delete_all()  
	{
		f=document.form1;
		f.operacion.value="DELETE_ALL";
		f.action="sigesp_scb_d_chequera.php";
		f.submit();
	}
	
function catalogo_usuarios()
{
    f=document.form1;
	f.operacion.value="";			
	li_totrowche = f.total.value;
	if (li_totrowche>=2)
	   {
	     window.open("sigesp_cat_usuarios.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	   }
	else
	   {
	     alert("Deben Existir Cheques Generados !!!");
	   }	
}




function uf_asignar_cheques()
{
  f=document.form1;
  li_totrowche = f.total.value;
  ls_chequera   = f.txtchequera.value;
  ls_codban = f.txtcodban.value;
  ls_ctaban = f.txtcuenta.value;
  if (li_totrowche>1)
     {
	   li_totrowusu = f.totrows.value;
	   if (li_totrowusu>1)
	      {
		    if (li_totrowusu==2)
		       {
			     ls_codusu    = eval("f.txtcodusu1.value");
			     li_totrowche = f.total.value;
			     for (li_i=1;li_i<=li_totrowche;li_i++)
				     {
					   eval("f.txtcodusuche"+li_i+".value=ls_codusu");
				     }
		       }
	        else
		       {
		         if (li_totrowusu>2)
			        {
					  la_cheques = la_usuasiche = la_checked = "";
					  for (li_z=1;li_z<=li_totrowche;li_z++)
					      {
						    ls_numche = eval("f.txtnumrefche"+li_z+".value");
							ls_codusu = eval("f.txtcodusuche"+li_z+".value");
							if (eval("f.chk"+li_z+".checked==true"))
							   {
							     lb_checked = true;
							   }
							else
							   {
							     lb_checked = false;
							   }
							if (la_cheques=='')
							   {
							     la_cheques   = ls_numche;
								 la_usuasiche = ls_codusu;
								 la_checked   = lb_checked;
							   }
							else
							   {
							     la_cheques   = la_cheques+";"+ls_numche;
								 la_usuasiche = la_usuasiche+";"+ls_codusu;
							     la_checked   = la_checked+";"+lb_checked;
							   }
						  }
					  la_usuarios = "";
					  for (li_y=1;li_y<li_totrowusu;li_y++)
					      {
							ls_codusu = eval("f.txtcodusu"+li_y+".value");
							if (la_usuarios=='')
							   {
								 la_usuarios = ls_codusu;
							   }
							else
							   {
								 la_usuarios = la_usuarios+";"+ls_codusu;
							   }
						  }
	                 if(li_totrowche<=320)
					 { 
                         window.open("sigesp_cat_asignacion_cheques.php?hidtotrowche="+li_totrowche+"&hidnumche="+la_cheques+"&hidcodusu="+la_usuarios+"&hidcheemi="+la_checked+"&hidusuasiche="+la_usuasiche+"&hidtotrowusu="+li_totrowusu+"&chequera="+ls_chequera+"&ctaban="+ls_ctaban+"&codban="+ls_codban,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=540,height=460,resizable=yes,location=no,dependent=yes");

					 }
					 else
					 {  
					     la_usuasiche = "";
					     la_checked   = "";
					     window.open("sigesp_cat_asignacion_cheques.php?hidtotrowche="+li_totrowche+"&hidcodusu="+la_usuarios+"&hidtotrowusu="+li_totrowusu+"&chequera="+ls_chequera+"&ctaban="+ls_ctaban+"&codban="+ls_codban,"_blank","menubar=no,toolbar=no,scrollbars=yes,width=540,height=460,resizable=yes,location=no,dependent=yes");
					 }

			        }
		       }	      
		  }
	   else
	      {
		    alert("Debe registrar al menos un Usuario para la asignación de Cheques !!!");
		  }
	 }
  else
     {
	   alert("Deben Existir Cheques Generados !!!");
	 }
}

function uf_evaluar_asignacion()
{
  f=document.form1;
  li_totrowche  = f.total.value; 
  li_totrowusu  = f.totrows.value;
  lb_encontrado = true;
  for (li_i=1;(li_i<=li_totrowusu) && lb_encontrado;li_i++)
      {
	    ls_codusu = eval("f.txtcodusu"+li_i+".value"); 
		if (ls_codusu!='')
		   {
			 lb_encontrado = false;
			 for (li_y=1;li_y<=li_totrowche;li_y++)
				 {
				   ls_usuario = eval("f.txtcodusuche"+li_y+".value"); 
				   if (ls_usuario==ls_codusu)
				      {
					    lb_encontrado = true;
						break;
					  }
				 }
		   }
	  }
  return lb_encontrado;
}

function ue_validarnumero(valor)
{
	val = valor.value;
	longitud = val.length;
	texto = "";
	textocompleto = "";
	for(r=0;r<=longitud;r++)
	{
		texto = valor.value.substring(r,r+1);
		if((texto=="0")||(texto=="1")||(texto=="2")||(texto=="3")||(texto=="4")||(texto=="5")||(texto=="6")||(texto=="7")||(texto=="8")||(texto=="9"))
		{
			textocompleto += texto;
		}	
	}
	valor.value=textocompleto;
}

function uf_delete_dt_usu(li_row)  
{
  f=document.form1;
  f.filadel.value = li_row;   
  li_totrowusu 	  = f.totrows.value;
  if (li_totrowusu>=2)
     {
	   lb_valido 	= true;
	   ls_codusu    = eval("f.txtcodusu"+li_row+".value");
	   li_totrowche = f.total.value;
	   for (li_i=1;li_i<=li_totrowche;li_i++)
	       {
		     ls_codusuche = eval("f.txtcodusuche"+li_i+".value");
			 if (ls_codusu==ls_codusuche)
			    {
			      lb_valido = false;
				  break;  
			    }		   
		   }
	   if (lb_valido)
	      {
		    f.operacion.value="DELETE_DT_USU";
		    f.action="sigesp_scb_d_chequera.php";
		    f.submit();
		  }
	   else
	      {
		    alert("Existen Cheques asignados para este Usuario !!!");
		  }
	 }
}
	  
</script>
</html>
