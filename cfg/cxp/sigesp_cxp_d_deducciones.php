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
<title>Registro de Deducciones</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../../shared/js/disabled_keys.js"></script>
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
		<td width="353" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  <tr>
		<td height="20" bgcolor="#E7E7E7">&nbsp;</td>
		<td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
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
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("class_folder/sigesp_cxp_c_deducciones.php");
require_once("../../shared/class_folder/class_funciones_db.php");
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/evaluate_formula.php"); 
require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");

$io_conect      = new sigesp_include();//Instanciando la Sigesp_Include.
$conn           = $io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql         = new class_sql($conn);//Instanciando la Clase Class Sql.
$io_deducciones = new sigesp_cxp_c_deducciones($conn);//Instanciando la Clase Sigesp Definiciones.
$io_funcion     = new class_funciones();//Instanciando la Clase Class_Funciones.
$io_funciondb   = new class_funciones_db($conn);
$io_msg         = new class_mensajes();
$io_formula     = new evaluate_formula();
$io_chkrel      = new sigesp_c_check_relaciones($conn); 
$lb_existe      = "";
$lb_valido      = "";

//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();

	$arre        = $_SESSION["la_empresa"];
	$ls_empresa  = $arre["codemp"];
	$ls_codemp   = $ls_empresa;
	$ls_logusr   = $_SESSION["la_logusr"];
	$ls_sistema  = "CFG";
	$ls_ventanas = "sigesp_cxp_d_deducciones.php";

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
		$ls_permisos=$io_seguridad->uf_sss_load_permisos($ls_empresa,$ls_logusr,$ls_sistema,$ls_ventanas,$la_accesos);
	}
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////


if (array_key_exists("operacion",$_POST))
   {
     $ls_operacion              = $_POST["operacion"];
     $ls_codigo                 = $_POST["txtcodigo"];
     $lr_datos["codigo"]        = $ls_codigo; 
     $ls_denominacion           = $_POST["txtdenominacion"];
     $lr_datos["denominacion"]  = $ls_denominacion;
     $ld_porcentaje             = $_POST["txtporcentaje"];
     $lr_datos["porcentaje"]    = $ld_porcentaje;
     $ls_contable               = $_POST["txtcuentaplan"];
	 $lr_datos["contable"]      = $ls_contable;
     $ls_denocuenta             = $_POST["txtdencuentaplan"];
	 $ld_deducible              = $_POST["txtdeducible"];
	 $lr_datos["deducible"]     = $ld_deducible;
	 $ls_formula                = $_POST["txtformula"];
     $lr_datos["formula"]       = $ls_formula;
	 $ls_tipodeduccion          = $_POST["radiotipodeduccion"];
	 $lr_datos["tipodeduccion"] = $ls_tipodeduccion;
     $ls_estatus                = $_POST["hidestatus"];
     $ls_codconret              = $_POST["txtcodconret"];
     $lr_datos["codconret"]     = $ls_codconret; 
     $ls_denconret              = $_POST["txtdenconret"];
	 if (array_key_exists("radiotipoperdeduccion",$_POST))
	 {
		 $ls_tipoperdeduccion       = $_POST["radiotipoperdeduccion"];
		 $lr_datos["tipoperdeduccion"] = $ls_tipoperdeduccion;
     }
	 else
	 {
	   $ls_tipoperdeduccion       = "";
		 $lr_datos["tipoperdeduccion"] = $ls_tipoperdeduccion;
	 }

   }
else
   {
     $ls_operacion           = "NUEVO";
     $ls_codigo              = "";
     $ls_denominacion        = "";
     $ld_porcentaje          = "0.0";
     $lr_datos["porcentaje"] = $ld_porcentaje;
     $ls_contable            = "";
     $ls_denocuenta          = "";
	 $ld_deducible           = "0,00";
	 $ls_formula             = "";
	 $ls_tipodeduccion       = "";
	 $ls_estatus             = "NUEVO";	
	 $ls_tipoperdeduccion    = "";  
	 $ls_codconret           = "";  
	 $ls_denconret           = "";  
	}	
$lb_empresa = true;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////             Operación  Nuevo    ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="NUEVO")
   {
	 $ls_codigo = $io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sigesp_deducciones','codded');
	 if (empty($ls_codigo))
	    {
	 	  $io_msg->message($io_funciondb->is_msg_error);
	    }
   }  
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////            Fin  Operación  Nuevo         ///////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////         Operaciones de Inserción y Actualización      //////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ue_guardar")
   { 
	$lb_existe=$io_deducciones->uf_select_deduccion($ls_codemp,$ls_codigo);
	if ($lb_existe)
       { 
	     if ($ls_estatus=="NUEVO")
		    {
			  $io_msg->message("Este Código de Deducción ya existe !!!");  
			  $lb_valido=false;
			}
		 elseif($ls_estatus=="GRABADO")
		    {
	           $lb_valfor = $io_formula->uf_validar_formula($ls_formula,10000);
			   if ($lb_valfor>=0)
			      {
				    $lb_valido=$io_deducciones->uf_update_deduccion($ls_codemp,$lr_datos,$la_seguridad);
					if ($lb_valido)
					   {
					     $io_sql->commit();
						 $io_msg->message("Registro Actualizado !!!");
						 $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sigesp_deducciones','codded');
						 $ls_denominacion="";
						 $ls_contable="";
						 $ls_denocuenta="";
						 $ld_porcentaje="0.0";
						 $ld_deducible="0,00";
						 $ls_formula="";
						 $ls_tipodeduccion="";
						 $ls_estatus="NUEVO";
						 $ls_tipoperdeduccion="";
					     $ls_codconret="";
					     $ls_denconret="";
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
	     $lb_valfor = $io_formula->uf_validar_formula($ls_formula,10000);
 		 if ($lb_valfor)
		    {
		      $lb_valido=$io_deducciones->uf_insert_deduccion($ls_codemp,$lr_datos,$la_seguridad);
			  if ($lb_valido)
			     {
				   $io_sql->commit();
				   $io_msg->message("Registro Incluido !!!");
				   $ls_codigo=$io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sigesp_deducciones','codded');
				   $ls_denominacion="";
				   $ls_contable="";
				   $ls_denocuenta="";
				   $ld_porcentaje="0.0";
				   $ld_deducible="0,00";
				   $ls_formula="";
				   $ls_tipodeduccion="";
				   $ls_estatus="NUEVO";
				   $ls_tipoperdeduccion="";
				   $ls_codconret="";
				   $ls_denconret="";
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
///////////////////////////////////     Fin de las Operaciones de Insercion y Actualizacion       ///////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////    Operaciones de Eliminar ////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($ls_operacion=="ELIMINAR")
   {
	  $lb_existe = $io_deducciones->uf_select_deduccion($ls_codemp,$ls_codigo);
	  if ($lb_existe)
	     {
		   $ls_condicion = " AND (column_name='codded')";//Nombre del o los campos que deseamos buscar.
	       $ls_mensaje   = "";                           //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
	       $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_codemp,$ls_condicion,'sigesp_deducciones',$ls_codigo,$ls_mensaje);//Verifica los movimientos asociados a la cuenta  
		   if (!$lb_tiene)
		      {
		        $lb_valido=$io_deducciones->uf_delete_deduccion($ls_codemp,$ls_codigo,$ls_denominacion,$la_seguridad);
		        if ($lb_valido)
	               {
			         $io_sql->commit();
			         $io_msg->message("Registro Eliminado !!!");
			    	 $ls_codigo        = $io_funciondb->uf_generar_codigo($lb_empresa,$ls_codemp,'sigesp_deducciones','codded');
				     $ls_denominacion  = "";
				     $ls_contable      = "";
				     $ls_denocuenta    = "";
				     $ld_porcentaje    = "0.0";
				     $ld_deducible     = "0,00";
				     $ls_formula       = "";
				     $ls_tipodeduccion = "";
			         $ls_estatus       = "NUEVO";
					 $ls_tipoperdeduccion="";
				     $ls_codconret="";
				     $ls_denconret="";
			       }
		        else
			       {
			         $io_sql->rollback();
			         $io_msg->message($io_deducciones->is_msg_error);
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
		 }
   }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////       Operacion de Eliminar           ////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<p align="center">&nbsp;</p>
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
          <div align="center">
            <table width="728" height="268" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr>
                <td width="789" height="259"><div align="center">
                  <table width="705"  border="0" align="center" class="formato-blanco" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="22" colspan="7" class="titulo-ventana">Deducciones</td>
                    </tr>
                    <tr>
                      <td height="22" >&nbsp;</td>
                      <td height="22" colspan="6" ><input name="hidestatus" type="hidden" id="hidestatus" value="<?php print $ls_estatus ?>"></td>
                    </tr>
                    <tr>
                      <td width="85" height="22" style="text-align:right">C&oacute;digo</td>
                      <td height="22" style="text-align:left"><input name="txtcodigo" type="text" id="txtcodigo" value="<?php print  $ls_codigo ?>" size="5" maxlength="5" onKeyPress="return keyRestrict(event,'1234567890');" style="text-align:center"  onBlur="javascript:rellenar_cadena(this.value,5);">
                          <input name="operacion" type="hidden" id="operacion"  value="<?php print $ls_operacion ?>">                      </td>
                    </tr>
                    <tr>
                      <td height="22" style="text-align:right">Denominaci&oacute;n</td>
                      <td height="22" colspan="6" style="text-align:left"><input name="txtdenominacion" id="txtdenominacion" value="<?php print $ls_denominacion?>" type="text" size="97" maxlength="254"  onKeyPress="return keyRestrict(event,'1234567890'+'abcdefghijklmn&ntilde;opqrstuvwxyz '+'.,-()%');"></td>
                    </tr>
                    <tr>
                      <td height="22" style="text-align:right">%</td>
                      <td height="22" colspan="6" style="text-align:left"><input name="txtporcentaje" type="text" id="txtporcentaje" onKeyPress="return keyRestrict(event,'1234567890'+'.');" value="<?php print $ld_porcentaje ?>" style="text-align:right"></td>
                    </tr>
                    <tr>
                      <td height="22" align="left"><div align="right">C&oacute;digo Contable</div></td>
                      <td height="22" colspan="6" style="text-align:left"><input name="txtcuentaplan" type="text" id="txtcuentaplan" value="<?php print $ls_contable ?>" style="text-align:center" readonly>
                          <a href="javascript:catalogo_cuentas();"><img src="../../shared/imagebank/tools15/buscar.gif" width="15" height="15" border="0"></a> 
                          <input name="txtdencuentaplan" type="text" class="sin-borde" id="txtdencuentaplan" value="<?php print $ls_denocuenta ?>" size="80" readonly>                          </td>
                    </tr>
                    <tr>
                      <td height="22" align="left"><div align="right">Deducible</div></td>
                      <td width="127" height="22" style="text-align:left"><input name="txtdeducible" type="text" id="txtdeducible" value="<?php print $ld_deducible ?>" style="text-align:right"  onKeyPress="return(currencyFormat(this,'.',',',event))">                      </td>
                      <td width="85" height="22"><?php 	 
	switch ($ls_tipodeduccion)
	{
		case"S":
			$ls_deducisrl="checked";
			$ls_deduciva="";
			$ls_deducmunicipal="";
			$ls_deducsocial="";
			$ls_deducotras="";
		break;
		case"I":
             $ls_deducisrl="";
		     $ls_deduciva="checked";
		     $ls_deducmunicipal="";
			 $ls_deducsocial="";
			 $ls_deducotras="";
		break;
		case"M":
 		     $ls_deducisrl="";
		     $ls_deduciva="";
		     $ls_deducmunicipal="checked";
	  		 $ls_deducsocial="";
			 $ls_deducotras="";
		break;
		case"A":
 		     $ls_deducisrl="";
		     $ls_deduciva="";
		     $ls_deducmunicipal="";
	  		 $ls_deducsocial="checked";
			 $ls_deducotras="";
		break;
		
		case"0":
 		     $ls_deducisrl="";
		     $ls_deduciva="";
		     $ls_deducmunicipal="";
	  		 $ls_deducsocial="";
			 $ls_deducotras="checked";
		break;
		default:
			$ls_deducisrl="checked";
			$ls_deduciva="";
			$ls_deducmunicipal="";
			$ls_deducsocial="";
			$ls_deducotras="";
		break;
	}

/*	if(($ls_tipodeduccion=="S")||($ls_tipodeduccion==""))
	  {
		$ls_deducisrl="checked";
		$ls_deduciva="";
		$ls_deducmunicipal="";
		$ls_deducsocial="";
      }
      else
	  {
        if ($ls_tipodeduccion=="I")
		   { 
             $ls_deducisrl="";
		     $ls_deduciva="checked";
		     $ls_deducmunicipal="";
			 $ls_deducsocial="";

		   }
		else
		   {
 		     $ls_deducisrl="";
		     $ls_deduciva="";
		     $ls_deducmunicipal="checked";
	  		 $ls_deducsocial="";
		   }
      }*/
	  ?>
                          <input name="radiotipodeduccion" type="radio" class="sin-borde" onClick="cambiarestatus2()" value="S" <?php print $ls_deducisrl ?>>
      I.S.L.R.</td>
                      <td width="85" height="22"><input name="radiotipodeduccion" type="radio" class="sin-borde" value="I" onClick="cambiarestatus()" <?php print $ls_deduciva?>>
      Ret. IVA</td>
                      <td width="110" height="22"><input name="radiotipodeduccion" type="radio" class="sin-borde" value="M" onClick="cambiarestatus()" <?php print $ls_deducmunicipal ?>>
      Ret. Municipal</td>
                      <td width="138" height="22"><input name="radiotipodeduccion" type="radio" class="sin-borde" onClick="cambiarestatus()" value="A" <?php print $ls_deducsocial ?>>
                      Ret. Aporte Social </td>
                      <td width="73"><label>
                        <input name="radiotipodeduccion" type="radio" class="sin-borde" value="O" onClick="cambiarestatus()" <? print $ls_deducotras?>>
                        Otras
                      </label></td>
                    </tr>
                    <tr>
                      <td height="22" style="text-align:right" valign="top">F&oacute;rmula</td>
                      <td height="22" colspan="6" style="text-align:left"><input name="txtformula" type="text" id="txtformula" value="<?php print $ls_formula ?>" size="85" readonly>
                          <input name="Button" type="button" class="boton" onClick="uf_editor()" value="F&oacute;rmula" style="cursor:pointer"></td>
                    </tr>
                    <tr>
	<?php
	if(($ls_deducisrl=="checked"))
	  {
		$ls_perju="checked";
		$ls_pernat="";
      }
      else
	  {
		 $ls_perju="";
		 $ls_pernat="checked";
      }
	  ?>
       
                      <td height="20" style="text-align:right" valign="top"><div align="right">Tipo de persona </div></td>
                      <td height="20" style="text-align:left"><input name="radiotipoperdeduccion" type="radio" class="sin-borde" value="J" <?php print $ls_perju ?>>
                        Jur&iacute;dica </td>
                      <td height="20" style="text-align:left"><input name="radiotipoperdeduccion" type="radio" class="sin-borde" value="N" <?php print $ls_pernat ?>>
                        Natural</td>
                      <td height="20" style="text-align:left">&nbsp;</td>
                      <td height="20" style="text-align:left">&nbsp;</td>
                      <td style="text-align:left">&nbsp;</td>
                      <td height="20" style="text-align:left">&nbsp;</td>
                    </tr>
                    <tr>
                      <td height="20" style="text-align:right"><p align="right">Concepto</p></td>
                      <td height="20" colspan="6" style="text-align:left"><input name="txtcodconret" type="text" id="txtcodconret" style="text-align:center" value="<?php print $ls_codconret; ?>" size="12" maxlength="10" readonly>
                      <a href="javascript:catalogo_concepto();"><img src="../../shared/imagebank/tools15/buscar.gif" alt="Buscar Conceptos" width="15" height="15" border="0"></a>
                      <input name="txtdenconret" type="text" class="sin-borde" id="txtdenconret" value="<?php print $ls_denconret; ?>" size="80" readonly>
                      </td>
                    </tr>
				
                    <tr>
                      <td height="22" colspan="7" align="right" valign="top">&nbsp;</td>
                    </tr>
                  </table>
                </div></td>
              </tr>
            </table>
          </div>
          <div align="center"></div>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
  </div>
    </table>
</form>
</body>
<script language="JavaScript">

function ue_nuevo()
{
  f=document.form1;
  li_incluir=f.incluir.value;
  if (li_incluir==1)
	 {	
	   f.operacion.value="NUEVO";
	   f.txtdenominacion.value="";
	   f.txtporcentaje.value="0.0";
	   f.txtcuentaplan.value="";
	   f.txtdencuentaplan.value="";
	   f.txtdeducible.value="0,00";
	   f.radiotipodeduccion.value="";
	   f.radiotipoperdeduccion.value="";
	   f.txtformula.value="";
	   f.action="sigesp_cxp_d_deducciones.php";
	   f.txtdenominacion.focus(true);
	   f.submit();
	}
  else
	{
	  alert("No tiene permiso para realizar esta operación");
	}
}

function cambiarestatus()
{  
	f=document.form1; 
	f.radiotipoperdeduccion[0].disabled=true;
	f.radiotipoperdeduccion[1].disabled=true;
	f.txtcodconret.value="";
	f.txtdenconret.value="";
}

function cambiarestatus2()
{  
  f=document.form1; 
  f.radiotipoperdeduccion[0].disabled=false;
  f.radiotipoperdeduccion[1].disabled=false;
}

function ue_guardar()
{
var resul="";
f=document.form1;
li_incluir=f.incluir.value;
li_cambiar=f.cambiar.value;
lb_status=f.hidestatus.value;
if (((lb_status=="GRABADO")&&(li_cambiar==1))||(lb_status=="NUEVO")&&(li_incluir==1))
   {
     with (document.form1)
	      {
	        if (campo_requerido(txtcodigo,"El código de la deducción debe estar lleno !!")==false)
		       {
		         txtcodigo.focus();
	 	       }
	        else
		       {
		         resul=rellenar_cadena(document.form1.txtcodigo.value,5);	   
		         if (campo_requerido(txtdenominacion,"La denominación de la deducción debe estar llena !!!")==false)
			        {
			          txtdenominacion.focus();
			        }
		         else
			        {
			          if (campo_requerido(txtporcentaje,"El Porcentaje de la deducción debe estar lleno !!!")==false)
				         {
					       txtporcentaje.focus();
				         }
			          else
				         {
					       if (campo_requerido(txtcuentaplan,"La Cuenta de la deducción debe estar llena !!!")==false)
					          {
						        txtcuentaplan.focus();
					          }
				 	       else
					          {
						        if (campo_requerido(txtformula,"La Fórmula de la deducción debe estar llena !!!")==false)
							       {
							         txtformula.focus();
							       }
						        else
							       {
							         f=document.form1;
							         f.operacion.value="ue_guardar";
							         f.action="sigesp_cxp_d_deducciones.php";
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

f=document.form1;
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
			   f.operacion.value="ELIMINAR";
			   f.action="sigesp_cxp_d_deducciones.php";
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
	f=document.form1;
    li_leer=f.leer.value;
	if (li_leer==1)
	   {
         f.operacion.value="";			
	     pagina="sigesp_cxp_cat_deducciones.php";
	     window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=650,height=400,resizable=yes,location=no"); 
       }
	else
	   {
		 alert("No tiene permiso para realizar esta operación");
	   }
}

function catalogo_cuentas()
{
	f=document.form1;
	f.operacion.value="";			
	pagina="sigesp_catdinamic_ctas.php";
	window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
}

function catalogo_concepto()
{
	f=document.form1;
	if(f.radiotipodeduccion[0].checked==true)
	{
		pagina="sigesp_cat_conceptosret.php";
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=520,height=400,resizable=yes,location=no");
	}
	else
	{
		alert("La deduccion debe ser de I.S.L.R. para tener habilitada esta opcion.");
	}
}

function uf_editor()
 {
   f=document.form1;
   ls_formula   = f.txtformula.value;
   ld_deducible = f.txtdeducible.value;
   window.open("class_sigesp_formulas.php?txtformula="+ls_formula+"&txtdeducible="+ld_deducible,"catalogo","menubar=no,toolbar=no,scrollbars=no,width=560,height=270,resizable=yes,location=no");
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
	if (whichCode == 8)  return true; // Enter 
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
</script>
</html>