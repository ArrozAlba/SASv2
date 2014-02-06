<?php
session_start();
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
   
   
   $ls_loncodestpro1=$_SESSION["la_empresa"]["loncodestpro1"];
   $ls_loncodestpro2=$_SESSION["la_empresa"]["loncodestpro2"];
   $ls_loncodestpro3=$_SESSION["la_empresa"]["loncodestpro3"];
   $ls_loncodestpro4=$_SESSION["la_empresa"]["loncodestpro4"];
   $ls_loncodestpro5=$_SESSION["la_empresa"]["loncodestpro5"];
   $ls_longitudtotal=$ls_loncodestpro1+$ls_loncodestpro2+$ls_loncodestpro3+$ls_loncodestpro4+$ls_loncodestpro5+10;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Registro de Otros Créditos</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
<script language="javascript">
if (document.all)
   {
	 document.onkeydown = function(){ 
	 if (window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey))
	    {
		  window.event.keyCode = 505; 
	    }
	if  (window.event.keyCode == 505){ return false;} 
	    } 
}
</script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset="><style type="text/css">
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
</style>
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr> 
    <td height="30" class="cd-logo"><img src="../../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" class="cd-menu">
	<table width="776" border="0" align="center" cellpadding="0" cellspacing="0">
	
		<td width="423" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Configuración</td>
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
    <td height="20" bgcolor="#FFFFFF" class="toolbar"><a href="javascript:ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript:ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript:ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript:ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="sigespwindow_blank.php"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></td>
  </tr>
</table>
<?php 

require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("class_folder/sigesp_cxp_c_otroscreditos.php");
require_once("../../shared/class_folder/class_funciones_db.php");
require_once("../../shared/class_folder/class_mensajes.php"); 
require_once("../../shared/class_folder/evaluate_formula.php");
require_once("../../shared/class_folder/sigesp_c_check_relaciones.php"); 

$io_conect        = new sigesp_include();//Instanciando la Sigesp_Include.
$conn             = $io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql           = new class_sql($conn);//Instanciando la Clase Class Sql.
$io_otroscreditos = new sigesp_cxp_c_otroscreditos($conn);//Instanciando la Clase Sigesp Definiciones.
$io_msg           = new class_mensajes();//Instanciando la Clase Class  Mensajes.
$io_dscargos      = new class_datastore();//Instanciando la Clase Class  DataStore.
$io_funcion       = new class_funciones();//Instanciando la Clase Class_Funciones.
$io_funciondb     = new class_funciones_db($conn);
$io_formula       = new evaluate_formula(); 
$io_chkrel       = new sigesp_c_check_relaciones($conn);
$lb_existe        = "";

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre         = $_SESSION["la_empresa"];
	$ls_empresa   = $arre["codemp"];
	$ls_codemp    = $ls_empresa;
	$li_estmodest = $arre["estmodest"];
	$ls_logusr    = $_SESSION["la_logusr"];
	$ls_sistema   = "CFG";
	$ls_ventanas  = "sigesp_cxp_d_otroscreditos.php";

	$la_seguridad["empresa"]  = $ls_empresa;
	$la_seguridad["logusr"]   = $ls_logusr;
	$la_seguridad["sistema"]  = $ls_sistema;
	$la_seguridad["ventanas"] = $ls_ventanas;

	if (array_key_exists("permisos",$_POST)||($ls_logusr=="PSEGIS"))
	{	
		if($ls_logusr=="PSEGIS")
		{
			$ls_permisos="";
			$la_accesos=$io_seguridad->uf_sss_load_permisossigesp();
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
		$ls_permisos            = $io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
$ls_confiva="";
$ls_valido=$io_otroscreditos->uf_select_configuracion_iva($ls_codemp,&$ls_confiva);
if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion				= $_POST["operacion"];
	 $ls_codigo					= $_POST["txtcodigo"];
	 $lr_datos["codigo"]		= $ls_codigo;
	 $ls_denominacion			= $_POST["txtdenominacion"];
	 $lr_datos["denominacion"]	= $ls_denominacion;
	 $ld_porcentaje				= $_POST["txtporcentaje"];
	 $lr_datos["porcentaje"]    = $ld_porcentaje;
	 $ls_formula				= $_POST["txtformula"];
	 $lr_datos["formula"]		= $ls_formula;
	 $ls_spgcuenta				= $_POST["txtpresupuestaria"];
	 $lr_datos["spg_cuenta"]	= $ls_spgcuenta;
	 $ls_confiva="";
	 $ls_codestpro="";
	 $ls_valido=$io_otroscreditos->uf_select_configuracion_iva($ls_codemp,&$ls_confiva);
     if(($ls_valido)&&($ls_confiva=="C"))
	 {
			$ls_estcla= "";
	 		$ls_codestpro1= "";
			$ls_codestpro2= "";
			$ls_codestpro3= "";
	 		$ls_codestpro4= "";
			$ls_codestpro5= "";
	 }
	 else
	 {
		if ($ls_confiva=="P")
		{
			$ls_estcla= $_POST["txtestcla"];
	 		$ls_codestpro1= $_POST["txtcodestpro1"];
			$ls_codestpro2= $_POST["txtcodestpro2"];
			$ls_codestpro3= $_POST["txtcodestpro3"];
	 		$ls_codestpro4= $_POST["txtcodestpro4"];
			$ls_codestpro5= $_POST["txtcodestpro5"];
		}
	 }	 
	 $lr_datos["codestpro"]= $ls_codestpro; 
	 $ls_estatus= $_POST["hidestatus"];
	 $lr_datos["estcla"]= $ls_estcla;
	 $lr_datos["codestpro1"]= $ls_codestpro1;
	 $lr_datos["codestpro2"]= $ls_codestpro2;
	 $lr_datos["codestpro3"]= $ls_codestpro3;
	 $lr_datos["codestpro4"]= $ls_codestpro4;
	 $lr_datos["codestpro5"]= $ls_codestpro5;
   }
else
   {
     $ls_operacion				= "NUEVO";
	 $ls_codigo					= "";
	 $ls_denominacion           = "";
	 $ld_porcentaje             = "0.0";
	 $lr_datos["porcentaje"]    = $ld_porcentaje;
	 $ls_formula				= "";
	 $ls_spgcuenta				= "";
	 $ls_codestpro				= "";
	 $ls_estatus				= "NUEVO";
	 $ls_codestpro1				= "";
	 $ls_codestpro2				= "";
	 $ls_codestpro3				= "";
	 $ls_codestpro4				= "";
	 $ls_codestpro5				= "";
   }	
   	
$lb_empresa = true;
if (array_key_exists("chklibcompras",$_POST))
   {
	 $li_estlibcompras          = $_POST["chklibcompras"];
	 $lr_datos["estlibcompras"] = $li_estlibcompras;
	 $ls_estlibcompras          = "checked";
   }
else
   {
	 $li_estlibcompras          = 0;
	 $lr_datos["estlibcompras"] = $li_estlibcompras;
	 $ls_estlibcompras          = "";
   }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////             Operación  Nuevo    ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="NUEVO")
   {
	 $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sigesp_cargos','codcar');
	 if(empty($ls_codigo))
	 {
	 	$io_msg->message($io_funciondb->is_msg_error);
	 }
   }   
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////           Fin  Operacion  Nuevo     ///////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////      Operaciones de Insercion y Actualización     //////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_guardar")
   {
	 $lb_existe=$io_otroscreditos->uf_select_otroscreditos($ls_codemp,$ls_codigo);
	 if ($lb_existe)
        { 
	      if ($ls_estatus=="NUEVO")
		     {
			   $io_msg->message("El Código del Crédito ya existe !!!");  
			   $lb_valido=false;
			 }
		  elseif($ls_estatus=="GRABADO")
		     {
			   $lb_valfor = $io_formula->uf_evaluar_formula($ls_formula,10000);
			   if ($lb_valfor)
			      {
					if (array_key_exists("chklibcompras",$_POST))
					   {
						 $li_estlibcompras          = $_POST["chklibcompras"];
						 $lr_datos["estlibcompras"] = $li_estlibcompras;
						 $ls_estlibcompras          = "checked";
					   }
					else
					   {
						 $li_estlibcompras          = 0;
						 $lr_datos["estlibcompras"] = $li_estlibcompras;
						 $ls_estlibcompras          = "";
					   }
					$lb_valido = $io_otroscreditos->uf_update_otroscreditos($ls_codemp,$lr_datos,$li_estmodest,$la_seguridad,$ls_estcla);
				    if ($lb_valido)
					   {
						 $io_sql->commit();
						 $io_msg->message("Registro Actualizado !!!");
						 $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sigesp_cargos','codcar');
						 $ls_denominacion="";
						 $ld_porcentaje="0.0";
						 $li_estlibcompras="";
						 $ls_formula="";
						 $ls_spgcuenta="";
						 $ls_codestpro="";
						 $ls_estatus="NUEVO";
					   }
					 else
					   {
						 $io_sql->rollback();
						 $io_msg->message("Error en Actualización !!!");
					   }
			 	  }
			   else
				  {
				    $io_msg->message("Error en Actualización: Fórmula Invalida !!!");
				  }
			 }  
	    }
	 else
		{  
		  $lb_valfor = $io_formula->uf_evaluar_formula($ls_formula,10000);
		  if ($lb_valfor)
			 {
			   $lb_valido=$io_otroscreditos->uf_insert_otroscreditos($ls_codemp,$lr_datos,$li_estmodest,$la_seguridad,$ls_estcla);
			   if ($lb_valido)
				  {
				    $io_sql->commit();
				    $io_msg->message("Registro Incluido !!!");
					$ls_codigo        = $io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sigesp_cargos','codcar');
					$ls_denominacion  = "";
					$ld_porcentaje    = "0.0";
					$li_estlibcompras = "";
					$ls_formula       = "";
					$ls_spgcuenta     = "";
					$ls_codestpro     = "";
					$ls_estatus       = "NUEVO";
					$ls_codestpro1				= "";
					$ls_codestpro2				= "";
					$ls_codestpro3				= "";
					$ls_codestpro4				= "";
					$ls_codestpro5				= "";
				  }
			   else
				  {
				    $io_sql->rollback();
					$io_msg->message("Error en Inclusión !!!");
				  }
			 }  
          else
		     {
			   $io_msg->message("Error en Inclusión: Fórmula Invalida !!!");
			 }
	    } 
   }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////       Fin de las Operaciones de Inserción y Actualización    ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////    Operacion   de Eliminar  ///////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ELIMINAR")
   {
	  $lb_existe=$io_otroscreditos->uf_select_otroscreditos($ls_codemp,$ls_codigo);
	  if ($lb_existe)
	     {
		        $lb_valido=$io_otroscreditos->uf_delete_otroscreditos($ls_codemp,$ls_codigo,$ls_denominacion,$la_seguridad);
			   	if ($lb_valido)
				   {
					 $io_sql->commit();
					 $io_msg->message("Registro Eliminado !!!");
					 $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sigesp_cargos','codcar');
					 $ls_denominacion="";
					 $ld_porcentaje="0.0";
					 $li_estlibcompras="";
					 $ls_formula="";
					 $ls_spgcuenta="";
					 $ls_codestpro="";
					 $ls_estatus="NUEVO";
					 $ls_codestpro1				= "";
					 $ls_codestpro2				= "";
					 $ls_codestpro3				= "";
					 $ls_codestpro4				= "";
					 $ls_codestpro5				= "";
				   }
			    else
				   {
				     $io_sql->rollback();
				     if(!empty($io_otroscreditos->is_msg_error))
				     {
				     	$io_msg->message($io_otroscreditos->is_msg_error);
				     }
				   }	 
		 }
	  else
	     {
		    $io_msg->message("Este Registro No Existe !!!");
		 }
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////    FIN  Operacion de Eliminar          ////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
      <table width="781" height="268" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
        <tr>
          <td width="781" height="266"><div align="center">
            <table width="637"  border="0" align="center" class="formato-blanco" cellpadding="0" cellspacing="0">
              <tr>
                <td height="22" colspan="3" class="titulo-ventana">Otros Cr&eacute;ditos</td>
              </tr>
              <tr>
                <td height="22" >&nbsp;</td>
                <td height="22" colspan="2" ><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>"></td>
              </tr>
              <tr>
                <td width="102" height="22" align="right">C&oacute;digo</td>
                <td height="22" colspan="2" ><input name="txtcodigo" type="text" id="txtcodigo" value="<?php print  $ls_codigo ?>" size="5" maxlength="5" onKeyPress="return keyRestrict(event,'1234567890');" style="text-align:center"  onBlur="javascript:rellenar_cadena(this.value,5);">
                    <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion?>">
                </td>
              </tr>
              <tr>
                <td height="22" align="right">Denominaci&oacute;n</td>
                <td height="22" colspan="2"><input name="txtdenominacion" id="txtdenominacion" value="<?php print $ls_denominacion ?>" type="text" size="72" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmnñopqrstuvwxyz '+'.,-()%');"></td>
              </tr>
              <tr>
                <td height="22" align="right">%</td>
                <td height="22" colspan="2"><input name="txtporcentaje" type="text" id="txtporcentaje" onKeyPress="return keyRestrict(event,'1234567890.');" value="<?php print $ld_porcentaje ?>" style="text-align:right">
                </td>
              </tr>
			  <?php 
			     $ls_confiva="";
			     $ls_valido=$io_otroscreditos->uf_select_configuracion_iva($ls_codemp,&$ls_confiva);
				 if(($ls_valido)&&($ls_confiva=='P'))
				 {
			  ?>
              <tr>
                <td height="22" align="right">Presupuesto</td>
                <td width="156" height="22"><input name="txtpresupuestaria" type="text" id="txtpresupuestaria" style="text-align:center" value="<?php print $ls_spgcuenta ?>" size="25" maxlength="25" readonly></td>
                <td width="379" height="22"><input name="txtcodestpro"      type="text" id="txtcodestpro"      style="text-align:center" value="<?php print $ls_codestpro ?>" size="50" maxlength="60" readonly>
                <a href="javascript:cat_presupuesto();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> 
                 <input name="txtcodestpro1" type="hidden" id="txtcodestpro1" value="<?php print $ls_codestpro1?>" >
                 <input name="txtcodestpro2" type="hidden" id="txtcodestpro2" value="<?php print $ls_codestpro2?>" >
                 <input name="txtcodestpro3" type="hidden" id="txtcodestpro3" value="<?php print $ls_codestpro3?>" >
                 <input name="txtcodestpro4" type="hidden" id="txtcodestpro4" value="<?php print $ls_codestpro4?>" >
                 <input name="txtcodestpro5" type="hidden" id="txtcodestpro5" value="<?php print $ls_codestpro5?>" >
                </td>
              </tr>
			  <?php 
			     }
				 elseif(($ls_valido)&&($ls_confiva=='C'))
				 {
			  ?>
              <tr>
                <td height="22" align="right">Contable</td>
                <td width="156" height="22"><input name="txtpresupuestaria" type="text" id="txtpresupuestaria" style="text-align:center" value="<?php print $ls_spgcuenta ?>" size="25" maxlength="25" readonly>                </td>
                <td width="320" height="22"><a href="javascript:catalogo_contable();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a></td>
              </tr>
			  <?php 
			     }
			  ?>
              <tr>
                <td height="22" align="right">Libro de Compras</td>
                <td height="22" colspan="2"><input name="chklibcompras" type="checkbox" class="sin-borde" id="chklibcompras" value="1" checked <?php print $ls_estlibcompras; ?>>
                
                 <input name="txtestcla" type="hidden" id="txtestcla" value="<?php print $ls_estcla?>" ></td>
              </tr>
              <tr>
                <td height="22" align="right" valign="top">F&oacute;rmula</td>
                <td height="22" colspan="2"><input name="txtformula" type="text" id="txtformula" value="<?php print $ls_formula ?>" size="60" readonly>
                    <input name="btnformula" type="button" class="boton" id="btnformula" onClick="uf_editor()" value="F&oacute;rmula" style="cursor: pointer"></td>
              </tr>
              <tr>
                <td height="22">&nbsp;</td>
                <td height="22" colspan="2"><input name="confiva" type="hidden" id="confiva"  value="<?php print $ls_confiva?>"></td>
              </tr>
            </table>
          </div></td>
        </tr>
  </table>
  <p>&nbsp;</p>
</form>
</body>
<script language="JavaScript">
f = document.form1;
function ue_nuevo()
{
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
       f.operacion.value="NUEVO";
	   f.txtdenominacion.value="";
	   f.txtporcentaje.value="";
	   f.txtpresupuestaria.value="";
	   f.txtcodestpro.value="";
	   f.txtformula.value="";
	   f.txtdenominacion.focus(true);
	   f.action="sigesp_cxp_d_otroscreditos.php";
	   f.submit();
	}
  else
	{
	  alert("No tiene permiso para realizar esta operación");
	}
}

function ue_guardar()
{
var resul="";

li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.hidestatus.value;
if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status=="NUEVO")&&(li_incluir==1))
   {
     with (document.form1)
	      {
	        if (campo_requerido(txtcodigo,"El código del crédito debe estar lleno !!")==false)
	           {
	             txtcodigo.focus();
		       }
	        else
	 	       {
		         resul=rellenar_cadena(document.form1.txtcodigo.value,5);
		         if (campo_requerido(txtdenominacion,"La denominación del crédito debe estar llena !!!")==false)
			        {
			          txtdenominacion.focus();
		            }
	             else
			        {
		              if (campo_requerido(txtporcentaje,"El porcentaje del crédito debe estar lleno !!!")==false)
				         {
				           txtporcentaje.focus();
				         }
			          else
				         {
				           if (campo_requerido(txtformula,"La Fórmula del Crédito debe estar llena !!!")==false)
					          {
					            txtformula.focus();
					          }
					       else
					          {   
      				            if (campo_requerido(txtpresupuestaria,"La Cuenta del Crédito debe estar llena !!!")==false)
				                   {
					                 txtpresupuestaria.focus();
					               }
					            else
					               {   
								     f.operacion.value="ue_guardar";
								     f.action="sigesp_cxp_d_otroscreditos.php";
								     f.submit();
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
					
function ue_eliminar()
{
var borrar="";

li_eliminar=f.eliminar.value;
if (li_eliminar==1)
   {	
     if (f.txtcodigo.value=="")
        {
  	      alert("No ha seleccionado ningún registro para eliminar !!!");
        }
     else
	    {
		  borrar=confirm("¿ Esta seguro de eliminar este registro ?");
		  if (borrar==true)
		     { 
			   f=document.form1;
			   f.operacion.value="ELIMINAR";
			   f.action="sigesp_cxp_d_otroscreditos.php";
			   f.submit();
		     }
		  else
		     { 
			   alert("Eliminación Cancelada !!!");
		     }
	    }
   }
else
   {
     alert("No tiene permiso para realizar esta operación");
   }
}

function campo_requerido(field,mensaje)
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
		
function rellenar_cadena(cadena,longitud)
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
	document.form1.txtcodigo.value=cadena;
}
		
function ue_buscar()
{
    li_leer=f.leer.value;
	confiva=f.confiva.value;
	if (li_leer==1)
	   {
  	     f.operacion.value="";			
	     pagina="sigesp_cxp_cat_creditos.php?confiva="+confiva;
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=720,height=400,resizable=yes,location=no");
	   }
    else
       {
		 alert("No tiene permiso para realizar esta operación");
	   }
}
		
function uf_editor()
 {
   formula=f.txtformula.value;
   window.open("class_sigesp_formulas.php?txtformula="+formula,"catalogo","menubar=no,toolbar=no,scrollbars=no,width=560,height=270,resizable=yes,location=no");
 }
		 
function cat_presupuesto()
{
	pagina="sigesp_cat_ctasspg.php";  
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=600,resizable=yes,location=no");
}

function catalogo_contable()
{
	pagina="sigesp_cxp_cat_scgcuentas.php";  
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=700,height=600,resizable=yes,location=no");
}

</script>
</html>