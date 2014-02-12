<?php
session_start();
if((!array_key_exists("ls_database",$_SESSION))||(!array_key_exists("ls_hostname",$_SESSION))||(!array_key_exists("ls_gestor",$_SESSION))||(!array_key_exists("ls_login",$_SESSION)))
{
	print "<script language=JavaScript>";
	print "location.href='sigesp_conexion.php'";
	print "</script>";		
}

 function uf_print_lista($as_nombre,$as_campoclave,$as_campoimprimir,$aa_lista)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function : uf_print_lista
		//		   Access : private
		//      Arguments : $as_nombre  // Nombre del Campo
		//      			$as_campoclave  // campo por medio del cual se va filtrar la lista
		//      			$as_campoimprimir  // campo que se va a mostrar
		//      			$aa_lista  // arreglo que se va a colocar en la lista
		//	  Description : Función que imprime el contenido de una caja de texto multiple
		//	   Creado Por : Ing. Luis Anibal Lang
		// Fecha Creación : 26/10/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
		if(empty($aa_lista)) 
		{
			$li_total=0;
		}
		else
		{ 
			$li_total=count($aa_lista);
		}
		print "<select name='".$as_nombre."[]' id='".$as_nombre."' size='10' style='width:300px' multiple>";
		for($li_i=0;$li_i<$li_total;$li_i++)
		{ 
			print "<option value='".$aa_lista[$li_i][$as_campoclave]."'>".$aa_lista[$li_i][$as_campoimprimir];
		} 
		print "</select>";
   }  // end function uf_print_lista
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
<html>
<head>
<title>Registro de Control de Número </title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
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

<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.Estilo1 {font-size: 14px}
.Estilo2 {
	font-size: 14;
	color: #6699CC;
}
-->
</style>
</head>
<body link="#006699" vlink="#006699" alink="#006699">
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema Estilo2">Sistema de Configuración</td>
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-negrita"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right" class="letras-negrita"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
	</table></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>
  <tr> 
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:uf_confirmacion();"><img src="../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>

<?php
require_once("class_folder/sigesp_cfg_c_ctrl_numero.php");
require_once("../shared/class_folder/class_datastore.php");
require_once("../shared/class_folder/sigesp_include.php");
require_once("../shared/class_folder/class_mensajes.php");
require_once("../shared/class_folder/class_funciones_db.php");
require_once("../shared/class_folder/class_sql.php");
require_once("../shared/class_folder/sigesp_c_check_relaciones.php");
require_once("class_folder/class_funciones_cfg.php");
$io_cfs=new class_funciones_cfg();
$io_ctrl_numero = new sigesp_cfg_c_ctrl_numero();
$io_conect       = new sigesp_include();
$con             = $io_conect-> uf_conectar ();
$la_emp          = $_SESSION["la_empresa"];
$io_msg          = new class_mensajes(); //Instanciando la clase mensajes 
$fun_db          = new class_funciones_db($con);
$io_sql          = new class_sql($con); //Instanciando  la clase sql
$io_dsest        = new class_datastore(); //Instanciando la clase datastore
$lb_valido       = "";
$io_chkrel       = new sigesp_c_check_relaciones($con);


//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CFG";
	$ls_ventanas = "sigesp_cfg_d_ctrl_numero.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
		}
		else
		{
			$ls_permisos            = $_POST["permisos"];
			$la_accesos["leer"]     = $_POST["leer"];
			$la_accesos["incluir"]  = $_POST["incluir"];
			$la_accesos["cambiar"]  = $_POST["cambiar"];
			$la_accesos["eliminar"] = $_POST["eliminar"];
			$la_accesos["imprimir"] = $_POST["imprimir"];
			$la_accesos["anular"]   = $_POST["anular"];
			$la_accesos["ejecutar"] = $_POST["ejecutar"];
		}
	}
	else
	{
		$la_accesos["leer"]     = "";
		$la_accesos["incluir"]  = "";
		$la_accesos["cambiar"]  = "";
		$la_accesos["eliminar"] = "";
		$la_accesos["imprimir"] = "";
		$la_accesos["anular"]   = "";
		$la_accesos["ejecutar"] = "";
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
 function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////
		//	      Function:  uf_limpiarvariables
		//	        Access: private
		//	   Description: Función que limpia todas las variables necesarias en la página
		//	   Creado Por : Ing. Luis Anibal Lang
		// Fecha Creación : 22/02/2006 								Fecha Última Modificación : 
		//////////////////////////////////////////////////////////////////////////////
   		global $ls_codigo,$ls_codsis,$ls_maxlon,$ls_prefijo,$ls_numini,$ls_numfin,$ls_numact,$ls_estatus;
		global $la_disponibles,$ls_operacion,$ls_read,$ls_activo,$la_asignados,$ls_numcominicero,$ls_nunasig;
		
		 $ls_codigo        = "";
		 $ls_codsis        = "";
		 $ls_maxlon        = "";
		 $ls_prefijo       = "";
		 $ls_numini        = "";
		 $ls_numfin        = "";
		 $ls_numact        = "";
		 $ls_estatus       = "NUEVO";
		 $ls_operacion="";
		 $ls_read="";
		 $ls_activo="";
		 $la_disponibles="";
		 $la_asignados="";
		 $ls_numcominicero="";
		 $ls_nunasig="";
   }  // end function uf_limpiarvariables

if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion             = $_POST["operacion"];
	 $ls_codigo                = $_POST["txtcodigo"];
	 $lr_datos["codigo"]       = $ls_codigo;
	 if(array_key_exists("cmbsis",$_POST))
	 {
	   $ls_codsis                = $_POST["cmbsis"];
	 }else 
	 {
	 	$ls_codsis                = "";
	 }
	 //$ls_codsis                = $_POST["txtcodsis"];
	 $lr_datos["codsis"]       = $ls_codsis; 
	 if(array_key_exists("txtprefi",$_POST))
	 {
	    $ls_prefijo=$_POST["txtprefi"];
	 }else 
	 {
	 	$ls_prefijo= "";
	 }
	 $lr_datos["prefijo"]      = $ls_prefijo;
	 $ls_maxlon                = $_POST["txtlong"];
	 $lr_datos["maxlon"]       = $ls_maxlon;
	 $ls_numini				   = $_POST["txtnumini"];
	 $lr_datos["numini"]       = $ls_numini;
	 $ls_numfin				   = $_POST["txtnumfin"];
	 $lr_datos["numfin"]	   = $ls_numfin;
	 $ls_numact                = $_POST["txtnumact"];
	 $lr_datos["nunact"]       = $ls_numact;
	 $ls_status                = $_POST["status"];
	 $ls_estatus			   = $_POST["status"];
	 $ls_nunasig=$_POST["total"];
   }
else
   {
	 $ls_codigo        = "";
     $ls_codsis        = "";
	 $ls_maxlon        = "";
	 $ls_prefijo       = "";
	 $ls_numini        = "";
	 $ls_numfin        = "";
	 $ls_numact        = "";
	 $ls_estatus       = "NUEVO";
	 $ls_operacion="";
	 $ls_read="";
     $ls_activo="";
     $ls_nunasig="";
	 $la_disponibles="";
	 $la_asignados="";
   }
   if (array_key_exists("chkestinicero",$_POST))
   {
     
	  $ls_numcominicero=$_POST["chkestinicero"];
	  $lr_datos["estcompscg"]=$ls_numcominicero;
	  $ls_numcominicero = 'checked';
   }
else
   {
      $ls_numcominicero ='0';
	  $lr_datos["estcompscg"]=$ls_numcominicero; 
	  $ls_numcominicero=""; 
   }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//uf_limpiarvariables();
if($ls_operacion=="NUEVO")
{
    uf_limpiarvariables();
    $ls_codigo   = $fun_db->uf_generar_codigo(false,"","sigesp_ctrl_numero","id");
}
 if ($ls_operacion=="GUARDAR")
	{ 
		/*  list($lb_existe,$ls_existe)=$io_ctrl_numero->uf_verificar_procede($ls_empresa,$ls_prefijo,$lr_datos["codsis"] ,$ls_logusr);
		   if($ls_existe=='0')
		   {*/
		    $la_estasig=$io_cfs->uf_obtenervalor("txtasignados",""); 
			$la_estdisp=$io_cfs->uf_obtenervalor("txtdisponibles","");
			$li_conasig=count($la_estasig); 
			$li_condisp=count($la_estdisp);
			$ls_codproc = $lr_datos["codsis"];
			if(!empty($la_estasig))
			{
				for($li_i=0;$li_i<$li_conasig;$li_i++)
				{ 
					 $ls_codusu=$la_estasig[$li_i];   
					 list($lb_valido,$ls_codsis)=$io_ctrl_numero->uf_buscar_campo("sigesp_procedencias","codsis"," procede='".$ls_codproc."'");
					 $lb_existe=$io_ctrl_numero->uf_select_ctrl_numero($ls_empresa,$ls_codsis,$ls_codproc,$ls_codusu);
					 if (!$lb_existe)
					 {    
						$lb_valido = $io_ctrl_numero->uf_guardar_ctrl_numero($lr_datos,$ls_codusu,$ls_codsis,$la_seguridad);
					 } 
					 else
					 { 
						$lb_valido = $io_ctrl_numero->uf_actualizar_ctrl_numero($lr_datos,$ls_codusu,$ls_codsis,$la_seguridad);
					 }
				}// fin del for
				if($lb_valido)
				{
					if(!empty($la_estdisp))
					{
						for($li_i=0;$li_i<$li_condisp;$li_i++)
						{
							$ls_codusu=$la_estdisp[$li_i];
							$lb_existe=$io_ctrl_numero->uf_buscar_usuarios_disponibles($ls_empresa,$ls_codsis,$ls_prefijo,$la_asignados);;
								if($lb_existe)
								{
									$lb_valido=$io_ctrl_numero->uf_delete_usuarioasignados($ls_empresa,$ls_codsis,$ls_codproc,$ls_prefijo,$la_asignados,$ls_codusu,$la_seguridad);
								}
						}
					}
				}
				if($lb_valido)
				{
				   $io_sql->commit();
				   $io_msg->message("El Control de Número ha sido procesado !!!");
				}
				else
				{
				   $io_sql->rollback();
				   $io_msg->message("No se pudo procesar el Control de Número !!!");
				}					 
					 
				 $ls_operacion     = "";
				 $ls_codigo        = "";
				 $ls_codsis        = "";
				 $ls_maxlon        = "";
				 $ls_prefijo       = "";
				 $ls_numini        = "";
				 $ls_numfin        = "";
				 $ls_numact        = "";
				 $ls_codsis        = "";
				 $ls_estatus       = "NUEVO";
				 $ls_read="";
				 $ls_activo="";
				 $ls_activo2="disabled";
				 $ls_numcominicero="";
				 $la_disponibles="";
				 $la_asignados="";
				 $ls_nunasig="";
			}
		/* }
		 else 
		 {
		 	 $io_msg->message("Ya Existe otro usuario con el mismo Prefijo para el mismo Documento    !!!");
		 	 $ls_read="";
     		 $ls_activo="";
		 	
		 }*/
    }
	   
if ($ls_operacion=="ELIMINAR")
{
 	list($lb_valido,$as_existe)=$io_ctrl_numero->uf_verificar_eliminacion($lr_datos["codsis"]); 
	if($as_existe=='0')
	{
		   $lb_valido=$io_ctrl_numero->uf_delete_ctrl_numero($lr_datos,$la_seguridad);
			if($lb_valido)
			{
			 $io_sql->commit();
		     $io_msg->message("Registro Eliminado !!!");
		     $ls_codigo        = "";
		     $ls_codsis        = "";
			 $ls_maxlon        = "";
			 $ls_prefijo       = "";
			 $ls_numini        = "";
			 $ls_numfin        = "";
			 $ls_numact        = "";
			 $ls_codsis        = "";
			 $ls_estatus       = "NUEVO";
			 $ls_activo2="disabled";
			 $ls_read="";
     		 $ls_activo="";
			 $ls_numcominicero="";
			 $la_disponibles="";
		     $la_asignados="";
			 $ls_nunasig="";
			}
		   	else
		    { 
			     $io_sql->rollback();
			     $io_msg->message($io_procedencias->is_msg_error);
			}
	}
	else 
	{
		$io_msg->message(" El registro no puede ser Eliminado porque existe Documentos con el Prefijo ".$lr_datos["prefijo"]); 
		$ls_read="readonly";
 		$ls_activo="disabled";
	}
   	/* $lb_existe = $io_procedencias->uf_select_procedencia($ls_codigo);
      if ($lb_existe)
	     {
		   $ls_condicion = " AND (column_name='procede' OR column_name='procede_doc')";//Nombre del o los campos que deseamos buscar.
	       $ls_mensaje   = "";  //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
	       $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_empresa,$ls_condicion,'sigesp_procedencias',$ls_codigo,$ls_mensaje);//Verifica los movimientos asociados a la cuenta
	       if (!$lb_tiene)
	          {
		        $lb_valido = $io_procedencias->uf_delete_procedencia($ls_codigo,$la_seguridad);
		        if ($lb_valido)
	               {
			         $io_sql->commit();
			        c
			         $ls_codigo        = "";
				     $ls_codsis        = "";
					 $ls_maxlon        = "";
					 $ls_prefijo       = "";
					 $ls_numini        = "";
					 $ls_numfin        = "";
					 $ls_numact        = "";
					 $ls_codsis        = "";
			       }
		        else
			       { 
			         $io_sql->rollback();
			         $io_msg->message($io_procedencias->is_msg_error);
			       }	 
		      }
		   else
		      {
			    $io_msg->message($io_chkrel->is_msg_error);
			  }
		 }
	  else
	     {
		    $io_msg->message("Este Registro No Existe !!!");
		 }*/
}
if ($ls_operacion=="buscar")
{
 $ls_codsis=$_POST["txtcodsis"]; 
 $ls_read="readonly";
 $ls_activo='readonly="true"';  
 $lb_valido=$io_ctrl_numero->uf_buscar_usuarios_disponibles($ls_empresa,$ls_codsis,$ls_prefijo,$la_disponibles);
 $ls_nunasig=$io_ctrl_numero->uf_buscar_usuarios_asignados($ls_empresa,$ls_codsis,$ls_prefijo,$la_asignados);
}

if($ls_operacion=="LLENARGRID") 
{
    $ls_codsis= $_POST["cmbsis"];
	$ls_prefi= $_POST["txtprefi"];
	if($ls_codsis=='CXPSOP')
	  {
	     $ls_desdoc="Solicitud de Pago";
	  }
	  elseif($ls_codsis=='SEPSPC') 
	  {
	     $ls_desdoc="Solicitud de Ejecución Presupuestaria";
	  }elseif($ls_codsis=='SOCCOC') 
	  {
	     $ls_desdoc="Orden de Compra";
	  }elseif($ls_codsis=='SCGCMP') 
	  {
		 $ls_desdoc="Comprobante Contable";
	  }elseif($ls_codsis=='SOCCOS') 
	  {
	    $ls_desdoc="Orden de Servicios";
	  }
	$lb_valido=$io_ctrl_numero->uf_verificar_prefijo($ls_empresa,&$ls_codsis,&$ls_prefi,&$li_sal);
	if($li_sal!=0)
	{
	     $io_msg->message("Ya existe el prefijo ".$ls_prefi." para el documento ".$ls_desdoc);
		 uf_limpiarvariables();
		
	}
	else
	{
		$lb_valido=$io_ctrl_numero->uf_sss_load_usuarios_disponibles($ls_empresa,$ls_codsis,$ls_prefijo,$la_disponibles);
		$lb_valido=$io_ctrl_numero->uf_sss_load_usuarios_asignados($ls_empresa,$ls_prefijo,$la_asignados);
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
<table width="611" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td width="616" height="283"><table width="575"  border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="22" colspan="4" class="titulo-ventana">Control N&uacute;mero</td>
        </tr>
        <tr>
          <td height="22">&nbsp;</td>
          <td width="96" height="22"><input name="status" type="hidden" id="status" value="<?php print $ls_estatus ?>"></td>
          <tr>
          <td width="159" height="22" align="right">C&oacute;digo</td>
          <td height="22"><input name="txtcodigo" type="text" id="txtcodigo" size="8" maxlength="4" onBlur="javascript:rellenar_cad(this.value,4,'cod');" value="<?php print $ls_codigo ?>"  style="text-align:center " readonly>
<!--            <td width="385" height="22"><input name="txtcodigo" type="text" id="txtcodigo" size="8" maxlength="4" onKeyUp="" onBlur="javascript:rellenar_cad(this.value,4,'cod');" onKeyPress="" value="<?php print $ls_codigo ?>"  style="text-align:center " >
--></td>
          </tr>
        <tr>
          <td height="22" align="right"> Documento</td>
          <td height="22" colspan="3"><label>
            <select name="cmbsis" id="cmbsis" <? print $ls_activo;?> onChange="javascript:uf_activar();">
    		
             
              <?php 
              if($ls_codsis=='CXPSOP')
              {

			  ?>
              <option value="--">---------SELECCIONE UNA--------</option>	
			  <option value="CXPSOP" selected>Solicitud de Pago</option>
			  <option value="SEPSPC">Solicitud de Ejecucion Presupuestria</option>
			  <option value="SOCCOC">Orden de Compra</option>
			  <option value="SCGCMP">Comprobante Contable</option>
			  <option value="SOCCOS">Orden de Servicios</option>
              <?
              }
              elseif($ls_codsis=='SEPSPC') 
              {

              ?>
               <option value="--">---------SELECCIONE UNA--------</option>	
               <option value="CXPSOP">Solicitud de Pago</option>
               <option value="SEPSPC" selected>Solicitud de Ejecucion Presupuestria</option>
			   <option value="SOCCOC">Orden de Compra</option>
			   <option value="SCGCMP">Comprobante Contable</option>
			  <option value="SOCCOS">Orden de Servicios</option>
              <?
              }elseif($ls_codsis=='SOCCOC') 
              {
              ?>
              <option value="--">---------SELECCIONE UNA--------</option>
              <option value="CXPSOP">Solicitud de Pago</option>
              <option value="SEPSPC">Solicitud de Ejecucion Presupuestria</option>
			  <option value="SOCCOC" selected>Orden de Compra</option>
			  <option value="SCGCMP">Comprobante Contable</option>
			  <option value="SOCCOS">Orden de Servicios</option>
			   <?
              }elseif($ls_codsis=='SCGCMP') 
              {
              ?>
              <option value="--">---------SELECCIONE UNA--------</option>
              <option value="CXPSOP">Solicitud de Pago</option>
              <option value="SEPSPC">Solicitud de Ejecucion Presupuestria</option>
			  <option value="SOCCOC">Orden de Compra</option>
			  <option value="SCGCMP" selected>Comprobante Contable</option>
			  <option value="SOCCOS">Orden de Servicios</option>
              <?
              }elseif($ls_codsis=='SOCCOS') 
              {
              ?>
			   <option value="--">---------SELECCIONE UNA--------</option>
              <option value="CXPSOP">Solicitud de Pago</option>
              <option value="SEPSPC">Solicitud de Ejecucion Presupuestria</option>
			  <option value="SOCCOC">Orden de Compra</option>
			  <option value="SCGCMP">Comprobante Contable</option>
			  <option value="SOCCOS" selected>Orden de Servicios</option>
			  <?
              }else
              {
              ?>
			  <option value="--" selected>---------SELECCIONE UNA--------</option>
              <option value="CXPSOP">Solicitud de Pago</option>
              <option value="SEPSPC">Solicitud de Ejecucion Presupuestria</option>
			   <option value="SOCCOC">Orden de Compra</option>
			   <option value="SCGCMP">Comprobante Contable</option>
			  <option value="SOCCOS">Orden de Servicios</option>
              <?
              }
              ?>
		    </select>
			
    </label>        
     <!--        <select name="cmbsis" id="cmbsis" <? print $ls_activo;?> >
		   
              <option value="--" selected>---------SELECCIONE UNA--------</option>	
			  <option value="CXPSOP" >Solicitud de Pago</option>
			  <option value="SEPSPC">Solicitud de Ejecucion Presupuestria</option>
			  <option value="SOCCOC">Orden de Compra</option>
			  <option value="SOCCOS">Orden de Servicios</option>
            </select>
</label>-->
            <input name="txtcodsis" type="hidden" id="txtcodsis"  value="<?php print $ls_codsis ?>"  style="text-align:center ">            </td>
        </tr>
        <tr style="display:none">
          <td height="22" align="right">Logitud del Campo</td>
          <td height="22" colspan="3"><input name="txtlong" type="text" id="txtlong" size="6" maxlength="2" onKeyPress="return keyRestrict(event,'0123456789');" value="15"  style="text-align:center "></td>
        </tr>
        <tr>
          <td height="22" align="right">Prefijo</td>
          <td height="22" colspan="3"><input name="txtprefi" type="text" id="txtprefi" size="10" maxlength="6" onBlur="llenargrid()" onKeyUp="calcularnumeroactual();" value="<?php print $ls_prefijo ?>" "<?php if($ls_operacion=='buscar'){print $ls_activo;}?>" style="text-align:center "></td>
<!--		  <td height="22" colspan="2"><input name="txtprefi" type="text" id="txtprefi" size="10" maxlength="6" onChange="" onKeyup="calcularnumeroactual();" value="<?php //sprint $ls_prefijo ?>"  style="text-align:center "></td>
-->		
</tr>
        <tr style="display:none">
          <td height="22" align="right">Numero Inicial</td>
          <td height="22" colspan="3"><input name="txtnumini" type="text" id="txtnumini" size="10" maxlength="6" onKeyPress="return keyRestrict(event,'0123456789');" value="1"  style="text-align:center "></td>
        </tr>
        <tr style="display:none">
          <td height="22" align="right">N&uacute;mero Final</td>
          <td height="22" colspan="3"><input name="txtnumfin" type="text" id="txtnumfin" size="10" maxlength="6"  onKeyPress="return keyRestrict(event,'0123456789');" onChange=""  onkeyup="" onBlur="" value="999999"  style="text-align:center "></td>
        </tr>
        <tr>
          <td height="22" align="right">Numero Actual</td>
          <td height="22" colspan="3"><input name="txtnumact" type="text" id="txtnumact" size="20" maxlength="15"  onBlur="calcularnumeroactual()" value="<?php print $ls_numact ?>"  style="text-align:center " readonly></td>
        </tr>
		<?php
		if ($ls_operacion=="buscar")
		{
          if ($ls_codsis=='SCGCMP')
		  {
		    $ls_activo="enabled";
		  }
		  else
		  { 
		    $ls_activo="disabled";
		  }
		}
		else
		{
		  $ls_activo="disabled";
		}
		?>
        <tr>
          <td height="22" align="right">Inicializar a cero el consecutivo del n&uacute;mero de comprobante al final del mes </td>
          <td height="22" colspan="3"><input name="chkestinicero" type="checkbox" id="chkestinicero" value="1" <?php print $ls_numcominicero ?> "<?php  print $ls_activo;?>"></td>
        </tr>
        <tr>
          <td height="15" align="right">&nbsp;</td>
          <td height="15" colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" rowspan="6"><div align="center" class="titulo-celdanew"><span class="Estilo1">Disponibles</span>
                  <?php uf_print_lista("txtdisponibles","codusu","codusu",$la_disponibles);?>
          </div></td>
          <td width="65"><div align="center"> </div></td>
          <td width="253" rowspan="6"><div align="center" class="titulo-celdanew"><span class="Estilo1">Asignados </span> <span> </span>
                  <input name="total" type="hidden" id="total" value="<?php print $ls_nunasig;?>">
                  <input name="total1" type="hidden" id="total1">
                  <?php uf_print_lista("txtasignados","codusu","codusu",$la_asignados);?>
          </div></td>
        </tr>
        <tr>
          <td height="13"><div align="center">
              <input name="btnincluirpersonal" type="button" class="boton" id="btnincluirpersonal3" style="width: 40px" value="&gt;" onClick="javascript: ue_pasar(form1.txtdisponibles,form1.txtasignados);">
          </div></td>
        </tr>
        <tr>
          <td height="21"><div align="center">
              <input name="btnincluirpersonaltodos" type="button" class="boton" id="btnincluirpersonaltodos3" style="width: 40px" value="&gt;&gt;" onClick="javascript: ue_pasartodos(form1.txtdisponibles,form1.txtasignados);">
          </div></td>
        </tr>
        <tr>
          <td height="13"><div align="center">
              <input name="btnexcluirpersonal" type="button" class="boton" id="btnexcluirpersonal3" style="width: 40px" value="&lt;"  onClick="javascript: ue_pasar(form1.txtasignados,form1.txtdisponibles);">
          </div></td>
        </tr>
        <tr>
          <td height="14"><div align="center">
              <input name="btnexcluirpersonaltodos" type="button" class="boton" id="btnexcluirpersonaltodos3" style="width: 40px" value="&lt;&lt;" onClick="javascript: ue_pasartodos(form1.txtasignados,form1.txtdisponibles);">
          </div></td>
        </tr>
        <tr>
          <td><div align="center">
              <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion;?>" >
          </div></td>
        </tr>
        
        <tr>
          <td height="22" colspan="4" align="right">
            &nbsp;<input name="txtcodusu" type="hidden" id="txtcodusu"  value="<?php print $_SESSION["la_logusr"]; ?>"  style="text-align:center "></td>
          </tr>
      </table></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>

<script language="javascript">

function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.txtcodigo.readOnly=false;
	   f.txtcodigo.value="";
	   f.txtcodsis.value="";
	   f.txtlong.value="";
	   f.txtprefi.value="";
	   f.txtnumini.value="";
	   f.txtnumfin.value="";
	   f.txtnumact.value="";
	   f.total.value="";
	   f.txtcodigo.focus(); 
	   f.operacion.value="NUEVO";
	   f.submit();
	}
  else
	{
	  alert("No tiene permiso para realizar esta operación");
	}
}

function ue_guardar()
{//1
  var resul="";
 
f          = document.form1;
li_incluir = f.incluir.value;
li_cambiar = f.cambiar.value;
lb_status  = f.status.value;
ls_disponibles=f.txtdisponibles.value;
ls_asignados=f.txtasignados.value; 
if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status!="GRABADO")&&(li_incluir==1))
   {
     with (document.form1)
	      {
            if (valida_null(txtcodigo,"El Código esta vacio  !!!")==false)
               {
                 f.txtcodigo.focus();
               }
            else
               {
	             
               	if(f.cmbsis.value=='--')
               	{
               	alert('El Documento esta vacio !!!');
               	}
               	else
               	{
               	  if (valida_null(txtlong,"La Longitud del Número esta vacia !!!")==false)
	                {
	                  f.txtlong.focus();
	                }
	             else  
	                {
                      if (valida_null(txtprefi,"El Prefijo  esta vacio !!!")==false)
  		                 {
 	                       f.txtprefi.focus();
	    		         }
		              else
		                 {
		                   if (valida_null(txtnumini,"El Número inicial  esta vacio !!!")==false)
  		                      {
 	                            f.txtnumini.focus();
	    		              }
							
				           else 
				              {
							      ls_prefijo=f.txtprefi.value;
								  var mystring  =new String(ls_prefijo);
								  if (valida_null(txtnumfin,"El Número Final  esta vacio !!!")==false)
								  {
										f.txtnumfin.focus();
								  }
							   	  else
								  {
											ls_prefijo=f.txtprefi.value;
											var mystring  =new String(ls_prefijo);
											lenprefijo       = mystring.length;
												
								  	          if (lenprefijo<4)
											  {
													alert('El Prefijo debe ser de 4 caractares alfanumericos');
											  }
										   	  else if(ls_asignados=="")
											  {
											     alert('Debe asignar uno o todos los usuarios para este prefijo');
											  }										  
											  else 
											  {
										  		f=document.form1;
												f.operacion.value="GUARDAR";
												f.action="sigesp_cfg_d_ctrl_numero.php";
												f.submit();
											  }		
								  	
								  }			                  
							  }
				         }
			        }
               	}
	           }
	      }
   }
  else
	{
	  alert("No tiene permiso para realizar esta operación");
	}
}


function uf_confirmacion()
{	f          = document.form1;
	lb_status  = f.status.value;
	if(lb_status=='C')
	{
		 actualizar=confirm("¿ Esta seguro de Actualizar este registro ?");
		if(f.txtasignados!=null)
		{
			li_totasi=f.txtasignados.length;	
		}
		for(i=0;i<li_totasi;i++)
		{ 
			f.txtasignados[i].selected=true;
		}
		if(f.txtdisponibles!=null)
		{
			li_totdis=f.txtdisponibles.length;	
		}
		for(i=0;i<li_totdis;i++)
		{
			f.txtdisponibles[i].selected=true;
		}
		 if (actualizar==true)
	     { 
	     	 /*confirmacion=confirm("Si Actualiza el Registro perdera la secuencia del Consecutivo Anterior y Comenzará con el Nuevo consecutivo.¿Desea Continuar?");
			 if (confirmacion==true)
		     { 
			  ue_guardar();
		     }
		  	 else
		     { 
			   alert("Actualización Cancelada !!!");
			 }*/
			 ue_guardar();
	     }
	  	 else
	     { 
		   alert("Actualización Cancelada !!!");
		 }
		 //alert("No puede actualizar el Registro");
	}
	else
	{
	    if(f.txtasignados!=null)
		{
			li_totasi=f.txtasignados.length;	
		}
		for(i=0;i<li_totasi;i++)
		{
			f.txtasignados[i].selected=true;
		}

		if(f.txtdisponibles!=null)
		{
			li_totdis=f.txtdisponibles.length;	
		}
		for(i=0;i<li_totdis;i++)
		{
			f.txtdisponibles[i].selected=true;
		}
		ue_guardar();	
		
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


function ue_eliminar()
{
var borrar="";

f=document.form1;
li_eliminar=f.eliminar.value;
ls_estatus=f.status.value;
if (li_eliminar==1)
   {	
     if (f.txtcodigo.value=="")
        {
	      alert("No ha seleccionado ningún registro para eliminar !!!");
        }
	 else
	    {
		  if(ls_estatus=="C")
		  {
		  	borrar=confirm("¿ Esta seguro de eliminar este registro ?");
			 if (borrar==true)
		     { 
			   f.cmbsis.disabled=false;
		       f.operacion.value="ELIMINAR";
			   f.action="sigesp_cfg_d_ctrl_numero.php";
			   f.submit();
		     }
			 else
		     { 
			   alert("Eliminación Cancelada !!!");
			 }  
		  	
		  }else
		  {
		     alert("No ha seleccionado ningún registro para eliminar !!!");
		  }
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
    ls_usuario=f.txtcodusu.value;
	if (li_leer==1)
	   {
	     f.operacion.value="buscar";			
	     pagina="sigesp_cfg_cat_ctrl_numero.php?txtcodusu="+ls_usuario;
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
       }
	else
	   {
	     alert("No tiene permiso para realizar esta operación");
	   }   
}

function rellenar_cad(cadena,longitud,campo)
{
	var mystring=new String(cadena);
	if (cadena!="")
	   {
	     cadena_ceros = "";
	     lencad       = mystring.length;
	     total        = longitud-lencad;
	     for (i=1;i<=total;i++)
	         {
		       cadena_ceros=cadena_ceros+"0";
	         }
 	     cadena = cadena_ceros+cadena;
	     if (campo=="cod")
	        {
		      document.form1.txtcodigo.value=cadena;
	        }
	   }
}


function calcularnumeroactual(longitud)
{
    f = document.form1;
	ls_prefijo=f.txtprefi.value;
	ls_inicial=f.txtnumini.value;
	ls_logitud=f.txtlong.value;
	var mystring  =new String(ls_prefijo);
	var mystring2 =new String(ls_inicial);
	if (ls_prefijo!="" && ls_inicial!="" )
	{
	     cadena_ceros = "";
	     lenprefijo       = mystring.length;
		 leninicial       = mystring2.length;
	     total            = ls_logitud-lenprefijo;
		 totalfinal       = total-leninicial;
		 
	     for (i=1;i<=totalfinal;i++)
		 {
		   cadena_ceros=cadena_ceros+"0";
		 }
		 cadena =ls_prefijo+cadena_ceros+ls_inicial;
	    
		 document.form1.txtnumact.value=cadena;
	       
	 }
}

function uf_activar()
{
  f = document.form1; 
  ls_seleccion=f.cmbsis.value;
 if (ls_seleccion=='SCGCMP')
     { 
	   f.chkestinicero.disabled=false;
	 }
  else
     { 
	   f.chkestinicero.disabled=true;
	 }
}

function llenargrid()
{
  f = document.form1; 
  ls_seleccion=f.cmbsis.value;
  ls_prefijo=f.txtprefi.value;
  if (ls_prefijo!='')
     {  
	    f.operacion.value="LLENARGRID";
		f.action="sigesp_cfg_d_ctrl_numero.php";
		f.submit();
     }
  else
     { 
	   alert("Debe tipear un prefijo para asignar los usuarios");
	 }
}

//////////////////////////////////agregado el 28/08/08///////////////////////////////////////////////// 
function ue_pasar(obj_desde,obj_hasta)
{
	totdes=obj_desde.length;  
	tothas=obj_hasta.length;  
	for(i=0;i<totdes;i++)
	{
		if(obj_desde.options[i].selected)
		{
			asignar = new Option(obj_desde.options[i].text, obj_desde.options[i].value, false, false);
			asignados=obj_hasta.length;
			if (asignados< 1)
			{
				obj_hasta.options[asignados] = asignar;
			}
			else
			{
				obj_hasta.options[tothas] = asignar;  
			}
			tothas=asignados + 1;
		}
	
	} 
	ue_borrar_listaseleccionado(obj_desde);
}

function ue_pasartodos(obj_desde,obj_hasta)
{
	totdes=obj_desde.length; 
	tothas=obj_hasta.length;
	for(i=0;i<totdes;i++)
	{
		asignar = new Option(obj_desde.options[i].text, obj_desde.options[i].value, false, false);
		asignados=obj_hasta.length;
		if (asignados< 1)
		{
			obj_hasta.options[asignados] = asignar;
		}
		else
		{
			obj_hasta.options[tothas] = asignar;
		}
		tothas=asignados + 1;
		
	}
	ue_borrar_listacompleta(obj_desde);
}

function ue_borrar_listacompleta(obj) 
{
	var  largo= obj.length;
	for (i=largo-1;i>=0;i--) 
	{	
		obj.options[i] = null;
	}
}

function ue_borrar_listaseleccionado(obj) 
{
	var largo= obj.length;
	var x;
	var count=0;
	arrSelected = new Array();
	for(i=0;i<largo;i++) // se coloca en el arreglo los campos seleccionados
	{	
		if(obj.options[i].selected) 
		{
			arrSelected[count]=obj.options[i].value;
		}
		count++;
	}
	for(i=0;i<largo;i++) // se colocan en null los que están en el arreglo
	{
		for(x=0;x<arrSelected.length;x++) 
		{
			if (obj.options[i].value==arrSelected[x]) 
			{
				obj.options[i]=null;
			}
		}
		largo = obj.length;
	}
}


</script>
</html>