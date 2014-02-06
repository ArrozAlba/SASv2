<?php
session_start();
$dat=$_SESSION["la_empresa"];
if (!array_key_exists("la_logusr",$_SESSION))
   {
	 print "<script language=JavaScript>";
	 print "location.href='../../sigesp_inicio_sesion.php'";
	 print "</script>";		
   }
    //--------------------------------------------------------------
	function uf_agregarlineablanca(&$aa_object,$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_agregarlineablanca
		//         Access: private
		//      Argumento: $aa_object // arreglo de titulos 
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que agrega una linea en blanco al final del grid
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 23/03/2006 								Fecha Última Modificación : 23/03/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$aa_object[$ai_totrows][1]="<input name=txtdenban".$ai_totrows." type=text   id=txtdenban".$ai_totrows." class=sin-borde size=30 readonly>".
								   "<input name=txtcodban".$ai_totrows." type=hidden id=txtcodban".$ai_totrows." class=sin-borde size=17 readonly>";
		$aa_object[$ai_totrows][2]="<input name=txtctaban".$ai_totrows." type=text   id=txtctaban".$ai_totrows."  class=sin-borde size=30 maxlength=15 readonly>";
		$aa_object[$ai_totrows][3]="<input name=txtdencta".$ai_totrows." type=text   id=txtdencta".$ai_totrows." class=sin-borde size=45 readonly>";
		$aa_object[$ai_totrows][4]="<a href=javascript:uf_delete_dt(".$ai_totrows.");><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
		
	}
   //--------------------------------------------------------------

   //--------------------------------------------------------------
   function uf_limpiarvariables()
   {
		//////////////////////////////////////////////////////////////////////////////////
		//	Function:  uf_limpiarvariables
		//	Description: Función que limpia todas las variables necesarias en la página
		/////////////////////////////////////////////////////////////////////////////////
   		global $ls_titletable,$li_widthtable,$ls_nametable,$lo_title;
		
		$ls_titletable="Cuentas Bancarias";
		$li_widthtable=600;
		$ls_nametable="grid";
		$lo_title[1]="Banco";
		$lo_title[2]="Cuenta";
		$lo_title[3]="Denominacion de la Cuenta";
		$lo_title[4]="";
   }
   //--------------------------------------------------------------

   //--------------------------------------------------------------
    function uf_pintardetalle(&$aa_object,$ai_totrows)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintardetalle
		//         Access: private
		//      Argumento: $aa_object // arreglo de objetos
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que se encarga de repintar el detalle existente en el grid.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 11/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		for($li_i=1;$li_i<$ai_totrows;$li_i++)
		{
			$ls_codban= $_POST["txtcodban".$li_i];
			$ls_denban= $_POST["txtdenban".$li_i];
			$ls_ctaban= $_POST["txtctaban".$li_i];
			$ls_dencta= $_POST["txtdencta".$li_i];
			$aa_object[$li_i][1]="<input name=txtdenban".$li_i." type=text   id=txtdenban".$li_i." class=sin-borde size=30 value='".$ls_denban."' readonly>".
								 "<input name=txtcodban".$li_i." type=hidden id=txtcodban".$li_i." class=sin-borde size=17 value='".$ls_codban."' readonly>";
			$aa_object[$li_i][2]="<input name=txtctaban".$li_i." type=text   id=txtctaban".$li_i." class=sin-borde size=30 value='".$ls_ctaban."' readonly>";
			$aa_object[$li_i][3]="<input name=txtdencta".$li_i." type=text   id=txtdencta".$li_i." class=sin-borde size=45 value='".$ls_dencta."' readonly>";
			$aa_object[$li_i][4]="<a href=javascript:uf_delete_dt(".$li_i.");><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
		}
		uf_agregarlineablanca($aa_object,$ai_totrows);
	}
   //--------------------------------------------------------------

   //--------------------------------------------------------------
    function uf_eliminardetalle(&$aa_object,&$ai_totrows,$li_row)
	{
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_pintardetalle
		//         Access: private
		//      Argumento: $aa_object // arreglo de objetos
		//				   $ai_totrows // ultima fila pintada en el grid
		//	      Returns: 
		//    Description: Funcion que se encarga de repintar el detalle existente en el grid.
		//	   Creado Por: Ing. Luis Anibal Lang
		// Fecha Creación: 11/04/2006 								Fecha Última Modificación : 11/04/2006 
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$li_aux=0;
		for($li_i=1;$li_i<$ai_totrows;$li_i++)
		{
			if($li_i!=$li_row)
			{
				$li_aux++;
				$ls_codban= $_POST["txtcodban".$li_i];
				$ls_denban= $_POST["txtdenban".$li_i];
				$ls_ctaban= $_POST["txtctaban".$li_i];
				$ls_dencta= $_POST["txtdencta".$li_i];
				$aa_object[$li_aux][1]="<input name=txtdenban".$li_aux." type=text   id=txtdenban".$li_aux." class=sin-borde size=30 value='".$ls_denban."' readonly>".
									 "<input name=txtcodban".$li_aux." type=hidden id=txtcodban".$li_aux." class=sin-borde size=17 value='".$ls_codban."' readonly>";
				$aa_object[$li_aux][2]="<input name=txtctaban".$li_aux." type=text   id=txtctaban".$li_aux." class=sin-borde size=30 value='".$ls_ctaban."' readonly>";
				$aa_object[$li_aux][3]="<input name=txtdencta".$li_aux." type=text   id=txtdencta".$li_aux." class=sin-borde size=45 value='".$ls_dencta."' readonly>";
				$aa_object[$li_aux][4]="<a href=javascript:uf_delete_dt(".$li_aux.");><img src=../../shared/imagebank/tools15/eliminar.gif alt=Eliminar width=15 height=15 border=0></a>";
			}
		}
		$ai_totrows=$ai_totrows-1;
		uf_agregarlineablanca($aa_object,$ai_totrows);
	}
   //--------------------------------------------------------------

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Definici&oacute;n de Conceptos de Movimiento</title>
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
<link href="../css/cfg.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../../shared/css/ventanas.css" rel="stylesheet" type="text/css">
</head>

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
    <td height="20" class="toolbar"><div align="left"><a href="javascript: ue_nuevo();"><img src="../../shared/imagebank/tools20/nuevo.gif" title="Nuevo" alt="Nuevo" width="20" height="20" border="0"></a><a href="javascript: ue_guardar();"><img src="../../shared/imagebank/tools20/grabar.gif" title="Guardar" alt="Grabar" width="20" height="20" border="0"></a><a href="javascript: ue_buscar();"><img src="../../shared/imagebank/tools20/buscar.gif" title="Buscar" alt="Buscar" width="20" height="20" border="0"></a><img src="../../shared/imagebank/tools20/imprimir.gif" title="Imprimir" alt="Imprimir" width="20" height="20"><a href="javascript: ue_eliminar();"><img src="../../shared/imagebank/tools20/eliminar.gif" title="Eliminar" alt="Eliminar" width="20" height="20" border="0"></a><a href="javascript: ue_cerrar();"><img src="../../shared/imagebank/tools20/salir.gif" title="Salir" alt="Salir" width="20" height="20" border="0"></a></div>      <div align="center"></div>      <div align="center"></div>      <div align="center"></div>      <div align="center"></div></td>
  </tr>
</table>
<?php
require_once("../../shared/class_folder/class_mensajes.php");
require_once("../../shared/class_folder/class_funciones_db.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/sigesp_c_check_relaciones.php");
require_once("sigesp_scb_c_conceptos.php");
require_once("../../shared/class_folder/sigesp_c_generar_consecutivo.php");
$io_keygen= new sigesp_c_generar_consecutivo();

$io_conect = new sigesp_include();//Instanciando la Sigesp_Include.
$conn      = $io_conect->uf_conectar();//Asignacion de valor a la variable $conn a traves del metodo uf_conectar de la clase sigesp_include.
$io_sql    = new class_sql($conn);//Instanciando la Clase Class Sql.
$fun_db    = new class_funciones_db($conn);
$io_msg    = new class_mensajes();
$ds        = null;
$io_chkrel = new sigesp_c_check_relaciones($conn);
require_once("../../shared/class_folder/grid_param.php");
$in_grid= new grid_param();

	//////////////////////////////////////////////  SEGURIDAD   /////////////////////////////////////////////
	require_once("../../shared/class_folder/sigesp_c_seguridad.php");
	$io_seguridad= new sigesp_c_seguridad();
	
	$arre       = $_SESSION["la_empresa"];
	$ls_empresa = $arre["codemp"];
	$ls_codemp  = $ls_empresa;
	if(array_key_exists("la_logusr",$_SESSION))
	{
	$ls_logusr=$_SESSION["la_logusr"];
	}
	else
	{
	$ls_logusr="";
	}
	$ls_sistema="CFG";
	$ls_ventanas="sigesp_scb_d_conceptos.php";
	$la_security[1]=$ls_empresa;
	$la_security[2]=$ls_sistema;
	$la_security[3]=$ls_logusr;
	$la_security[4]=$ls_ventanas;
    $in_classconceptos=new sigesp_scb_c_conceptos($la_security);
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

	$ls_detalle=$in_classconceptos->uf_select_configuracion();
	if( array_key_exists("operacion",$_POST))
	{
		$ls_operacion   = $_POST["operacion"];
		$ls_codigo      = $_POST["txtcodigo"];
		$ls_denominacion= $_POST["txtdenominacion"];
		$ls_codope      = $_POST["cmboperacion"];
		$ls_status      =$_POST["status"];
		$li_totrows      =$_POST["totalfilas"];
		$readonly       = "";
	}
	else
	{
		$li_totrows=1;
		uf_limpiarvariables();
		uf_agregarlineablanca($lo_object,$li_totrows);
		$ls_operacion= "NUEVO";
		$ls_codigo   = "";
		$ls_denominacion = "";
		$ls_codope   = "ND";
		$ls_status="N";
		$readonly="";
	}
 if ($ls_operacion == "NUEVO")
	{
		$ls_codigo= $io_keygen->uf_generar_numero_nuevo("CFG","scb_concepto","codconmov","CFGCTO",3,"","","");
//		$ls_codigo   = $fun_db->uf_generar_codigo(false,"","scb_concepto","codconmov");
		$ls_denominacion = "";
		$ls_status="N";
		$readonly="";
		uf_limpiarvariables();
		$li_totrows=1;
		uf_agregarlineablanca(&$lo_object,$li_totrows);
	}
if ($ls_operacion == "GUARDAR")
	{
 		$ls_codigoaux=$ls_codigo;
		$in_classconceptos->io_sql->begin_transaction();//Inicio la transaccion
		$lb_valido=$in_classconceptos->uf_guardar_conceptos($ls_codigo,$ls_codope,$ls_denominacion,$ls_status);
		$readonly="readonly";
		$lb_valido=$in_classconceptos->uf_delete_detalles($ls_codigo);
		if($li_totrows>1)
		{
			for($li_i=1;$li_i<$li_totrows;$li_i++)
			{
				$ls_codban=$_POST["txtcodban".$li_i];
				$ls_ctaban=$_POST["txtctaban".$li_i];
				$lb_valido=$in_classconceptos->uf_insert_detalle($ls_codigo,$ls_codban,$ls_ctaban);
			}
		}
		uf_limpiarvariables();
		uf_pintardetalle(&$lo_object,$li_totrows);
		if($lb_valido)
		{
			$in_classconceptos->io_sql->commit();
			if($ls_codigoaux!=$ls_codigo)
			{
				$io_msg->message("Se le asigno el codigo ".$ls_codigo);
			}
			$io_msg->message($in_classconceptos->is_msg_error);
		}
		else
		{
			$in_classconceptos->io_sql->rollback();
			$io_msg->message($in_classconceptos->is_msg_error);
		}
		
	}

if ($ls_operacion == "ELIMINAR")
   {
	 $lb_existe = $in_classconceptos->uf_select_conceptos($ls_codigo,$ls_codope);
	 if ($lb_existe)
	    {
		  $ls_condicion = " AND (column_name='codconmov')";//Nombre del o los campos que deseamos buscar.
		  $ls_mensaje   = "";                              //Mensaje que será enviado al usuario si se encuentran relaciones a asociadas al campo.
		  $lb_tiene     = $io_chkrel->uf_check_relaciones($ls_codemp,$ls_condicion,'scb_concepto',$ls_codigo,$ls_mensaje);//Verifica los movimientos asociados a la cuenta 
		  if (!$lb_tiene)
			 {
	           $lb_valido = $in_classconceptos->uf_delete_conceptos($ls_codigo,$ls_codope,$ls_denominacion,$ls_status);
			   if ($lb_valido)
			      {
				    $io_sql->commit();
				    $io_msg->message("Registro Eliminado !!!");
					uf_limpiarvariables();
					$li_totrows=1;
					uf_agregarlineablanca(&$lo_object,$li_totrows);
					$ls_codigo= $io_keygen->uf_generar_numero_nuevo("CFG","scb_concepto","codconmov","CFGCTO",3,"","","");
					$ls_denominacion = "";
			        $ls_codope       = "ND";
			        $readonly        = "";
				  }
               else
			      {
		            $io_msg->message($in_classconceptos->is_msg_error);		  
				    $readonly = "readonly";
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

	if($ls_codope=='ND')
		{
			$lb_nd="selected";
			$lb_nc="";
			$lb_dp="";
			$lb_re="";
			$lb_ch="";
		}
		if($ls_codope=='NC')
		{
			$lb_nd="";
			$lb_nc="selected";
			$lb_dp="";
			$lb_re="";
			$lb_ch="";
		}
		if($ls_codope=='DP')
		{
			$lb_nd="";
			$lb_nc="";
			$lb_dp="selected";
			$lb_re="";
			$lb_ch="";
		}
		if($ls_codope=='RE')
		{
			$lb_nd="";
			$lb_nc="";
			$lb_dp="";
			$lb_re="selected";
			$lb_ch="";
		}
		if($ls_codope=='CH')
		{
			$lb_nd="";
			$lb_nc="";
			$lb_dp="";
			$lb_re="";
			$lb_ch="selected";
		}
if($ls_operacion == "AGREGARDETALLE")
{
	$li_totrows   = $_POST["totalfilas"];
	$li_totrows = $li_totrows+1;
	uf_limpiarvariables();
	uf_pintardetalle(&$lo_object,$li_totrows);
}
if($ls_operacion == "ELIMINARDETALLE")
{
	$li_totrows   = $_POST["totalfilas"];
	$ls_rowdelete = $_POST["rowdelete"];
	uf_limpiarvariables();
	uf_eliminardetalle(&$lo_object,&$li_totrows,$ls_rowdelete);
}	
if($ls_operacion == "BUSCARDETALLE")
{
	if($ls_detalle==true)
	{
		$li_totrows   = $_POST["totalfilas"];
		$ls_rowdelete = $_POST["rowdelete"];
		uf_limpiarvariables();
		$lb_valido=$in_classconceptos->uf_select_buscardetalle($ls_codigo,&$li_totrows,&$lo_object);
	}
}	
?>
<p>&nbsp;</p>
<div align="center">
  <table width="601" height="174" border="0" cellpadding="0" cellspacing="0" class="formato-blanco">
      <tr>
        <td width="601" height="172" valign="top"><form name="form1" method="post" action="">
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
                
				<br>
				<br>
								
				<table width="566" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
              <tr class="titulo-ventana">
                <td height="22" colspan="3">Definici&oacute;n de Conceptos de Movimiento </td>
              </tr>
              <tr class="formato-blanco">
                <td height="22">&nbsp;</td>
                <td width="434" height="22" colspan="2">&nbsp;</td>
              </tr>
              <tr class="formato-blanco">
                <td width="130" height="22"><div align="right" >
                    <p>C&oacute;digo</p>
                </div></td>
                <td height="22" colspan="2"><div align="left" >
                    <input name="txtcodigo" type="text" id="txtcodigo" style="text-align:center " value="<?php print $ls_codigo?>" size="6" maxlength="3" onBlur="javascript:rellenar_cad(this.value,3,'cod')" <?php print $readonly ?> onKeyPress="return keyRestrict(event,'1234567890');">
                </div></td>
              </tr>
              <tr class="formato-blanco">
                <td height="22"><div align="right">Denominaci&oacute;n</div></td>
                <td height="22" colspan="2"><div align="left">
                  <input name="txtdenominacion" type="text" id="txtdenominacion" style="text-align:left" value="<?php print $ls_denominacion?>" size="60" maxlength="80">
                </div></td>
              </tr>
            <tr class="formato-blanco">
              <td height="22"><div align="right">Operaci&oacute;n Asociada</div></td>
              <td height="22" colspan="2"><div align="left">
                <select name="cmboperacion" id="cmboperacion" >
                  <option value="ND" <?php print $lb_nd;?>>Nota de D&eacute;bito</option>
                  <option value="NC" <?php print $lb_nc;?>>Nota Cr&eacute;dito</option>
                  <option value="DP" <?php print $lb_dp;?>>Dep&oacute;sito</option>
                  <option value="RE" <?php print $lb_re;?>>Retiro</option>
                  <option value="CH" <?php print $lb_ch;?>>Cheque</option>
                </select>
              </div></td>
            </tr>
            <tr class="formato-blanco">
              <td height="22">&nbsp;</td>
              <td height="22" colspan="2">&nbsp;</td>
            </tr>
			<?php
				  if($ls_detalle==true)
				  {
			?>
            <tr class="formato-blanco">
              <td height="22" colspan="3"><div align="left"><a href="javascript: ue_agregardetalle();"><img src="../../shared/imagebank/tools20/nuevo.gif" alt="Agregar" width="20" height="20" class="sin-borde">Agregar</a></div></td>
            </tr>
 			<?php
				 }
			?>
           <tr class="formato-blanco">
              <td height="22" colspan="3">
			    <div align="center">
			      <?php
				  if($ls_detalle==true)
				  {
			 			$in_grid->makegrid($li_totrows,$lo_title,$lo_object,$li_widthtable,$ls_titletable,$ls_nametable);
				  }
			 ?>
	            </div></td>
              </tr>
          </table>
            <p align="center"><input name="operacion" type="hidden" id="operacion">
              <input name="status" type="hidden" id="status" value="<?php print $ls_status;?>">
            <input name="totalfilas" type="hidden" id="totalfilas" value="<?php print $li_totrows;?>">
            <input name="rowdelete" type="hidden" id="rowdelete" value="<?php print $ls_rowdelete; ?>">
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
         f.action="sigesp_scb_d_conceptos.php";
		 f.submit();
	   }
	else
	   {
 	     alert("No tiene permiso para realizar esta operación");
	   } 
}

function ue_guardar()
{
	f=document.form1;
	li_incluir=f.incluir.value;
	li_cambiar=f.cambiar.value;
	lb_status=f.status.value;
	if (((lb_status=="C")&&(li_cambiar==1))||(lb_status=="N")&&(li_incluir==1))
	   {
	     ls_codigo=f.txtcodigo.value;
	     ls_denominacion=f.txtdenominacion.value;
	     if ((ls_codigo!="")&&(ls_denominacion!=""))
 	        {
		      f.operacion.value ="GUARDAR";
		      f.action="sigesp_scb_d_conceptos.php";
		      f.submit();
	        }
	     else
	        {
		      alert("No ha completado los datos");
	        }
       }
     else
	   {
 	     alert("No tiene permiso para realizar esta operación");
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
	      ls_codigo=f.txtcodigo.value;
		  ls_denominacion=f.txtdenominacion.value;
		  if ((ls_codigo!="")&&(ls_denominacion!=""))
	 	     {  
			   f.operacion.value ="ELIMINAR";
			   f.action="sigesp_scb_d_conceptos.php";
			   f.submit();
 	         } 	
		  else
	         {
		       alert("No ha seleccionado el registro a eliminar");
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

function uf_delete_dt(li_row)
{
	f=document.form1;
	f.rowdelete.value=li_row;
	f.operacion.value ="ELIMINARDETALLE";
	f.action="sigesp_scb_d_conceptos.php";
	f.submit();
}
function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if (li_leer==1)
       {
	     window.open("sigesp_scb_cat_conceptos.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
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
		if(campo=="cmp")
		{
			document.form1.txtcomprobante.value=cadena;
		}
		if(campo=="cod")
		{
			document.form1.txtcodigo.value=cadena;
		}
	}
	function ue_agregardetalle()
	{
		f=document.form1;
		ls_codcon=f.txtcodigo.value;
		if(ls_codcon=="")
		{
			alert("Debe existir un codigo de concepto");
		}
		else
		{
			li_totrow=f.totalfilas.value;
			window.open("sigesp_cfg_pdt_ctabanco.php","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=630,height=500,left=50,top=50,location=no,resizable=yes");
		}
	}
	
</script>
</html>
